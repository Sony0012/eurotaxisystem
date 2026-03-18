# Fix Laravel IDE Extension PHP Path
Write-Host "Setting up PHP path for Laravel IDE Extension..." -ForegroundColor Green

# Add XAMPP PHP to current session PATH
$env:PATH = "C:\xampp\php;" + $env:PATH

# Set for current user (persistent)
[Environment]::SetEnvironmentVariable("PATH", "C:\xampp\php;" + [Environment]::GetEnvironmentVariable("PATH"), "User")

Write-Host "PHP Path updated successfully!" -ForegroundColor Green
Write-Host "Current PHP Path: C:\xampp\php" -ForegroundColor Yellow
Write-Host ""
Write-Host "Laravel IDE Extension should now work correctly!" -ForegroundColor Cyan
Write-Host "Please restart VS Code to apply changes." -ForegroundColor Yellow
Write-Host ""
Write-Host "Press any key to continue..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
