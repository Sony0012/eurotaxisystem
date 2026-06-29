$file = $args[0]
$text = Get-Content $file -Raw
$count = 0
$line = 1
for ($i=0; $i -lt $text.Length; $i++) {
    if ($text[$i] -eq "`n") { $line++ }
    if ($text[$i] -eq '{') { $count++ }
    if ($text[$i] -eq '}') { $count-- }
    if ($count -lt 0) {
        Write-Output "Unmatched } at line $line"
        break
    }
}
Write-Output "Final count: $count"
