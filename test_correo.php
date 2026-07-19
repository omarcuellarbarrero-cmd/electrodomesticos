<?php
// ============================================
// 🧪 PRUEBA DE CORREO CON PHPMAILER
// ⚠️ ELIMINAR DESPUÉS DE PROBAR
// ============================================

require_once 'mailer.php';

echo "<h2>📧 Prueba de Correo</h2>";
echo "<p><strong>SMTP_USER:</strong> " . SMTP_USER . "</p>";
echo "<p><strong>SMTP_PASS:</strong> " . (SMTP_PASS ? '(configurada)' : '(VACÍA - revisar variable de entorno)') . "</p>";

$to = 'omarcuellarbarrero@gmail.com';
$subject = '🧪 Prueba de PHPMailer con Brevo';
$htmlBody = "
<html>
<body style='font-family: Arial; padding: 20px;'>
    <h2 style='color: #667eea;'>¡Correo enviado exitosamente!</h2>
    <p>Si ves esto, PHPMailer + Brevo funciona correctamente.</p>
    <p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>
</body>
</html>
";

$result = enviarCorreoPHPMailer($to, $subject, $htmlBody);

if ($result) {
    echo "<h3 style='color: green;'>✅ ¡Correo enviado! Revisa tu bandeja.</h3>";
} else {
    echo "<h3 style='color: red;'>❌ Error al enviar. Revisa las credenciales en Coolify.</h3>";
}
?>