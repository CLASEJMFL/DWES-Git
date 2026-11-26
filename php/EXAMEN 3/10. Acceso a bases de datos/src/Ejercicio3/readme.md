# 🛒 Carrito de la Compra - Arquitectura MVC

Aplicación web de gestión de tienda online y carrito de la compra desarrollada bajo el patrón arquitectónico **Modelo-Vista-Controlador (MVC)** con persistencia en base de datos relacional.

---

## 🚀 Características Principales

* **Arquitectura MVC Pura:** Separación estricta entre la lógica de negocio/datos (Modelo), la interfaz de usuario (Vista) y el flujo de control (Controlador).
* **Control Inteligente de Stock:** Validación en tiempo real al añadir productos al carrito, comprobando tanto las existencias en base de datos como las unidades acumuladas en la sesión actual.
* **Proceso de Compra Completo:**
  * Descuento automático de stock en la base de datos tras confirmar el pedido.
  * Generación dinámica de **ticket/factura** en archivo independiente.
  * Apertura automática del comprobante en una nueva pestaña del navegador.
  * Vaciado automático de la cesta al finalizar.
* **Seguridad en Operaciones:** Confirmación previa antes de eliminar productos para evitar borrados accidentales.

---

## 🗄️ Base de Datos y Modelo

### Esquema SQL (`productos`)

```sql
CREATE DATABASE IF NOT EXISTS tienda_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_mvc;

CREATE TABLE productos (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0
);
```

### Clase `Producto` (Modelo)

La entidad `Producto` encapsula los atributos de la tabla y define la lógica de inventario:

| Elemento | Tipo | Descripción |
| :--- | :--- | :--- |
| `__construct(...)` | Método | Inicializa una instancia de producto con sus propiedades. |
| `getCodigo()` | Getter | Devuelve el identificador único autonumérico. |
| `getNombre()` | Getter | Devuelve el nombre del artículo. |
| `getPrecio()` | Getter | Devuelve el precio unitario. |
| `getStock()` | Getter | Devuelve las unidades disponibles actualmente. |
| `reponer($cantidad)` | Método | Incrementa el stock disponible del producto. |
| `vender($cantidad)` | Método | Reduce el stock tras confirmarse una compra. |

---

## ⚙️ Flujo de Funcionamiento

### 1. Validación al Añadir a la Cesta
Cuando el usuario intenta añadir un artículo, el controlador evalúa la siguiente condición antes de modificar la sesión:

$$\text{Stock Disponible} \ge (\text{Cantidad en Cesta} + 1)$$

Si no se cumple, se bloquea la adición y se muestra un aviso de stock insuficiente.

### 2. Finalización de Compra (*Comprar*)
1. Se recorren los artículos de la cesta y se ejecuta `vender()` sobre cada producto en la base de datos.
2. Se genera un fichero de factura/ticket con el desglose de artículos, subtotales, total e importe.
3. El navegador abre el ticket generado en una pestaña nueva (`target="_blank"`).
4. Se reinicia la variable de sesión del carrito dejándolo vacío.

---

## 📁 Estructura del Proyecto

```text
proyecto-carrito-mvc/
├── config/
│   └── database.php           # Configuración y conexión a la BD
├── controllers/
│   ├── ProductoController.php # CRUD de productos y reposición de stock
│   └── CarritoController.php  # Lógica de cesta, validaciones y checkout
├── models/
│   └── Producto.php           # Modelo de datos y métodos de negocio
├── views/
│   ├── layouts/               # Plantillas base (header, footer)
│   ├── productos/             # Catálogo y gestión de inventario
│   └── carrito/               # Vista de la cesta de la compra
├── tickets/                   # Directorio de salida para facturas generadas
├── index.php                  # Punto de entrada (Front Controller)
└── README.md                  # Documentación del proyecto
```

---

## 🛠️ Instalación y Despliegue

1. **Clonar o descargar** el repositorio en el directorio raíz de tu servidor local (ej. `htdocs` en XAMPP o `www` en Laragon).
2. **Importar la base de datos** ejecutando el script SQL detallado en la sección anterior.
3. **Configurar credenciales** de acceso en `config/database.php`.
4. **Permisos de escritura:** Asegúrate de que la carpeta `tickets/` tenga permisos de escritura para poder generar los comprobantes de compra.
5. Acceder desde el navegador a `http://localhost/proyecto-carrito-mvc/`.