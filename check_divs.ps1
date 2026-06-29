$file = $args[0]
$text = Get-Content $file -Raw

$divOpen = ([regex]::Matches($text, "(?i)<div\b")).Count
$divClose = ([regex]::Matches($text, "(?i)</div\b")).Count

Write-Output "<div> count: $divOpen"
Write-Output "</div> count: $divClose"
Write-Output "Difference: $($divOpen - $divClose)"
