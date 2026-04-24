<?php
require 'vendor/autoload.php';
// Mock Laravel environment for standalone test
require 'app/Helpers/MailerHelper.php';

function config($key, $default = null) {
    $map = [
        'mail.mailers.smtp.host' => 'smtp.gmail.com',
        'mail.mailers.smtp.username' => 'eurotaxisystem@gmail.com',
        'mail.mailers.smtp.password' => 'uhoa dxiz hmmk dvqv',
        'mail.mailers.smtp.encryption' => 'tls',
        'mail.mailers.smtp.port' => 587,
        'mail.from.address' => 'eurotaxisystem@gmail.com',
        'mail.from.name' => 'Euro Taxi System Test'
    ];
    return $map[$key] ?? $default;
}

class Log {
    public static function info($msg) { echo "INFO: $msg\n"; }
    public static function error($msg) { echo "ERROR: $msg\n"; }
}

$to = 'bertltv@gmail.com'; // User's email likely
$subject = 'Test SMTP Check';
$body = '<h1>Local SMTP Test</h1><p>Testing PHPMailer logic.</p>';

echo "Starting local test...\n";
if (send_custom_email($to, $subject, $body)) {
    echo "SUCCESS: Email sent!\n";
} else {
    echo "FAILURE: Check logs/output.\n";
}
