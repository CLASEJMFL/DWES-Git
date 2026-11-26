<?php

require_once "../conexion.php";

// ELIMINAR CLIENTE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['DNI']) && !empty($_POST['DNI'])) {

    $DNI = $_POST['DNI'];

    $SQL = "DELETE FROM CLIENTES WHERE DNI = :DNI";

    $CONSULTA = $CONEXION->prepare($SQL);

    $CONSULTA->execute([
        ':DNI' => $DNI
    ]);

    header("Location: index.php");
    exit();
} else {

    header("Location: index.php");
    exit();
}
