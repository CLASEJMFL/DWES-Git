<?php
$productosFactura = $productosFactura ?? [];
$total = $total ?? 0;
$numeroFactura = $numeroFactura ?? "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Factura de compra</h1>

    <p>
        Número de factura:
        <?php echo htmlspecialchars((string)$numeroFactura); ?>
    </p>

    <p>
        Fecha:
        <?php echo date("d/m/Y H:i"); ?>
    </p>

    <h2>Productos comprados</h2>

    <?php if (empty($productosFactura)): ?>

        <p>No hay productos en esta factura.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productosFactura as $producto): ?>

                    <tr>
                        <td><?php echo $producto["codigo"]; ?></td>

                        <td>
                            <?php echo htmlspecialchars($producto["nombre"]); ?>
                        </td>

                        <td>
                            <?php echo number_format(
                                $producto["precio"],
                                2,
                                ",",
                                "."
                            ); ?> €
                        </td>

                        <td><?php echo $producto["cantidad"]; ?></td>

                        <td>
                            <?php echo number_format(
                                $producto["precio"] * $producto["cantidad"],
                                2,
                                ",",
                                "."
                            ); ?> €
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <h2>
            Total:
            <?php echo number_format($total, 2, ",", "."); ?> €
        </h2>

    <?php endif; ?>

    <button onclick="window.print()">Imprimir factura</button>

    <a href="index.php">Volver a la tienda</a>

</body>

</html>