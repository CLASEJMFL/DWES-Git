<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: text/html; charset=UTF-8");

require_once __DIR__ . "/controllers/ProductoController.php";
require_once __DIR__ . "/controllers/CarritoController.php";

$productoController = new ProductoController();
$carritoController = new CarritoController();

$accion = $_POST["accion"] ?? $_GET["accion"] ?? "productos";

$mensaje = "";
$producto = null;
$productos = [];
$productosCarrito = [];
$productosFactura = [];
$total = 0;
$numeroFactura = "";

// ------------------------------------
// MOSTRAR CATÁLOGO
// ------------------------------------

if ($accion == "productos") {

    $productos = $productoController->listar();

    require __DIR__ . "/views/productos.php";
}

// ------------------------------------
// MOSTRAR FORMULARIO DE ALTA
// ------------------------------------

elseif ($accion == "alta") {

    require __DIR__ . "/views/alta.php";
}

// ------------------------------------
// GUARDAR PRODUCTO NUEVO
// ------------------------------------

elseif ($accion == "guardarAlta") {

    if ($productoController->alta($_POST)) {
        $mensaje = "Producto añadido correctamente.";
    } else {
        $mensaje = "No se pudo añadir el producto. Comprueba los datos o si el código ya existe.";
    }

    $productos = $productoController->listar();

    require __DIR__ . "/views/productos.php";
}

// ------------------------------------
// MOSTRAR FORMULARIO DE MODIFICACIÓN
// ------------------------------------

elseif ($accion == "modificar") {

    if (isset($_GET["codigo"])) {
        $producto = $productoController->buscar($_GET["codigo"]);
    }

    require __DIR__ . "/views/modificar.php";
}

// ------------------------------------
// GUARDAR MODIFICACIÓN
// ------------------------------------

elseif ($accion == "guardarModificacion") {

    if ($productoController->modificar($_POST)) {
        $mensaje = "Producto modificado correctamente.";
    } else {
        $mensaje = "No se pudo modificar el producto.";
    }

    $productos = $productoController->listar();

    require __DIR__ . "/views/productos.php";
}

// ------------------------------------
// ELIMINAR PRODUCTO
// ------------------------------------

elseif ($accion == "eliminar") {

    if (
        isset($_POST["codigo"]) &&
        $productoController->eliminar($_POST["codigo"])
    ) {

        $mensaje = "Producto eliminado correctamente.";
    } else {
        $mensaje = "No se pudo eliminar el producto.";
    }

    $productos = $productoController->listar();

    require __DIR__ . "/views/productos.php";
}

// ------------------------------------
// COMPRAR: AÑADIR AL CARRITO
// ------------------------------------

elseif ($accion == "comprar") {

    if (
        isset($_POST["codigo"]) &&
        $carritoController->comprar($_POST["codigo"])
    ) {

        $mensaje = "Producto añadido al carrito.";
    } else {
        $mensaje = "No se pudo añadir el producto. Comprueba el stock.";
    }

    $productos = $productoController->listar();

    require __DIR__ . "/views/productos.php";
}

// ------------------------------------
// MOSTRAR CARRITO
// ------------------------------------

elseif ($accion == "carrito") {

    $productosCarrito = $carritoController->listar();
    $total = $carritoController->calcularTotal();

    require __DIR__ . "/views/carrito.php";
}

// ------------------------------------
// QUITAR PRODUCTO DEL CARRITO
// ------------------------------------

elseif ($accion == "quitar") {

    if (isset($_POST["codigo"])) {
        $carritoController->eliminar($_POST["codigo"]);
    }

    $productosCarrito = $carritoController->listar();
    $total = $carritoController->calcularTotal();

    require __DIR__ . "/views/carrito.php";
}

// ------------------------------------
// FINALIZAR COMPRA
// ------------------------------------


elseif ($accion == "finalizarCompra") {

    if ($carritoController->finalizarCompra()) {

        $productosFactura = $_SESSION["productosFactura"];
        $total = $_SESSION["totalFactura"];
        $numeroFactura = $_SESSION["numeroFactura"];

        require __DIR__ . "/views/factura.php";
    } else {

        $mensaje = $_SESSION["errorCompra"] ??
            "No se pudo completar la compra.";

        unset($_SESSION["errorCompra"]);

        $productosCarrito = $carritoController->listar();
        $total = $carritoController->calcularTotal();

        require __DIR__ . "/views/carrito.php";
    }
}


// ------------------------------------
// MOSTRAR FACTURA
// ------------------------------------

elseif ($accion == "factura") {

    $productosFactura = $_SESSION["productosFactura"] ?? [];
    $total = $_SESSION["totalFactura"] ?? 0;
    $numeroFactura = $_SESSION["numeroFactura"] ?? "";

    require __DIR__ . "/views/factura.php";
}

// ------------------------------------
// ACCIÓN NO RECONOCIDA
// ------------------------------------

else {

    $productos = $productoController->listar();

    require __DIR__ . "/views/productos.php";
}
