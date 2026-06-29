$content = Get-Content "c:\xampp\htdocs\eurotaxisystem\resources\views\dashboard.blade.php" -Raw
$pattern = "(?s)<script>(.*?)</script>"
$matches = [regex]::Matches($content, $pattern)
$allScripts = ""
foreach ($match in $matches) {
    $allScripts += $match.Groups[1].Value + "`n`n"
}

$pattern2 = "(?s)</script><script>(.*?)</script>"
$matches2 = [regex]::Matches($content, $pattern2)
foreach ($match in $matches2) {
    $allScripts += $match.Groups[1].Value + "`n`n"
}

$pattern3 = "(?s)<script>(.*?)</script><script>"
$matches3 = [regex]::Matches($content, $pattern3)
foreach ($match in $matches3) {
    $allScripts += $match.Groups[1].Value + "`n`n"
}

Set-Content -Path "c:\xampp\htdocs\eurotaxisystem\js_dump.js" -Value $allScripts -Encoding UTF8
