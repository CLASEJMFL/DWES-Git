<?php
session_start();

// Catálogo de productos
$productos = [
    1 => [
        "nombre" => "Portátil",
        "descripcion" => "Portátil de 15 pulgadas con 8GB de RAM.",
        "precio" => 599.99,
        "imagen" => "imagenes/portatil.png"
    ],
    2 => [
        "nombre" => "Teléfono móvil",
        "descripcion" => "Smartphone con pantalla de alta resolución.",
        "precio" => 299.99,
        "imagen" => "imagenes/movil.png"
    ],
    3 => [
        "nombre" => "Auriculares",
        "descripcion" => "Auriculares inalámbricos con sonido estéreo.",
        "precio" => 49.99,
        "imagen" => "imagenes/auriculares.png"
    ],
    4 => [
        "nombre" => "Teclado",
        "descripcion" => "Teclado mecánico para ordenador.",
        "precio" => 39.99,
        "imagen" => "imagenes/teclado.png"
    ],
    5 => [
        "nombre" => "Ratón",
        "descripcion" => "Ratón inalámbrico ergonómico.",
        "precio" => 19.99,
        "imagen" => "imagenes/raton.png"
    ]
];

// Inicializar el carrito
if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

// Comprar producto
if (isset($_POST["comprar"])) {

    $id = (int) $_POST["comprar"];

    if (isset($productos[$id])) {

        // Si ya existe, aumentar cantidad
        if (isset($_SESSION["carrito"][$id])) {
            $_SESSION["carrito"][$id]++;
        } else {
            // Si no existe, añadirlo
            $_SESSION["carrito"][$id] = 1;
        }
    }
}

// Eliminar producto del carrito
if (isset($_POST["eliminar"])) {

    $id = (int) $_POST["eliminar"];

    unset($_SESSION["carrito"][$id]);
}

// Calcular total
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi tienda online</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <h1>Mi tienda online</h1>
        <p>Bienvenido a nuestra tienda</p>
    </header>

    <main>

        <section class="catalogo">

            <h2>Catálogo de productos</h2>

            <div class="productos">

                <?php foreach ($productos as $id => $producto): ?>

                    <div class="producto">

                        <img src="<?php echo $producto["imagen"]; ?>"
                            alt="<?php echo $producto["nombre"]; ?>">

                        <h3><?php echo $producto["nombre"]; ?></h3>

                        <p><?php echo $producto["descripcion"]; ?></p>

                        <p class="precio">
                            <?php echo number_format($producto["precio"], 2, ",", "."); ?> €
                        </p>

                        <form method="POST">
                            <button type="submit"
                                name="comprar"
                                value="<?php echo $id; ?>">
                                Comprar
                            </button>
                        </form>

                    </div>

                <?php endforeach; ?>

            </div>

        </section>


        <section class="carrito">

            <h2>Carrito de la compra</h2>

            <?php if (empty($_SESSION["carrito"])): ?>

                <p>El carrito está vacío.</p>

            <?php else: ?>

                <table>

                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>

                    <?php foreach ($_SESSION["carrito"] as $id => $cantidad): ?>

                        <?php
                        $producto = $productos[$id];
                        $subtotal = $producto["precio"] * $cantidad;
                        $total += $subtotal;
                        ?>

                        <tr>

                            <td><?php echo $producto["nombre"]; ?></td>

                            <td>
                                <?php echo number_format($producto["precio"], 2, ",", "."); ?> €
                            </td>

                            <td><?php echo $cantidad; ?></td>

                            <td>
                                <?php echo number_format($subtotal, 2, ",", "."); ?> €
                            </td>

                            <td>

                                <form method="POST">
                                    <button type="submit"
                                        name="eliminar"
                                        value="<?php echo $id; ?>"
                                        class="eliminar">
                                        Eliminar
                                    </button>
                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

                <h3 class="total">
                    Total: <?php echo number_format($total, 2, ",", "."); ?> €
                </h3>

            <?php endif; ?>

        </section>

    </main>

    <footer>
        <p>Mi tienda online - 2026</p>
    </footer>

</body>

</html>