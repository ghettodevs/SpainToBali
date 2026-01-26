<?php
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // 2. Mostramos un mensaje breve y disparamos la descarga/redirección
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Descargando Guía - SpainToBali</title>
            <meta http-equiv="refresh" content="3;url=gracias.php?info=guia">
            <style>
                body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f9f6f2; color: #2D5A27; }
            </style>
        </head>
        <body>
            <div style="text-align: center;">
                <h2>Generando tu guía de SpainToBali...</h2>
                <p>La descarga comenzará en un segundo.</p>
            </div>

            <script>
                // Forzamos la descarga del PDF
                const link = document.createElement('a');
                link.href = 'assets/docs/guia-bali-spaintobali.pdf';
                link.download = 'Guia-Bali-SpainToBali.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Redirigimos a la página de gracias tras la descarga
                setTimeout(function(){
                    window.location.href = 'gracias ?info=guia';
                }, 1500);
            </script>
        </body>
        </html>
        <?php
        exit;
    }
}
header("Location: index.php");
exit;