# Auto-sync script for EuroTaxi System
# This script monitors changes and auto-commits every X minutes

# Configuration
$REPO_PATH = "c:\xampp\htdocs\eurotaxisystem"
$COMMIT_INTERVAL = 300  # seconds (5 minutes)
$GIT_PATH = "C:\Program Files\Git\bin\git.exe"

# Ensure path to git is set
$env:Path += ";C:\Program Files\Git\bin"

# Check if repo exists
if (-not (Test-Path "$REPO_PATH\.git")) {
    Write-Host "Error: Git repository not found at $REPO_PATH" -ForegroundColor Red
    exit 1
}

# Set location to repo
Set-Location $REPO_PATH

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   EuroTaxi - Auto Git Sync Script" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Repository: $REPO_PATH" -ForegroundColor Green
Write-Host "Commit Interval: $COMMIT_INTERVAL seconds (5 minutes)" -ForegroundColor Green
Write-Host ""
Write-Host "Starting auto-sync... Press Ctrl+C to stop" -ForegroundColor Yellow
Write-Host ""

while ($true) {
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    
    try {
        # Get list of changed files
        $changes = & git status --porcelain
        $changeCount = @($changes).Count
        
        if ($changeCount -gt 0) {
            Write-Host "[$timestamp] Found $changeCount changed file(s). Auto-committing..." -ForegroundColor Yellow
            
            # Stage all changes
            & git add .
            
            # Commit with timestamp
            $commitMsg = "auto: $timestamp - Auto-synced changes ($changeCount files)"
            & git commit -m $commitMsg
            
            # Push to GitHub
            Write-Host "[$timestamp] Pushing to GitHub..." -ForegroundColor Cyan
            & git push origin main
            
            Write-Host "[$timestamp] ✅ Successfully synced to GitHub!" -ForegroundColor Green
        } else {
            Write-Host "[$timestamp] ⏳ No changes detected. Waiting..." -ForegroundColor Gray
        }
    } catch {
        Write-Host "[$timestamp] ❌ Error: $_" -ForegroundColor Red
    }
    
    # Wait for next check
    Write-Host "[$timestamp] Sleeping for $COMMIT_INTERVAL seconds..." -ForegroundColor Gray
    Start-Sleep -Seconds $COMMIT_INTERVAL
}
