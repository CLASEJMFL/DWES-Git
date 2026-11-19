
<?php

require_once dirname(__DIR__, 2) . "/conexion.php";

class Articulo {

    private $conexion;

    public function __construct() {
        global $CONEXION;
        $this->conexion = $CONEXION;
    }

    // Obtener todos los artículos
    public function listar() {

        $sql = "SELECT * FROM almacen";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar un artículo por su código
    public function buscar($codigo) {

        $sql = "SELECT * FROM almacen WHERE codigo = ?";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([$codigo]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar un artículo
    public function insertar($codigo, $descripcion, $precioCompra, $precioVenta, $stock) {

        $sql = "INSERT INTO almacen
                (codigo, descripcion, precio_compra, precio_venta, stock)
                VALUES (?, ?, ?, ?, ?)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            $codigo,
            $descripcion,
            $precioCompra,
            $precioVenta,
            $stock
        ]);
    }

    // Modificar un artículo existente
    public function actualizar($codigo, $descripcion, $precioCompra, $precioVenta, $stock) {

        $sql = "UPDATE almacen
                SET descripcion = ?,
                    precio_compra = ?,
                    precio_venta = ?,
                    stock = ?
                WHERE codigo = ?";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            $descripcion,
            $precioCompra,
            $precioVenta,
            $stock,
            $codigo
        ]);
    }

    // Eliminar un artículo
    public function eliminar($codigo) {

        $sql = "DELETE FROM almacen WHERE codigo = ?";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([$codigo]);
    }

}
