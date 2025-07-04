<?php
require_once __DIR__ . '/../controllers/FuncionesController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_uri === '/api/funciones/obtenerTodos') {
    FuncionesController::index();
}

else if ($request_method === "GET" && preg_match('/\/api\/funciones\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    FuncionesController::show($id);
}

else if ($request_method === "POST" && $request_uri === '/api/funciones/crearFuncion') {
    FuncionesController::create();
}

else if ($request_method === "PUT" && $request_uri === '/api/funciones/modificarFuncion') {
    FuncionesController::update();
}

else if ($request_method === "DELETE" && preg_match('/\/api\/funciones\/eliminarFuncion\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    FuncionesController::delete($id);
}
else if($request_method === "GET" && preg_match('/\/api\/funciones\/pelicula\/(\d+)/', $request_uri, $matches)) {
    $id_pelicula = $matches[1];
    FuncionesController::getByPelicula($id_pelicula);
    exit;
}


?>