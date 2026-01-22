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

