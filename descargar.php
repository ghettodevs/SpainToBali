<?php
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email_guia']);

    if (!empty($email)) {
        try {
            // 1. Guardamos el lead en una tabla específica (opcional, pero recomendado)
            // Si no tienes la tabla 'leads', puedes usar la de 'mensajes' adaptándola
            $sql = "INSERT INTO mensajes (email, mensaje) VALUES (?, 'SOLICITUD DE GUÍA')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);

            // 2. Usamos JavaScript para abrir el PDF y luego redirigir la página principal
            echo "<script>
                window.open('assets/docs/guia-bali-spaintobali.pdf', '_blank');
                window.location.href = 'gracias.php?info=guia';
            </script>";
            exit;

        } catch (Exception $e) {
            header("Location: index.php");
        }
    }
}