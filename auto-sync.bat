@echo off
REM Auto-commit and push script for EuroTaxi System
REM This script will monitor for changes and auto-commit every X minutes

setlocal enabledelayedexpansion

REM Configuration
set REPO_PATH=c:\xampp\htdocs\eurotaxisystem
set COMMIT_INTERVAL=300
set GIT_PATH=C:\Program Files\Git\bin\git.exe

REM Check if repo exists
if not exist "%REPO_PATH%\.git" (
    echo Error: Git repository not found at %REPO_PATH%
    pause
    exit /b 1
)

cd /d %REPO_PATH%

echo.
echo ============================================
echo   EuroTaxi - Auto Git Sync Script
echo ============================================
echo Repository: %REPO_PATH%
echo Commit Interval: %COMMIT_INTERVAL% seconds
echo.
echo Starting auto-sync... Press Ctrl+C to stop
echo.

:loop
    REM Get current timestamp
    for /f "tokens=2-4 delims=/- " %%a in ('date /t') do set TODAY=%%c-%%a-%%b
    for /f "tokens=1-2 delims=/:" %%a in ('time /t') do set NOW=%%a:%%b

    REM Check if there are changes
    "%GIT_PATH%" status --porcelain > nul 2>&1
    if errorlevel 1 goto error

    REM Count changed files
    for /f %%c in ('"%GIT_PATH%" status --porcelain ^| find /c /v ""') do set CHANGES=%%c

    if %CHANGES% GTR 0 (
        echo [%TODAY% %NOW%] Found %CHANGES% changed file(s). Auto-committing...
        
        REM Stage all changes
        "%GIT_PATH%" add .
        
        REM Commit with timestamp
        "%GIT_PATH%" commit -m "auto: %TODAY% %NOW% - Auto-synced changes"
        
        REM Push to GitHub
        "%GIT_PATH%" push origin main
        
        if errorlevel 1 (
            echo [%TODAY% %NOW%] Warning: Push failed. Changes committed locally.
        ) else (
            echo [%TODAY% %NOW%] Successfully pushed to GitHub!
        )
    ) else (
        echo [%TODAY% %NOW%] No changes detected. Waiting...
    )

    REM Wait for next check
    timeout /t %COMMIT_INTERVAL% /nobreak
    goto loop

:error
    echo Error: Git command failed
    pause
    exit /b 1
