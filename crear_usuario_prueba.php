<?php
require 'db.php';

$email = 'test@omarcuellar.co';
$password = '123456';

// Hashear la contraseña (seguridad)
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->execute([$email, $hash]);
    echo "✅ ¡Usuario creado exitosamente!<br>";
    echo "Correo: <strong>$email</strong><br>";
    echo "Contraseña: <strong>$password</strong>";
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "⚠️ El usuario ya existe en la base de datos.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>