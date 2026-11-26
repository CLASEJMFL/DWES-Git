# Resumen: Acceso a bases de datos desde PHP

## 1. Formas de conectar PHP con una base de datos

PHP permite conectarse a distintos sistemas gestores de bases de datos
(SGBD) y ejecutar instrucciones SQL.

Existen tres interfaces principales:

-   **`mysql`**: interfaz antigua para MySQL. Está obsoleta y no se
    recomienda en proyectos nuevos, aunque conviene conocerla por su uso
    histórico.
-   **`mysqli`**: versión mejorada de `mysql`. Añade funciones como
    transacciones, procedimientos almacenados y consultas múltiples. Se
    puede usar en aplicaciones nuevas.
-   **`PDO` (PHP Data Objects)**: proporciona una capa de abstracción
    que permite trabajar con distintos SGBD utilizando métodos
    similares. Facilita cambiar de gestor sin tener que modificar gran
    parte del código PHP.

**Recomendación:** utilizar PDO o mysqli en proyectos nuevos. En este
tema se trabaja con PDO.

## 2. PDO: conexión con la base de datos

La conexión se crea mediante el constructor `PDO`, indicando el gestor,
el servidor, la base de datos, el usuario y la contraseña.

``` php
try {
    $conexion = new PDO(
        "mysql:host=localhost;dbname=banco;charset=utf8",
        "root",
        "root"
    );

    echo "Conexión establecida.";
} catch (PDOException $e) {
    die("No se pudo conectar: " . $e->getMessage());
}
```

-   `mysql`: gestor de base de datos utilizado.
-   `host=localhost`: servidor donde está la base de datos.
-   `dbname=banco`: nombre de la base de datos.
-   `charset=utf8`: codificación de caracteres.
-   `root`: usuario y contraseña del ejemplo.
-   `try/catch`: permite capturar errores de conexión.

PDO también admite otros gestores. El identificador cambia según el
SGBD: `pgsql` (PostgreSQL), `sqlite` (SQLite), `firebird`, `informix` y
`OCI` (Oracle).

Cuando se termina de trabajar con la base de datos, se cierra la
conexión asignándole `null`:

``` php
$conexion = null;
```

## 3. Consultar y mostrar todos los registros de una tabla

Para obtener registros se puede utilizar `query()` con una consulta
`SELECT`. El método `fetchObject()` obtiene cada fila como un objeto.

``` php
$consulta = $conexion->query(
    "SELECT dni, nombre, direccion, telefono FROM cliente"
);

while ($cliente = $consulta->fetchObject()) {
    echo $cliente->dni;
    echo $cliente->nombre;
    echo $cliente->direccion;
    echo $cliente->telefono;
}
```

Los campos se consultan como propiedades del objeto: `$cliente->dni`,
`$cliente->nombre`, etc.

`rowCount()` devuelve el número de filas afectadas o, según el
controlador, el número de filas del resultado. **En consultas `SELECT`
con MySQL no es fiable en todos los casos**; para contar resultados es
preferible recorrerlos o ejecutar una consulta `SELECT COUNT(*)`.

## 4. Insertar, borrar y actualizar registros

El material presenta `exec()` para ejecutar operaciones de modificación,
como `INSERT`, `UPDATE` y `DELETE`.

Flujo habitual para dar de alta un cliente:

1.  Mostrar un formulario HTML que solicite DNI, nombre, dirección y
    teléfono.
2.  Enviar los datos a otro archivo PHP mediante `method="post"`.
3.  Conectar con la base de datos.
4.  Comprobar si ya existe un cliente con ese DNI.
5.  Si no existe, insertar el nuevo registro.

Ejemplo de formulario:

``` html
<form action="altaClienteAccion.php" method="post">
    DNI <input type="text" name="dni" required>
    Nombre <input type="text" name="nombre">
    Dirección <input type="text" name="direccion">
    Teléfono <input type="tel" name="telefono">
    <input type="submit" value="Enviar">
</form>
```

### Importante: usar consultas preparadas

Los ejemplos originales concatenan directamente los datos de `$_POST`
dentro de las consultas SQL. **Esto es inseguro**, porque puede permitir
una inyección SQL. En código real se deben utilizar consultas preparadas
con `prepare()` y `execute()`.

Ejemplo seguro de inserción:

``` php
$sql = "INSERT INTO cliente (dni, nombre, direccion, telefono)
        VALUES (:dni, :nombre, :direccion, :telefono)";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':dni' => $_POST['dni'],
    ':nombre' => $_POST['nombre'],
    ':direccion' => $_POST['direccion'],
    ':telefono' => $_POST['telefono']
]);
```

Para comprobar si el DNI existe, también debe utilizarse una consulta
preparada:

``` php
$stmt = $conexion->prepare(
    "SELECT dni FROM cliente WHERE dni = :dni"
);
$stmt->execute([':dni' => $_POST['dni']]);

if ($stmt->fetch()) {
    echo "Ya existe un cliente con ese DNI.";
} else {
    // Realizar la inserción con otra consulta preparada.
}
```

Además, conviene validar los datos recibidos y definir el DNI como clave
primaria o con una restricción `UNIQUE` en la base de datos para evitar
duplicados incluso si dos altas se producen simultáneamente.

## 5. Recuperar MySQL en XAMPP si no inicia

Si MySQL no arranca porque los datos se han corrompido, el material
propone reconstruir la carpeta de datos utilizando la copia de seguridad
de XAMPP.

Pasos indicados:

1.  Ir a `C:\xampp\mysql`.
2.  Renombrar la carpeta `data` como `data_old`.
3.  Crear una nueva carpeta llamada `data`.
4.  Copiar el contenido de `backup` dentro de la nueva carpeta `data`.
5.  Copiar `ibdata1` desde `data_old` a la nueva carpeta `data`,
    sobrescribiendo si se solicita.
6.  Copiar a la nueva carpeta `data` las bases de datos que se quieran
    recuperar desde `data_old`.

**Precaución:** este procedimiento puede provocar pérdida de datos o
problemas de compatibilidad, especialmente con archivos de tablas
InnoDB. Antes de modificar nada, hay que detener MySQL y hacer una copia
completa de `C:\xampp\mysql`. No se debe copiar indiscriminadamente todo
el contenido de `data_old`; la recuperación depende de la versión de
MySQL/MariaDB y del tipo de tablas. Si se restaura la base de datos que
estaba dañada, el problema podría repetirse.

------------------------------------------------------------------------

## Ideas clave para estudiar

-   `mysql` está obsoleta; para proyectos nuevos se recomienda `PDO` o
    `mysqli`.
-   PDO permite trabajar con diferentes SGBD mediante una interfaz
    común.
-   `new PDO(...)` crea la conexión y `catch (PDOException $e)` permite
    gestionar errores.
-   `query()` ejecuta consultas SQL; `fetchObject()` obtiene filas como
    objetos.
-   `exec()` se utiliza para operaciones que no devuelven un conjunto de
    filas, como inserciones, actualizaciones y borrados.
-   Para datos introducidos por usuarios, usar siempre `prepare()` y
    `execute()` para evitar inyecciones SQL.
-   Para cerrar la conexión: `$conexion = null;`.