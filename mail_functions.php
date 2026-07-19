<?php
// ============================================
// 📧 FUNCIONES DE ENVÍO DE CORREO (PHPMAILER)
// ============================================

require_once __DIR__ . '/mailer.php';

function enviarCorreoBienvenida($email, $name, $password, $productName = 'Asistente Técnico') {
    $subject = "🔧 Bienvenido a $productName - Tus credenciales de acceso";
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);'>
            <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;'>
                <h1 style='color: white; margin: 0;'>🔧 $productName</h1>
                <p style='color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 14px;'>Tu asistente técnico con IA</p>
            </div>
            <div style='padding: 30px;'>
                <h2 style='color: #333;'>¡Hola $name! 👋</h2>
                <p style='color: #555; line-height: 1.6;'>Gracias por tu compra. Tu cuenta ha sido creada exitosamente.</p>
                <div style='background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; border-radius: 8px; margin: 25px 0;'>
                    <h3 style='color: #667eea; margin: 0 0 15px;'>🔐 Tus Credenciales</h3>
                    <p style='margin: 8px 0;'><strong>Usuario:</strong> $email</p>
                    <p style='margin: 8px 0;'><strong>Contraseña:</strong> <code style='background: #e8e8e8; padding: 4px 8px; border-radius: 4px; font-size: 16px;'>$password</code></p>
                </div>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='https://electrodomesticos.omarcuellar.co' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: bold;'>🚀 Ingresar a la App</a>
                </div>
            </div>
            <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;'>
                <p style='margin: 0; color: #999; font-size: 12px;'>Asistente Técnico v1.0 — Powered by Groq AI</p>
            </div>
        </div>
    </body>
    </html>
    ";

    return enviarCorreoPHPMailer($email, $subject, $message);
}

function enviarCorreoReenvio($email, $name, $productName = 'Asistente Técnico') {
    $subject = "🔧 $productName - Tu cuenta ya existe";
    $message = "
    <html>
    <body style='font-family: Arial; background: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px;'>
            <h2 style='color: #667eea;'>¡Hola $name!</h2>
            <p>Ya tienes una cuenta activa con este correo.</p>
            <p style='text-align: center; margin: 25px 0;'>
                <a href='https://electrodomesticos.omarcuellar.co' style='background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Ingresar a la App</a>
            </p>
            <p style='color: #888; font-size: 13px;'>Si no recuerdas tu contraseña, contacta al administrador.</p>
        </div>
    </body>
    </html>
    ";

    return enviarCorreoPHPMailer($email, $subject, $message);
}
?>