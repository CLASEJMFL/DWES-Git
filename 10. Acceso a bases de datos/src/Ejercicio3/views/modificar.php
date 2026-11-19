<?php
$mensaje = $mensaje ?? "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Modificar producto</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Modificar producto</h1>

    <nav>
        <a href="index.php">Volver al catálogo</a>
    </nav>

    <p><?php echo htmlspecialchars($mensaje); ?></p>

    <?php if (!empty($producto)): ?>

        <form action="index.php" method="POST">

            <input type="hidden" name="accion"
                value="guardarModificacion">

            <input type="hidden" name="codigo"
                value="<?php echo $producto["codigo"]; ?>">

            <label>Código:</label>
            <strong><?php echo $producto["codigo"]; ?></strong>
            <br><br>

            <label>Nombre:</label>
            <input type="text" name="nombre"
                value="<?php echo htmlspecialchars($producto["nombre"]); ?>"
                maxlength="100" required>
            <br><br>

            <label>Precio:</label>
            <input type="number" name="precio"
                value="<?php echo $producto["precio"]; ?>"
                min="0" step="0.01" required>
            <br><br>

            <label>Stock:</label>
            <input type="number" name="stock"
                value="<?php echo $producto["stock"]; ?>"
                min="0" required>
            <br><br>

            <button type="submit">Guardar cambios</button>

        </form>

    <?php else: ?>

        <p>No se ha encontrado el producto.</p>

    <?php endif; ?>

</body>

</html>