<?php
// ============================================
// 🔔 WEBHOOK DE HOTMART (SEGURO)
// ============================================
require 'db.php';
require 'mail_functions.php';

// 🔐 TOKEN SECRETO (debe coincidir con el configurado en Hotmart)
define('HOTMART_TOKEN', 'HM_ELECTRO_2026_X7K9M2P4Q8R1');

// Verificar token de seguridad
$headers = getallheaders();
$receivedToken = $headers['X-Hotmart-Token'] ?? ($_GET['token'] ?? '');

if ($receivedToken !== HOTMART_TOKEN) {
    http_response_code(401);
    echo json_encode(['error' => 'Token no válido']);
    exit;
}

// Obtener datos del webhook
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$event = $input['event'] ?? '';

// Registrar evento en log para depuración
file_put_contents(__DIR__ . '/webhook_log.txt', 
    date('Y-m-d H:i:s') . " - Evento: $event\n", FILE_APPEND);

// Solo procesar compras aprobadas
if ($event !== 'PURCHASE_APPROVED') {
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Evento ignorado: ' . $event]);
    exit;
}

// ============================================
// 📥 EXTRAER DATOS DEL COMPRADOR
// ============================================
$email = $input['data']['buyer']['email'] 
      ?? $input['purchase']['buyer']['email'] 
      ?? '';
$name = $input['data']['buyer']['name'] 
     ?? $input['purchase']['buyer']['name'] 
     ?? 'Cliente';
$hotmartId = $input['data']['id'] 
          ?? $input['purchase']['id'] 
          ?? '';
$productName = $input['data']['product']['name'] 
            ?? $input['purchase']['product']['name'] 
            ?? 'Asistente Técnico';

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'No se encontró el email del comprador']);
    exit;
}

// ============================================
// 👤 CREAR USUARIO
// ============================================
$password = substr(bin2hex(random_bytes(4)), 0, 8);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Verificar si el usuario ya existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        // Usuario ya existe, enviar credenciales existentes
        file_put_contents(__DIR__ . '/webhook_log.txt', 
            date('Y-m-d H:i:s') . " - Usuario ya existe: $email\n", FILE_APPEND);
        
        enviarCorreoReenvio($email, $name, $productName);
        
        http_response_code(200);
        echo json_encode(['status' => 'ok', 'message' => 'Usuario ya existe, correo reenviado']);
        exit;
    }

    // Insertar nuevo usuario
    $stmt = $db->prepare("INSERT INTO users (email, password, hotmart_id, status) VALUES (?, ?, ?, 'active')");
    $stmt->execute([$email, $hashedPassword, $hotmartId]);
    
    // Registrar en log
    file_put_contents(__DIR__ . '/webhook_log.txt', 
        date('Y-m-d H:i:s') . " - Usuario creado: $email\n", FILE_APPEND);
    
    // Enviar correo de bienvenida
    enviarCorreoBienvenida($email, $name, $password, $productName);
    
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Usuario creado exitosamente']);
    
} catch (PDOException $e) {
    error_log('Error creando usuario: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno']);
}
?>