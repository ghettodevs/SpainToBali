<?php
// 1. CARGA DE LIBRERÍAS
require 'vendor/autoload.php'; 

// 2. CARGAR VARIABLES DE ENTORNO (Solo si no se cargaron en index.php)
if (!isset($_ENV['DB_HOST'])) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

// 3. CONEXIÓN A POSTGRESQL (Usando tus variables del .env)
try {
    $dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    error_log("Fallo de conexión en SpainToBali: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Seguridad Honeypot
    if (!empty($_POST['honeypot'])) {
        die("Acceso no autorizado.");
    }

    // Recogida y limpieza de datos
    $nombre   = htmlspecialchars($_POST['nombre'] ?? '');
    $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $mensaje  = htmlspecialchars($_POST['mensaje'] ?? '');
    $fecha    = $_POST['fecha'] ?? null; 
    $personas = intval($_POST['personas'] ?? 1);

    if (empty($nombre) || empty($email) || !$fecha) {
        die("Por favor, completa todos los campos obligatorios.");
    }

    try {
        // 4. INSERCIÓN EN LA DB (Asegúrate que la tabla se llame 'mensajes')
        $sql = "INSERT INTO public.mensajes (nombre, email, fecha_viaje, num_personas, mensaje, fecha_envio) 
                VALUES (:nombre, :email, :fecha, :personas, :mensaje, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':fecha'    => $fecha,
            ':personas' => $personas,
            ':mensaje'  => $mensaje
        ]);

        // 5. REDIRECCIÓN LIMPIA (Sin .php para que el Router funcione)
        header("Location: gracias?info=contacto");
        exit; 

    } catch (PDOException $e) {
        error_log("Error DB SpainToBali: " . $e->getMessage());
        echo "Error al guardar los datos. Revisa la terminal del VPS.";
    }
}