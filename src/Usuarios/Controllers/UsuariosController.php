<?php
require_once __DIR__ . '/../services/UsuariosService.php';

class UsuariosController {
    public static function index() {
        $usuarios = UsuariosService::obtenerTodos();
        header('Content-Type: application/json');
        echo json_encode($usuarios);
    }

    public static function show($id) {
        $usuario = UsuariosService::obtenerPorId($id);
        header('Content-Type: application/json');

        if (!$usuario) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }
        echo json_encode($usuario);
    }

    public static function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $contraseña = $data['contraseña'];

        if (UsuariosService::crearUsuario($nombre, $correo, $contraseña)) {
            header("HTTP/1.1 201 Created");
            echo json_encode(["message" => "Usuario creado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al crear el usuario"]);
        }
    }

    public static function update() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $contraseña = $data['contraseña'];

        if (UsuariosService::actualizarUsuario($id, $nombre, $correo, $contraseña)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Usuario actualizado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al actualizar el usuario"]);
        }
    }

    public static function delete($id) {
        if (UsuariosService::eliminarUsuario($id)) {
            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Usuario eliminado exitosamente"]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(["error" => "Error al eliminar el usuario"]);
        }
    }
}
?>