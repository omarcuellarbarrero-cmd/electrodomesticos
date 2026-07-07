<?php
// ============================================
// 🔔 WEBHOOK DE HOTMART
// ============================================
require 'db.php';
require 'mail_functions.php';

// Obtener datos del webhook
$input = json_decode(file_get_contents('php://input'), true);
$event = $input['event'] ?? '';

// Solo procesar compras aprobadas
if ($event !== 'PURCHASE_APPROVED') {
    http_response_code(200);
    exit;
}

// Extraer datos del comprador
$email = $input['purchase']['buyer']['email'] ?? '';
$name = $input['purchase']['buyer']['name'] ?? 'Cliente';
$hotmartId = $input['purchase']['id'] ?? '';

if (!$email) {
    http_response_code(400);
    exit;
}

// Generar contraseña aleatoria
$password = bin2hex(random_bytes(4));
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Verificar si el usuario ya existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        http_response_code(200);
        exit;
    }

    // Insertar nuevo usuario
    $stmt = $db->prepare("INSERT INTO users (email, password, hotmart_id) VALUES (?, ?, ?)");
    $stmt->execute([$email, $hashedPassword, $hotmartId]);
    
    // Enviar correo de bienvenida
    enviarCorreoBienvenida($email, $name, $password);
    
    http_response_code(200);
    
} catch (PDOException $e) {
    error_log('Error creando usuario: ' . $e->getMessage());
    http_response_code(500);
}
?>