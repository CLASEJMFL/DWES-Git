
<?php

require_once dirname(__DIR__) . "/models/Producto.php";

class ProductoController {

    private $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }

    // Mostrar todos los productos
    public function listar() {
        return $this->modelo->listar();
    }

    // Buscar un producto
    public function buscar($codigo) {
        return $this->modelo->buscar($codigo);
    }

    // Dar de alta un producto
    public function alta($datos) {

        $codigo = (int) $datos["codigo"];
        $nombre = trim($datos["nombre"]);
        $precio = (float) $datos["precio"];
        $stock = (int) $datos["stock"];

        if ($codigo <= 0 || $nombre == "" ||
            $precio < 0 || $stock < 0) {

            return false;
        }

        return $this->modelo->insertar(
            $codigo,
            $nombre,
            $precio,
            $stock
        );
    }

    // Modificar un producto
    public function modificar($datos) {

        $codigo = (int) $datos["codigo"];
        $nombre = trim($datos["nombre"]);
        $precio = (float) $datos["precio"];
        $stock = (int) $datos["stock"];

        if ($codigo <= 0 || $nombre == "" ||
            $precio < 0 || $stock < 0) {

            return false;
        }

        return $this->modelo->modificar(
            $codigo,
            $nombre,
            $precio,
            $stock
        );
    }

    // Eliminar un producto
    public function eliminar($codigo) {

        $codigo = (int) $codigo;

        if ($codigo <= 0) {
            return false;
        }

        return $this->modelo->eliminar($codigo);
    }

}

?>  