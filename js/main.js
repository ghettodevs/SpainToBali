document.addEventListener('DOMContentLoaded', () => {
    // 1. Scroll suave para los enlaces del menú
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // 2. Validación del formulario de SpainToBali
    const form = document.getElementById('viajeForm');
    if (form) {
        form.addEventListener('submit', (e) => {
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;

            if (nombre.length < 3 || !email.includes('@')) {
                e.preventDefault();
                alert("Por favor, completa correctamente tu nombre y correo para que podamos contactarte.");
            } else {
                const btn = document.getElementById('btnEnviar');
                btn.innerText = "Enviando tu solicitud...";
                btn.style.opacity = "0.7";
            }
        });
    }
});



const menuToggle = document.querySelector('.menu-toggle');
const navMenu = document.querySelector('.nav-menu');
const menuOverlay = document.querySelector('.menu-overlay');

// Función única para abrir/cerrar el menú y el overlay
function toggleMenu() {
    const isActive = navMenu.classList.toggle('active');
    menuToggle.classList.toggle('is-active');
    
    // Controlamos el overlay (si existe)
    if (menuOverlay) {
        menuOverlay.classList.toggle('active');
    }

    // Bloqueamos el scroll del body para que el usuario no se pierda al navegar el menú
    document.body.style.overflow = isActive ? 'hidden' : 'auto';
}

// Evento para el botón hamburguesa
menuToggle.addEventListener('click', toggleMenu);

// Evento para cerrar al hacer clic en el desenfoque (overlay)
if (menuOverlay) {
    menuOverlay.addEventListener('click', toggleMenu);
}

// Cerrar automáticamente al hacer clic en cualquier enlace (Inicio, Destinos, etc.)
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        // Solo cerramos si el menú está abierto
        if (navMenu.classList.contains('active')) {
            toggleMenu();
        }
    });
});