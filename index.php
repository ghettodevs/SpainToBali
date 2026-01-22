<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Spain<span>To</span>Bali</h1>
        <p>Expertos en viajes a medida desde España al paraíso indonesio.</p>
        <a href="#contacto" class="btn">Diseñar mi viaje</a>
    </div>
</section>

<section id="galeria" class="gallery-container container">
    <div class="section-title">
        <h2>Explora Bali con nosotros</h2>
        <p>Seleccionamos los rincones más auténticos para tu experiencia personalizada.</p>
    </div>

    <div class="grid-gallery">
        <div class="item" style="background-image: url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800')">
            <div class="overlay"><span>Ubud & Selva</span></div>
        </div>
        <div class="item" style="background-image: url('https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=800')">
            <div class="overlay"><span>Templos Sagrados</span></div>
        </div>
        <div class="item" style="background-image: url('https://images.unsplash.com/photo-1539367628448-4bc5c9d171c8?auto=format&fit=crop&w=800')">
            <div class="overlay"><span>Islas paradisíacas</span></div>
        </div>
        <div class="item" style="background-image: url('https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?auto=format&fit=crop&w=800')">
            <div class="overlay"><span>Cascadas Ocultas</span></div>
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
                    <li>✅ Hoteles boutique seleccionados</li>
                    <li>✅ Guías locales en español</li>
                    <li>✅ Asistencia 24/7</li>
                </ul>
            </div>



            <form action="contacto.php" method="POST">
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
<section class="lead-magnet">
    <div class="container">
        <div class="magnet-wrapper">
            <div class="magnet-text">
                <span class="badge">Gratis</span>
                <h2>Guía de Supervivencia: Bali 2026</h2>
                <p>Todo lo que las agencias no te cuentan: desde dónde cambiar moneda sin comisiones hasta cómo evitar el "Bali Belly".</p>
                
                <form action="descargar.php" method="POST" class="magnet-form">
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