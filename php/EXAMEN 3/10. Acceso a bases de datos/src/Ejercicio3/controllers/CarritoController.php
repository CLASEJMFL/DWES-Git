
<?php

require_once dirname(__DIR__) . "/models/Producto.php";

class CarritoController
{

    private $modelo;
    private $conexion;

    public function __construct()
    {

        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        $this->modelo = new Producto();

        global $CONEXION;
        $this->conexion = $CONEXION;

        if (!isset($_SESSION["carrito"])) {
            $_SESSION["carrito"] = [];
        }
    }

    // Añadir producto al carrito
    public function comprar($codigo)
    {

        $codigo = (int) $codigo;
        $producto = $this->modelo->buscar($codigo);

        if (!$producto || $producto["stock"] <= 0) {
            return false;
        }

        $cantidadActual = $_SESSION["carrito"][$codigo] ?? 0;

        if ($cantidadActual >= $producto["stock"]) {
            return false;
        }

        $_SESSION["carrito"][$codigo] = $cantidadActual + 1;

        return true;
    }

    // Eliminar producto del carrito
    public function eliminar($codigo)
    {

        $codigo = (int) $codigo;

        if (isset($_SESSION["carrito"][$codigo])) {
            unset($_SESSION["carrito"][$codigo]);
            return true;
        }

        return false;
    }

    // Obtener productos del carrito
    public function listar()
    {

        $productos = [];

        foreach ($_SESSION["carrito"] as $codigo => $cantidad) {

            $producto = $this->modelo->buscar($codigo);

            if ($producto) {
                $producto["cantidad"] = $cantidad;
                $productos[] = $producto;
            } else {
                unset($_SESSION["carrito"][$codigo]);
            }
        }

        return $productos;
    }

    // Calcular total sin IVA
    public function calcularTotal()
    {

        $total = 0;

        foreach ($this->listar() as $producto) {
            $total += $producto["precio"] * $producto["cantidad"];
        }

        return round($total, 2);
    }

    // Vaciar carrito
    public function vaciar()
    {
        $_SESSION["carrito"] = [];
    }

    // Finalizar compra y generar factura
    public function finalizarCompra()
    {

        $productos = $this->listar();

        if (empty($productos)) {
            return false;
        }

        try {

            $this->conexion->beginTransaction();

            $productosFactura = [];
            $baseImponible = 0;

            foreach ($productos as $producto) {

                // Comprobar y descontar stock de forma segura
                $sql = "UPDATE PRODUCTOS
                        SET stock = stock - ?
                        WHERE codigo = ? AND stock >= ?";

                $consulta = $this->conexion->prepare($sql);

                $consulta->execute([
                    $producto["cantidad"],
                    $producto["codigo"],
                    $producto["cantidad"]
                ]);

                if ($consulta->rowCount() != 1) {
                    throw new Exception(
                        "Stock insuficiente para " . $producto["nombre"]
                    );
                }

                $subtotal = $producto["precio"] * $producto["cantidad"];

                $producto["subtotal"] = round($subtotal, 2);

                $productosFactura[] = $producto;

                $baseImponible += $subtotal;
            }

            // IVA del 21 %
            $baseImponible = round($baseImponible, 2);
            $iva = round($baseImponible * 0.21, 2);
            $total = round($baseImponible + $iva, 2);

            $this->conexion->commit();

            // Guardar información de la factura en sesión
            $_SESSION["productosFactura"] = $productosFactura;
            $_SESSION["baseImponible"] = $baseImponible;
            $_SESSION["ivaFactura"] = $iva;
            $_SESSION["totalFactura"] = $total;
            $_SESSION["numeroFactura"] = "FAC-" . date("YmdHis");

            // Guardar ticket en archivo
            $this->guardarTicket(
                $_SESSION["productosFactura"],
                $baseImponible,
                $iva,
                $total,
                $_SESSION["numeroFactura"]
            );

            $this->vaciar();

            return true;
        } catch (Exception $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            $_SESSION["errorCompra"] = $e->getMessage();

            return false;
        }
    }

    // Guardar ticket en la carpeta tickets
    private function guardarTicket(
        $productos,
        $baseImponible,
        $iva,
        $total,
        $numeroFactura
    ) {

        $carpeta = dirname(__DIR__) . "/tickets";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0775, true);
        }

        $contenido = "FACTURA DE COMPRA\n";
        $contenido .= "Numero: " . $numeroFactura . "\n";
        $contenido .= "Fecha: " . date("d/m/Y H:i:s") . "\n\n";

        foreach ($productos as $producto) {

            $contenido .= $producto["nombre"] . "\n";

            $contenido .= "Cantidad: " . $producto["cantidad"] . "\n";

            $contenido .= "Precio: " . number_format(
                $producto["precio"],
                2,
                ",",
                "."
            ) . " euros\n";

            $contenido .= "Subtotal: " . number_format(
                $producto["subtotal"],
                2,
                ",",
                "."
            ) . " euros\n\n";
        }

        $contenido .= "Base imponible: " .
            number_format($baseImponible, 2, ",", ".") . " euros\n";

        $contenido .= "IVA (21%): " .
            number_format($iva, 2, ",", ".") . " euros\n";

        $contenido .= "TOTAL: " .
            number_format($total, 2, ",", ".") . " euros\n";

        $archivo = $carpeta . "/" . $numeroFactura . ".txt";

        file_put_contents($archivo, $contenido);
    }
}
?>
