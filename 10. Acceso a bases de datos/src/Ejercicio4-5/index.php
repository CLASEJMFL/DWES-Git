
<?php

require_once __DIR__ . "/controllers/articulocontroller.php";

$controlador = new ArticuloController();

$accion = $_POST["accion"] ?? $_GET["accion"] ?? "listado";

$articulos = [];
$articulo = null;
$mensaje = "";
$datos = [];

// MOSTRAR LISTADO
if ($accion == "listado") {

    $articulos = $controlador->listar();

    require __DIR__ . "/views/listado.php";

// MOSTRAR FORMULARIO DE ALTA
} elseif ($accion == "alta") {

    require __DIR__ . "/views/alta.php";

// GUARDAR ARTICULO NUEVO
} elseif ($accion == "guardarAlta") {

    $datos = $_POST;

    if ($controlador->alta($_POST)) {

        $mensaje = "Artículo añadido correctamente.";
        $articulos = $controlador->listar();

        require __DIR__ . "/views/listado.php";

    } else {

        $mensaje = "No se pudo añadir el artículo. Comprueba los datos o si el código ya existe.";

        require __DIR__ . "/views/alta.php";

    }

// MOSTRAR FORMULARIO DE MODIFICACION
} elseif ($accion == "modificar") {

    $codigo = $_GET["codigo"] ?? "";

    $articulo = $controlador->buscar($codigo);

    if ($articulo) {

        require __DIR__ . "/views/modificar.php";

    } else {

        $mensaje = "No se ha encontrado el artículo.";
        $articulos = $controlador->listar();

        require __DIR__ . "/views/listado.php";

    }

// GUARDAR MODIFICACION
} elseif ($accion == "guardarModificacion") {

    if ($controlador->actualizar($_POST)) {

        $mensaje = "Artículo modificado correctamente.";

    } else {

        $mensaje = "No se pudo modificar el artículo. Comprueba los datos.";
    }

    $articulos = $controlador->listar();

    require __DIR__ . "/views/listado.php";

// ELIMINAR ARTICULO
} elseif ($accion == "eliminar") {

    $codigo = $_POST["codigo"] ?? "";

    if ($controlador->eliminar($codigo)) {

        $mensaje = "Artículo eliminado correctamente.";

    } else {

        $mensaje = "No se pudo eliminar el artículo.";
    }

    $articulos = $controlador->listar();

    require __DIR__ . "/views/listado.php";

// ACCION DESCONOCIDA
} else {

    $articulos = $controlador->listar();

    require __DIR__ . "/views/listado.php";

}

?>
