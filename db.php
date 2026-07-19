<?php
// ============================================
// 🗄️ CONFIGURACIÓN DE BASE DE DATOS (SQLite)
// ============================================

$dbFile = __DIR__ . '/users.db';

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear tabla de usuarios si no existe
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        hotmart_id TEXT,
        status TEXT DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
} catch (PDOException $e) {
    error_log('Error DB: ' . $e->getMessage());
    die('Error de base de datos.');
}
?>