<?php
// ============================================
// 🧪 PRUEBA PERSONAL DEL WEBHOOK
// ⚠️ ELIMINAR DESPUÉS DE PROBAR
// ============================================

$token = 'HM_ELECTRO_2026_X7K9M2P4Q8R1';
$url = 'https://electrodomesticos.omarcuellar.co/hotmart_webhook.php?token=' . $token;

// 🔧 CAMBIA ESTOS DATOS POR LOS TUYOS
$miEmail = 'omarcuellarbarrero@gmail.com';  // ← CAMBIA ESTO
$miNombre = 'Omar Cuellar';              // ← CAMBIA ESTO

$payload = json_encode([
    'event' => 'PURCHASE_APPROVED',
    'data' => [
        'id' => 'TEST_PERSONAL_' . time(),
        'buyer' => [
            'email' => $miEmail,
            'name' => $miNombre
        ],
        'product' => [
            'name' => 'Asistente Técnico Multi-Electrodomésticos'
        ]
    ]
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Hotmart-Token: ' . $token
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h2>🧪 Resultado de la Prueba Personal</h2>";
echo "<p><strong>Correo enviado a:</strong> $miEmail</p>";
echo "<p><strong>Código HTTP:</strong> $httpCode</p>";
echo "<p><strong>Respuesta:</strong> $response</p>";

if ($httpCode == 200) {
    echo "<h3 style='color:green;'>✅ ¡Revisa tu bandeja de entrada!</h3>";
    echo "<p>El correo debería llegar en menos de 1 minuto.</p>";
    echo "<p>⚠️ Si no lo ves, revisa <strong>SPAM</strong> y <strong>Promociones</strong></p>";
} else {
    echo "<h3 style='color:red;'>❌ Algo falló</h3>";
}
?>