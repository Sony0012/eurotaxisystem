# Laravel IDE Extension PHP Path Fix

## Problem
The Laravel IDE extension is showing errors:
```
'php' is not recognized as an internal or external command
```

## Root Cause
The Laravel IDE extension cannot find PHP because it's not in the system PATH.

## Solutions

### Option 1: Quick Fix (Recommended)
Run the PowerShell script:
```powershell
.\fix-laravel-ide.ps1
```

### Option 2: Batch File
Run the batch file:
```cmd
fix-laravel-ide.bat
```

### Option 3: VS Code Settings
1. Copy `vscode-settings.json` contents to VS Code settings
2. Or create `.vscode/settings.json` in your project

### Option 4: Manual PATH Update
1. Open System Environment Variables
2. Add `C:\xampp\php` to PATH
3. Restart VS Code

## Files Created
- `fix-laravel-ide.ps1` - PowerShell script
- `fix-laravel-ide.bat` - Batch script  
- `vscode-settings.json` - VS Code settings

## Verification
After running any fix:
1. Restart VS Code
2. Laravel IDE extension should work without errors
3. Auto-completion and intellisense will function

## Notes
- This fixes the current session only
- For permanent fix, use Option 4 (System PATH)
- XAMPP PHP location: `C:\xampp\php\php.exe`
