<?php
// ============================================
// 🔐 SISTEMA DE AUTENTICACIÓN
// ============================================
session_start();
require 'db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    echo json_encode(['success' => true, 'redirect' => 'app.html']);
} else {
    echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
}
?>