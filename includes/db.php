<?php
// Seguridad: Si alguien intenta acceder directamente a este archivo, se corta la ejecución.
if (basename($_SERVER['PHP_SELF']) == 'db.php') {
    die("Acceso denegado.");
}

$host = '127.0.0.1';
$db   = 'SpainToBali';
$user = 'root';
$pass = ''; // Déjalo vacío si usas XAMPP estándar
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En producción, no deberías mostrar $e->getMessage() por seguridad
    die("Error de conexión a la base de datos.");
}
?>