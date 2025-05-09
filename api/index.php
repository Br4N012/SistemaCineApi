<?php
// api/index.php
require_once '../src/Peliculas/routes/PeliculasRoutes.php';
require_once '../src/Peliculas/routes/CompraRoutes.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if (strpos($request_uri, '/api/peliculas') === 0) {
    include '../src/peliculas/index.php';
    include '../src/Usuarios/index.php';
    include '../src/Salas/index.php';
    include '../src/Funciones/index.php';
} else {
    header("HTTP/1.1 404 Not Found");
}
?>