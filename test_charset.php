<?php
$conn = new mysqli("localhost", "u747826271_euro", "euro2026", "u747826271_euro");
$result = $conn->query("SHOW FULL COLUMNS FROM chat_messages LIKE \"reactions\"");
print_r($result->fetch_assoc());
$conn->close();

