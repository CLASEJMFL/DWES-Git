<!DOCTYPE html>
<html>
<head>
    <title>Ejercicio 3</title>
</head>
<body>

    <h1>Buscar productos</h1>

    <h2>Buscar por nombre</h2>

    <form action="peticion.php" method="POST">

        <label>Nombre del producto:</label>
        <input type="text" name="nombre" required>

        <input type="submit" value="Buscar por nombre">

    </form>

    <hr>

    <h2>Buscar por precio</h2>

    <form action="peticion.php" method="POST">

        <label>Precio mínimo:</label>
        <input type="number" name="precio_min" step="0.01" required>

        <label>Precio máximo:</label>
        <input type="number" name="precio_max" step="0.01" required>

        <input type="submit" value="Buscar por precio">

    </form>

</body>
</html>