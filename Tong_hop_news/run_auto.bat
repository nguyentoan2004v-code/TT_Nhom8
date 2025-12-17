@echo off
REM Enable UTF-8 encoding for console and Python
chcp 65001 >nul 2>&1
set PYTHONUTF8=1
set PYTHONIOENCODING=utf-8

cd /d "D:\DOANCN\TT_Nhom8\Tong_hop_news"

REM Log start timestamp
echo [%date% %time%] Starting scraper >> scrape.log

REM Run Python scraper unbuffered and tee output to console and scrape.log
REM Uses PowerShell's Tee-Object so you can see live output in the opened CMD window
powershell -NoProfile -Command "py -u main.py 2>&1 | Tee-Object -FilePath 'scrape.log' -Append"

REM Log completion timestamp
echo [%date% %time%] Scraper finished >> scrape.log
echo.

pause