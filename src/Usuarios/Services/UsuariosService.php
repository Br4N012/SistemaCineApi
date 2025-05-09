<?php
require_once __DIR__ . '/../models/Usuarios.php';

class UsuariosService {
    public static function obtenerTodos() {
        return Usuarios::obtenerTodos();
    }

    public static function obtenerPorId($id) {
        return Usuarios::obtenerPorId($id);
    }

    public static function crearUsuario($nombre, $correo, $contraseña) {
        return Usuarios::crearUsuario($nombre, $correo, $contraseña);
    }

    public static function actualizarUsuario($id, $nombre, $correo, $contraseña) {
        return Usuarios::actualizarUsuario($id, $nombre, $correo, $contraseña);
    }

    public static function eliminarUsuario($id) {
        return Usuarios::eliminarUsuario($id);
    }
}
?>