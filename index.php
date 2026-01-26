<?php
require 'vendor/autoload.php';

// Cargar variables de entorno desde el .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

switch ($path) {
    case '':
    case 'inicio':
        include 'home.php';
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
        http_response_code(404);
        include 'home.php'; 
        break;
}