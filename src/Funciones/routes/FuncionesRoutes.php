<?php
require_once __DIR__ . '/../controllers/FuncionesController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_uri === '/api/funciones') {
    FuncionesController::index();
}

else if ($request_method === "GET" && preg_match('/\/api\/funciones\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    FuncionesController::show($id);
}

else if ($request_method === "POST" && $request_uri === '/api/funciones') {
    FuncionesController::create();
}

else if ($request_method === "PUT" && $request_uri === '/api/funciones') {
    FuncionesController::update();
}

else if ($request_method === "DELETE" && preg_match('/\/api\/funciones\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    FuncionesController::delete($id);
}

else {
    header("HTTP/1.1 404 Not Found");
}
?>