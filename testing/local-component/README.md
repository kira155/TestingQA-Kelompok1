# Local component harness

Test-only, no production data. Run on a disposable copy of the 24 November source with PHP CLI **without mysqli**, Python requests, Playwright and Chromium executable on PATH. Do not deploy the adapter into the website. The adapter only records SQL calls and returns fixtures; it does not emulate SQL semantics or persistence. Source is not included in this QA repository.

```bash
python testing/local-component/run.py /path/to/parent/of/cleaning /path/to/test-output
```

The script starts two localhost PHP processes and terminates them when done. Offline DOM checks use actual PHP-rendered HTML with external assets blocked. The captured results are in `results/2026-11-28/`; fixture screenshots are under `evidence/2026-11-28/`. Do not count these as manual end-to-end evidence. The credentials in the adapter are synthetic and valid only in the isolated fixture. 
