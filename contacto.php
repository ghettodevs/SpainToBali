<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. CARGA DE LIBRERÍAS (Ruta absoluta para Docker)
require_once __DIR__ . '/vendor/autoload.php';

// 2. CARGA DE VARIABLES DE ENTORNO
// Forzamos la ruta /var/www/html/ que es donde está tu .env según el ls -la
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
} catch (Exception $e) {
    // Si falla el .env, lo registramos pero intentamos seguir para ver el error real
    error_log("Error cargando .env: " . $e->getMessage());
}

// 3. CONEXIÓN A BASE DE DATOS
try {
    $dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos. Verifica tu archivo .env");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protección básica
    if (!empty($_POST['honeypot'])) die("SPAM detectado.");

    $nombre   = htmlspecialchars($_POST['nombre'] ?? '');
    $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $mensaje  = htmlspecialchars($_POST['mensaje'] ?? '');
    $fecha    = $_POST['fecha'] ?? null;
    $personas = intval($_POST['personas'] ?? 1);

    try {
        // 4. GUARDAR EN POSTGRESQL
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

        // 5. ENVÍO DE EMAIL CON PHPMAILER
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_USER'], 'SpainToBali');
        $mail->addAddress($_ENV['SMTP_USER']);
        $mail->addReplyTo($email, $nombre);

        $mail->isHTML(true);
        // ... después de $mail->isHTML(true);

        $mail->Subject = "✈️ Nueva solicitud de viaje: $nombre";

        // Diseño HTML para el correo
        $mail->Body = "
<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;'>
    <div style='background-color: #007bff; color: white; padding: 20px; text-align: center;'>
        <h1 style='margin: 0; font-size: 24px;'>SpainToBali</h1>
    </div>
    <div style='padding: 20px; color: #333;'>
        <p style='font-size: 16px;'>Has recibido una nueva solicitud de información desde la web:</p>
        <hr style='border: 0; border-top: 1px solid #eee;'>
        <table style='width: 100%; font-size: 15px;'>
            <tr>
                <td style='padding: 10px 0; font-weight: bold; width: 30%;'>Nombre:</td>
                <td>$nombre</td>
            </tr>
            <tr>
                <td style='padding: 10px 0; font-weight: bold;'>Email:</td>
                <td><a href='mailto:$email' style='color: #007bff; text-decoration: none;'>$email</a></td>
            </tr>
            <tr>
                <td style='padding: 10px 0; font-weight: bold;'>Fecha de viaje:</td>
                <td>$fecha</td>
            </tr>
            <tr>
                <td style='padding: 10px 0; font-weight: bold;'>Personas:</td>
                <td>$personas</td>
            </tr>
        </table>
        <div style='margin-top: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 5px;'>
            <p style='margin: 0; font-weight: bold;'>Mensaje:</p>
            <p style='margin: 10px 0 0 0; font-style: italic; line-height: 1.5;'>\"$mensaje\"</p>
        </div>
    </div>
    <div style='background-color: #f4f4f4; color: #777; padding: 15px; text-align: center; font-size: 12px;'>
        <p style='margin: 0;'>Este es un mensaje automático enviado desde spaintobali.es</p>
    </div>
</div>";

        // Texto alternativo para gestores de correo que no aceptan HTML
        $mail->AltBody = "Nueva solicitud de $nombre ($email). Mensaje: $mensaje. Fecha: $fecha. Personas: $personas.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error en el proceso: " . $e->getMessage());
    }

    // 6. REDIRECCIÓN
    header("Location: gracias?info=contacto");
    exit;
}
