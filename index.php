<?php
// Capturamos la URL solicitada
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

// Lógica de enrutamiento para SpainToBali
switch ($path) {
    case '':
    case 'inicio':
        include 'home.php'; // Aquí cargamos tu diseño principal
        break;
        
    case 'contacto':
        include 'contacto.php';
        break;
        
    case 'gracias':
        include 'gracias.php';
        break;

    case 'descargar':
        include 'descargar.php';
        break;

    default:
        // Si la URL no existe, enviamos a la home o una 404
        http_response_code(404);
        include 'home.php'; 
        break;
}