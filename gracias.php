<?php 
// Detectamos si el usuario viene de la guía o del contacto
$tipo = isset($_GET['info']) && $_GET['info'] == 'guia'; 
include 'includes/header.php'; 
?>

<main class="thanks-container">
    <section class="thanks-content reveal active">
        <div class="icon-check"><?php echo $tipo ? '📖' : '🌴'; ?></div>
        
        <h1><?php echo $tipo ? '¡Tu Guía está lista!' : '¡Terima Kasih!'; ?></h1>
        
        <p>
            <?php 
            if ($tipo) {
                echo "Se ha abierto una nueva pestaña con tu <strong>Guía de Supervivencia Bali 2026</strong>. ¡Disfrútala!";
            } else {
                echo "Tu solicitud ha llegado a manos de nuestros expertos en <strong>SpainToBali</strong>.";
            }
            ?>
        </p>

        <div class="next-steps">
            <h3><?php echo $tipo ? '¿Qué encontrarás en la guía?' : '¿Qué pasa ahora?'; ?></h3>
            <ul>
                <?php if ($tipo): ?>
                    <li>✅ Consejos sobre moneda y propinas.</li>
                    <li>✅ Cómo evitar el famoso "Bali Belly".</li>
                    <li>✅ Los mejores templos sin multitudes.</li>
                <?php else: ?>
                    <li>✅ Revisaremos tu itinerario ideal.</li>
                    <li>✅ Te contactaremos en menos de 24 horas.</li>
                    <li>✅ Prepararemos un presupuesto a tu medida.</li>
                <?php endif; ?>
            </ul>
        </div>
        
        <a href="index.php" class="btn">Volver al inicio</a>
    </section>
</main>

<?php include 'includes/footer.php'; ?>