<?php

$resultado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cambio = $_POST["cambio"];
    $cantidad = $_POST["cantidad"];

    if ($cambio == "Euro") {
        $resultado = number_format($cantidad * 166.386, 2, ",", ".") . " Pesetas";
    } 
    else if ($cambio == "Peseta") {
        $resultado = number_format($cantidad / 166.386, 2, ",", ".") . " Euros";
    }

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
</head>

<body>
    <form method="post" action="">

        <select id="modalidad" name="cambio">
            <option value="Euro">Euro >> Peseta</option>
            <option value="Peseta">Peseta >> Euro</option>
        </select>

        <br><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" step="any" required>

        <br><br>

        <button type="submit">Enviar</button>

        <label for="resultado moneda">Resultado:</label>
        <input type="text" id="resultado" name="resultado" value="<?php echo $resultado; ?>" readonly>

    </form>
</body>

</html>