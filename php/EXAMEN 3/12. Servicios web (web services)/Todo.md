# Funciones y comandos de los 4 ejercicios de Servicios Web

## 1. Comandos comunes de PHP y HTML

Estos comandos aparecen en varios ejercicios.

### `echo`

Muestra información por pantalla o devuelve información desde un servicio web.

```php
echo "Hola";
echo $resultado;
echo json_encode($datos);
```

### `isset()`

Comprueba si una variable está definida y no es `null`.

```php
if (isset($_GET['euros'])) {
    // Existe el parámetro euros
}
```

### `if`, `else if` y `else`

Permiten ejecutar código dependiendo de una condición.

```php
if ($cantidad > 0) {
    echo "Cantidad correcta";
} else {
    echo "Cantidad incorrecta";
}
```

### `$_GET`

Recoge los datos enviados mediante la URL.

Ejemplo de URL:

```text
conversor.php?euros=10
```

Para obtener el valor:

```php
$euros = $_GET['euros'];
```

### `$_POST`

Recoge los datos enviados mediante un formulario con el método POST.

```php
$nombre = $_POST['nombre'];
```

### `$_REQUEST`

Permite recoger parámetros enviados mediante GET o POST.

```php
$nombre = $_REQUEST['nombre'];
```

### `$_SERVER`

Contiene información sobre la petición recibida por el servidor.

En estos ejercicios se utiliza especialmente para conocer el método HTTP:

```php
$metodo = $_SERVER['REQUEST_METHOD'];
```

### `foreach`

Recorre los elementos de una colección.

```php
foreach ($productos as $producto) {
    echo $producto->nombre;
}
```

### `require_once`

Incluye otro archivo PHP una sola vez. Se puede utilizar para incluir la conexión a la base de datos.

```php
require_once 'conexion.php';
```

---

# 2. Ejercicio 1: conversor de euros y pesetas

## Funciones y comandos

### `$_GET`

Se utiliza para recoger la cantidad que llega por la URL.

```php
$euros = $_GET['euros'];
$pesetas = $_GET['pesetas'];
```

### `isset()`

Comprueba si se ha enviado una cantidad en euros o en pesetas.

```php
if (isset($_GET['euros'])) {
    // Convertir euros a pesetas
} else if (isset($_GET['pesetas'])) {
    // Convertir pesetas a euros
}
```

### `if` y `else if`

Permiten distinguir qué conversión hay que realizar.

### Operaciones matemáticas

La equivalencia es:

```text
1 euro = 166,386 pesetas
```

- De euros a pesetas: multiplicar por 166,386.
- De pesetas a euros: dividir entre 166,386.

```php
$resultado = $euros * 166.386;
```

```php
$resultado = $pesetas / 166.386;
```

### `echo`

Muestra el resultado de la conversión.

```php
echo "Resultado: " . $resultado;
```

### Concatenación con `.`

Une textos y variables.

```php
echo "El resultado es " . $resultado;
```

## HTML que se utiliza

### `<form>`

Crea un formulario.

### `action`

Indica a qué archivo se envían los datos.

### `method`

Indica cómo se envían los datos: GET o POST.

### `<input>`

Crea un campo para introducir información.

### `type="submit"`

Crea el botón para enviar el formulario.

---

# 3. Ejercicio 2: baraja española aleatoria

En este ejercicio se genera un número determinado de cartas, sin que se repita ninguna.

## Archivo JSON: `baraja.json`

Se utiliza un archivo JSON para guardar los palos y las figuras de la baraja.

```json
{
    "palos": ["oros", "copas", "espadas", "bastos"],
    "figuras": ["1", "2", "3", "4", "5", "6", "7", "sota", "caballo", "rey"]
}
```

## Funciones y comandos

### `$_POST`

Recoge el número de cartas enviado desde el formulario.

```php
$cantidad = $_POST['cantidad'];
```

### `file_get_contents()`

Lee el contenido de un archivo o realiza una petición a una URL.

En este ejercicio se utiliza para leer el archivo JSON:

```php
$datos = file_get_contents("baraja.json");
```

También se utiliza para que el cliente llame al servicio:

```php
$respuesta = file_get_contents($url, false, $contexto);
```

### `json_decode()`

Convierte el texto JSON en un objeto PHP para poder acceder a sus datos.

```php
$baraja = json_decode($datos);
```

Para acceder a los palos:

```php
$baraja->palos
```

Para acceder a las figuras:

```php
$baraja->figuras
```

### `json_encode()`

Convierte los datos PHP a formato JSON.

```php
echo json_encode($cartas);
```

### `count()`

Cuenta cuántos elementos tiene una colección.

```php
$totalPalos = count($baraja->palos);
```

### `rand()`

Genera un número aleatorio entre dos valores.

```php
$numero = rand(0, 39);
```

Se puede utilizar para elegir una posición aleatoria de la baraja.

### `in_array()`

Comprueba si un valor está dentro de un array.

```php
if (!in_array($numero, $usadas)) {
    // La posición todavía no se ha utilizado
}
```

### `while`

Repite instrucciones mientras se cumpla una condición.

```php
while (count($cartas) < $cantidad) {
    // Generar cartas
}
```

### `stream_context_create()`

Crea un contexto para configurar una petición HTTP.

```php
$opciones = [
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/x-www-form-urlencoded",
        "content" => http_build_query($datos)
    ]
];

$contexto = stream_context_create($opciones);
```

### `http_build_query()`

Convierte un array asociativo en una cadena de parámetros para enviar en una petición.

```php
$datos = ["cantidad" => 5];

$parametros = http_build_query($datos);
```

### `isset()`

Comprueba si se ha enviado el número de cartas.

### `echo`

Devuelve el resultado JSON al cliente.

## HTML que se utiliza

- `<form>`: crea el formulario.
- `action`: indica el archivo que recibe los datos.
- `method="POST"`: envía los datos mediante POST.
- `<input type="number">`: permite introducir el número de cartas.
- `<input type="submit">`: envía el formulario.

---

# 4. Ejercicio 3: servicio web de productos

Este ejercicio consulta productos de una base de datos y devuelve información en JSON.

Se puede buscar por nombre o por rango de precios.

## Funciones y comandos PHP

### `$_POST`

Recoge los datos enviados desde los formularios.

```php
$nombre = $_POST['nombre'];
$min = $_POST['min'];
$max = $_POST['max'];
```

### `isset()`

Comprueba qué tipo de búsqueda ha solicitado el usuario.

```php
if (isset($_REQUEST['filtraNombre'])) {
    // Buscar por nombre
} else if (isset($_REQUEST['filtraPrecio'])) {
    // Buscar por precio
}
```

### `file_get_contents()`

Realiza la petición al servicio web.

```php
$datos = file_get_contents($url);
```

### `json_decode()`

Convierte la respuesta JSON en un objeto PHP.

```php
$respuesta = json_decode($datos);
```

### `json_encode()`

Convierte los resultados de la consulta a JSON para devolverlos al cliente.

```php
echo json_encode($productos);
```

### `stream_context_create()`

Permite configurar la petición HTTP.

```php
$contexto = stream_context_create($opciones);
```

### `http_build_query()`

Convierte los datos en una cadena que se puede enviar en una petición HTTP.

```php
$parametros = http_build_query($datos);
```

### `PDO`

Es una extensión de PHP que permite conectarse a una base de datos.

Ejemplo de conexión:

```php
$conexion = new PDO(
    "mysql:host=localhost;dbname=carrito;charset=utf8",
    "root",
    ""
);
```

### `prepare()`

Prepara una consulta SQL.

```php
$sql = $conexion->prepare(
    "SELECT * FROM productos WHERE nombre LIKE :nombre"
);
```

### `execute()`

Ejecuta la consulta preparada.

```php
$sql->execute([
    ':nombre' => '%' . $nombre . '%'
]);
```

### `fetchAll()`

Obtiene todos los registros devueltos por una consulta.

```php
$productos = $sql->fetchAll(PDO::FETCH_OBJ);
```

### `fetch()`

Obtiene un registro de la consulta.

```php
$producto = $sql->fetch(PDO::FETCH_OBJ);
```

### `count()`

Cuenta el número de resultados.

```php
if (count($productos) == 0) {
    // No se han encontrado productos
}
```

### `LIKE`

Es una condición SQL que permite buscar una cadena dentro de un texto.

```sql
SELECT * FROM productos
WHERE nombre LIKE '%teclado%';
```

### `BETWEEN`

Permite buscar valores dentro de un intervalo.

```sql
SELECT * FROM productos
WHERE precio BETWEEN 10 AND 50;
```

### `header()`

Envía cabeceras HTTP desde el servidor.

```php
header("Content-Type: application/json;charset=utf-8");
```

### TOKEN

El enunciado del ejercicio exige que el cliente tenga un token registrado en la tabla `clientes`.

El servidor debe:

1. Recibir el token enviado por el cliente.
2. Comprobar que existe en la tabla `clientes`.
3. Rechazar la petición si el token no es válido.
4. Sumar uno al campo `peticiones` si el token es válido.

Para consultar el token se puede utilizar una consulta preparada con PDO.

---

# 5. Ejercicio 4: matriculaciones de alumnos

Este ejercicio permite consultar alumnos y asignaturas y realizar operaciones sobre las matrículas.

## Funciones y comandos PHP

### `$_POST`

Recoge los datos enviados desde los formularios.

```php
$grupo = $_POST['grupo'];
$nombre = $_POST['nombre'];
$idAlumno = $_POST['id_alumno'];
```

### `$_REQUEST`

Permite acceder a parámetros de las peticiones GET y POST.

```php
$grupo = $_REQUEST['grupo'];
```

### `$_SERVER['REQUEST_METHOD']`

Permite conocer el método HTTP utilizado en la petición.

```php
$metodo = $_SERVER['REQUEST_METHOD'];
```

Se utiliza para distinguir las operaciones:

- GET: consultar información.
- POST: insertar información.
- PUT: modificar información.
- DELETE: borrar información.

### `if`, `else if`

Permiten ejecutar una operación diferente según el método recibido.

```php
if ($metodo == 'GET') {
    // Consultar
} else if ($metodo == 'POST') {
    // Insertar
} else if ($metodo == 'PUT') {
    // Modificar
} else if ($metodo == 'DELETE') {
    // Borrar
}
```

### `file_get_contents("php://input")`

Lee el contenido recibido en el cuerpo de una petición HTTP.

Se utiliza especialmente con PUT y DELETE.

```php
$datos = file_get_contents("php://input");
```

### `parse_str()`

Convierte una cadena de parámetros en un array asociativo.

```php
parse_str(file_get_contents("php://input"), $parametros);
```

Después se pueden consultar los valores:

```php
$idAlumno = $parametros['id_alumno'];
```

### `stream_context_create()`

Crea el contexto de la petición HTTP para indicar el método y los datos que se envían.

```php
$contexto = stream_context_create($opciones);
```

### `http_build_query()`

Prepara los datos para enviarlos en la petición.

```php
"content" => http_build_query($datos)
```

### `file_get_contents()`

Realiza la petición al servicio.

```php
$respuesta = file_get_contents($url, false, $contexto);
```

### `json_decode()`

Lee los datos JSON que devuelve el servidor.

```php
$respuesta = json_decode($datos);
```

### `json_encode()`

Convierte los datos obtenidos en JSON.

```php
echo json_encode($datos);
```

### `header()`

Devuelve el código de estado y el mensaje de la operación.

```php
header("HTTP/1.1 200 OK");
```

También permite indicar que la respuesta es JSON:

```php
header("Content-Type: application/json;charset=utf-8");
```

### `substr()`

Extrae una parte de una cadena.

Se utiliza para separar el código de estado y el mensaje de la cabecera HTTP.

```php
$codigo = substr($http_response_header[0], 9, 3);

$mensaje = substr($http_response_header[0], 13);
```

### `PDO`

Permite conectarse a la base de datos para consultar y modificar alumnos, asignaturas y matrículas.

### `prepare()` y `execute()`

Preparan y ejecutan las consultas SQL.

```php
$sql = $conexion->prepare(
    "SELECT * FROM alumnos WHERE grupo = :grupo"
);

$sql->execute([
    ':grupo' => $grupo
]);
```

### `fetchAll()`

Obtiene todos los registros encontrados.

```php
$alumnos = $sql->fetchAll(PDO::FETCH_OBJ);
```

### `fetch()`

Obtiene un único registro.

```php
$alumno = $sql->fetch(PDO::FETCH_OBJ);
```

### `INSERT`

Introduce un nuevo registro en la base de datos.

```sql
INSERT INTO matriculas (id_alumno, id_asignatura)
VALUES (1, 2);
```

### `UPDATE`

Modifica un registro existente.

```sql
UPDATE alumnos
SET grupo = '2DAW'
WHERE id = 1;
```

### `DELETE`

Elimina registros de la base de datos.

```sql
DELETE FROM matriculas
WHERE id_alumno = 1;
```

---

# 6. Resumen de los métodos HTTP

| Método | Para qué sirve | Ejercicio |
|---|---|---|
| GET | Consultar datos | 1, 2, 3 y 4 |
| POST | Enviar o insertar datos | 2, 3 y 4 |
| PUT | Modificar datos | 4 |
| DELETE | Eliminar datos | 4 |

# 7. Resumen de JSON

JSON es el formato utilizado para intercambiar datos entre el cliente y el servidor.

| Función | Qué hace |
|---|---|
| `json_encode()` | Convierte datos PHP a JSON |
| `json_decode()` | Convierte JSON a datos PHP |
| `file_get_contents()` | Lee un archivo o realiza una petición |
| `echo` | Devuelve el JSON al cliente |

Ejemplo:

```php
// Convertir datos PHP a JSON
$json = json_encode($datos);

// Convertir JSON a objeto PHP
$objeto = json_decode($json);

// Acceder a un atributo
echo $objeto->nombre;
```

# 8. Resumen de las funciones más importantes para estudiar

| Función o comando | Para qué sirve |
|---|---|
| `echo` | Mostrar o devolver información |
| `isset()` | Comprobar si existe una variable |
| `$_GET` | Recoger datos de la URL |
| `$_POST` | Recoger datos de un formulario POST |
| `$_REQUEST` | Recoger datos GET o POST |
| `$_SERVER` | Consultar información de la petición |
| `if / else` | Tomar decisiones |
| `foreach` | Recorrer una colección |
| `while` | Repetir instrucciones |
| `file_get_contents()` | Leer archivos o hacer peticiones |
| `json_decode()` | Leer JSON |
| `json_encode()` | Generar JSON |
| `rand()` | Generar números aleatorios |
| `count()` | Contar elementos |
| `in_array()` | Comprobar si existe un elemento en un array |
| `stream_context_create()` | Configurar una petición HTTP |
| `http_build_query()` | Preparar parámetros para enviar |
| `parse_str()` | Convertir parámetros en un array |
| `header()` | Enviar cabeceras HTTP |
| `substr()` | Extraer parte de una cadena |
| `PDO` | Conectarse a una base de datos |
| `prepare()` | Preparar una consulta SQL |
| `execute()` | Ejecutar una consulta |
| `fetch()` | Obtener un registro |
| `fetchAll()` | Obtener varios registros |
| `require_once` | Incluir otro archivo PHP |

## 9. Códigos de estado HTTP

| Código | Significado |
|---|---|
| 200 | Correcto |
| 400 | Solicitud incorrecta |
| 401 | No autorizado |
| 403 | Prohibido |
| 404 | No encontrado |
| 409 | Conflicto |
| 500 | Error interno del servidor |
| 503 | Servicio no disponible |

**Consejo para el examen:** aprende especialmente `$_GET`, `$_POST`, `file_get_contents()`, `json_decode()`, `json_encode()`, `stream_context_create()`, `http_build_query()` y `$_SERVER['REQUEST_METHOD']`. Son fundamentales para entender cómo se comunican el cliente y el servidor en estos ejercicios.