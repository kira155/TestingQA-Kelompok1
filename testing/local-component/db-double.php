<?php
// Test-only adapter: no MySQL, no production records, never deploy to htdocs.
if (extension_loaded('mysqli')) { exit('Use PHP without mysqli for this isolated component harness.'); }
define('MYSQLI_ASSOC', 1);
function fixture_users() { return [ ['id_user'=>1,'username'=>'qa_staff','password'=>password_hash('FixtureOnly123!',PASSWORD_DEFAULT),'role'=>'staff'], ['id_user'=>2,'username'=>'qa_admin','password'=>password_hash('FixtureOnly123!',PASSWORD_DEFAULT),'role'=>'admin'] ]; }
function capture($kind,$sql,$params=[]) { if (str_contains($sql,'INSERT INTO users')) $params[1]='[HASH REDACTED]'; file_put_contents(getenv('QA_CAPTURE'),json_encode([$kind,trim(preg_replace('/\s+/', ' ', $sql)),$params]).PHP_EOL,FILE_APPEND); }
class FakeResult {
 public $num_rows; function __construct(public array $rows=[]) { $this->num_rows=count($rows); }
 function fetch_assoc(){return array_shift($this->rows);}
 function fetch_all($mode=1){return $this->rows;}
}
class FakeStmt {
 public $num_rows=0; public $error=''; private $params=[]; private $result;
 function __construct(public string $sql){}
 function bind_param($types,&...$params){$this->params=$params;}
 function execute(){capture('execute',$this->sql,$this->params); $rows=[]; if(preg_match('/SELECT .* FROM users WHERE username/i',$this->sql)) $rows=array_values(array_filter(fixture_users(),fn($u)=>$u['username']===($this->params[0]??''))); $this->result=new FakeResult($rows); $this->num_rows=count($rows); return true;}
 function get_result(){return $this->result;} function store_result(){} function close(){}
}
class mysqli {
 public $connect_errno=0; public $error=''; function __construct(...$args){} function set_charset($s){} function close(){}
 function prepare($sql){return new FakeStmt($sql);}
 function query($sql){capture('query',$sql); $s=trim(preg_replace('/\s+/',' ',$sql)); if(str_starts_with($s,'SELECT harga_barang FROM barang')) return new FakeResult([['harga_barang'=>5000]]); if(str_starts_with($s,'SELECT stok_sekarang FROM barang')) return new FakeResult([['stok_sekarang'=>10]]); if(str_contains($s,'SELECT id_barang, nama_barang, satuan, stok_sekarang')) return new FakeResult([['id_barang'=>1,'nama_barang'=>'QA Test Item','satuan'=>'pcs','stok_sekarang'=>10]]); if(str_starts_with($s,'UPDATE')||str_starts_with($s,'DELETE')) return true; return new FakeResult([]); }
}
