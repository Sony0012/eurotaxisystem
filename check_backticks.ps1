$text = Get-Content 'c:\xampp\htdocs\eurotaxisystem\js_dump.js' -Raw
$count = 0
foreach ($c in $text.ToCharArray()) {
    if ($c -eq [char]96) { $count++ }
}
Write-Output "Backticks: $count"
