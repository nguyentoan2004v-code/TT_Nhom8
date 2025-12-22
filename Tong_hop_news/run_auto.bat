@echo off
REM 
chcp 65001 >nul 2>&1
set PYTHONUTF8=1
set PYTHONIOENCODING=utf-8

cd /d "%~dp0"

REM 
echo [%date% %time%] Starting scraper >> scrape.log

REM 
REM 
powershell -NoProfile -Command "py -u main.py 2>&1 | Tee-Object -FilePath 'scrape.log' -Append"

REM 
echo [%date% %time%] Scraper finished >> scrape.log
echo.

pause