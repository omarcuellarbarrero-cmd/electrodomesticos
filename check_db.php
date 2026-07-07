<?php
// ============================================
// 🔍 DIAGNÓSTICO DE BASE DE DATOS
// ⚠️ ELIMINAR DESPUÉS DE USAR
// ============================================

echo "<h2>🔍 Diagnóstico de Base de Datos</h2>";

$dbFile = __DIR__ . '/users.db';

// 1. Verificar si el archivo existe
echo "<h3>1. Verificación del archivo</h3>";
if (file_exists($dbFile)) {
    echo "✅ El archivo <code>users.db</code> EXISTE<br>";
    echo "📏 Tamaño: " . filesize($dbFile) . " bytes<br>";
    echo "🔒 Permisos: " . substr(sprintf('%o', fileperms($dbFile)), -4) . "<br>";
} else {
    echo "❌ El archivo <code>users.db</code> NO EXISTE<br>";
    echo "📁 Directorio actual: " . __DIR__ . "<br>";
}

// 2. Verificar permisos del directorio
echo "<h3>2. Permisos del directorio</h3>";
echo "📁 Directorio: " . __DIR__ . "<br>";
echo "🔒 Permisos: " . substr(sprintf('%o', fileperms(__DIR__)), -4) . "<br>";
if (is_writable(__DIR__)) {
    echo "✅ El directorio tiene permisos de escritura<br>";
} else {
    echo "❌ El directorio NO tiene permisos de escritura<br>";
}

// 3. Verificar extensión PDO SQLite
echo "<h3>3. Extensión PDO SQLite</h3>";
if (extension_loaded('pdo_sqlite')) {
    echo "✅ La extensión <code>pdo_sqlite</code> está instalada<br>";
} else {
    echo "❌ La extensión <code>pdo_sqlite</code> NO está instalada<br>";
}

// 4. Intentar crear la base de datos
echo "<h3>4. Intento de conexión</h3>";
try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Conexión exitosa a SQLite<br>";
    
    // Crear tabla si no existe
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        hotmart_id TEXT,
        status TEXT DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    echo "✅ Tabla <code>users</code> creada/verificada<br>";
    
    // Verificar que la tabla existe
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if ($stmt->fetch()) {
        echo "✅ La tabla <code>users</code> existe en la base de datos<br>";
    } else {
        echo "❌ La tabla <code>users</code> NO existe<br>";
    }
    
    // Contar usuarios
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "👥 Usuarios en la base de datos: <strong>$count</strong><br>";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: <strong>" . $e->getMessage() . "</strong><br>";
    echo "📝 Código de error: " . $e->getCode() . "<br>";
}

echo "<hr>";
echo "<h3>📋 Resumen</h3>";
echo "<p>Si todo está en verde (✅), la base de datos está lista para usar.</p>";
echo "<p>Si hay errores (❌), copia este resultado y compártelo para solucionar.</p>";
?>