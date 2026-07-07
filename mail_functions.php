<?php
// ============================================
// 📧 FUNCIONES DE ENVÍO DE CORREO
// ============================================

function enviarCorreoBienvenida($email, $name, $password, $productName = 'Asistente Técnico') {
    $to = $email;
    $subject = "🔧 Bienvenido a $productName - Tus credenciales de acceso";
    
    $message = "
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Bienvenido</title>
    </head>
    <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0;'>
        <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);'>
            
            <!-- Header -->
            <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;'>
                <h1 style='color: white; margin: 0; font-size: 24px;'>🔧 $productName</h1>
                <p style='color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 14px;'>Tu asistente técnico con Inteligencia Artificial</p>
            </div>
            
            <!-- Body -->
            <div style='padding: 30px;'>
                <h2 style='color: #333; font-size: 20px;'>¡Hola $name! 👋</h2>
                <p style='color: #555; line-height: 1.6;'>
                    Gracias por tu compra. Tu cuenta ha sido creada exitosamente y ya puedes comenzar a usar el Asistente Técnico.
                </p>
                
                <!-- Credenciales -->
                <div style='background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; border-radius: 8px; margin: 25px 0;'>
                    <h3 style='color: #667eea; margin: 0 0 15px; font-size: 16px;'>🔐 Tus Credenciales de Acceso</h3>
                    <p style='margin: 8px 0; color: #333;'>
                        <strong>Usuario (Email):</strong><br>
                        <span style='color: #667eea;'>$email</span>
                    </p>
                    <p style='margin: 8px 0; color: #333;'>
                        <strong>Contraseña:</strong><br>
                        <code style='background: #e8e8e8; padding: 4px 8px; border-radius: 4px; font-size: 16px;'>$password</code>
                    </p>
                </div>
                
                <!-- Botón -->
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='https://electrodomesticos.omarcuellar.co' 
                       style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: bold; display: inline-block;'>
                        🚀 Ingresar a la App
                    </a>
                </div>
                
                <!-- Tips -->
                <div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; color: #856404; font-size: 13px;'>
                        ⚠️ <strong>Importante:</strong> Por tu seguridad, te recomendamos memorizar tu contraseña. 
                        Si necesitas recuperarla, contacta al administrador.
                    </p>
                </div>
            </div>
            
            <!-- Footer -->
            <div style='background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;'>
                <p style='margin: 0; color: #999; font-size: 12px;'>
                    Asistente Técnico v1.0 — Powered by Groq AI<br>
                    © 2026 Omar Cuéllar. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </body>
    </html>
    ";

    enviarCorreo($to, $subject, $message);
}

function enviarCorreoReenvio($email, $name, $productName = 'Asistente Técnico') {
    $to = $email;
    $subject = "🔧 $productName - Tu cuenta ya existe";
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px;'>
            <h2 style='color: #667eea;'>¡Hola $name!</h2>
            <p>Notamos que intentaste comprar de nuevo, pero ya tienes una cuenta activa con este correo.</p>
            <p>Puedes ingresar directamente en:</p>
            <p style='text-align: center;'>
                <a href='https://electrodomesticos.omarcuellar.co' 
                   style='background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;'>
                    Ingresar a la App
                </a>
            </p>
            <p style='color: #888; font-size: 13px;'>Si no recuerdas tu contraseña, contacta al administrador.</p>
        </div>
    </body>
    </html>
    ";

    enviarCorreo($to, $subject, $message);
}

// Función central de envío
function enviarCorreo($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Asistente Técnico <no-reply@omarcuellar.co>' . "\r\n";
    $headers .= 'Reply-To: soporte@omarcuellar.co' . "\r\n";

    mail($to, $subject, $message, $headers);
}
?>