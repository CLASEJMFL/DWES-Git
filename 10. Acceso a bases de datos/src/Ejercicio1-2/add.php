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

    // VOLVER AL LISTADO
    header("Location: index.php");
    exit();
}
?>
