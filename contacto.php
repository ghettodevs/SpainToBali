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
        $mail->Subject = "Nueva solicitud web: $nombre";
        $mail->Body = "Fecha: $fecha";
        $mail->Body = "personas: $personas";
        $mail->Body    = "Has recibido un nuevo mensaje de <b>$nombre</b> ($email).<br><br>Mensaje: $mensaje";

        $mail->send();

    } catch (Exception $e) {
        error_log("Error en el proceso: " . $e->getMessage());
    }

    // 6. REDIRECCIÓN
    header("Location: gracias?info=contacto");
    exit;
}