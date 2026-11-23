
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

    <main class="formulario-contenedor">

        <div class="formulario-cabecera">
            <h2>Alta de artículo</h2>
            <p>Introduce los datos del nuevo artículo para añadirlo al almacén.</p>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="POST" class="formulario">

            <input type="hidden" name="accion" value="guardarAlta">

            <div class="campo">
                <label for="codigo">Código del artículo</label>
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    maxlength="20"
                    placeholder="Ejemplo: ART001"
                    required
                    value="<?= htmlspecialchars($datos["codigo"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                >
            </div>

            <div class="campo">
                <label for="descripcion">Descripción</label>
                <input
                    type="text"
                    id="descripcion"
                    name="descripcion"
                    maxlength="150"
                    placeholder="Nombre o descripción del artículo"
                    required
                    value="<?= htmlspecialchars($datos["descripcion"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                >
            </div>

            <div class="campo">
                <label for="precio_compra">Precio de compra (€)</label>
                <input
                    type="number"
                    id="precio_compra"
                    name="precio_compra"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    required
                    value="<?= htmlspecialchars($datos["precio_compra"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                >
            </div>

            <div class="campo">
                <label for="precio_venta">Precio de venta (€)</label>
                <input
                    type="number"
                    id="precio_venta"
                    name="precio_venta"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    required
                    value="<?= htmlspecialchars($datos["precio_venta"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                >
            </div>

            <div class="campo">
                <label for="stock">Cantidad en stock</label>
                <input
                    type="number"
                    id="stock"
                    name="stock"
                    min="0"
                    step="1"
                    placeholder="0"
                    required
                    value="<?= htmlspecialchars($datos["stock"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                >
            </div>

            <div class="botones-formulario">

                <button type="submit" class="btn-guardar">
                    Guardar artículo
                </button>

                <a href="index.php" class="btn-volver">
                    Volver al listado
                </a>

            </div>

        </form>

    </main>

</body>
</html>
