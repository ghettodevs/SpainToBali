<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. CARGA DE LIBRERÍAS
require_once __DIR__ . '/vendor/autoload.php';

// 2. CARGA DE VARIABLES DE ENTORNO
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
} catch (Exception $e) {
    error_log("Error cargando .env: " . $e->getMessage());
}

// 3. CONEXIÓN A BASE DE DATOS
try {
    $dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        
        // CONFIGURACIÓN PARA ACENTOS Y EMOJIS
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

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
        
        // ASUNTO CODIFICADO PARA EMOJIS
        $mail->Subject = "=?UTF-8?B?".base64_encode("✈️ Nueva solicitud de viaje: $nombre")."?=";

        // DISEÑO MEJORADO: NEGRO Y DORADO SPAINTOBALI
        $mail->Body = "
<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #d4af37; border-radius: 12px; overflow: hidden; background-color: #ffffff;'>
    <div style='background-color: #1a1a1a; padding: 30px; text-align: center; border-bottom: 4px solid #d4af37;'>
        <h1 style='margin: 0; color: #d4af37; font-size: 26px; letter-spacing: 2px;'>SPAINTOBALI</h1>
    </div>
    <div style='padding: 30px; color: #333;'>
        <p style='font-size: 17px; margin-bottom: 20px;'>Has recibido una nueva solicitud de información:</p>
        <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #1a1a1a; width: 40%;'>Nombre:</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>$nombre</td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #1a1a1a;'>Email:</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'><a href='mailto:$email' style='color: #d4af37; text-decoration: none;'>$email</a></td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #1a1a1a;'>Fecha viaje:</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>$fecha</td>
            </tr>
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #1a1a1a;'>Nº Personas:</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>$personas</td>
            </tr>
        </table>
        <div style='margin-top: 20px; padding: 20px; background-color: #fcf8e8; border-left: 5px solid #d4af37; border-radius: 4px;'>
            <p style='margin: 0; font-weight: bold; color: #1a1a1a; margin-bottom: 10px;'>Mensaje:</p>
            <p style='margin: 0; font-style: italic; color: #444; line-height: 1.6;'>\"$mensaje\"</p>
        </div>
    </div>
    <div style='background-color: #1a1a1a; color: #d4af37; padding: 20px; text-align: center; font-size: 12px;'>
        <p style='margin: 0;'>© 2026 SpainToBali | Viajes a medida</p>
    </div>
</div>";

        $mail->AltBody = "Nueva solicitud de $nombre ($email). Mensaje: $mensaje.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error en el proceso: " . $e->getMessage());
    }

    header("Location: gracias?info=contacto");
    exit;
}