<?php 
// Detectamos si el usuario viene de la guía o del contacto
$tipo = (isset($_GET['info']) && $_GET['info'] == 'guia'); 
include 'includes/header.php'; 
?>

<main class="thanks-container">
    <section class="thanks-content reveal active">
        <div class="icon-check"><?php echo $tipo ? '📖' : '🌴'; ?></div>
        
        <h1><?php echo $tipo ? '¡Tu Guía está lista!' : '¡Terima Kasih!'; ?></h1>
        
        <p>
            <?php 
            if ($tipo) {
                echo "La descarga de tu <strong>Guía de Supervivencia Bali 2026</strong> ha comenzado. ¡Revísala ahora!";
            } else {
                echo "Tu solicitud ha llegado a manos de nuestros expertos en <strong>SpainToBali</strong>.";
            }
            ?>
        </p>

        <div class="next-steps">
            <?php if ($tipo) { ?>
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
        
        <a href="inicio" class="btn-outline" style="display: block; margin-top: 20px; color: #666; text-decoration: none; text-align: center;">Volver al inicio</a>
    </section>
</main>

<?php if ($tipo): ?>
<script>
    // Esto inicia la descarga llamando a tu ruta de 'descargar' sin cerrar esta página
    setTimeout(() => {
        window.location.href = '/descargar';
    }, 1000); // Espera 1 segundo para que cargue la animación
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>