<?php
$productosCarrito = $productosCarrito ?? [];
$total = $total ?? 0;
$mensaje = $mensaje ?? "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi carrito</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1>Mi tienda online</h1>

        <nav>
            <a href="index.php">Catálogo</a>
            <a href="index.php?accion=alta">Añadir producto</a>
            <a href="index.php?accion=carrito">Ver carrito</a>
        </nav>
    </header>

    <main>

        <h2>Mi carrito de compra</h2>

        <?php if ($mensaje != ""): ?>
            <p class="error"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <?php if (empty($productosCarrito)): ?>

            <p>El carrito está vacío.</p>

        <?php else: ?>

            <?php foreach ($productosCarrito as $producto): ?>

                <div class="producto">

                    <h3><?php echo htmlspecialchars($producto["nombre"]); ?></h3>

                    <p>
                        Precio unitario:
                        <?php echo number_format($producto["precio"], 2, ",", "."); ?> €
                    </p>

                    <p>Cantidad: <?php echo $producto["cantidad"]; ?></p>

                    <p>
                        Subtotal:
                        <?php echo number_format(
                            $producto["precio"] * $producto["cantidad"],
                            2,
                            ",",
                            "."
                        ); ?> €
                    </p>

                    <form action="index.php" method="POST">
                        <input type="hidden" name="accion" value="quitar">
                        <input type="hidden" name="codigo"
                            value="<?php echo $producto["codigo"]; ?>">

                        <button type="submit" class="eliminar">
                            Quitar del carrito
                        </button>
                    </form>

                </div>

            <?php endforeach; ?>

            <h2>
                Total:
                <?php echo number_format($total, 2, ",", "."); ?> €
            </h2>

            <form action="index.php" method="POST">
                <input type="hidden" name="accion" value="finalizarCompra">
                <button type="submit">Finalizar compra</button>
            </form>

        <?php endif; ?>

    </main>

</body>

</html>