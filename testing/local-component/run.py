"""Run against a disposable local source copy, PHP without mysqli, Python requests and Playwright.
No production URL. Fixture SQL adapter only; does not test database persistence.
Usage: python run.py /path/to/directory/containing/cleaning /path/to/output
"""
import os, sys, subprocess, pathlib, tempfile, time, json, csv, shutil, socket, requests
from playwright.sync_api import sync_playwright
ROOT=pathlib.Path(sys.argv[1]).resolve(); OUT=pathlib.Path(sys.argv[2]).resolve(); OUT.mkdir(parents=True,exist_ok=True)
E=OUT/'evidence'; E.mkdir(exist_ok=True); T=pathlib.Path(tempfile.mkdtemp()); (T/'sessions').mkdir(); CAP=T/'capture.jsonl'; CAP.touch();
rows=[]
def record(id,method,area,expected,actual,ok):
 rows.append(dict(ID=id,Method=method,Area=area,Expected=expected,Actual=actual,Status='PASS' if ok else 'FAIL')); print(rows[-1],flush=True)
def capture(): return [json.loads(s) for s in CAP.read_text().splitlines()]
def req(path,data=None,s=None):
 return (s or requests.Session()).request('POST' if data is not None else 'GET',base+path,data=data,allow_redirects=False,timeout=5)
def flash(s):
 sid=s.cookies.get('PHPSESSID'); p=T/'sessions'/('sess_'+sid) if sid else None; return p.read_text() if p and p.exists() else ''
def port():
 with socket.socket() as s:s.bind(('127.0.0.1',0));return s.getsockname()[1]
servers=[]
def start(mock):
 p=port(); args=['php','-d',f'session.save_path={T}/sessions','-d','display_errors=0'];
 if mock:args+=['-d',f'auto_prepend_file={pathlib.Path(__file__).with_name("db-double.php")}']
 log=open(OUT/('php-fixture.log' if mock else 'php-native.log'),'w'); srv=subprocess.Popen(args+['-S',f'127.0.0.1:{p}','-t',str(ROOT)],stdout=log,stderr=log,env={**os.environ,'QA_CAPTURE':str(CAP)});servers.append(srv);time.sleep(.35);return f'http://127.0.0.1:{p}/cleaning/'
try:
 base=start(False)
 r=req(''); record('BB-01','HTTP native','Root','302 ke login',f'{r.status_code} {r.headers.get("Location")}',r.status_code==302 and r.headers.get('Location')=='/cleaning/auth/login.php')
 r=req('scripts/create_admin.php'); record('BB-02','HTTP native','Setup admin HTTP','403',f'{r.status_code} {r.text}',r.status_code==403); (E/'BB-02-response.txt').write_text(f'HTTP {r.status_code}\n{r.text}')
 r=req('auth/logout.php'); record('BB-03','HTTP native','Logout redirect','302 ke login?logout=success',f'{r.status_code} {r.headers.get("Location")}',r.status_code==302 and r.headers.get('Location','').endswith('logout=success'))
 base=start(True)
 M='HTTP fixture'; P='FixtureOnly123!';
 for id, data, msg in [('BB-04',{},'Username dan password wajib diisi!'),('BB-05',{'username':'qa_staff','password':'wrong'},'Password salah!'),('BB-06',{'username':'nonexistent','password':P},'Username tidak ditemukan!')]:
  r=req('auth/login.php',data); record(id,M,'Login',msg,f'HTTP {r.status_code}; pesan ditemukan={msg in r.text}',r.status_code==200 and msg in r.text)
 staff=requests.Session(); r=req('auth/login.php',{'username':'qa_staff','password':P},staff); record('BB-07',M,'Login benar','302 dashboard; sesi staff',f'{r.status_code} {r.headers.get("Location")}; role staff={"staff" in flash(staff)}',r.status_code==302 and 'staff' in flash(staff))
 for id,u,p,c,msg in [('BB-08','ab',P,P,'Username minimal 3 karakter!'),('BB-09','new_staff','12345','12345','Password minimal 6 karakter!'),('BB-10','new_staff',P,'different','Konfirmasi password tidak cocok!'),('BB-11','qa_staff',P,P,'Username sudah terdaftar!')]:
  r=req('auth/register.php',{'username':u,'password':p,'confirm_password':c}); record(id,M,'Registrasi',msg,f'HTTP {r.status_code}; pesan ditemukan={msg in r.text}',msg in r.text)
 for id,role in [('BB-12','staff'),('BB-13','admin')]:
  r=req('auth/register.php',{'username':'new_'+role,'password':P,'confirm_password':P,'role':role}); q=capture()[-1]; ok='Registrasi berhasil!' in r.text and q[0]=='execute' and q[2][-1]=='staff'; record(id,M,'Registrasi role','INSERT role staff; respons berhasil (simulasi)',f'HTTP {r.status_code}; parameter INSERT role={q[2][-1]}',ok)
 r=req('auth/register.php',s=staff); record('BB-14',M,'Register saat login','302 dashboard',f'{r.status_code} {r.headers.get("Location")}',r.status_code==302 and r.headers.get('Location','').endswith('dashboard.php'))
 r=req('pages/users.php',s=staff); record('BB-15',M,'Staff ke Kelola User','403',f'HTTP {r.status_code}; penolakan={"Akses Ditolak" in r.text}',r.status_code==403 and 'Akses Ditolak' in r.text)
 for id,qty,jenis,msg in [('BB-16',0,'keluar','Data tidak lengkap'),('BB-17',-1,'keluar','Data tidak lengkap'),('BB-18',11,'keluar','Stok tidak mencukupi'),('BB-19',10,'keluar','Barang keluar berhasil dicatat'),('BB-20',1,'invalid','Jenis transaksi tidak valid')]:
  before=len(capture()); r=req('actions/transaksi_action.php',{'action':'input_transaksi','id_barang':1,'qty':qty,'jenis':jenis},staff); f=flash(staff); inserts=[q for q in capture()[before:] if 'INSERT INTO transaksi' in q[1]]; should=id=='BB-19'; record(id,M,'Validasi transaksi',msg+('; INSERT dipanggil' if should else '; tidak INSERT'),f'HTTP {r.status_code}; pesan={msg in f}; INSERT={len(inserts)}',msg in f and bool(inserts)==should)
 r=req('actions/permintaan_action.php',{'action':'buat_permintaan','id_barang':1,'qty':0},staff); record('BB-21',M,'Permintaan qty 0','Data tidak lengkap',f'HTTP {r.status_code}; pesan={"Data tidak lengkap" in flash(staff)}','Data tidak lengkap' in flash(staff))
 before=len(capture()); r=req('actions/transaksi_action.php',{'action':'input_transaksi','id_barang':1,'qty':1,'jenis':'masuk'}); inserts=[q for q in capture()[before:] if 'INSERT INTO transaksi' in q[1]]; record('BB-22',M,'Transaksi tanpa sesi','Redirect login sebelum INSERT',f'HTTP {r.status_code}; tujuan={r.headers.get("Location")}; INSERT dipanggil={bool(inserts)}',r.headers.get('Location')=='/cleaning/auth/login.php' and not inserts)
 with sync_playwright() as p:
  b=p.chromium.launch(executable_path=shutil.which('chromium'),headless=True,args=['--no-sandbox']); context=b.new_context(viewport={'width':375,'height':812}); context.route('**/*',lambda route:route.abort()); page=context.new_page(); page.set_content(req('auth/login.php').text,wait_until='load'); text=page.locator('body').inner_text(); page.screenshot(path=str(E/'BB-23-login-mobile.png'),full_page=True); record('BB-23','Offline DOM fixture','Footer login','Brand baru dan tanpa kredit kelompok',f'brand={"Cleaning Stock Management" in text}; kredit lama={"Kelompok 1" in text}', 'Cleaning Stock Management' in text and 'Kelompok 1' not in text)
  page.set_content(req('pages/transaksi_keluar.php',s=staff).text,wait_until='load'); footer=page.locator('.app-footer');
  for id,width in [('BB-24',375),('BB-25',768),('BB-26',1280)]:
   page.set_viewport_size({'width':width,'height':812}); footer.scroll_into_view_if_needed(); m=footer.evaluate('(el)=>({left:el.getBoundingClientRect().left,right:el.getBoundingClientRect().right,scroll:el.scrollWidth,client:el.clientWidth})'); page.screenshot(path=str(E/f'{id}-footer-{width}.png')); record(id,'Offline DOM fixture','Footer transaksi',f'Footer dalam viewport {width}px dan tanpa overflow internal',str(m),m['left']>=-1 and m['right']<=width+1 and m['scroll']<=m['client']+1)
  page.set_viewport_size({'width':375,'height':812}); page.evaluate('window.scrollTo(0,0)'); toggle=page.locator('#sidebarToggle'); toggle.evaluate('(e)=>e.click()'); opened=toggle.get_attribute('aria-expanded');page.keyboard.press('Escape'); closed=toggle.get_attribute('aria-expanded');toggle.evaluate('(e)=>e.click()');page.locator('#content').evaluate('(e)=>e.click()'); outside=toggle.get_attribute('aria-expanded');record('BB-27','Offline DOM fixture','Sidebar mobile','true saat buka, false setelah Escape dan klik luar',f'{opened}, {closed}, {outside}',(opened,closed,outside)==('true','false','false'));b.close()
finally:
 for srv in servers:srv.terminate();srv.wait(timeout=5)
 with (OUT/'component-results.csv').open('w',newline='',encoding='utf-8') as f:
  w=csv.DictWriter(f,fieldnames=['ID','Method','Area','Expected','Actual','Status']);w.writeheader();w.writerows(rows)
 shutil.copyfile(CAP,OUT/'fixture-query-trace.jsonl');shutil.rmtree(T)
