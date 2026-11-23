
<?php

require_once dirname(__DIR__) . "/models/articulo.php";

class ArticuloController {

    private $modelo;

    public function __construct() {
        $this->modelo = new Articulo();
    }

    // Mostrar todos los artículos
    public function listar() {
        return $this->modelo->listar();
    }

    // Buscar un artículo
    public function buscar($codigo) {
        return $this->modelo->buscar($codigo);
    }

    // Comprobar los datos recibidos
    private function validarDatos($datos) {

        $codigo = trim($datos["codigo"] ?? "");
        $descripcion = trim($datos["descripcion"] ?? "");
        $precioCompra = $datos["precio_compra"] ?? "";
        $precioVenta = $datos["precio_venta"] ?? "";
        $stock = $datos["stock"] ?? "";

        if (
            $codigo == "" ||
            $descripcion == "" ||
            !is_numeric($precioCompra) ||
            !is_numeric($precioVenta) ||
            filter_var($stock, FILTER_VALIDATE_INT) === false
        ) {
            return false;
        }

        if (
            $precioCompra < 0 ||
            $precioVenta < 0 ||
            $stock < 0
        ) {
            return false;
        }

        if (
            strlen($codigo) > 20 ||
            strlen($descripcion) > 150
        ) {
            return false;
        }

        return true;
    }

    // Dar de alta un artículo
    public function alta($datos) {

        if (!$this->validarDatos($datos)) {
            return false;
        }

        try {

            return $this->modelo->insertar(
                trim($datos["codigo"]),
                trim($datos["descripcion"]),
                $datos["precio_compra"],
                $datos["precio_venta"],
                $datos["stock"]
            );

        } catch (PDOException $e) {
            return false;
        }
    }

    // Modificar un artículo
    public function actualizar($datos) {

        if (!$this->validarDatos($datos)) {
            return false;
        }

        try {

            return $this->modelo->actualizar(
                trim($datos["codigo"]),
                trim($datos["descripcion"]),
                $datos["precio_compra"],
                $datos["precio_venta"],
                $datos["stock"]
            );

        } catch (PDOException $e) {
            return false;
        }
    }

    // Eliminar un artículo
    public function eliminar($codigo) {

        if (empty($codigo)) {
            return false;
        }

        try {
            return $this->modelo->eliminar($codigo);
        } catch (PDOException $e) {
            return false;
        }
    }

}