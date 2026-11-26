<?php

header("Content-Type: application/json; charset=utf-8");

// Recibimos la cantidad enviada mediante POST.
$cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);

if ($cantidad === false || $cantidad === null || $cantidad < 1 || $cantidad > 40) {

    http_response_code(400);

    echo json_encode([
        "error" => "El número de cartas debe estar entre 1 y 40"
    ], JSON_UNESCAPED_UNICODE);

    exit;

} else {

    // Leemos el archivo JSON.
    $datos = file_get_contents(__DIR__ . "/baraja.json");

    if ($datos === false) {
        http_response_code(500);
        echo json_encode(["error" => "No se ha podido leer baraja.json"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Decodificamos el JSON.
    $baraja = json_decode($datos);

    if ($baraja === null || !isset($baraja->palos) || !isset($baraja->figuras)) {
        http_response_code(500);
        echo json_encode(["error" => "El archivo baraja.json no es válido"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $resultado = [];
    $usadas = ",";

    for ($i = 0; $i < $cantidad; $i++) {

        // Elegimos una posición aleatoria sin repetir.
        do {
            $numero = rand(0, 39);
        } while (strpos($usadas, "," . $numero . ",") !== false);

        $usadas .= $numero . ",";

        // Obtenemos el palo y la figura.
        $palo = intval($numero / 10);
        $figura = $numero % 10;

        $carta = (object) [
            "palo" => $baraja->palos[$palo],
            "figura" => $baraja->figuras[$figura]
        ];

        $resultado[] = $carta;
    }

    // Devolvemos las cartas en formato JSON.
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
}

?>