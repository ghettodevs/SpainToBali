<?php
require_once 'includes/db.php';

$viene_de_guia = (isset($_GET['info']) && $_GET['info'] == 'guia');
$viene_de_contacto = (isset($_GET['info']) && $_GET['info'] == 'contacto');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $viene_de_guia) {
    // Detectamos si el usuario viene de la guía o del contacto
    $email = filter_var($_POST['email_guia'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // 1. Intentamos guardar en la tabla leads
            $sql = "INSERT INTO leads (email, origen) 
                    VALUES (:email, 'Guía Bali 2026') 
                    ON CONFLICT (email, origen) DO UPDATE SET fecha_registro = CURRENT_TIMESTAMP";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
        } catch (PDOException $e) {
            // Si la tabla 'leads' no existe aún, no bloqueamos la descarga
        }
    }
} else if (!$viene_de_guia && !$viene_de_contacto) {
    header("Location: index.php"); 
    exit;
}

include 'includes/header_gracias.php'; 
?>

<main class="thanks-container">
    <section class="thanks-content reveal active">
        <div class="icon-check"><?php echo $viene_de_guia ? '📖' : '🌴'; ?></div>
        
        <h1><?php echo $viene_de_guia ? '¡Tu Guía está lista!' : '¡Terima Kasih!'; ?></h1>
        
        <p>
            <?php 
            if ($viene_de_guia) {
                echo "La descarga de tu <strong>Guía de Supervivencia Bali 2026</strong> ha comenzado. ¡Revísala ahora!";
            } else {
                echo "Tu solicitud ha llegado a manos de nuestros expertos en <strong>SpainToBali</strong>.";
            }
            ?>
        </p>

        <div class="next-steps">
            <?php if ($viene_de_guia) { ?>
                <div class="consultancy-box" style="background: #f4f1ea; padding: 25px; border: 2px solid #C5A059; border-radius: 15px; margin: 20px 0; text-align: center;">
                    <h3 style="color: #2D5A27; margin-top: 0;">¿Quieres un itinerario perfecto? 🗺️</h3>
                    <p>No pierdas tiempo planificando. Reserva una <strong>consultoría gratuita de 15 min</strong> con nosotros.</p>
                    <a href="https://calendly.com/contacto-spaintobali/30min" target="_blank" class="btn" style="background: #2D5A27; color: white; display: inline-block; padding: 12px 25px; margin-top: 10px; text-decoration: none; border-radius: 5px;">Reservar mi llamada gratis</a>
                </div>
                
                <h3>¿Qué encontrarás en la guía?</h3>
                <ul>
                    <li>✅ Consejos sobre moneda y propinas locales.</li>
                    <li>✅ Cómo evitar el famoso "Bali Belly" y salud.</li>
                    <li>✅ Movilidad inteligente: Apps de transporte.</li>
                </ul>
            <?php } else { ?>
                <h3>¿Qué pasa ahora?</h3>
                <ul>
                    <li>✅ Revisaremos tus fechas y número de viajeros.</li>
                    <li>✅ Te contactaremos por email en menos de 24 horas.</li>
                    <li>✅ Recibirás un presupuesto 100% a medida para Bali.</li>
                </ul>
            <?php } ?>
        </div>
        
        <a href="/" class="btn-outline" style="display: block; margin-top: 20px; color: #666; text-decoration: none; text-align: center;">Volver al inicio</a>
    </section>
</main>

<?php if ($viene_de_guia): ?>
<script>
    // Esto inicia la descarga llamando a tu ruta de 'descargar' sin cerrar esta página
    setTimeout(() => {
        const link = document.createElement('a');
        link.href = 'assets/docs/guia-bali-spaintobali.pdf';
        link.download = 'Guia-Bali-SpainToBali.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }, 1000); // Espera 1 segundo para que cargue la animación
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>