<?php
$productos = $productos ?? [];
$mensaje = $mensaje ?? "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo - Mi tienda</title>
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

        <h2>Catálogo de productos</h2>

        <?php if ($mensaje != ""): ?>
            <p class="mensaje">
                <?php echo htmlspecialchars($mensaje); ?>
            </p>
        <?php endif; ?>

        <div class="productos">

            <?php foreach ($productos as $producto): ?>

                <div class="producto">

                    <h3>
                        <?php echo htmlspecialchars($producto["nombre"]); ?>
                    </h3>

                    <p>Código: <?php echo $producto["codigo"]; ?></p>

                    <p>
                        Precio:
                        <strong>
                            <?php echo number_format($producto["precio"], 2, ",", "."); ?> €
                        </strong>
                    </p>

                    <p>Stock: <?php echo $producto["stock"]; ?></p>

                    <form action="index.php" method="POST">
                        <input type="hidden" name="accion" value="comprar">
                        <input type="hidden" name="codigo"
                            value="<?php echo $producto["codigo"]; ?>">

                        <button type="submit">Comprar</button>
                    </form>

                    <a href="index.php?accion=modificar&codigo=<?php echo $producto["codigo"]; ?>">
                        Modificar
                    </a>

                    <form action="index.php" method="POST"
                        onsubmit="return confirm('¿Seguro que quieres eliminar este producto?');">

                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="codigo"
                            value="<?php echo $producto["codigo"]; ?>">

                        <button type="submit" class="eliminar">Eliminar</button>
                    </form>

                </div>

            <?php endforeach; ?>

        </div>

    </main>

</body>

</html>