<?php
$old_lines = file("old_index.blade.php");
$top10_block = implode("", array_slice($old_lines, 137, 314 - 137));

$current_lines = file("resources/views/unit-profitability/index.blade.php");
$new_content = "";
foreach($current_lines as $i => $line) {
    if ($i == 137) {
        $new_content .= $top10_block;
    }
    $new_content .= $line;
}
file_put_contents("resources/views/unit-profitability/index.blade.php", $new_content);
echo "Restored Top 10 block.\n";
?>
