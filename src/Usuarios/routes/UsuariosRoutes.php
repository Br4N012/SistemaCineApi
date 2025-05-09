<?php
require_once __DIR__ . '/../controllers/UsuariosController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_uri === '/api/usuarios/obtenerTodos') {
    UsuariosController::index();
}

else if ($request_method === "GET" && preg_match('/\/api\/usuarios\/obtenerPorId\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    UsuariosController::show($id);
}

else if ($request_method === "POST" && $request_uri === '/api/usuarios/registrarUsuario') {
    UsuariosController::create();
}

else if ($request_method === "PUT" && $request_uri === '/api/usuarios/actualizarUsuario') {
    UsuariosController::update();
}

else if ($request_method === "DELETE" && preg_match('/\/api\/usuarios\/eliminarUsuario\/(\d+)/', $request_uri, $matches)) {
    $id = $matches[1];
    UsuariosController::delete($id);
}

else {
    header("HTTP/1.1 404 Not Found");
}
?>