<?php
require_once __DIR__ . '/../models/Peliculas.php';

class PeliculasServices{

    public static function obtenerCartelera(){
        return Peliculas::obtenerCartelera();

    }
    public static function obtenerPelicula($titulo){
        return Peliculas::obtenerPelicula($titulo);
    }

}













?>