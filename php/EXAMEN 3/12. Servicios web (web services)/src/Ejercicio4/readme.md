# Ejercicio 4. Servicio web de matriculaciones con JSON

## 1. Estructura de archivos

Creamos una carpeta llamada `Ejercicio4` con los siguientes archivos:

```text
Ejercicio4/
│
├── index.php
├── peticion.php
├── matriculas.php
├── alumnos.json
├── asignaturas.json
└── matriculas.json
```

No necesitamos `conexion.php`, porque no utilizaremos MySQL ni PDO. Los archivos JSON almacenarán los datos.

## 2. Archivo `alumnos.json`

Contiene los alumnos registrados.

```json
[
    {
        "id": 1,
        "nombre": "Ana",
        "grupo": "DAW1"
    },
    {
        "id": 2,
        "nombre": "Pedro",
        "grupo": "DAW2"
    }
]
```

## 3. Archivo `asignaturas.json`

Contiene las asignaturas disponibles.

```json
[
    {
        "id": 1,
        "nombre": "Programación"
    },
    {
        "id": 2,
        "nombre": "Bases de datos"
    }
]
```

## 4. Archivo `matriculas.json`

Relaciona los alumnos con las asignaturas.

```json
[
    {
        "id_alumno": 1,
        "id_asignatura": 1
    },
    {
        "id_alumno": 1,
        "id_asignatura": 2
    },
    {
        "id_alumno": 2,
        "id_asignatura": 1
    }
]
```

## 5. Archivo `index.php`

Contiene los formularios para realizar las consultas y operaciones.

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Servicio de matriculaciones</title>
</head>
<body>

<h1>Servicio web de matriculaciones</h1>

<form action="peticion.php" method="post">
    <h2>Consultar alumnos de un grupo</h2>

    Grupo:
    <input type="text" name="grupo" required>

    <input type="submit" name="consultaGrupo" value="Consultar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Buscar alumnos por nombre</h2>

    Nombre:
    <input type="text" name="nombre" required>

    <input type="submit" name="consultaNombre" value="Buscar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Consultar asignaturas de un alumno</h2>

    ID del alumno:
    <input type="number" name="id_alumno" required>

    <input type="submit" name="consultaAsignaturas" value="Consultar">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Matricular alumno en asignatura</h2>

    ID del alumno:
    <input type="number" name="id_alumno" required><br>

    ID de la asignatura:
    <input type="number" name="id_asignatura" required><br>

    <input type="submit" name="matricular" value="Matricular">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Cambiar grupo de un alumno</h2>

    ID del alumno:
    <input type="number" name="id_alumno" required><br>

    Nuevo grupo:
    <input type="text" name="grupo" required><br>

    <input type="submit" name="cambiarGrupo" value="Cambiar grupo">
</form>

<hr>

<form action="peticion.php" method="post">
    <h2>Borrar alumno</h2>

    ID del alumno:
    <input type="number" name="id_alumno" required>

    <input type="submit" name="borrarAlumno" value="Borrar">
</form>

</body>
</html>
```

## 6. Archivo `peticion.php`

Este archivo actúa como cliente. Recoge los datos de los formularios y realiza la petición al servicio web utilizando GET, POST, PUT o DELETE.

```php
<?php

$url = "http://localhost:8080/Ejercicio4/matriculas.php";

$metodo = "";
$datos = [];
$parametros = "";

// Consultas GET
if (isset($_REQUEST['consultaGrupo'])) {

    $metodo = "GET";
    $parametros = "?grupo=" . urlencode($_REQUEST['grupo']);

} else if (isset($_REQUEST['consultaNombre'])) {

    $metodo = "GET";
    $parametros = "?nombre=" . urlencode($_REQUEST['nombre']);

} else if (isset($_REQUEST['consultaAsignaturas'])) {

    $metodo = "GET";
    $parametros = "?id_alumno=" . $_REQUEST['id_alumno'];

// Matricular: POST
} else if (isset($_REQUEST['matricular'])) {

    $metodo = "POST";

    $datos = [
        "id_alumno" => $_REQUEST['id_alumno'],
        "id_asignatura" => $_REQUEST['id_asignatura']
    ];

// Cambiar grupo: PUT
} else if (isset($_REQUEST['cambiarGrupo'])) {

    $metodo = "PUT";

    $datos = [
        "id_alumno" => $_REQUEST['id_alumno'],
        "grupo" => $_REQUEST['grupo']
    ];

// Borrar alumno: DELETE
} else if (isset($_REQUEST['borrarAlumno'])) {

    $metodo = "DELETE";

    $datos = [
        "id_alumno" => $_REQUEST['id_alumno']
    ];

} else {

    exit("Petición no válida");

}

// Creamos el contexto de la petición
$opciones = [
    "http" => [
        "header" => "Content-type: application/x-www-form-urlencoded\r\n",
        "method" => $metodo,
        "content" => http_build_query($datos)
    ]
];

$contexto = stream_context_create($opciones);

// Realizamos la petición
$respuesta = @file_get_contents(
    $url . $parametros,
    false,
    $contexto
);

if ($respuesta !== false) {

    $resultado = json_decode($respuesta);

    $codigo = substr($http_response_header[0], 9, 3);
    $mensaje = substr($http_response_header[0], 13);

    echo "STATUS: " . $codigo . "<br>";
    echo "MENSAJE: " . $mensaje . "<br>";

    if ($codigo == 200) {

        echo "<h2>Resultado</h2>";

        foreach ($resultado as $dato) {

            echo "<p>";

            foreach ($dato as $campo => $valor) {

                echo $campo . ": " . $valor . "<br>";

            }

            echo "</p><hr>";

        }

    }

} else {

    echo "No se pudo conectar con el servicio";

}

echo "<a href='index.php'>Volver</a>";

?>
```

**Importante:** la dirección utilizada es `http://localhost:8080/Ejercicio4/matriculas.php`, siguiendo el puerto del entorno utilizado en el ejercicio anterior. Si cambia el puerto o la dirección del servidor, habrá que modificar esta URL.

## 7. Archivo `matriculas.php`

Este es el servicio web. Recibe las peticiones HTTP, lee los archivos JSON, realiza las operaciones y devuelve los resultados en formato JSON.

```php
<?php

$metodo = $_SERVER['REQUEST_METHOD'];

$codigo = 200;
$mensaje = "OK";
$devolver = [];

// Leemos los archivos JSON
$alumnos = json_decode(file_get_contents("alumnos.json"), true);
$asignaturas = json_decode(file_get_contents("asignaturas.json"), true);
$matriculas = json_decode(file_get_contents("matriculas.json"), true);

// GET: consultas
if ($metodo == "GET") {

    // Alumnos de un grupo
    if (isset($_GET['grupo'])) {

        foreach ($alumnos as $alumno) {

            if ($alumno['grupo'] == $_GET['grupo']) {

                $devolver[] = $alumno;

            }

        }

    // Alumnos cuyo nombre contiene una cadena
    } else if (isset($_GET['nombre'])) {

        foreach ($alumnos as $alumno) {

            if (stripos($alumno['nombre'], $_GET['nombre']) !== false) {

                $devolver[] = $alumno;

            }

        }

    // Asignaturas de un alumno
    } else if (isset($_GET['id_alumno'])) {

        foreach ($matriculas as $matricula) {

            if ($matricula['id_alumno'] == $_GET['id_alumno']) {

                foreach ($asignaturas as $asignatura) {

                    if ($asignatura['id'] == $matricula['id_asignatura']) {

                        $devolver[] = $asignatura;

                    }

                }

            }

        }

    } else {

        $codigo = 400;
        $mensaje = "FALTAN PARAMETROS";

    }

    if ($codigo == 200 && count($devolver) == 0) {

        $codigo = 400;
        $mensaje = "NO SE HAN ENCONTRADO DATOS";

    }

// POST: matricular un alumno
} else if ($metodo == "POST") {

    $id_alumno = $_POST['id_alumno'] ?? null;
    $id_asignatura = $_POST['id_asignatura'] ?? null;

    if ($id_alumno && $id_asignatura) {

        $alumnoExiste = false;
        $asignaturaExiste = false;
        $matriculaExiste = false;

        // Comprobamos que exista el alumno
        foreach ($alumnos as $alumno) {

            if ($alumno['id'] == $id_alumno) {

                $alumnoExiste = true;

            }

        }

        // Comprobamos que exista la asignatura
        foreach ($asignaturas as $asignatura) {

            if ($asignatura['id'] == $id_asignatura) {

                $asignaturaExiste = true;

            }

        }

        // Comprobamos si ya está matriculado
        foreach ($matriculas as $matricula) {

            if (
                $matricula['id_alumno'] == $id_alumno &&
                $matricula['id_asignatura'] == $id_asignatura
            ) {

                $matriculaExiste = true;

            }

        }

        if (!$alumnoExiste || !$asignaturaExiste) {

            $codigo = 400;
            $mensaje = "ALUMNO O ASIGNATURA NO ENCONTRADOS";

        } else if ($matriculaExiste) {

            $codigo = 400;
            $mensaje = "EL ALUMNO YA ESTA MATRICULADO";

        } else {

            $matriculas[] = [
                "id_alumno" => (int)$id_alumno,
                "id_asignatura" => (int)$id_asignatura
            ];

            file_put_contents(
                "matriculas.json",
                json_encode($matriculas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $mensaje = "MATRICULA REALIZADA CORRECTAMENTE";

        }

    } else {

        $codigo = 400;
        $mensaje = "FALTAN PARAMETROS";

    }

// PUT: cambiar grupo
} else if ($metodo == "PUT") {

    parse_str(file_get_contents("php://input"), $parametros);

    $id_alumno = $parametros['id_alumno'] ?? null;
    $grupo = $parametros['grupo'] ?? null;

    if ($id_alumno && $grupo) {

        $encontrado = false;

        foreach ($alumnos as &$alumno) {

            if ($alumno['id'] == $id_alumno) {

                $alumno['grupo'] = $grupo;
                $encontrado = true;

            }

        }

        unset($alumno);

        if ($encontrado) {

            file_put_contents(
                "alumnos.json",
                json_encode($alumnos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $mensaje = "GRUPO CAMBIADO CORRECTAMENTE";

        } else {

            $codigo = 400;
            $mensaje = "ALUMNO NO ENCONTRADO";

        }

    } else {

        $codigo = 400;
        $mensaje = "FALTAN PARAMETROS";

    }

// DELETE: borrar alumno
} else if ($metodo == "DELETE") {

    parse_str(file_get_contents("php://input"), $parametros);

    $id_alumno = $parametros['id_alumno'] ?? null;

    if ($id_alumno) {

        $encontrado = false;

        // Buscamos el alumno
        foreach ($alumnos as $clave => $alumno) {

            if ($alumno['id'] == $id_alumno) {

                unset($alumnos[$clave]);
                $encontrado = true;

            }

        }

        if ($encontrado) {

            // Eliminamos las matrículas del alumno
            foreach ($matriculas as $clave => $matricula) {

                if ($matricula['id_alumno'] == $id_alumno) {

                    unset($matriculas[$clave]);

                }

            }

            // Reordenamos los índices
            $alumnos = array_values($alumnos);
            $matriculas = array_values($matriculas);

            // Guardamos los cambios
            file_put_contents(
                "alumnos.json",
                json_encode($alumnos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            file_put_contents(
                "matriculas.json",
                json_encode($matriculas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $mensaje = "ALUMNO BORRADO CORRECTAMENTE";

        } else {

            $codigo = 400;
            $mensaje = "ALUMNO NO ENCONTRADO";

        }

    } else {

        $codigo = 400;
        $mensaje = "FALTA EL ID DEL ALUMNO";

    }

} else {

    $codigo = 400;
    $mensaje = "METODO NO VALIDO";

}

// Devolvemos el código y el mensaje HTTP
header("HTTP/1.1 $codigo $mensaje");
header("Content-Type: application/json;charset=utf-8");

// Devolvemos los datos en JSON
echo json_encode($devolver, JSON_UNESCAPED_UNICODE);

?>
```

## 8. ¿Cómo funciona?

### Consultas GET

- El cliente envía el grupo, nombre o identificador del alumno por URL.
- El servicio recoge los parámetros con `$_GET`.
- Lee los archivos JSON con `file_get_contents()`.
- Convierte el JSON en arrays PHP mediante `json_decode()`.
- Busca los datos utilizando `foreach`.
- Devuelve los resultados con `json_encode()`.

### Matricular: POST

- El cliente envía el identificador del alumno y de la asignatura.
- El servicio recibe los datos mediante `$_POST`.
- Comprueba que existan el alumno y la asignatura.
- Comprueba que no exista ya esa matrícula.
- Añade la nueva matrícula al array.
- Guarda los cambios en `matriculas.json` con `file_put_contents()`.

### Cambiar grupo: PUT

- El cliente envía el identificador del alumno y el nuevo grupo.
- El servicio lee los datos con `file_get_contents("php://input")`.
- Utiliza `parse_str()` para obtener los parámetros.
- Modifica el grupo del alumno.
- Guarda los cambios en `alumnos.json`.

### Borrar alumno: DELETE

- El cliente envía el identificador del alumno.
- El servicio lee los parámetros con `parse_str()`.
- Busca y elimina al alumno.
- Elimina también sus matrículas.
- Guarda los cambios en los archivos JSON.

## 9. Conceptos del tema utilizados

- Formularios HTML.
- `$_REQUEST`, `$_GET` y `$_POST`.
- `$_SERVER['REQUEST_METHOD']`.
- Métodos GET, POST, PUT y DELETE.
- `file_get_contents()`.
- `file_put_contents()`.
- `parse_str()`.
- `stream_context_create()`.
- `http_build_query()`.
- `json_encode()` y `json_decode()`.
- `foreach`.
- `stripos()`.
- `array_values()`.
- Cabeceras HTTP y códigos de estado.

## 10. Cómo probar el ejercicio

Abre en el navegador:

`http://localhost:8080/Ejercicio4/index.php`

| Operación | Datos para probar | Resultado esperado |
|---|---|---|
| Consultar grupo | DAW1 | Ana |
| Buscar nombre | Ana | Ana |
| Consultar asignaturas | ID 1 | Programación y Bases de datos |
| Matricular | Alumno 2, asignatura 2 | Matrícula realizada |
| Cambiar grupo | Alumno 1, DAW2 | Grupo cambiado |
| Borrar alumno | ID 2 | Alumno borrado |

**Importante:** los archivos JSON deben tener permisos de escritura para el servidor PHP. Este ejemplo está pensado para practicar; para un servicio real habría que añadir controles de concurrencia, validación y protección de acceso.

Con este ejercicio se realizan las mismas operaciones que en la versión con MySQL, pero utilizando exclusivamente archivos JSON.
