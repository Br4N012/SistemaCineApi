<?php
// api/index.php
require_once '../src/Peliculas/routes/PeliculasRoutes.php';
require_once '../src/Peliculas/routes/CompraRoutes.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

if (strpos($request_uri, '/api/peliculas') === 0) {
    require_once '../src/Peliculas/index.php';
} elseif (strpos($request_uri, '/api/usuarios') === 0) {
    require_once '../src/Usuarios/index.php';
} elseif (strpos($request_uri, '/api/salas') === 0) {
    require_once '../src/Salas/index.php';
} elseif (strpos($request_uri, '/api/funciones') === 0) {
    require_once '../src/Funciones/index.php';
} else {
    header("HTTP/1.1 404 Not Found");
}
?>