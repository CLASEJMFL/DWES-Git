# Ejercicio 7: Carrito de la Compra con Base de Datos y Subida de Imágenes

## 📋 Descripción del Proyecto
El objetivo de este ejercicio es evolucionar la aplicación del carrito de la compra (que anteriormente leía los productos desde un fichero plano) para conectarla a una base de datos relacional llamada **`tienda`**.

### Requisitos Funcionales
1. **Carga inicial:** Al iniciar la aplicación, se deben recuperar y mostrar todos los productos almacenados en la base de datos `tienda`.
2. **Inserción de productos:** Añadir un formulario que permita registrar nuevos productos en la base de datos.
3. **Subida de imágenes:** El formulario de creación debe incluir un campo `<input type="file">` para subir la imagen del producto al servidor y almacenar su ruta/nombre en la base de datos.
4. **Eliminación de productos:** Permitir borrar productos existentes tanto de la base de datos como (opcionalmente) su imagen asociada del servidor.

---

## 🗂️ Estructura Recomendada del Proyecto

```text
proyecto-tienda/
├── init.sql              # Script de creación e inicialización de la BD
├── index.php             # Vista principal (listado de productos, carrito y formulario)
├── conexion.php          # Archivo de conexión a MySQL/MariaDB (PDO o MySQLi)
├── procesar_producto.php # Lógica para subir la imagen e insertar en la BD
├── eliminar_producto.php # Lógica para eliminar el producto de la BD
└── uploads/              # Carpeta en el servidor donde se guardarán las imágenes subidas
```

---

## ⚙️ Detalles Técnicos Clave

### 1. El Formulario y la Subida de Archivos (`index.php`)
Para que el navegador envíe correctamente archivos al servidor mediante un `input type="file"`, es **obligatorio** incluir el atributo `enctype="multipart/form-data"` en la etiqueta `<form>`:

```html
<form action="procesar_producto.php" method="POST" enctype="multipart/form-data">
    <label>Nombre del producto:</label>
    <input type="text" name="nombre" required>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea>

    <label>Precio (€):</label>
    <input type="number" step="0.01" name="precio" required>

    <label>Imagen del producto:</label>
    <input type="file" name="imagen" accept="image/*" required>

    <button type="submit">Añadir Producto</button>
</form>
```

### 2. Gestión de la Imagen en el Servidor (`procesar_producto.php`)
* **Recepción:** Se captura mediante la variable superglobal `$_FILES['imagen']`.
* **Validación:** Comprobar que no hay errores (`$_FILES['imagen']['error'] === UPLOAD_ERR_OK`) y validar la extensión/tipo MIME (`.jpg`, `.png`, `.webp`).
* **Almacenamiento:** Usar `move_uploaded_file()` para mover el archivo temporal a la carpeta `uploads/`. Se recomienda renombrar el archivo (por ejemplo, usando `time()` o `uniqid()`) para evitar colisiones de nombres.
* **Base de datos:** Guardar únicamente la ruta relativa (ej. `uploads/1700000_camiseta.jpg`) en la columna `imagen` de la tabla `productos`.

### 3. Eliminación de Productos (`eliminar_producto.php`)
* Recibir el `id` del producto a eliminar (por `GET` o `POST`).
* *(Opcional pero recomendado)* Hacer un `SELECT imagen FROM productos WHERE id = ?` para borrar el archivo físico del servidor usando `unlink($ruta_imagen)`.
* Ejecutar `DELETE FROM productos WHERE id = ?` utilizando consultas preparadas para evitar inyección SQL.

---

## 🚀 Instrucciones de Despliegue
1. Asegúrate de tener levantado tu servidor web (Apache/Nginx) y el gestor de base de datos (MySQL/MariaDB).
2. Importa el archivo **`init.sql`** en tu gestor (phpMyAdmin, DBeaver o consola MySQL) para crear la base de datos `tienda` y sus datos de prueba.
3. Crea la carpeta `uploads/` en la raíz de tu proyecto y asegúrate de que tenga permisos de escritura (`chmod 775` en Linux/Mac).
4. Configura tus credenciales de acceso en `conexion.php` y abre `index.php` en el navegador.