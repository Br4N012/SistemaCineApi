<?php
require_once __DIR__ . '/../services/UsuariosService.php';
require_once __DIR__ . '/../../../handler/XmlHandler.php';

class UsuariosController {
    public static function index() {
        $usuarios = UsuariosService::obtenerTodos();
        header('Content-Type: application/xml');
        echo XmlHandler::generarXml($usuarios, 'usuarios', 'usuario');
    }

    public static function show($id) {
        $usuario = UsuariosService::obtenerPorId($id);
        header('Content-Type: application/xml');

        if (!$usuario) {
            header("HTTP/1.1 404 Not Found");
            echo "<error>Usuario no encontrado</error>";
            return;
        }

        echo XmlHandler::generarXml($usuario, 'usuario');
    }

    public static function create() {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);

        $nombre = (string)$xml->nombre;
        $correo = (string)$xml->correo;
        $contraseña = (string)$xml->contraseña;

        if (UsuariosService::crearUsuario($nombre, $correo, $contraseña)) {
            header("HTTP/1.1 201 Created");
            echo "<message>Usuario creado exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al crear el usuario</error>";
        }
    }

    public static function update() {
        $data = file_get_contents("php://input");
        $xml = simplexml_load_string($data);

        $id = (int)$xml->id;
        $nombre = (string)$xml->nombre;
        $correo = (string)$xml->correo;
        $contraseña = (string)$xml->contraseña;

        if (UsuariosService::actualizarUsuario($id, $nombre, $correo, $contraseña)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Usuario actualizado exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al actualizar el usuario</error>";
        }
    }

    public static function delete($id) {
        if (UsuariosService::eliminarUsuario($id)) {
            header("HTTP/1.1 200 OK");
            echo "<message>Usuario eliminado exitosamente</message>";
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<error>Error al eliminar el usuario</error>";
        }
    }
}
?>
<?php