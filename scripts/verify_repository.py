#!/usr/bin/env python3
"""Pemeriksaan konsistensi artefak QA, tanpa akses web, database, atau dependensi tambahan."""
import csv
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
CSV = ROOT / 'test-cases' / 'test-cases.csv'


def check():
    problems = []
    with CSV.open('r', encoding='utf-8-sig', newline='') as handle:
        rows = list(csv.DictReader(handle))
    ids = [row['ID'].strip() for row in rows]

    if len(ids) != 30:
        problems.append(f'Diharapkan 30 test case historis; ditemukan {len(ids)}.')
    if len(ids) != len(set(ids)):
        problems.append('ID test case duplikat ditemukan.')
    expected = [f'TC-{i:03d}' for i in range(1, 31)]
    if ids != expected:
        problems.append('Urutan atau ID test case tidak sesuai TC-001 s.d. TC-030.')
    if any(not row['Status'].strip() for row in rows):
        problems.append('Ada status test case yang kosong.')

    missing_evidence = []
    evidence_ref = set()
    for row in rows:
        for item in row.get('Evidence', '').split(';'):
            item = item.strip()
            if not item or item.lower() in {'-', 'belum teridentifikasi', 'n/a'}:
                continue
            if '/' in item or '\\' in item or not item.lower().endswith('.png'):
                problems.append(f"Format evidence tidak dikenal pada {row['ID']}: {item}")
                continue
            evidence_ref.add(item)
            path = ROOT / 'evidence' / item
            if not path.is_file():
                missing_evidence.append(f"{row['ID']}: {item}")
            elif path.read_bytes()[:8] != b'\x89PNG\r\n\x1a\n':
                problems.append(f'Bukan berkas PNG valid: {item}')
    if missing_evidence:
        problems.append('Bukti yang direferensikan belum ditemukan: ' + ', '.join(missing_evidence))

    for document in (ROOT / 'README.md', ROOT / 'results/test-execution-report.md'):
        content = document.read_text(encoding='utf-8')
        for raw_link in re.findall(r'\]\(([^)]+)\)', content):
            target = raw_link.split('#', 1)[0].strip()
            if not target or '://' in target or target.startswith(('mailto:', '#')):
                continue
            if not (document.parent / target).exists():
                problems.append(f'Link rusak pada {document.relative_to(ROOT)}: {target}')

    def load_csv(name):
        with (ROOT / 'test-cases' / name).open(encoding='utf-8-sig', newline='') as f:
            return list(csv.DictReader(f))
    cases = load_csv('keyword-test-cases.csv')
    steps = load_csv('keyword-steps.csv')
    library = load_csv('keyword-library.csv')
    case_ids = {x['ID'] for x in cases}
    keywords = {x['Keyword'] for x in library}
    if len(cases) != 53 or len(case_ids) != 53 or not set(ids).issubset(case_ids):
        problems.append('Daftar 53 kasus KDT tidak konsisten.')
    if len(keywords) != len(library):
        problems.append('Keyword duplikat.')
    for case in cases:
        group = [x for x in steps if x['ID'] == case['ID']]
        if not group or [int(x['Urutan']) for x in group] != list(range(1, len(group)+1)):
            problems.append('Urutan keyword tidak lengkap: ' + case['ID'])
        if not case['Expected_Result'] or not case['Precondition']:
            problems.append('Expected result / precondition kosong: ' + case['ID'])
        if case['Status'] in {'PASS', 'FAIL'} and not all(case[k] for k in ('Actual_Result', 'Tester', 'Tanggal', 'Bukti_Baru')):
            problems.append('Hasil manual belum lengkap: ' + case['ID'])
    if any(x['Keyword'] not in keywords or x['ID'] not in case_ids for x in steps):
        problems.append('Referensi keyword atau ID tidak dikenal.')
    print(f'KDT: {len(cases)} kasus, {len(steps)} langkah, {len(keywords)} keyword')

    screenshots = list((ROOT / 'evidence').glob('*.png'))
    print(f'Test case CSV: {len(rows)} | ID unik: {len(set(ids))}')
    print(f'Evidence tercantum: {len(evidence_ref)} | PNG tersedia: {len(screenshots)}')
    print('Pemeriksaan: ID, status, referensi evidence, tanda tangan PNG, dan tautan Markdown.')
    if problems:
        print('\nGAGAL:')
        for item in problems:
            print(' - ' + item)
        return 1
    print('LULUS: konsistensi repository terverifikasi secara lokal.')
    return 0


if __name__ == '__main__':
    sys.exit(check())
