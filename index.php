<?php 
require 'vendor/autoload.php';

// Cargar variables de entorno desde el .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

include 'includes/header.php';
?>

<section id="inicio" class="hero">
    <div class="hero-content">
        <h1>Spain<span>To</span>Bali</h1>
        <p>Expertos en viajes a medida desde España al paraíso indonesio.</p>
        <a href="#contacto" class="btn">Diseñar mi viaje</a>
    </div>
</section>

<section id="destinos" class="gallery-container container">
    <div class="section-title">
        <h2>Explora Bali con nosotros</h2>
        <p>Seleccionamos los rincones más auténticos para tu experiencia personalizada.</p>
    </div>

    <div class="grid-gallery">
    <a href="ubud.php" class="item" style="background-image: url(/assets/img/ubud.jpeg)">
        <div class="overlay"><span>Ubud & Selva</span></div>
    </a>
    <a href="templos.php" class="item" style="background-image: url('/assets/img/templos.jpeg')">
        <div class="overlay"><span>Templos Sagrados</span></div>
    </a>
    <a href="playas.php" class="item" style="background-image: url('/assets/img/playas.jpeg')">
        <div class="overlay"><span>Playas paradisíacas</span></div>
    </a>
    <a href="cascadas.php" class="item" style="background-image: url('/assets/img/cascadas.jpeg')">
        <div class="overlay"><span>Cascadas Ocultas</span></div>
    </a>
</div>
    </div>
</section>

<section id="contacto" class="form-section">
    <div class="container">
        <div class="form-container">
            <div class="form-info">
                <h2>¿Hablamos?</h2>
                <p>Cuéntanos tu idea de viaje y nosotros nos encargamos de toda la logística desde España.</p>
                <ul class="features">
                    <li>✅ Hoteles y Villas seleccionados</li>
                    <li>✅ Guías locales en español/ingles</li>
                    <li>✅ Asistencia 24/7</li>
                </ul>
            </div>

            <form action="contacto" method="POST">
                <div style="display:none;">
                    <input type="text" name="honeypot">
                </div>

                <div class="input-group">
                    <input type="text" name="nombre" placeholder="Tu nombre completo" required>
                </div>
                
                <div class="input-group">
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                </div>

                <div class="row">
                    <div class="input-group">
                        <label>Fecha prevista</label>
                        <input type="date" name="fecha" required>
                    </div>
                    <div class="input-group">
                        <label>Nº Viajeros</label>
                        <input type="number" name="personas" min="1" value="2">
                    </div>
                </div>

                <div class="input-group">
                    <textarea name="mensaje" placeholder="¿Cómo imaginas tu viaje ideal?"></textarea>
                </div>

                <button type="submit" class="btn-submit">Empezar aventura</button>
            </form>
        </div>
    </div>
</section>

<section class="beneficios">
    <div class="container">
        <h2 class="section-title">¿Por qué viajar con SpainToBali?</h2>
        <div class="beneficios-grid">
            <div class="beneficio-item">
                <span class="icon">🇪🇸</span>
                <h3>Soporte en Castellano</h3>
                <p>Olvídate de la barrera del idioma. Te acompañamos en tu lengua nativa de principio a fin.</p>
            </div>
            <div class="beneficio-item">
                <span class="icon">🌴</span>
                <h3>Expertos Locales</h3>
                <p>No somos una agencia genérica. Vivimos y respiramos Bali para ofrecerte rincones secretos.</p>
            </div>
            <div class="beneficio-item">
                <span class="icon">🛡️</span>
                <h3>Seguridad 24/7</h3>
                <p>Asistencia constante durante tu viaje en 2026 para que tu única preocupación sea disfrutar.</p>
            </div>
        </div>
    </div>
</section>

<section id="guia" class="lead-magnet">
    <div class="container">
        <div class="magnet-wrapper">
            <div class="magnet-text">
                <span class="badge">Gratis</span>
                <h2>Guía de Supervivencia: Bali 2026</h2>
                <p>Todo lo que las agencias no te cuentan: desde dónde cambiar moneda sin comisiones hasta cómo evitar el "Bali Belly".</p>
                
                <form action="/gracias?info=guia" method="POST" class="magnet-form">
                    <input type="email" name="email_guia" placeholder="Tu email..." required>
                    <button type="submit" class="btn-gold">Enviar Guía a mi Email</button>
                </form>
                
                <small>🔒 No enviamos spam. Solo cultura balinesa.</small>
            </div>
            <div class="magnet-image">
                <img src="https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?auto=format&fit=crop&w=400" alt="Guía Bali">
            </div>
        </div>
    </div>
</section>


<?php include 'includes/footer.php'; ?>