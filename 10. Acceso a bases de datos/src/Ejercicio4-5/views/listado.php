<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTISIMAL - Listado de artículos</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1>GESTISIMAL</h1>
        <p>Gestión simplificada de almacén</p>
    </header>

    <main>

        <h2>Listado de artículos</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje">
                <?= htmlspecialchars($mensaje) ?>
            </p>
        <?php endif; ?>

        <a href="index.php?accion=alta">
            Añadir nuevo artículo
        </a>

        <table>

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Precio de compra</th>
                    <th>Precio de venta</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($articulos)): ?>

                    <?php foreach ($articulos as $articulo): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($articulo['codigo']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($articulo['descripcion']) ?>
                            </td>

                            <td>
                                <?= number_format($articulo['precio_compra'], 2, ',', '.') ?> €
                            </td>

                            <td>
                                <?= number_format($articulo['precio_venta'], 2, ',', '.') ?> €
                            </td>

                            <td>
                                <?= htmlspecialchars($articulo['stock']) ?>
                            </td>

                            <td class="acciones">

                                <!-- MODIFICAR -->
                                <a href="index.php?accion=modificar&codigo=<?= urlencode($articulo['codigo']) ?>"
                                    class="btn-modificar">
                                    Modificar
                                </a>

                                <!-- ELIMINAR -->
                                <form action="index.php"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('¿Estás seguro de que quieres eliminar este artículo?');">

                                    <input type="hidden"
                                        name="accion"
                                        value="eliminar">

                                    <input type="hidden"
                                        name="codigo"
                                        value="<?= htmlspecialchars($articulo['codigo']) ?>">

                                    <button type="submit" class="btn-eliminar">
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6">
                            No hay artículos registrados.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </main>

</body>

</html>