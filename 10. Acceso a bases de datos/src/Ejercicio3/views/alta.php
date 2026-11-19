<?php
$mensaje = $mensaje ?? "";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir producto</title>
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

        <h2>Añadir producto</h2>

        <?php if ($mensaje != ""): ?>
            <p class="error"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form action="index.php" method="POST">

            <input type="hidden" name="accion" value="guardarAlta">

            <label>Código:</label>
            <input type="number" name="codigo" min="1" required>

            <label>Nombre:</label>
            <input type="text" name="nombre" maxlength="100" required>

            <label>Precio:</label>
            <input type="number" name="precio" min="0" step="0.01" required>

            <label>Stock:</label>
            <input type="number" name="stock" min="0" required>

            <button type="submit">Guardar producto</button>

        </form>

    </main>

</body>

</html>