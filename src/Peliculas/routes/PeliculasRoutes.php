<?php
require_once __DIR__ . '/../controllers/PeliculasController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_uri === '/api/peliculas/obtenerTodos') {
    PeliculasController::index();
}

else if ($request_method === "GET" && preg_match('/\/api\/peliculas\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    PeliculasController::show($id);
}

else if ($request_method === "GET" && $request_uri === '/api/peliculas/clasificacion') {
    PeliculasController::ordenarPorClasificacion();
}

else if ($request_method === "POST" && $request_uri === '/api/peliculas/registrarPelicula') {
    PeliculasController::create();
}

else if ($request_method === "PUT" && $request_uri === '/api/peliculas/actualizarPelicula') {
    PeliculasController::update();
}

else if ($request_method === "DELETE" && preg_match('/\/api\/peliculas\/eliminarPelicula\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    PeliculasController::delete($id);
}
else if ($request_method == 'GET' && preg_match('/\/api\/peliculas\/obtenerPelicula\/([^\/]+)/', $request_uri, $matches)) {
    $titulo = urldecode($matches[1]); // Decodificar el título en caso de caracteres especiales
    PeliculasController::search($titulo);
}
else if($request_method === "GET" && preg_match('/\/api\/peliculas\/mes\/([a-zA-Z]+)/', $request_uri, $matches)) {
    $mes = $matches[1];
    PeliculasController::getByMes($mes);
    exit;
}
?>