document.addEventListener("DOMContentLoaded", function() {
    const banner = document.getElementById("cookie-banner");
    const btnAccept = document.getElementById("btn-accept");
    const btnDeny = document.getElementById("btn-deny");

    // Función para cargar los scripts de seguimiento (Google y Clarity)
    function loadTrackingScripts() {
        // Ejecutar gtag (Google Analytics)
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-F409Y5EXVY');

        // Ejecutar Microsoft Clarity
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "v8oebjvc74");
    }

    // Comprobar si ya existe el consentimiento en el navegador
    const consent = localStorage.getItem("stb_cookies_consent");

    if (!consent) {
        banner.style.display = "block";
    } else if (consent === "accepted") {
        loadTrackingScripts();
    }

    // Acción al Aceptar
    btnAccept.addEventListener("click", () => {
        localStorage.setItem("stb_cookies_consent", "accepted");
        banner.style.display = "none";
        loadTrackingScripts(); // Activamos los scripts al momento
    });

    // Acción al Rechazar
    btnDeny.addEventListener("click", () => {
        localStorage.setItem("stb_cookies_consent", "denied");
        banner.style.display = "none";
    });
});