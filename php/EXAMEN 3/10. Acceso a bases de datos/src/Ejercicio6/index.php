<?php
require_once("../conexion.php");

// Consultar horario
$sql = "SELECT * FROM horario";
$resultado = $CONEXION->query($sql);

// Horas del horario
$horas = [
    "primera",
    "segunda",
    "tercera",
    "cuarta",
    "quinta",
    "sexta"
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario Escolar</title>
</head>
<body>

<h1>Horario Escolar</h1>

<table border="1" cellpadding="10">

    <tr>
        <th>Día</th>
        <th>1ª Hora</th>
        <th>2ª Hora</th>
        <th>3ª Hora</th>
        <th>4ª Hora</th>
        <th>5ª Hora</th>
        <th>6ª Hora</th>
    </tr>

    <?php while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>

        <tr>

            <td><?php echo htmlspecialchars($fila['dia']); ?></td>

            <?php foreach ($horas as $hora) { ?>

                <td>
                    <a href="editar.php?dia=<?php echo urlencode($fila['dia']); ?>&hora=<?php echo $hora; ?>">
                        <?php echo htmlspecialchars($fila[$hora]); ?>
                    </a>
                </td>

            <?php } ?>

        </tr>

    <?php } ?>

</table>

</body>
</html>
