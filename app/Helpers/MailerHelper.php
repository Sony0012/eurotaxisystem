<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Manual inclusion because it's not in the vendor/autoload
require_once __DIR__ . '/../Libraries/PHPMailer/Exception.php';
require_once __DIR__ . '/../Libraries/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../Libraries/PHPMailer/SMTP.php';

if (!function_exists('send_custom_email')) {
    /**
     * Send an email using PHPMailer (Anti-Spam Configuration)
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email content (HTML)
     * @param string|null $altBody Plain text version of the body
     * @return bool True if sent, false otherwise
     */
    function send_custom_email($to, $subject, $body, $altBody = null)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set to DEBUG_SERVER for troubleshooting
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Port = env('MAIL_PORT', 587);

            // Anti-Spam Headers
            $mail->CharSet = 'UTF-8';
            $mail->setFrom(env('MAIL_FROM_ADDRESS', 'noreply@eurotaxisystem.site'), env('MAIL_FROM_NAME', 'Eurotaxisystem'));
            $mail->addAddress($to);
            $mail->addReplyTo(env('MAIL_FROM_ADDRESS', 'support@eurotaxisystem.site'), env('MAIL_FROM_NAME', 'Support'));

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            // Additional headers to avoid spam filters
            $mail->addCustomHeader('X-Priority', '3');
            $mail->addCustomHeader('X-Mailer', 'EurotaxisystemPHPMailer');

            return $mail->send();
        } catch (Exception $e) {
            \Log::error("Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
