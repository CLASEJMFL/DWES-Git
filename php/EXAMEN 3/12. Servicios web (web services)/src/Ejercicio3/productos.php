<?php

// Indicar que la respuesta será JSON
header("Content-Type: application/json; charset=UTF-8");

// Leer el archivo JSON
$contenido = file_get_contents("productos.json");

// Convertir el JSON en un array PHP
$productos = json_decode($contenido, true);

// Array donde guardaremos los resultados
$resultados = [];

// Buscar por nombre
if (isset($_GET["nombre"])) {

    $nombre = $_GET["nombre"];

    foreach ($productos as $producto) {

        if (stripos($producto["nombre"], $nombre) !== false) {

            $resultados[] = [
                "nombre" => $producto["nombre"],
                "precio" => $producto["precio"],
                "url_imagen" => $producto["url_imagen"]
            ];

        }

    }

// Buscar por rango de precios
} else if (isset($_GET["precio_min"]) && isset($_GET["precio_max"])) {

    $minimo = $_GET["precio_min"];
    $maximo = $_GET["precio_max"];

    foreach ($productos as $producto) {

        if ($producto["precio"] >= $minimo &&
            $producto["precio"] <= $maximo) {

            $resultados[] = [
                "nombre" => $producto["nombre"],
                "precio" => $producto["precio"],
                "url_imagen" => $producto["url_imagen"]
            ];

        }

    }

} else {

    http_response_code(400);

    echo json_encode([
        "error" => "Faltan parámetros para realizar la búsqueda"
    ]);

    exit;

}

// Devolver los resultados en formato JSON
echo json_encode($resultados);

?>