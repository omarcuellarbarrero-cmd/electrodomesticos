<?php
// ============================================
// 📧 CONFIGURACIÓN DE PHPMAILER CON BREVO
// ============================================

require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Las credenciales se leen desde Coolify (Environment Variables)
define('SMTP_HOST', 'smtp-relay.brevo.com');
define('SMTP_PORT', 587);
define('SMTP_USER', getenv('BREVO_SMTP_USER'));
define('SMTP_PASS', getenv('BREVO_SMTP_PASS'));
define('SMTP_FROM', 'no-reply@omarcuellar.co');
define('SMTP_FROM_NAME', 'Asistente Técnico');

function enviarCorreoPHPMailer($to, $subject, $htmlBody) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);
        $mail->addReplyTo('soporte@omarcuellar.co', 'Soporte Técnico');
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags($htmlBody);
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Error enviando correo: {$mail->ErrorInfo}");
        return false;
    }
}
?>