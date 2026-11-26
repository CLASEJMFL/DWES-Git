<?php

$url = "http://localhost/ejercicio3/productos.php?";

if (isset($_POST["nombre"])) {

    $parametros = http_build_query([
        "nombre" => $_POST["nombre"]
    ]);

} else if (isset($_POST["precio_min"]) && isset($_POST["precio_max"])) {

    $parametros = http_build_query([
        "precio_min" => $_POST["precio_min"],
        "precio_max" => $_POST["precio_max"]
    ]);

} else {

    echo "No se han recibido los datos correctamente.";
    exit;

}

// Realizar la petición al servicio web
$respuesta = file_get_contents($url . $parametros);

// Convertir el JSON recibido en datos PHP
$productos = json_decode($respuesta, true);

// Mostrar los resultados
echo "<h1>Resultados de la búsqueda</h1>";

if (isset($productos["error"])) {

    echo $productos["error"];

} else if (count($productos) > 0) {

    foreach ($productos as $producto) {

        echo "<h3>" . $producto["nombre"] . "</h3>";
        echo "Precio: " . $producto["precio"] . " €<br>";
        echo "Imagen: " . $producto["url_imagen"] . "<br>";
        echo "<hr>";

    }

} else {

    echo "No se han encontrado productos.";

}

?>