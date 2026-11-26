# Ejercicio 3: Servicio web de productos usando JSON

## 1. Objetivo

Crear un servicio web que proporcione información sobre productos almacenados en un archivo JSON.

El servicio devolverá los siguientes datos de cada producto:

- Nombre.
- Precio.
- URL de la imagen.

Se podrán realizar dos tipos de consultas:

1. **Por nombre:** devolverá los productos cuyo nombre contenga el texto introducido.
2. **Por precio:** devolverá los productos cuyo precio esté entre un mínimo y un máximo.

La información se devolverá en formato JSON.

## 2. Estructura de archivos

Creamos una carpeta llamada `ejercicio3` con estos archivos:

```text
ejercicio3/
│
├── productos.json
├── index.php
├── peticion.php
└── productos.php
```

## 3. Archivo productos.json

Este archivo almacena los productos. Cada producto tiene un nombre, un precio y la URL de su imagen.

```json
[
    {
        "nombre": "Ordenador portátil",
        "precio": 750,
        "url_imagen": "imagenes/portatil.jpg"
    },
    {
        "nombre": "Ratón inalámbrico",
        "precio": 25,
        "url_imagen": "imagenes/raton.jpg"
    },
    {
        "nombre": "Teclado mecánico",
        "precio": 60,
        "url_imagen": "imagenes/teclado.jpg"
    },
    {
        "nombre": "Monitor",
        "precio": 180,
        "url_imagen": "imagenes/monitor.jpg"
    },
    {
        "nombre": "Ordenador sobremesa",
        "precio": 900,
        "url_imagen": "imagenes/sobremesa.jpg"
    }
]
```

## 4. Archivo index.php

Es la página principal. Contiene dos formularios: uno para buscar por nombre y otro para buscar por rango de precios.

```php
<!DOCTYPE html>
<html>
<head>
    <title>Buscar productos</title>
</head>
<body>

    <h1>Buscar productos</h1>

    <h2>Buscar por nombre</h2>

    <form action="peticion.php" method="POST">

        <label>Nombre del producto:</label>
        <input type="text" name="nombre" required>

        <input type="submit" value="Buscar por nombre">

    </form>

    <hr>

    <h2>Buscar por precio</h2>

    <form action="peticion.php" method="POST">

        <label>Precio mínimo:</label>
        <input type="number" name="precio_min" step="0.01" required>

        <label>Precio máximo:</label>
        <input type="number" name="precio_max" step="0.01" required>

        <input type="submit" value="Buscar por precio">

    </form>

</body>
</html>
```

## 5. Archivo peticion.php

Este archivo recibe los datos de los formularios y realiza una petición GET al servicio web `productos.php`.

Utiliza `http_build_query()` para preparar los parámetros de la URL, `file_get_contents()` para realizar la petición y `json_decode()` para interpretar la respuesta JSON.

```php
<?php

$url = "http://localhost/ejercicio3/productos.php?";

if (isset($_POST["nombre"])) {

    $parametros = http_build_query([
        "nombre" => $_POST["nombre"]
    ]);

} else if (isset($_POST["precio_min"]) && isset($_POST["precio_max"])) {

    $parametros = http_build_query([
        "precio_min" => $_POST["precio_min"],
        "precio_max" => $_POST["precio_max"]
    ]);

} else {

    echo "No se han recibido los datos correctamente.";
    exit;

}

// Realizar la petición al servicio web
$respuesta = file_get_contents($url . $parametros);

// Convertir el JSON recibido en datos PHP
$productos = json_decode($respuesta, true);

// Mostrar los resultados
echo "<h1>Resultados de la búsqueda</h1>";

if (isset($productos["error"])) {

    echo $productos["error"];

} else if (count($productos) > 0) {

    foreach ($productos as $producto) {

        echo "<h3>" . $producto["nombre"] . "</h3>";
        echo "Precio: " . $producto["precio"] . " €<br>";
        echo "Imagen: " . $producto["url_imagen"] . "<br>";
        echo "<hr>";

    }

} else {

    echo "No se han encontrado productos.";

}

?>
```

## 6. Archivo productos.php

Este es el servicio web. Lee los productos del archivo JSON, comprueba los parámetros recibidos y filtra los productos según la búsqueda.

Finalmente, devuelve los resultados en formato JSON.

```php
<?php

// Indicar que la respuesta será JSON
header("Content-Type: application/json; charset=UTF-8");

// Leer el archivo JSON
$contenido = file_get_contents("productos.json");

// Convertir el JSON en un array PHP
$productos = json_decode($contenido, true);

// Array donde guardaremos los resultados
$resultados = [];

// Buscar por nombre
if (isset($_GET["nombre"])) {

    $nombre = $_GET["nombre"];

    foreach ($productos as $producto) {

        if (stripos($producto["nombre"], $nombre) !== false) {

            $resultados[] = [
                "nombre" => $producto["nombre"],
                "precio" => $producto["precio"],
                "url_imagen" => $producto["url_imagen"]
            ];

        }

    }

// Buscar por rango de precios
} else if (isset($_GET["precio_min"]) && isset($_GET["precio_max"])) {

    $minimo = $_GET["precio_min"];
    $maximo = $_GET["precio_max"];

    foreach ($productos as $producto) {

        if ($producto["precio"] >= $minimo &&
            $producto["precio"] <= $maximo) {

            $resultados[] = [
                "nombre" => $producto["nombre"],
                "precio" => $producto["precio"],
                "url_imagen" => $producto["url_imagen"]
            ];

        }

    }

} else {

    http_response_code(400);

    echo json_encode([
        "error" => "Faltan parámetros para realizar la búsqueda"
    ]);

    exit;

}

// Devolver los resultados en formato JSON
echo json_encode($resultados);

?>
```

## 7. Cómo probar el ejercicio

1. Guarda los cuatro archivos dentro de la carpeta `ejercicio3`.
2. Coloca la carpeta dentro de `htdocs` si utilizas XAMPP.
3. Inicia Apache.
4. Abre en el navegador:

```text
http://localhost/ejercicio3/index.php
```

### Prueba 1: buscar por nombre

Introduce `ordenador`.

El servicio devolverá los productos cuyo nombre contenga esa palabra.

### Prueba 2: buscar por precio

Introduce:

- Precio mínimo: `20`
- Precio máximo: `100`

El servicio devolverá los productos cuyo precio esté entre 20 y 100 euros, incluidos ambos límites.

## 8. Funciones y conceptos utilizados del tema

| Función o concepto | Para qué sirve |
|---|---|
| `file_get_contents()` | Leer el archivo JSON y realizar la petición al servicio web. |
| `json_decode()` | Convertir JSON en datos PHP. |
| `json_encode()` | Convertir los resultados PHP en JSON. |
| `$_POST` | Recibir los datos enviados desde los formularios. |
| `$_GET` | Recibir los parámetros de la petición del servicio web. |
| `isset()` | Comprobar si existe un parámetro. |
| `http_build_query()` | Crear los parámetros de la URL. |
| `foreach` | Recorrer los productos. |
| `stripos()` | Comprobar si el nombre contiene el texto buscado. |
| `if / else if / else` | Elegir el tipo de búsqueda. |
| `header()` | Indicar que la respuesta es JSON. |
| `json_decode(..., true)` | Obtener los datos JSON como arrays asociativos. |

**Importante:** en esta versión los productos se guardan en `productos.json`, no en una base de datos. Tampoco se utiliza ningún token de acceso.