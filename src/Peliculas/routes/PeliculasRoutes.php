<?php
require_once __DIR__ . '/../controllers/PeliculasController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Obtener la cartelera de películas
if ($request_method == 'GET' && $request_uri == '/api/peliculas/obtenerCartelera') {
    PeliculasController::obtenerCartelera();
}
// Obtener detalles de una película por nombre
else if ($request_method == 'GET' && preg_match('/\/api\/peliculas\/obtenerPelicula\/([^\/]+)/', $request_uri, $matches)) {
    $titulo = urldecode($matches[1]); // Decodificar el título en caso de caracteres especiales
    PeliculasController::obtenerPelicula($titulo);
} else {
    header("HTTP/1.0 404 Not Found");
   
}
?>
