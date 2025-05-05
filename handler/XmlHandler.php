<?php

class XmlHandler {
    public static function generarXml($data, $rootElement, $childElement = null) {
        $xml = new SimpleXMLElement("<$rootElement/>");

        /*
        is_array($data): verifica si es una array
        && isset($data[0]) verifica si el primer elemento existe, es un solo array
        && is_array($data[0]) verifica el segundo elemento, es un array de arrays
        */
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            // Caso: lista de elementos
            foreach ($data as $item) {
                $child = $xml->addChild($childElement);
                foreach ($item as $key => $value) {
                    $child->addChild($key, htmlspecialchars($value));
                }
            }
        } else {
            // Caso: un solo elemento
            foreach ($data as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
    }
}
?>