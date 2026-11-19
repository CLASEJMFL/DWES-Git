<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTISIMAL - Alta de artículo</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1>GESTISIMAL</h1>
        <p>Gestión simplificada de almacén</p>
    </header>

    <main>

        <h2>Alta de artículo</h2>

        <?php if (!empty($mensaje)): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form action="index.php" method="POST">

            <input type="hidden" name="accion" value="guardarAlta">

            <label for="codigo">Código:</label>
            <input
                type="text"
                id="codigo"
                name="codigo"
                maxlength="20"
                required
                value="<?= htmlspecialchars($datos["codigo"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label for="descripcion">Descripción:</label>
            <input
                type="text"
                id="descripcion"
                name="descripcion"
                maxlength="150"
                required
                value="<?= htmlspecialchars($datos["descripcion"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label for="precio_compra">Precio de compra:</label>
            <input
                type="number"
                id="precio_compra"
                name="precio_compra"
                step="0.01"
                min="0"
                required
                value="<?= htmlspecialchars($datos["precio_compra"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label for="precio_venta">Precio de venta:</label>
            <input
                type="number"
                id="precio_venta"
                name="precio_venta"
                step="0.01"
                min="0"
                required
                value="<?= htmlspecialchars($datos["precio_venta"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label for="stock">Stock:</label>
            <input
                type="number"
                id="stock"
                name="stock"
                min="0"
                step="1"
                required
                value="<?= htmlspecialchars($datos["stock"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <button type="submit">Guardar artículo</button>

        </form>

        <p>
            <a href="index.php">Volver al listado</a>
        </p>

    </main>

</body>
</html>