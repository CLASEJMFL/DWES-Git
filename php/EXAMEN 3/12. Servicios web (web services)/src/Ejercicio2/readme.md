# Ejercicio 2. Servicio web de baraja española

## 1. Archivo `baraja.json`

Contiene los palos y las figuras de la baraja española.

```json
{
    "palos": ["oros", "copas", "espadas", "bastos"],
    "figuras": ["1", "2", "3", "4", "5", "6", "7", "sota", "caballo", "rey"]
}
```

## 2. Archivo `index.php`

Muestra el formulario, envía el número de cartas al servicio y recibe el resultado en JSON.

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Baraja española</title>
</head>
<body>

<h1>Obtener cartas aleatorias</h1>

<form action="index.php" method="post">
    Número de cartas:
    <input type="number" name="cantidad" min="1" max="40" required>
    <input type="submit" value="Obtener cartas">
</form>

<?php

if (isset($_POST['cantidad'])) {

    $url = "http://localhost/ejercicio2/cartas.php";

    $datos = [
        "cantidad" => $_POST['cantidad']
    ];

    $opciones = [
        "http" => [
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($datos)
        ]
    ];

    $contexto = stream_context_create($opciones);

    $respuesta = @file_get_contents($url, false, $contexto);

    if ($respuesta !== false) {

        $cartas = json_decode($respuesta);

        echo "<h2>Cartas obtenidas:</h2>";

        if (isset($cartas->error)) {
            echo $cartas->error;
        } else {
            foreach ($cartas as $carta) {
                echo $carta->figura . " de " . $carta->palo . "<br>";
            }
        }

    } else {
        echo "Error al conectar con el servicio";
    }
}

?>

</body>
</html>
```

## 3. Archivo `cartas.php`

Es el servicio web. Recibe el número de cartas, comprueba que sea válido y devuelve las cartas en JSON sin repetirlas.

```php
<?php

header("Content-Type: application/json;charset=utf-8");

$cantidad = $_REQUEST['cantidad'] ?? 0;

if ($cantidad < 1 || $cantidad > 40) {

    http_response_code(400);

    echo json_encode([
        "error" => "El número de cartas debe estar entre 1 y 40"
    ]);

} else {

    // Leemos el archivo JSON
    $datos = file_get_contents("baraja.json");

    // Decodificamos el JSON
    $baraja = json_decode($datos);

    $resultado = "[";
    $usadas = ",";

    for ($i = 0; $i < $cantidad; $i++) {

        // Elegimos una posición aleatoria sin repetir
        do {
            $numero = rand(0, 39);
        } while (strpos($usadas, "," . $numero . ",") !== false);

        $usadas .= $numero . ",";

        // Obtenemos el palo y la figura
        $palo = intval($numero / 10);
        $figura = $numero % 10;

        $carta = (object) [
            "palo" => $baraja->palos[$palo],
            "figura" => $baraja->figuras[$figura]
        ];

        if ($i > 0) {
            $resultado .= ",";
        }

        $resultado .= json_encode($carta);
    }

    $resultado .= "]";

    echo $resultado;
}

?>
```

## 4. Funcionamiento

1. El usuario introduce el número de cartas en el formulario.
2. `index.php` envía el dato mediante POST.
3. `cartas.php` recibe el número mediante `$_REQUEST`.
4. Comprueba que esté entre 1 y 40.
5. Lee `baraja.json` mediante `file_get_contents()`.
6. Utiliza `json_decode()` para acceder a los palos y figuras.
7. Genera posiciones aleatorias sin repetirlas.
8. Devuelve las cartas en JSON mediante `echo`.
9. El cliente utiliza `json_decode()` para mostrar las cartas.

# PHP – Métodos y funciones fundamentales

## 1. `$_POST` y `$_REQUEST`

Son variables superglobales de PHP que permiten recoger datos enviados desde formularios o peticiones HTTP.

### `$_POST`

Se utiliza para recibir datos enviados mediante el método POST. Los datos no aparecen en la URL.

**Ejemplo:**

```php
// formulario.html
<form method="POST" action="procesar.php">
    <input type="text" name="nombre">
    <input type="submit" value="Enviar">
</form>
```

```php
// procesar.php
$nombre = $_POST['nombre'];

echo "Hola, " . $nombre;
```

### `$_REQUEST`

Permite acceder a datos enviados mediante GET, POST y, dependiendo de la configuración de PHP, cookies.

**Ejemplo:**

```php
$nombre = $_REQUEST['nombre'];

echo "Hola, " . $nombre;
```

**Diferencia principal:**
- `$_POST`: recoge datos enviados mediante POST.
- `$_REQUEST`: puede recoger datos de GET, POST y cookies.

> Es recomendable utilizar `$_POST` o `$_GET` cuando se conoce el origen de los datos.

---

## 2. `file_get_contents()`

Es una función de PHP que permite leer el contenido de un archivo o de una URL y devolverlo como una cadena de texto.

**Sintaxis:**

```php
file_get_contents(ruta);
```

**Ejemplo leyendo un archivo:**

```php
$contenido = file_get_contents("datos.txt");

echo $contenido;
```

**Ejemplo leyendo una URL:**

```php
$respuesta = file_get_contents("https://ejemplo.com");

echo $respuesta;
```

**¿Para qué sirve?**

- Leer archivos de texto.
- Obtener información de una página web.
- Realizar peticiones HTTP GET.
- Recuperar datos de una API.

---

## 3. `stream_context_create()`

Es una función que permite crear un contexto para configurar cómo se realizan determinadas operaciones con archivos o conexiones HTTP.

Se utiliza especialmente para personalizar peticiones a servidores, indicando el método HTTP, las cabeceras y el contenido que se quiere enviar.

**Ejemplo:**

```php
$opciones = [
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/json",
        "content" => '{"nombre":"Juan"}'
    ]
];

$contexto = stream_context_create($opciones);

$respuesta = file_get_contents(
    "https://ejemplo.com/api",
    false,
    $contexto
);

echo $respuesta;
```

**¿Para qué sirve?**

- Configurar peticiones GET y POST.
- Enviar información a una API.
- Establecer cabeceras HTTP.
- Configurar opciones de conexión.

---

## 4. `http_build_query()`

Es una función que convierte un array asociativo en una cadena de parámetros URL.

Se utiliza habitualmente para enviar datos mediante peticiones GET.

**Ejemplo:**

```php
$datos = [
    "nombre" => "Juan",
    "edad" => 20,
    "ciudad" => "Sevilla"
];

$query = http_build_query($datos);

echo $query;
```

**Resultado:**

```text
nombre=Juan&edad=20&ciudad=Sevilla
```

También podemos utilizarlo para construir una URL:

```php
$url = "https://ejemplo.com/api?" . http_build_query($datos);

echo $url;
```

**¿Para qué sirve?**

- Convertir arrays en parámetros URL.
- Preparar datos para peticiones GET.
- Enviar información a servicios web.

---

## 5. `json_decode()` y `json_encode()`

Son funciones que permiten trabajar con datos en formato JSON.

JSON es un formato utilizado para intercambiar información entre aplicaciones, servidores y APIs.

### `json_encode()`

Convierte una variable de PHP en una cadena de texto JSON.

**Ejemplo:**

```php
$datos = [
    "nombre" => "Ana",
    "edad" => 25
];

$json = json_encode($datos);

echo $json;
```

**Resultado:**

```json
{"nombre":"Ana","edad":25}
```

### `json_decode()`

Convierte una cadena JSON en una variable de PHP.

**Ejemplo:**

```php
$json = '{"nombre":"Ana","edad":25}';

$datos = json_decode($json);

echo $datos->nombre;
```

**Resultado:**

```text
Ana
```

También podemos convertir el JSON directamente en un array asociativo:

```php
$datos = json_decode($json, true);

echo $datos['nombre'];
```

**Diferencia principal:**

- `json_encode()`: PHP → JSON.
- `json_decode()`: JSON → PHP.

---

## 6. Validación mediante `if`

La estructura `if` permite ejecutar un bloque de código solamente cuando se cumple una condición.

Se utiliza para comprobar que los datos recibidos son correctos antes de procesarlos.

**Sintaxis:**

```php
if (condicion) {
    // Código si se cumple la condición
} else {
    // Código si no se cumple
}
```

**Ejemplo:**

```php
$edad = 20;

if ($edad >= 18) {
    echo "Eres mayor de edad";
} else {
    echo "Eres menor de edad";
}
```

### Ejemplo validando datos de un formulario

```php
if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];

    echo "Nombre recibido: " . htmlspecialchars($nombre);
} else {
    echo "Debes introducir un nombre";
}
```

**Funciones utilizadas:**

- `isset()`: comprueba si una variable está definida y no es `null`.
- `empty()`: comprueba si una variable está vacía.
- `htmlspecialchars()`: convierte caracteres especiales en entidades HTML para evitar que se interpreten como código HTML.

**¿Para qué sirve?**

- Comprobar campos obligatorios.
- Validar edades y números.
- Controlar errores.
- Evitar procesar datos incorrectos.

---

## 7. Generación de números aleatorios con `rand()`

La función `rand()` permite generar un número entero aleatorio dentro de un intervalo determinado.

**Sintaxis:**

```php
rand(minimo, maximo);
```

**Ejemplo:**

```php
$numero = rand(1, 10);

echo $numero;
```

El resultado será un número aleatorio entre 1 y 10, ambos incluidos.

### Ejemplo: generar un número aleatorio para un sorteo

```php
$numero = rand(1, 100);

echo "El número ganador es: " . $numero;
```

### Ejemplo: generar un dado

```php
$dado = rand(1, 6);

echo "Has sacado un " . $dado;
```

**¿Para qué sirve?**

- Crear sorteos.
- Simular dados.
- Generar valores aleatorios.
- Crear juegos sencillos.

> `rand()` no es adecuado para generar contraseñas, tokens o datos sensibles. Para esos casos se recomienda utilizar `random_int()`.

---

## 8. Resumen general

| Función o variable | ¿Para qué sirve? |
|---|---|
| `$_POST` | Recoger datos enviados mediante POST. |
| `$_REQUEST` | Recoger datos de GET, POST y cookies según configuración. |
| `file_get_contents()` | Leer archivos o contenido de URLs. |
| `stream_context_create()` | Configurar conexiones y peticiones HTTP. |
| `http_build_query()` | Convertir arrays en parámetros URL. |
| `json_encode()` | Convertir datos PHP a JSON. |
| `json_decode()` | Convertir JSON a datos PHP. |
| `if` | Ejecutar código según una condición. |
| `rand()` | Generar números enteros aleatorios. |

---

## 9. Ejemplo práctico combinando varias funciones

Este ejemplo recoge un nombre mediante POST, valida que no esté vacío, genera un número aleatorio y devuelve los datos en formato JSON.

```php
<?php

if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {

    $nombre = $_POST['nombre'];

    $numero = rand(1, 100);

    $respuesta = [
        "nombre" => $nombre,
        "numero" => $numero,
        "mensaje" => "Datos recibidos correctamente"
    ];

    echo json_encode($respuesta);

} else {

    $respuesta = [
        "error" => "El nombre es obligatorio"
    ];

    echo json_encode($respuesta);
}

?>
```

**Posible resultado:**

```json
{
    "nombre": "Carlos",
    "numero": 47,
    "mensaje": "Datos recibidos correctamente"
}
```

Este ejemplo permite entender cómo combinar la recepción de datos, la validación, la generación de números aleatorios y la conversión de información a JSON.
