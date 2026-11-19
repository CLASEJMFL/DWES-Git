
<?php

require_once dirname(__DIR__, 2) . "/conexion.php";

class Producto {

    private $conexion;

    public function __construct() {
        global $CONEXION;
        $this->conexion = $CONEXION;
    }

    // Mostrar todos los productos
    public function listar() {

        $sql = "SELECT * FROM PRODUCTOS ORDER BY codigo";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar producto por código
    public function buscar($codigo) {

        $sql = "SELECT * FROM PRODUCTOS WHERE codigo = ?";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([$codigo]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Dar de alta un producto
    public function insertar($codigo, $nombre, $precio, $stock) {

        // Comprobar si el código ya existe
        if ($this->buscar($codigo)) {
            return false;
        }

        $sql = "INSERT INTO PRODUCTOS
                (codigo, nombre, precio, stock)
                VALUES (?, ?, ?, ?)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            $codigo,
            $nombre,
            $precio,
            $stock
        ]);
    }

    // Modificar producto
    public function modificar($codigo, $nombre, $precio, $stock) {

        // Comprobar que el producto existe
        if (!$this->buscar($codigo)) {
            return false;
        }

        $sql = "UPDATE PRODUCTOS
                SET nombre = ?, precio = ?, stock = ?
                WHERE codigo = ?";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            $nombre,
            $precio,
            $stock,
            $codigo
        ]);
    }

    // Eliminar producto
    public function eliminar($codigo) {

        // Comprobar que existe
        if (!$this->buscar($codigo)) {
            return false;
        }

        $sql = "DELETE FROM PRODUCTOS WHERE codigo = ?";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([$codigo]);
    }

}

?>