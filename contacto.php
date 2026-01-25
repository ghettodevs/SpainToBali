<?php
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Seguridad Honeypot
    if (!empty($_POST['honeypot'])) {
        die("Bot detectado.");
    }

    // Recogida de datos con validación de existencia (evita los Warnings de tu captura)
    $nombre   = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $email    = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $mensaje  = isset($_POST['mensaje']) ? htmlspecialchars($_POST['mensaje']) : '';
    
    // Estos son los que te daban error en la imagen
    $fecha    = !empty($_POST['fecha']) ? $_POST['fecha'] : null; 
    $personas = !empty($_POST['personas']) ? intval($_POST['personas']) : 1;

    if (empty($nombre) || empty($email) || !$fecha) {
        die("Por favor, completa los campos obligatorios, incluyendo la fecha.");
    }

    try {
        $sql = "INSERT INTO mensajes (nombre, email, fecha_viaje, num_personas, mensaje) 
                VALUES (:nombre, :email, :fecha, :personas, :mensaje)";
        
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':fecha'    => $fecha,
            ':personas' => $personas,
            ':mensaje'  => $mensaje
        ]);

        if ($resultado) {
            header("Location: gracias");
            exit; 
        }

    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}