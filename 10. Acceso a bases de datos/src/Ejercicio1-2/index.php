
<?php

require_once "../conexion.php";


// INSERTAR NUEVO CLIENTE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $DNI = $_POST['DNI'];
    $NOMBRE = $_POST['NOMBRE'];
    $DIRECCION = $_POST['DIRECCION'];
    $TELEFONO = $_POST['TELEFONO'];

    $SQL = "INSERT INTO CLIENTES (DNI, NOMBRE, DIRECCION, TELEFONO)
            VALUES (:DNI, :NOMBRE, :DIRECCION, :TELEFONO)";

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


// CONSULTAR TODOS LOS CLIENTES
$SQL = "SELECT * FROM CLIENTES";

$CONSULTA = $CONEXION->prepare($SQL);
$CONSULTA->execute();

$RESULTADO = $CONSULTA->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio1 y 2</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="contenedor">

        <h1>LISTADO DE CLIENTES</h1>

        <!-- TABLA DE CLIENTES -->

        <table>

            <tr>
                <th>DNI</th>
                <th>NOMBRE</th>
                <th>DIRECCION</th>
                <th>TELEFONO</th>
                <th></th>
                <th></th>
            </tr>

            <?php foreach ($RESULTADO as $CLIENTE) { ?>

                <tr>

                    <td><?php echo htmlspecialchars($CLIENTE['dni']); ?></td>
                    <td><?php echo htmlspecialchars($CLIENTE['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($CLIENTE['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($CLIENTE['telefono']); ?></td>

                    <!-- BOTON ELIMINAR -->

                    <td>

                        <form method="POST" action="eliminar.php">

                            <input type="hidden" name="DNI"
                                value="<?php echo htmlspecialchars($CLIENTE['dni']); ?>">

                            <button type="submit" style="background-color: red;">
                                ELIMINAR
                            </button>

                        </form>

                    </td>

                    <!-- BOTON MODIFICAR -->

                    <td>

                        <form method="POST" action="modificar.php">

                            <input type="hidden" name="DNI"
                                value="<?php echo htmlspecialchars($CLIENTE['dni']); ?>">

                            <button type="submit" style="background-color: orange;">
                                MODIFICAR
                            </button>

                        </form>

                    </td>

                </tr>

            <?php } ?>

        </table>


        <h2>AÑADIR NUEVO CLIENTE</h2>

        <!-- FORMULARIO PARA INSERTAR CLIENTES -->

        <form class="formulario" method="POST" action="clientes.php">

            <input type="text" name="DNI" placeholder="DNI" required>

            <input type="text" name="NOMBRE" placeholder="NOMBRE" required>

            <input type="text" name="DIRECCION" placeholder="DIRECCION" required>

            <input type="text" name="TELEFONO" placeholder="TELEFONO" required>

            <button type="submit">AÑADIR</button>

        </form>

    </div>

</body>

</html>