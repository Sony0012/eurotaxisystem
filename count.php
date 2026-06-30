<?php
$c = file_get_contents('resources/views/driver-management/partials/_driver_details_modal.blade.php');
echo 'Open: ' . substr_count($c, '<div') . ' Close: ' . substr_count($c, '</div');
