<?php
require_once("../conexion.php");

$dia = $_POST['dia'];
$hora = $_POST['hora'];
$asignatura = $_POST['asignatura'];

$horas = [
    "primera",
    "segunda",
    "tercera",
    "cuarta",
    "quinta",
    "sexta"
];

if (!in_array($hora, $horas)) {
    die("Hora no válida");
}

// Actualizar horario
$sql = "UPDATE horario SET $hora = ? WHERE dia = ?";

$stmt = $CONEXION->prepare($sql);

$stmt->execute([$asignatura, $dia]);

// Volver al horario principal
header("Location: index.php");
exit();
