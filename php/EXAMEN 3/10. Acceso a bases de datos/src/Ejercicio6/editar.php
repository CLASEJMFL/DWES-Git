<?php
require_once("../conexion.php");

$dia = $_GET['dia'];
$hora = $_GET['hora'];

$horas = [
    "primera", "segunda", "tercera",
    "cuarta", "quinta", "sexta"
];

if (!in_array($hora, $horas)) {
    die("Hora no válida");
}

// Consultar asignatura actual
$sql = "SELECT $hora FROM horario WHERE dia = ?";

$stmt = $CONEXION->prepare($sql);
$stmt->execute([$dia]);

$fila = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fila) {
    die("Día no encontrado");
}

$actual = $fila[$hora];

// Asignaturas disponibles
$asignaturas = [
    "Matemáticas",
    "Lengua",
    "Inglés",
    "Historia",
    "Física",
    "Educación Física",
    "Biología",
    "Química",
    "Filosofía"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar horario</title>
</head>
<body>

<h1>Editar horario</h1>

<p>Día: <?php echo htmlspecialchars($dia); ?></p>
<p>Hora: <?php echo htmlspecialchars($hora); ?></p>

<form action="actualizar.php" method="POST">

    <input type="hidden" name="dia"
           value="<?php echo htmlspecialchars($dia); ?>">

    <input type="hidden" name="hora"
           value="<?php echo htmlspecialchars($hora); ?>">

    <label>Selecciona una asignatura:</label>

    <select name="asignatura">

        <?php foreach ($asignaturas as $asignatura) { ?>

            <option value="<?php echo htmlspecialchars($asignatura); ?>"
                <?php if ($asignatura == $actual) echo "selected"; ?>>

                <?php echo htmlspecialchars($asignatura); ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <button type="submit">Grabar</button>

</form>

</body>
</html>
