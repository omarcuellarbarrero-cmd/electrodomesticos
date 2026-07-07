<?php
// ============================================
// 📧 DIAGNÓSTICO DE ENVÍO DE CORREO
// ⚠️ ELIMINAR DESPUÉS DE PROBAR
// ============================================

echo "<h2>📧 Diagnóstico de Envío de Correo</h2>";

// Configuración
$to = 'soporte@omarcuellar.co'; // ← CAMBIA ESTO POR TU CORREO
$subject = 'Prueba de envío desde servidor';
$message = 'Este es un correo de prueba enviado desde el servidor.';
$headers = 'From: no-reply@omarcuellar.co' . "\r\n" .
           'Reply-To: no-reply@omarcuellar.co' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

echo "<h3>1. Configuración</h3>";
echo "<p><strong>Destinatario:</strong> $to</p>";
echo "<p><strong>Asunto:</strong> $subject</p>";
echo "<p><strong>Remitente:</strong> no-reply@omarcuellar.co</p>";

echo "<h3>2. Función mail()</h3>";
if (function_exists('mail')) {
    echo "✅ La función <code>mail()</code> está disponible<br>";
} else {
    echo "❌ La función <code>mail()</code> NO está disponible<br>";
}

echo "<h3>3. Intento de envío</h3>";
$result = mail($to, $subject, $message, $headers);

if ($result) {
    echo "✅ La función <code>mail()</code> retornó <strong>TRUE</strong><br>";
    echo "⚠️ Pero esto NO garantiza que el correo llegue<br>";
    echo "📧 Revisa tu bandeja de entrada y SPAM<br>";
} else {
    echo "❌ La función <code>mail()</code> retornó <strong>FALSE</strong><br>";
    echo "🔍 El servidor no puede enviar correos<br>";
}

echo "<h3>4. Configuración de PHP</h3>";
echo "<p><strong>sendmail_path:</strong> " . ini_get('sendmail_path') . "</p>";

echo "<hr>";
echo "<h3>📋 Conclusión</h3>";
if ($result) {
    echo "<p>✅ El servidor puede enviar correos, pero probablemente caen en SPAM.</p>";
    echo "<p>💡 <strong>Solución:</strong> Usar PHPMailer con SMTP (Brevo, Gmail, etc.)</p>";
} else {
    echo "<p>❌ El servidor no tiene capacidad de enviar correos.</p>";
    echo "<p>💡 <strong>Solución obligatoria:</strong> Instalar PHPMailer con SMTP</p>";
}
?>