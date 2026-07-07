<?php
// ============================================
// 📧 FUNCIONES DE ENVÍO DE CORREO
// ============================================

function enviarCorreoBienvenida($email, $name, $password) {
    $to = $email;
    $subject = "¡Bienvenido al Asistente Técnico! Tus credenciales de acceso";
    
    $message = "
    <html>
    <head><title>Bienvenido</title></head>
    <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px;'>
            <h2 style='color: #667eea;'>¡Hola $name!</h2>
            <p>Gracias por adquirir el acceso al Asistente Técnico Multi-Electrodomésticos.</p>
            <p>Tu cuenta ha sido creada exitosamente. Aquí tienes tus credenciales para ingresar:</p>
            <div style='background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <p><strong>Usuario (Email):</strong> $email</p>
                <p><strong>Contraseña:</strong> $password</p>
            </div>
            <p style='text-align: center;'>
                <a href='https://electrodomesticos.omarcuellar.co' style='background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>Ingresar a la App</a>
            </p>
            <p style='font-size: 12px; color: #888;'>Por seguridad, te recomendamos cambiar tu contraseña después del primer ingreso.</p>
        </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Asistente Técnico <no-reply@omarcuellar.co>' . "\r\n";

    mail($to, $subject, $message, $headers);
}
?>