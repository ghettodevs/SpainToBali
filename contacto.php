<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

// 1. CARGA DE VARIABLES DE ENTORNO
if (!isset($_ENV['DB_HOST'])) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

// 2. CONEXIÓN A DB
try {
    $dsn = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    error_log("Error DB: " . $e->getMessage());
    die("Error de configuración interna.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['honeypot'])) die("Acceso denegado.");

    $nombre   = htmlspecialchars($_POST['nombre'] ?? '');
    $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $mensaje  = htmlspecialchars($_POST['mensaje'] ?? '');
    $fecha    = $_POST['fecha'] ?? null; 
    $personas = intval($_POST['personas'] ?? 1);

    try {
        // 3. GUARDAR EN DB (Prioritario)
        $sql = "INSERT INTO public.mensajes (nombre, email, fecha_viaje, num_personas, mensaje, fecha_envio) 
                VALUES (:nombre, :email, :fecha, :personas, :mensaje, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nombre'=>$nombre, ':email'=>$email, ':fecha'=>$fecha, ':personas'=>$personas, ':mensaje'=>$mensaje]);

        // 4. INTENTAR ENVIAR EMAIL (Si falla, el catch nos salvará)
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
        $mail->Subject = "Nueva solicitud: $nombre";
        $mail->Body    = "<b>Nombre:</b> $nombre<br><b>Email:</b> $email<br><b>Mensaje:</b> $mensaje";

        $mail->send();

    } catch (Exception $e) {
        // Si el correo falla, registramos el error en el servidor pero NO detenemos la web
        error_log("Error de envío en SpainToBali: " . $e->getMessage());
    }

    // 5. REDIRECCIÓN FINAL (Siempre ocurre, falle o no el mail)
    header("Location: gracias?info=contacto");
    exit;
}