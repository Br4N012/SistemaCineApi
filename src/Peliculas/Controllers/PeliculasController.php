<?php
require_once __DIR__ . '/../services/PeliculasServices.php';

class PeliculasController {
    public static function obtenerCartelera() {
        $peliculas = PeliculasServices::obtenerCartelera();
        header('Content-Type: application/xml');

        $xml = new SimpleXMLElement('<peliculas/>');
        foreach ($peliculas as $pelicula) {
            $peliculaXml = $xml->addChild('pelicula');
            foreach ($pelicula as $key => $value) {
                $peliculaXml->addChild($key, htmlspecialchars($value));
            }
        }
        echo $xml->asXML();
    }

    public static function obtenerPelicula($titulo) {
        $pelicula = PeliculasServices::obtenerPelicula($titulo);
        header('Content-Type: application/xml');

        if (!$pelicula) {
            header("HTTP/1.0 404 Not Found");
            echo "<error>Película no encontrada</error>";
            return;
        }

        $xml = new SimpleXMLElement('<pelicula/>');
        foreach ($pelicula as $key => $value) {
            $xml->addChild($key, htmlspecialchars($value));
        }
        echo $xml->asXML(); // Ahora se genera el XML correctamente
    }
}
?>
