
<?php

// Comprobar que se ha recibido el artículo desde index.php
if (!isset($articulo) || $articulo === false || $articulo === null) {
    echo "No se ha encontrado el artículo.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar artículo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Modificar artículo</h1>

    <form action="index.php" method="POST">

        <input type="hidden" name="accion" value="guardarModificacion">

        <input
            type="hidden"
            name="codigo"
            value="<?= htmlspecialchars($articulo["codigo"], ENT_QUOTES, "UTF-8") ?>"
        >

        <p>
            <label>Código:</label><br>
            <input
                type="text"
                value="<?= htmlspecialchars($articulo["codigo"], ENT_QUOTES, "UTF-8") ?>"
                readonly
            >
        </p>

        <p>
            <label>Descripción:</label><br>
            <input
                type="text"
                name="descripcion"
                maxlength="150"
                required
                value="<?= htmlspecialchars($articulo["descripcion"], ENT_QUOTES, "UTF-8") ?>"
            >
        </p>

        <p>
            <label>Precio de compra:</label><br>
            <input
                type="number"
                name="precio_compra"
                step="0.01"
                min="0"
                required
                value="<?= htmlspecialchars($articulo["precio_compra"], ENT_QUOTES, "UTF-8") ?>"
            >
        </p>

        <p>
            <label>Precio de venta:</label><br>
            <input
                type="number"
                name="precio_venta"
                step="0.01"
                min="0"
                required
                value="<?= htmlspecialchars($articulo["precio_venta"], ENT_QUOTES, "UTF-8") ?>"
            >
        </p>

        <p>
            <label>Stock:</label><br>
            <input
                type="number"
                name="stock"
                min="0"
                step="1"
                required
                value="<?= htmlspecialchars($articulo["stock"], ENT_QUOTES, "UTF-8") ?>"
            >
        </p>

        <button type="submit">Guardar cambios</button>

    </form>

    <p>
        <a href="index.php">Volver al listado</a>
    </p>

</body>
</html>
