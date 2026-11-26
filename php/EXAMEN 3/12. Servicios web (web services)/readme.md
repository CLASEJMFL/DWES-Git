# Tema 12. Servicios web (Web Services)

## 12.1. ¿Qué son los servicios web?

Actualmente, desarrollar una aplicación no implica tener que implementar todas sus características desde cero. Existen servicios gratuitos y de pago que proporcionan datos que podemos reutilizar en nuestras aplicaciones.

Por ejemplo, si queremos mostrar los resultados de partidos de fútbol, podríamos actualizar manualmente una base de datos. Sin embargo, existen empresas que ofrecen servicios con resultados deportivos en tiempo real. Nuestra aplicación puede solicitarles directamente esa información.

Un **servicio web** es un mecanismo mediante el cual dos aplicaciones pueden comunicarse e intercambiar datos.

Normalmente intervienen dos aplicaciones:

- **Proveedor:** aplicación que ofrece los datos o servicios.
- **Consumidor:** aplicación que solicita y utiliza esos datos.

Los dos tipos de servicios web más utilizados son SOAP y REST.

### SOAP

SOAP (*Simple Object Access Protocol*):

- Utiliza exclusivamente XML para transmitir datos.
- Suele consumir más ancho de banda.
- Intercambia objetos SOAP que deben estar definidos previamente.
- Normalmente existe un contrato formal entre cliente y servidor.
- Se utiliza en servicios financieros, pasarelas de pago y telecomunicaciones.

### REST

REST (*Representational State Transfer*):

- Puede trabajar con XML y JSON.
- Las peticiones se pueden realizar mediante una URL.
- Se utiliza mucho en redes sociales y aplicaciones móviles.
- Para consumir un servicio REST necesitamos conocer su URL.
- Permite realizar operaciones sobre los datos mediante peticiones HTTP.

---

## 12.2. Consumo de servicios REST

La forma de consumir un servicio web depende del proveedor. Por eso es recomendable consultar su documentación y revisar sus ejemplos.

La comunicación entre cliente y servidor consiste en enviar una petición HTTP y procesar la respuesta.

Existen varias formas de realizar estas peticiones.

### cURL

La librería **cURL** permite realizar peticiones a servidores remotos.

Es un método de bastante bajo nivel, por lo que también podemos utilizar otras alternativas.

### file_get_contents()

Una alternativa es utilizar la función `file_get_contents()`.

Esta función recibe como parámetro el nombre o la dirección del recurso que queremos leer y devuelve su contenido como una cadena de texto.

Ejemplo:

```php
$resultado = file_get_contents("http://miescuela.com/alumnos");
```

La respuesta puede estar en distintos formatos, principalmente XML o JSON.

- Para XML podemos utilizar la biblioteca `SimpleXML`.
- Para JSON podemos utilizar la función `json_decode()`.

### Ejemplo: OpenWeatherMap

OpenWeatherMap es un servicio web que proporciona información meteorológica de distintas ciudades.

Su página web es:

http://openweathermap.org/

Podemos utilizar su API REST para obtener información meteorológica de una ciudad.

Ejemplo en PHP:

```php
<html>
<head>
    <meta charset="UTF-8">
    <title>El tiempo</title>
</head>
<body>

<h2>El tiempo en Sevilla</h2>

<?php

$datos = file_get_contents(
    "http://api.openweathermap.org/data/2.5/weather?q=Sevilla,Spain&units=metric"
);

echo "<h3>Datos en bruto (JSON):</h3>";
echo $datos;
echo "<hr>";

$tiempo = json_decode($datos);

echo "<h3>Datos en un objeto:</h3>";
print_r($tiempo);
echo "<hr>";

echo "<h3>Datos sueltos:</h3>";

echo "Temperatura: " . $tiempo->main->temp . " ºC<br>";
echo "Humedad: " . $tiempo->main->humidity . "%<br>";
echo "Presión: " . $tiempo->main->pressure . " mb<br>";

?>

</body>
</html>
```

**Funcionamiento del programa:**

1. `file_get_contents()` realiza la petición a la API.
2. La respuesta se almacena en la variable `$datos`.
3. La respuesta recibida está en formato JSON.
4. `json_decode($datos)` transforma los datos JSON en un objeto PHP.
5. Podemos acceder a los datos utilizando los nombres de sus propiedades.

Por ejemplo:

```php
$tiempo->main->temp
```

Accede al atributo `temp`, que está dentro de `main`, dentro del objeto `$tiempo`.

Otros datos que podemos consultar son:

```php
$tiempo->main->humidity
$tiempo->main->pressure
```

---

## 12.3. Estructura de JSON

JSON significa *JavaScript Object Notation* (Notación de Objetos de JavaScript).

Es un formato ligero de intercambio de datos. Es fácil de leer y escribir para las personas y sencillo de interpretar y generar para las máquinas.

Aunque está basado en un subconjunto de JavaScript, es independiente del lenguaje de programación. Por eso se utiliza en muchos lenguajes, como PHP, Java, Python, C y JavaScript.

JSON está formado por dos estructuras principales:

1. Una colección de pares nombre/valor.
2. Una lista ordenada de valores.

### Objetos (object)

Un objeto es un conjunto de pares nombre/valor.

- Comienza con `{`.
- Termina con `}`.
- Cada nombre se separa de su valor mediante `:`.
- Los pares se separan mediante comas.

Ejemplo:

```json
{
    "nombre": "Pepe",
    "edad": 22
}
```

### Arrays (array)

Un array es una colección ordenada de valores.

- Comienza con `[`.
- Termina con `]`.
- Los valores se separan mediante comas.

Ejemplo:

```json
[
    "Pepe",
    "Ana",
    "Juan"
]
```

### Tipos de valores JSON

Un valor JSON puede ser:

- Una cadena de caracteres entre comillas dobles.
- Un número.
- `true`.
- `false`.
- `null`.
- Un objeto.
- Un array.

Estas estructuras pueden anidarse unas dentro de otras.

### Convertir JSON en PHP

Para convertir datos JSON en PHP utilizamos:

```php
json_decode($datos);
```

Por defecto, los objetos JSON se convierten en objetos PHP.

Ejemplo:

```php
$datos = '{"nombre":"Pepe","edad":22}';

$persona = json_decode($datos);

echo $persona->nombre;
echo $persona->edad;
```

También podemos indicar un segundo parámetro a `json_decode()`:

```php
$persona = json_decode($datos, true);
```

Si indicamos `true`, los datos se convierten en arrays asociativos en lugar de objetos.

### Convertir PHP en JSON

Para convertir un array PHP en JSON utilizamos:

```php
json_encode($datos);
```

Ejemplo:

```php
$persona = [
    'nombre' => 'Pepe',
    'edad' => 22
];

$codificado = json_encode($persona);
```

El resultado será:

```json
{
    "nombre": "Pepe",
    "edad": 22
}
```

### Arrays asociativos y objetos anidados

Podemos incluir arrays asociativos dentro de otros arrays asociativos.

Ejemplo:

```php
$persona = [
    'nombre' => 'Pepe',
    'edad' => 22,
    'coche' => [
        'marca' => 'Opel',
        'motor' => 1500
    ]
];

$codificado = json_encode($persona);

$persona = json_decode($codificado);

echo $persona->nombre;
echo $persona->coche->marca;
```

Al convertir el JSON en un objeto, podemos acceder a los atributos anidados mediante `->`.

También podemos crear un array no asociativo que contenga varios arrays asociativos:

```php
$personas[] = [
    'nombre' => 'Pepe',
    'edad' => 22,
    'coche' => [
        'marca' => 'Opel',
        'motor' => 1500
    ]
];

$personas[] = [
    'nombre' => 'Ana',
    'edad' => 19,
    'coche' => [
        'marca' => 'Fiat',
        'motor' => 1800
    ]
];

$personas[] = [
    'nombre' => 'Juana',
    'edad' => 27,
    'coche' => [
        'marca' => 'Mercedes',
        'motor' => 2500
    ]
];

$codificado = json_encode($personas);
$personas = json_decode($codificado);

foreach ($personas as $persona) {
    echo $persona->nombre;
    echo " tiene un coche de marca ";
    echo $persona->coche->marca;
}
```

**Importante:**

- Un array asociativo convertido a JSON se recupera normalmente como un objeto al utilizar `json_decode()` sin el segundo parámetro.
- Un array no asociativo se recupera como un array.
- Podemos combinar arrays asociativos y no asociativos para crear estructuras de datos complejas.

---

## 12.4. Cabeceras HTTP y códigos de estado

Las peticiones y respuestas HTTP pueden incluir información sobre la comunicación.

Para controlar el resultado de una petición nos interesa especialmente el **código de estado HTTP**, que indica si la operación se ha realizado correctamente o si se ha producido algún error.

### Consultar el código de respuesta en PHP

Cuando utilizamos `file_get_contents()`, podemos consultar la primera línea de la respuesta mediante:

```php
$http_response_header[0]
```

Ejemplo de respuesta:

```text
HTTP/1.1 200 OK
```

Podemos comprobar si la petición ha sido correcta:

```php
if ($http_response_header[0] == "HTTP/1.1 200 OK") {
    echo "Correcto";
} else {
    echo "Se ha producido un error";
}
```

### Extraer el código y el mensaje

Podemos utilizar la función `substr()` para extraer partes de la primera línea.

```php
$codEstado = substr($http_response_header[0], 9, 3);

$mensaje = substr($http_response_header[0], 13);
```

- `$codEstado`: almacena el código de estado.
- `$mensaje`: almacena el mensaje asociado al código.

La función `substr()` permite extraer una parte de una cadena indicando la posición inicial y, opcionalmente, el número de caracteres.

### Evitar warnings

Cuando se produce un error en una petición, PHP puede mostrar un warning.

Para evitar que aparezca ese aviso podemos colocar `@` delante de `file_get_contents()`:

```php
$datos = @file_get_contents("http://servicioweb.com");
```

### Códigos de estado HTTP más utilizados

| Código | Significado |
|---|---|
| 200 | Correcto. La petición se ha realizado correctamente. |
| 400 | Solicitud incorrecta. El cliente ha enviado una petición no válida. |
| 401 | No autorizado. No se ha podido autenticar al cliente. |
| 403 | Prohibido. El cliente no tiene permiso para acceder al recurso. |
| 404 | No encontrado. El recurso solicitado no existe. |
| 409 | Conflicto. La operación genera un conflicto, como insertar un elemento duplicado. |
| 500 | Error interno del servidor. Se ha producido un error en el servidor. |
| 503 | Servicio no disponible. El servicio no está disponible. |

### Las cinco categorías de códigos HTTP

- **1xx:** el servidor reconoce una solicitud.
- **2xx:** la solicitud se ha completado correctamente.
- **3xx:** redirección; el cliente debe realizar más acciones.
- **4xx:** error del cliente; la solicitud no es válida.
- **5xx:** error del servidor; no se ha podido completar una solicitud válida.

### Comprobar el resultado de una petición

```php
$datos = @file_get_contents($url);

$codEstado = substr($http_response_header[0], 9, 3);
$mensaje = substr($http_response_header[0], 13);

if ($codEstado == 200) {
    echo "Petición correcta";
} else {
    echo "Error: " . $codEstado;
    echo "<br>" . $mensaje;
}
```

### Devolver un código de estado desde el servidor

En el servidor podemos indicar el código y el mensaje que queremos devolver mediante `header()`:

```php
header("HTTP/1.1 $codEstado $mensaje");
```

De esta forma, el cliente podrá consultar el resultado de la operación.

---

## 12.5. Ejercicios para resolver

### Ejercicio 1

Utilizando la API de OpenWeatherMap, realizar una aplicación que muestre la información meteorológica de una ciudad.

Se deben investigar los distintos parámetros que puede proporcionar la API.

Para seleccionar la ciudad se puede utilizar:

- Una imagen de un mapa.
- Una o varias listas desplegables.
- Un campo de texto.
- Una combinación de los métodos anteriores.

La aplicación debe permitir seleccionar la ciudad y la información meteorológica que queremos consultar.

### Ejercicio 2

Realizar una aplicación que utilice cualquier API REST gratuita que proporcione información.

La aplicación debe:

1. Realizar una petición al servicio.
2. Procesar la información recibida.
3. Mostrar los datos correctamente formateados.

Como alternativa, se puede utilizar la página `football-data.org`, que ofrece información sobre fútbol, ligas, resultados, equipos y jugadores.

---

## 12.6. Creación de servicios web

Un programador debe saber tanto consumir servicios web como crear sus propios servicios para que otras aplicaciones puedan utilizarlos.

Vamos a crear un servicio web utilizando un archivo CSV con información de empleados.

### Archivo CSV

Un archivo CSV almacena datos separados por comas.

Ejemplo:

```csv
"Noel, Julie G.",Proin.mi@dolor.co.uk,659-285422,2805 Ac Avenue,Santo Domingo,Greece,1328
"Cooper, Abraham P.",diam.at@Inmi.com,639-392747,4050 Sit Street,Houtave,Thailand,668
"Curry, Tana A.",at@necleoMorbi.net,689-337896,"689-888 Quis, Rd.",Dampremy,Latvia,2165
"Mathews, Victoria",lorem.sit.amet@amet.com,614-190267,37 Vitae St.,Codognè,Tuvalu,3898
```

Cada línea contiene los datos de un empleado:

1. Nombre completo.
2. Correo electrónico.
3. Teléfono.
4. Dirección postal.
5. Ciudad.
6. País.
7. Sueldo neto mensual en euros.

### Crear el servicio listadoEmpleados.php

El servicio devolverá un JSON con los empleados cuyos nombres contengan una cadena que se pasa mediante la URL.

Por ejemplo:

```text
listadoEmpleados.php?nombre=me
```

Devolverá los empleados cuyos nombres contengan la cadena `me`, sin distinguir entre mayúsculas y minúsculas.

Si no se pasa ningún nombre, se devolverán todos los empleados.

Código:

```php
<?php

// Abrir el fichero en modo lectura
$f = fopen("datos_empleados.csv", "r");

while (($datosEmpleado = fgetcsv($f, 1000, ","))) {

    // Comprobar si el nombre contiene la cadena buscada
    // o si no se ha indicado ningún nombre.

    if (
        !isset($_GET['nombre']) ||
        mb_stripos($datosEmpleado[0], $_GET['nombre']) !== false
    ) {

        // Añadir los datos del empleado al array
        $empleados[] = [
            'nombre' => $datosEmpleado[0],
            'email' => $datosEmpleado[1],
            'telefono' => $datosEmpleado[2],
            'direccion' => $datosEmpleado[3],
            'ciudad' => $datosEmpleado[4],
            'pais' => $datosEmpleado[5],
            'sueldo' => $datosEmpleado[6]
        ];
    }
}

fclose($f);

// Convertir el resultado a JSON
echo json_encode($empleados);

?>
```

### Funciones utilizadas

**`fopen()`**

Abre un fichero.

```php
$f = fopen("datos_empleados.csv", "r");
```

El modo `"r"` indica que se abre para lectura.

**`fgetcsv()`**

Lee una línea de un archivo CSV y separa sus datos en un array.

```php
$datosEmpleado = fgetcsv($f, 1000, ",");
```

**`mb_stripos()`**

Busca una cadena dentro de otra sin distinguir entre mayúsculas y minúsculas.

```php
mb_stripos($datosEmpleado[0], $_GET['nombre'])
```

**`fclose()`**

Cierra el fichero.

```php
fclose($f);
```

**`json_encode()`**

Convierte el array PHP en una cadena JSON.

```php
echo json_encode($empleados);
```

### Ejemplo de resultado JSON

El servicio devuelve los empleados que cumplen el filtro en formato JSON.

Ejemplo de estructura:

```json
[
    {
        "nombre": "Pepe",
        "email": "pepe@email.com",
        "telefono": "123456789",
        "direccion": "Calle Mayor",
        "ciudad": "Sevilla",
        "pais": "España",
        "sueldo": "1500"
    }
]
```

### Devolver arrays y objetos con JSON

Cuando utilizamos `json_encode()` sobre un array asociativo, al recuperarlo mediante `json_decode()` obtenemos normalmente un objeto.

Ejemplo:

```php
$persona = [
    'nombre' => 'Pepe',
    'edad' => 22
];

$codificado = json_encode($persona);

$persona = json_decode($codificado);

echo $persona->nombre . ", " . $persona->edad;
```

Si el array asociativo contiene otros arrays asociativos, se genera una estructura de objetos anidados.

### Enviar la respuesta al cliente

Una vez generados los datos y convertidos a JSON, el servicio utiliza `echo` para devolver el resultado.

No se trata de mostrar una página web normal, sino de enviar los datos al cliente que ha realizado la petición.

### Importante: localhost

Cuando el cliente y el servidor están en la misma máquina, debemos indicar en la URL la dirección `localhost` seguida de la ruta del servicio PHP.

Ejemplo:

```php
$url = "http://localhost/ejerciciosPHP/tema13/ejer1Servidor.php";
```

---

### API REST completa: GET, POST, PUT y DELETE

Muchos servicios web se utilizan para proporcionar información mediante el método GET.

Sin embargo, también podemos necesitar modificar los datos almacenados en el servidor.

Para ello existen distintos métodos HTTP:

| Método | Función |
|---|---|
| GET | Consultar información. |
| POST | Añadir información. |
| PUT | Modificar o actualizar información. |
| DELETE | Eliminar información. |

Los formularios HTML permiten enviar directamente peticiones GET y POST.

Para utilizar PUT y DELETE podemos recurrir a tecnologías como AJAX, programas como Postman o peticiones PHP.

### Ejemplo: servicio de productos

Vamos a crear un servicio que permita:

- Consultar productos por nombre.
- Consultar productos por precio.
- Insertar productos.
- Borrar productos.
- Añadir stock a un producto.

### Archivo index.php

Este archivo muestra los formularios para realizar las distintas peticiones.

```html
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>API productos</title>
</head>

<body>

<h1>Consulta de la API productos</h1>

<form action="peticion.php" method="post">
    <h2>Filtro por nombre</h2>
    Nombre:
    <input type="text" name="nombre">
    <input type="submit" name="filtraNombre" value="Filtrar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Filtro por precio</h2>
    Mínimo:
    <input type="number" name="min" required>

    Máximo:
    <input type="number" name="max" required>

    <input type="submit" name="filtraPrecio" value="Filtrar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Insertar nuevo producto</h2>

    Nombre:
    <input type="text" name="nombre" required>

    Precio:
    <input type="number" name="precio" required>

    Stock:
    <input type="number" name="stock" required>

    <input type="submit" name="insertar" value="Insertar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Borrar producto</h2>

    Nombre:
    <input type="text" name="nombre" required>

    <input type="submit" name="borrar" value="Borrar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Añadir stock</h2>

    Nombre:
    <input type="text" name="nombre" required>

    Añadir:
    <input type="number" name="cantidad" required>

    Unidades

    <input type="submit" name="stock" value="Añadir">
</form>

</body>
</html>
```

### Archivo peticion.php

Este archivo recibe los datos de los formularios y realiza las peticiones al servicio.

```php
<?php

$url = "http://localhost/RESTFULL/consultaProductos.php";

if (isset($_REQUEST['filtraPrecio'])) {

    $parametros = '?min=' . $_REQUEST['min'] .
                  '&max=' . $_REQUEST['max'];

    mostrarDatos($url, $parametros);

} else if (isset($_REQUEST['filtraNombre'])) {

    $parametros = "?nombre=" . $_REQUEST['nombre'];

    mostrarDatos($url, $parametros);

} else if (isset($_REQUEST['insertar'])) {

    $datos = [
        "nombre" => $_REQUEST['nombre'],
        "precio" => $_REQUEST['precio'],
        "stock" => $_REQUEST['stock']
    ];

    pideServicio($url, $datos, "POST");

} else if (isset($_REQUEST['borrar'])) {

    $datos = [
        "nombre" => $_REQUEST['nombre']
    ];

    pideServicio($url, $datos, "DELETE");

} else if (isset($_REQUEST['stock'])) {

    $datos = [
        "nombre" => $_REQUEST['nombre'],
        "cantidad" => $_REQUEST['cantidad']
    ];

    pideServicio($url, $datos, "PUT");
}


// Mostrar el código y el mensaje de estado
function mostrarEstado($codEstado, $mensaje) {

    echo "STATUS: " . $codEstado;
    echo "<br>" . $mensaje;

}


// Consultar y mostrar productos
function mostrarDatos($url, $parametros) {

    $data = @file_get_contents($url . $parametros);

    $respuesta = json_decode($data);

    $codEstado = substr($http_response_header[0], 9, 3);
    $mensaje = substr($http_response_header[0], 13);

    if ($codEstado == 200) {

        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>Nombre</th>";
        echo "<th>Precio</th>";
        echo "<th>Stock</th>";
        echo "</tr>";

        foreach ($respuesta as $producto) {

            echo "<tr>";
            echo "<td>" . $producto->nombre . "</td>";
            echo "<td>" . $producto->precio . "</td>";
            echo "<td>" . $producto->stock . "</td>";
            echo "</tr>";
        }

        echo "</table>";

    } else {

        mostrarEstado($codEstado, $mensaje);

    }

    echo "<a href='index.php'>";
    echo "<h3>VOLVER A LA PÁGINA DE CONSULTAS</h3>";
    echo "</a>";

}


// Realizar peticiones POST, PUT y DELETE
function pideServicio($url, $datos, $metodo) {

    $opciones = [
        "http" => [
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => $metodo,
            "content" => http_build_query($datos)
        ],
    ];

    $contexto = stream_context_create($opciones);

    @file_get_contents($url, false, $contexto);

    $codEstado = substr($http_response_header[0], 9, 3);
    $mensaje = substr($http_response_header[0], 13);

    mostrarEstado($codEstado, $mensaje);

}

?>
```

### Explicación de peticion.php

**1. Consultar productos por precio**

```php
$parametros = '?min=' . $_REQUEST['min'] .
              '&max=' . $_REQUEST['max'];

mostrarDatos($url, $parametros);
```

Se construye la URL con los parámetros mínimo y máximo y se llama a `mostrarDatos()`.

**2. Consultar productos por nombre**

```php
$parametros = "?nombre=" . $_REQUEST['nombre'];

mostrarDatos($url, $parametros);
```

Se construye la URL con el nombre que queremos buscar.

**3. Insertar productos**

```php
pideServicio($url, $datos, "POST");
```

Se envían los datos del producto mediante POST.

**4. Borrar productos**

```php
pideServicio($url, $datos, "DELETE");
```

Se envía el nombre del producto que queremos borrar mediante DELETE.

**5. Añadir stock**

```php
pideServicio($url, $datos, "PUT");
```

Se envía el nombre del producto y la cantidad que queremos añadir mediante PUT.

### Funciones importantes

**`$_REQUEST`**

Permite acceder a los datos recibidos mediante GET, POST y COOKIE.

**`http_build_query()`**

Convierte un array asociativo en una cadena de parámetros con formato URL.

Ejemplo:

```php
$datos = [
    "nombre" => "Teclado",
    "precio" => 25,
    "stock" => 10
];

$parametros = http_build_query($datos);
```

Resultado:

```text
nombre=Teclado&precio=25&stock=10
```

**`stream_context_create()`**

Crea un contexto para configurar una petición HTTP.

Permite indicar el método, las cabeceras y el contenido que se enviará.

Ejemplo:

```php
$opciones = [
    "http" => [
        "method" => "POST",
        "content" => http_build_query($datos)
    ]
];

$contexto = stream_context_create($opciones);
```

**`php://input`**

Permite leer el contenido enviado en el cuerpo de una petición HTTP.

Para las peticiones PUT y DELETE del ejemplo se utiliza junto con `parse_str()`.

### Archivo consultaProductos.php

Este es el servicio que recibe las peticiones del cliente y realiza las operaciones sobre los productos.

```php
<?php

require_once 'Producto.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$codEstado = 200;
$mensaje = "OK";

if ($metodo == 'GET') {

    if (isset($_REQUEST['min']) && isset($_REQUEST['max'])) {

        $productos = Producto::getProductosFiltroPrecio(
            $_REQUEST['min'],
            $_REQUEST['max']
        );

    } else if (isset($_REQUEST['nombre'])) {

        $productos = Producto::getProductosFiltroNombre(
            $_REQUEST['nombre']
        );

    }

    if (count($productos) == 0) {

        $mensaje = "PRODUCTO NO ENCONTRADO";
        $codEstado = 404;

    } else {

        foreach ($productos as $producto) {

            $devolver[] = [
                'nombre' => $producto->getNombre(),
                'precio' => $producto->getPrecio(),
                'stock' => $producto->getStock()
            ];

        }

    }

} else if ($metodo == 'POST') {

    $producto = Producto::getProductoByNombre($_REQUEST['nombre']);

    if ($producto) {

        $mensaje = "CONFLICTO, PRODUCTO CON MISMO NOMBRE";
        $codEstado = 409;

    } else {

        $producto = new Producto(
            null,
            $_REQUEST['nombre'],
            $_REQUEST['precio'],
            null,
            $_REQUEST['stock']
        );

        $producto->insert();

        $mensaje = "PRODUCTO INSERTADO CORRECTAMENTE";
        $codEstado = 200;

    }

} else if ($metodo == 'DELETE') {

    // Leer los datos recibidos
    parse_str(file_get_contents("php://input"), $parametros);

    $producto = Producto::getProductoByNombre($parametros['nombre']);

    if ($producto) {

        $producto->delete();

        $mensaje = "PRODUCTO BORRADO CORRECTAMENTE";
        $codEstado = 200;

    } else {

        $mensaje = "PRODUCTO NO ENCONTRADO";
        $codEstado = 404;

    }

} else if ($metodo == 'PUT') {

    // Leer los datos recibidos
    parse_str(file_get_contents("php://input"), $parametros);

    $producto = Producto::getProductoByNombre($parametros['nombre']);

    if ($producto) {

        $producto->añade($parametros['cantidad']);

        $mensaje = "STOCK AÑADIDO CORRECTAMENTE";
        $codEstado = 200;

    } else {

        $mensaje = "PRODUCTO NO ENCONTRADO";
        $codEstado = 404;

    }

}


// Enviar el código y el mensaje de respuesta
setCabecera($codEstado, $mensaje);

echo json_encode($devolver);


function setCabecera($codEstado, $mensaje) {

    header("HTTP/1.1 $codEstado $mensaje");

    header("Content-Type: application/json;charset=utf-8");

}

?>
```

### Explicación de consultaProductos.php

**1. Obtener el método de la petición**

```php
$metodo = $_SERVER['REQUEST_METHOD'];
```

Permite conocer si la petición recibida utiliza GET, POST, PUT o DELETE.

**2. Método GET**

Se utiliza para consultar productos.

Puede filtrar por precio:

```php
$productos = Producto::getProductosFiltroPrecio(
    $_REQUEST['min'],
    $_REQUEST['max']
);
```

O por nombre:

```php
$productos = Producto::getProductosFiltroNombre(
    $_REQUEST['nombre']
);
```

Si no encuentra productos, devuelve el código 404.

**3. Método POST**

Se utiliza para insertar un producto.

Primero se comprueba si ya existe un producto con ese nombre:

```php
$producto = Producto::getProductoByNombre($_REQUEST['nombre']);
```

Si existe, se devuelve el código 409.

Si no existe, se crea el producto y se inserta:

```php
$producto = new Producto(
    null,
    $_REQUEST['nombre'],
    $_REQUEST['precio'],
    null,
    $_REQUEST['stock']
);

$producto->insert();
```

**4. Método DELETE**

Se utiliza para borrar un producto.

Los datos se leen mediante:

```php
parse_str(file_get_contents("php://input"), $parametros);
```

Después se busca el producto y se elimina:

```php
$producto->delete();
```

Si no existe, se devuelve el código 404.

**5. Método PUT**

Se utiliza para modificar un producto. En este ejemplo sirve para añadir stock.

```php
$producto->añade($parametros['cantidad']);
```

Si el producto no existe, se devuelve el código 404.

**6. Enviar la respuesta**

Se utiliza la función `setCabecera()` para devolver el código de estado y el mensaje correspondiente.

```php
header("HTTP/1.1 $codEstado $mensaje");
```

También se indica que la respuesta está en formato JSON:

```php
header("Content-Type: application/json;charset=utf-8");
```

Finalmente, se devuelve el resultado mediante:

```php
echo json_encode($devolver);
```

### Estructura de los datos devueltos

El servicio puede devolver un array con varios productos.

Ejemplo:

```php
$respuesta = [
    [
        'nombre' => 'teclado',
        'precio' => 25,
        'stock' => 10
    ],
    [
        'nombre' => 'raton',
        'precio' => 15,
        'stock' => 15
    ],
    [
        'nombre' => 'monitor',
        'precio' => 85,
        'stock' => 5
    ]
];
```

Al utilizar `json_encode()`, el array se transforma en JSON para enviarlo al cliente.

### Uso de Postman

**Postman** es un programa gratuito que permite realizar peticiones a servicios web y visualizar las respuestas.

Permite configurar gráficamente los parámetros de la URL, el método HTTP y el contenido de las peticiones.

Para utilizarlo:

- **GET y POST:** los parámetros se pueden indicar en la pestaña `Params`. El servidor puede acceder a ellos mediante `$_REQUEST`.
- **PUT y DELETE:** los datos se pueden enviar desde la pestaña `Body`, seleccionando `x-www-form-urlencoded`. En el servidor se pueden recuperar leyendo `php://input` y utilizando `parse_str()`.

---

## 12.7. Ejercicios para resolver

### Ejercicio 1. Conversor de euros y pesetas

Crear un servicio web que permita convertir euros a pesetas y pesetas a euros.

Si se pasa una cantidad en euros mediante la URL, el programa debe convertirla a pesetas y viceversa.

### Ejercicio 2. Baraja española

Crear un servicio web que proporcione aleatoriamente un conjunto de cartas de la baraja española.

Mediante la URL se indicará el número de cartas que se quieren obtener.

La aplicación debe proporcionar el palo y la figura de cada carta.

Condiciones:

- Las cartas no se pueden repetir.
- El número de cartas debe estar entre 1 y 40.
- Si el número es menor que 1 o mayor que 40, se debe devolver un error.

### Ejercicio 3. Servicio web de productos

Crear un servicio web que proporcione información sobre los productos de una base de datos del carrito de la compra.

Se devolverá en formato JSON:

- Nombre del producto.
- Precio.
- URL de la imagen.

La petición podrá realizarse de dos formas:

**Por nombre:**

El servicio devolverá los productos cuyo nombre contenga la cadena recibida como parámetro.

**Por precio:**

El servicio devolverá los productos cuyo precio esté dentro del rango mínimo y máximo recibido como parámetros.

### Ejercicio 4. Servicio web de matriculaciones

Crear un servicio web que proporcione información sobre las matriculaciones de los alumnos en asignaturas del ejercicio 4 de la unidad anterior.

Se podrán realizar las siguientes consultas:

- Obtener los alumnos de un grupo indicado mediante un parámetro.
- Obtener los alumnos cuyo nombre contenga una cadena indicada mediante un parámetro.
- Obtener las asignaturas de un alumno indicado mediante un parámetro.

El servicio también permitirá realizar las siguientes acciones:

- Matricular a un alumno en una asignatura.
- Cambiar de grupo a un alumno.
- Borrar un alumno.

El servicio debe devolver el código de estado de la petición, el mensaje asociado y los datos correspondientes.

Por último, se deben probar y guardar todas las peticiones posibles en Postman.

Como segunda parte del ejercicio, se debe realizar una página que actúe como cliente y realice peticiones al servidor mediante formularios.