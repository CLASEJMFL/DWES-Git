<?php

require_once "../conexion.php";

// SI SE PULSA GUARDAR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ACTUALIZAR'])) {

    $DNI = $_POST['DNI'];
    $NOMBRE = $_POST['NOMBRE'];
    $DIRECCION = $_POST['DIRECCION'];
    $TELEFONO = $_POST['TELEFONO'];

    $SQL = "UPDATE CLIENTES
            SET NOMBRE = :NOMBRE,
                DIRECCION = :DIRECCION,
                TELEFONO = :TELEFONO
            WHERE DNI = :DNI";

    $CONSULTA = $CONEXION->prepare($SQL);

    $CONSULTA->execute([
        ':DNI' => $DNI,
        ':NOMBRE' => $NOMBRE,
        ':DIRECCION' => $DIRECCION,
        ':TELEFONO' => $TELEFONO
    ]);

    header("Location: clientes.php");
    exit();
}


// SI SE PULSA MODIFICAR, BUSCAR EL CLIENTE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['DNI'])) {

    $DNI = $_POST['DNI'];

    $SQL = "SELECT * FROM CLIENTES WHERE DNI = :DNI";

    $CONSULTA = $CONEXION->prepare($SQL);

    $CONSULTA->execute([
        ':DNI' => $DNI
    ]);

    $CLIENTE = $CONSULTA->fetch(PDO::FETCH_ASSOC);

    if (!$CLIENTE) {
        die("Cliente no encontrado");
    }
} else {

    header("Location: clientes.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICAR CLIENTE</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="contenedor">

        <h1>MODIFICAR CLIENTE</h1>


        <form class="formulario" method="POST" action="modificar.php">

            <table>

                <tr>
                    <th>CAMPO</th>
                    <th>RELLENAR</th>
                </tr>

                <tr>
                    <td>DNI</td>
                    <td>
                        <input type="text" name="DNI"
                            value="<?php echo htmlspecialchars($CLIENTE['DNI']); ?>"
                            readonly>
                    </td>
                </tr>

                <tr>
                    <td>NOMBRE</td>
                    <td>
                        <input type="text" name="NOMBRE"
                            value="<?php echo htmlspecialchars($CLIENTE['NOMBRE']); ?>"
                            required>
                    </td>
                </tr>

                <tr>
                    <td>DIRECCION</td>
                    <td>
                        <input type="text" name="DIRECCION"
                            value="<?php echo htmlspecialchars($CLIENTE['DIRECCION']); ?>"
                            required>
                    </td>
                </tr>

                <tr>
                    <td>TELEFONO</td>
                    <td>
                        <input type="text" name="TELEFONO"
                            value="<?php echo htmlspecialchars($CLIENTE['TELEFONO']); ?>"
                            required>
                    </td>
                </tr>

            </table>

            <button type="submit" name="ACTUALIZAR">
                GUARDAR CAMBIOS
            </button>

        </form>


        <br>

        <a href="index.php">VOLVER</a>

    </div>

</body>

</html>