
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Baraja española</title>
</head>
<body>

    <h1>Obtener cartas de la baraja española</h1>

    <form action="index.php" method="POST">

        <label for="cantidad">
            ¿Cuántas cartas quieres obtener?
        </label>

        <input
            type="number"
            name="cantidad"
            id="cantidad"
            min="1"
            max="40"
            required
        >

        <input type="submit" value="Obtener cartas">

    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $cantidad = $_POST["cantidad"];

        // Datos que enviaremos al servicio
        $datos = http_build_query(
            array("cantidad" => $cantidad)
        );

        // Configuración de la petición POST
        $opciones = array(
            "http" => array(
                "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
                "method"  => "POST",
                "content" => $datos
            )
        );

        $contexto = stream_context_create($opciones);

        // Dirección del servicio web
        $url = "http://localhost/Ejercicio2/cartas.php";

        // Llamamos al servicio web
        $respuesta = file_get_contents($url, false, $contexto);

        if ($respuesta === false) {

            echo "<p>Error al conectar con el servicio.</p>";

        } else {

            // Convertimos el JSON en un objeto PHP
            $cartas = json_decode($respuesta);

            echo "<h2>Cartas obtenidas:</h2>";

            if (isset($cartas->error)) {

                echo "<p>" . htmlspecialchars($cartas->error) . "</p>";

            } else {

                foreach ($cartas as $carta) {

                    echo htmlspecialchars($carta->figura);
                    echo " de ";
                    echo htmlspecialchars($carta->palo);
                    echo "<br>";

                }

            }

        }

    }

    ?>

</body>
</html>