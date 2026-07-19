<?php
// ============================================
// 🧪 SIMULADOR DE COMPRA DE HOTMART
// ⚠️ ELIMINAR DESPUÉS DE PROBAR
// ============================================

$token = 'HM_ELECTRO_2026_X7K9M2P4Q8R1';
$url = 'https://electrodomesticos.omarcuellar.co/hotmart_webhook.php?token=' . $token;

// Simular datos de compra aprobada
$payload = json_encode([
    'event' => 'PURCHASE_APPROVED',
    'data' => [
        'id' => 'TEST_' . time(),
        'buyer' => [
            'email' => 'pruebacliente@gmail.com',
            'name' => 'Juan Pérez'
        ],
        'product' => [
            'name' => 'Asistente Técnico Multi-Electrodomésticos'
        ]
    ]
]);

// Enviar petición
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

echo "<h2>🧪 Resultado del Test</h2>";
echo "<p><strong>Código HTTP:</strong> $httpCode</p>";
echo "<p><strong>Respuesta:</strong> $response</p>";

if ($httpCode == 200) {
    echo "<h3 style='color:green;'>✅ ¡Webhook funciona correctamente!</h3>";
    echo "<p>Revisa el correo de <strong>pruebacliente@gmail.com</strong></p>";
} else {
    echo "<h3 style='color:red;'>❌ Algo falló. Revisa los logs.</h3>";
}
?>