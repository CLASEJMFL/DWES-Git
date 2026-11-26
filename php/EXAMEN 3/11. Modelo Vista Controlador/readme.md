# Resumen completo – Unidad 11: Modelo Vista Controlador (MVC)

## 1. ¿Qué es el patrón MVC?

El **Modelo Vista Controlador (MVC)** (*Model View Controller*) es un patrón de diseño de software que permite organizar una aplicación separando el código en tres partes principales:

1. **Modelo (Model):** se encarga del acceso y la gestión de los datos.
2. **Vista (View):** se encarga de mostrar la información al usuario y generar la interfaz.
3. **Controlador (Controller):** contiene la lógica de la aplicación y coordina el funcionamiento entre el modelo y la vista.

El objetivo principal del MVC es **separar responsabilidades**, consiguiendo que el código esté más organizado, sea más fácil de mantener y permita modificar una parte de la aplicación sin tener que cambiar las demás.

Para explicar su funcionamiento, el tema utiliza como ejemplo una aplicación de gestión de ofertas de una pizzería.

En esta aplicación, cada oferta tiene:

- Un identificador único (`id`).
- Un título (`titulo`).
- Una imagen (`imagen`).
- Una descripción (`descripcion`).

El identificador es autonumérico, por lo que MySQL lo genera automáticamente al insertar una nueva oferta.

---

## 2. Estructura de carpetas de una aplicación MVC

Una aplicación MVC organiza sus archivos en diferentes carpetas según su función.

```text
Proyecto/
│
├── Controller/
│   ├── actualizaOferta.php
│   ├── borraOferta.php
│   ├── grabaOferta.php
│   ├── index.php
│   ├── nuevaOferta.php
│   └── updateOferta.php
│
├── Model/
│   ├── Oferta.php
│   └── PizzeriaDB.php
│
├── View/
│   ├── nuevaOferta_view.php
│   ├── index_view.php
│   ├── actualizaOferta_view.php
│   └── images/
│       ├── pizza1.jpg
│       ├── pizza2.jpg
│       └── pizza3.jpg
│
└── index.php
```

Cada carpeta tiene una responsabilidad concreta:

- **Model:** contiene las clases encargadas de gestionar los datos y comunicarse con la base de datos.
- **View:** contiene los archivos HTML y los elementos de la interfaz, como CSS, JavaScript e imágenes.
- **Controller:** contiene los archivos que controlan la lógica de la aplicación y conectan el modelo con las vistas.
- **index.php (raíz):** normalmente redirige al `index.php` de la carpeta Controller.

---

# 3. El Modelo (Model)

## 3.1. ¿Qué es el modelo?

El modelo es la parte del MVC encargada de gestionar el acceso a los datos de la aplicación.

En una aplicación web, los datos suelen almacenarse en una base de datos. El modelo permite trabajar con ellos sin tener que escribir directamente las consultas SQL en todos los archivos de la aplicación.

Sus funciones principales son:

- Conectarse a la base de datos.
- Consultar información.
- Insertar nuevos registros.
- Modificar registros existentes.
- Eliminar registros.
- Devolver los datos en forma de objetos.

Toda esta parte del código se guarda en la carpeta `Model`.

## 3.2. Clase de conexión a la base de datos

En el ejemplo se utiliza el archivo `PizzeriaDB.php`, que contiene la clase encargada de establecer la conexión con MySQL.

```php
abstract class PizzeriaDB {

    private static $server = 'localhost';
    private static $db = 'pizzeria';
    private static $user = 'root';
    private static $password = '';

    public static function connectDB() {

        try {
            $connection = new PDO(
                "mysql:host=".self::$server.
                ";dbname=".self::$db.
                ";charset=utf8",
                self::$user,
                self::$password
            );

        } catch (PDOException $e) {

            echo "No se ha podido establecer conexión con el servidor de bases de datos.";
            die("Error: " . $e->getMessage());
        }

        return $connection;
    }
}
```

### ¿Qué hace esta clase?

Guarda los datos necesarios para conectarse a la base de datos:

- `$server`: servidor donde está alojada la base de datos.
- `$db`: nombre de la base de datos.
- `$user`: usuario de MySQL.
- `$password`: contraseña.

El método `connectDB()`:

1. Intenta establecer una conexión mediante PDO.
2. Si se produce un error, muestra un mensaje y detiene la ejecución.
3. Si todo funciona correctamente, devuelve la conexión.

**Importante:** el método es estático, por lo que no hace falta crear un objeto de la clase para utilizarlo.

Para conectarse basta con escribir:

```php
$miConexion = PizzeriaDB::connectDB();
```

### Ventaja de utilizar una clase de conexión

Si en el futuro cambia el nombre de la base de datos, el usuario o la contraseña, solamente habrá que modificar `PizzeriaDB.php`.

El resto de la aplicación seguirá funcionando sin necesidad de modificar sus archivos.

---

## 3.3. Clase correspondiente a una tabla

Además de la clase de conexión, se crea una clase por cada tabla de la base de datos.

En el ejemplo se utiliza la clase `Oferta`, que representa los registros de la tabla `oferta`.

Sus atributos deben tener los mismos nombres que los campos de la tabla.

```php
require_once 'PizzeriaDB.php';

class Oferta {

    private $id;
    private $titulo;
    private $imagen;
    private $descripcion;
}
```

### Constructor

El constructor permite crear objetos Oferta e inicializar sus atributos.

```php
function __construct($id=0, $titulo="", $imagen="", $descripcion="") {

    $this->id = $id;
    $this->titulo = $titulo;
    $this->imagen = $imagen;
    $this->descripcion = $descripcion;
}
```

Los valores predeterminados permiten crear objetos sin tener que proporcionar todos los datos.

### Getters

Los getters permiten obtener el valor de los atributos privados.

```php
public function getId() {
    return $this->id;
}

public function getTitulo() {
    return $this->titulo;
}

public function getImagen() {
    return $this->imagen;
}

public function getDescripcion() {
    return $this->descripcion;
}
```

---

## 3.4. Métodos principales de la clase Oferta

La clase Oferta contiene métodos que permiten realizar las operaciones habituales sobre la base de datos.

### A) Método insert()

Sirve para insertar una nueva oferta en la tabla.

```php
public function insert() {

    $conexion = PizzeriaDB::connectDB();

    $insercion = "INSERT INTO oferta
    (titulo, imagen, descripcion)
    VALUES ('$this->titulo',
    '$this->imagen',
    '$this->descripcion')";

    $conexion->exec($insercion);

    $conexion = null;
}
```

**Funcionamiento:**

1. Establece la conexión con la base de datos.
2. Construye una consulta SQL `INSERT`.
3. Ejecuta la consulta con `exec()`.
4. Cierra la conexión asignándole `null`.

No se inserta el identificador porque es autonumérico.

### B) Método delete()

Sirve para eliminar una oferta de la base de datos.

```php
public function delete() {

    $conexion = PizzeriaDB::connectDB();

    $borrado = "DELETE FROM oferta
    WHERE id='$this->id'";

    $conexion->exec($borrado);

    $conexion = null;
}
```

**Funcionamiento:**

1. Se conecta a la base de datos.
2. Busca el registro cuyo identificador coincide con el del objeto.
3. Ejecuta la consulta `DELETE`.
4. Cierra la conexión.

### C) Método update()

Sirve para modificar los datos de una oferta existente.

```php
public function update() {

    $conexion = PizzeriaDB::connectDB();

    $actualiza = "UPDATE oferta
    SET titulo='$this->titulo',
    imagen='$this->imagen',
    descripcion='$this->descripcion'
    WHERE id='$this->id'";

    $conexion->exec($actualiza);

    $conexion = null;
}
```

**Funcionamiento:**

1. Establece la conexión.
2. Ejecuta una consulta `UPDATE`.
3. Modifica el título, la imagen y la descripción.
4. Utiliza el `id` para identificar qué registro debe actualizar.
5. Cierra la conexión.

### D) Método estático getOfertas()

Devuelve un array con todas las ofertas de la base de datos.

```php
public static function getOfertas() {

    $conexion = PizzeriaDB::connectDB();

    $seleccion = "SELECT id, titulo, imagen, descripcion
    FROM oferta";

    $consulta = $conexion->query($seleccion);

    $ofertas = [];

    while ($registro = $consulta->fetchObject()) {

        $ofertas[] = new Oferta(
            $registro->id,
            $registro->titulo,
            $registro->imagen,
            $registro->descripcion
        );
    }

    $conexion = null;

    return $ofertas;
}
```

**Funcionamiento:**

1. Se conecta a la base de datos.
2. Ejecuta un `SELECT` para obtener todas las ofertas.
3. Crea un array vacío.
4. Recorre los registros obtenidos.
5. Convierte cada registro en un objeto de la clase `Oferta`.
6. Introduce los objetos en el array.
7. Cierra la conexión.
8. Devuelve el array.

Conceptos importantes:

- `query()`: ejecuta una consulta de selección.
- `fetchObject()`: obtiene un registro como objeto.
- `[]`: permite añadir elementos a un array.

### E) Método estático getOfertaById($id)

Devuelve un objeto con los datos de una oferta concreta según su identificador.

```php
public static function getOfertaById($id) {

    $conexion = PizzeriaDB::connectDB();

    $seleccion = "SELECT id, titulo, imagen, descripcion
    FROM oferta WHERE id=$id";

    $consulta = $conexion->query($seleccion);

    if ($consulta->rowCount() > 0) {

        $registro = $consulta->fetchObject();

        $oferta = new Oferta(
            $registro->id,
            $registro->titulo,
            $registro->imagen,
            $registro->descripcion
        );

        $conexion = null;

        return $oferta;

    } else {

        $conexion = null;

        return false;
    }
}
```

**Funcionamiento:**

1. Recibe un identificador como parámetro.
2. Se conecta a la base de datos.
3. Busca la oferta que tenga ese identificador.
4. Comprueba si existe algún resultado.
5. Si existe, crea y devuelve un objeto `Oferta`.
6. Si no existe, devuelve `false`.
7. Cierra la conexión.

### Diferencia entre los dos métodos de consulta

| Método | ¿Qué devuelve? |
|---|---|
| `getOfertas()` | Un array con todos los objetos Oferta. |
| `getOfertaById($id)` | Un único objeto Oferta o `false` si no existe. |

### Objetivo principal de la clase Oferta

La clase `Oferta` abstrae el acceso a la base de datos.

Esto significa que los controladores no necesitan escribir directamente las consultas SQL ni gestionar las conexiones. Simplemente llaman a los métodos de la clase.

---

# 4. La Vista (View)

## 4.1. ¿Qué es la vista?

La vista es la parte del patrón MVC encargada de mostrar la información al usuario.

Contiene principalmente:

- HTML.
- CSS.
- JavaScript.
- JQuery.
- Imágenes y otros recursos visuales.

Todos estos archivos se guardan en la carpeta `View`.

La vista no debe encargarse de recuperar los datos de la base de datos ni de ejecutar la lógica de negocio.

Su función es recibir los datos que le proporciona el controlador y mostrarlos.

## 4.2. Comunicación entre controlador y vista

Los datos que el controlador envía a la vista se almacenan en un array asociativo llamado `$data[]`.

Por ejemplo:

```php
$data['usuario'] = "Juan";
```

La vista puede mostrar ese dato de la siguiente manera:

```php
<h3>Usuario: <?= $data['usuario'] ?></h3>
```

El programador del controlador y el de la vista deben ponerse de acuerdo en los nombres de las posiciones del array `$data`.

Así ambos saben qué información se envía y cómo debe utilizarse.

## 4.3. Vista para mostrar todas las ofertas

El archivo `index_view.php` muestra el listado de ofertas de la pizzería.

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de ofertas</title>
</head>

<body>

    <h1>Pizzeria Peachepe</h1>

    <a href="../Controller/nuevaOferta.php">
        Nueva oferta
    </a>

    <hr>

    <?php
    foreach($data['ofertas'] as $oferta) {
    ?>

        <h3><?=$oferta->getTitulo()?></h3>

        <img src="../View/images/<?=$oferta->getImagen()?>"
        width="400">

        <br>

        <p><?=$oferta->getDescripcion()?></p>

        <br>

        <a href="../Controller/borraOferta.php?id=<?=$oferta->getId()?>">
            Borrar
        </a>

    <?php
    }
    ?>

</body>
</html>
```

**Funcionamiento:**

1. Recibe el array `$data['ofertas']`.
2. Utiliza un `foreach` para recorrer todas las ofertas.
3. Obtiene el título de cada oferta mediante `getTitulo()`.
4. Muestra su imagen mediante `getImagen()`.
5. Muestra su descripción mediante `getDescripcion()`.
6. Genera un enlace para borrar cada oferta utilizando su identificador.

La vista no realiza consultas SQL. Simplemente muestra los datos que ya ha preparado el controlador.

## 4.4. Vista para crear una nueva oferta

El archivo `nuevaOferta_view.php` muestra un formulario para introducir los datos de una nueva oferta.

```php
<form action="../Controller/grabaOferta.php"
enctype="multipart/form-data"
method="POST">

    <h3>Título</h3>
    <input type="text" size="40" name="titulo">

    <h3>Imagen</h3>
    <input type="file" id="imagen" name="imagen">

    <br>

    <h3>Descripción</h3>
    <textarea name="descripcion" cols="60" rows="6">
    </textarea>

    <hr>

    <input type="submit" value="Aceptar">

</form>
```

El formulario contiene:

- Un campo de texto para el título.
- Un campo para seleccionar una imagen.
- Un área de texto para la descripción.
- Un botón para enviar los datos.

### Atributos importantes del formulario

- `action`: indica el controlador que recibirá los datos.
- `method="POST"`: indica que los datos se enviarán mediante POST.
- `enctype="multipart/form-data"`: permite enviar archivos, como imágenes.
- `name`: identifica cada campo para poder recuperar su valor en PHP.

## 4.5. Vista para modificar una oferta

El archivo `actualizaOferta_view.php` muestra un formulario con los datos de una oferta que ya existe.

A diferencia del formulario de creación, este formulario carga previamente la información de la oferta seleccionada.

```php
<form action="../Controller/updateOferta.php"
enctype="multipart/form-data"
method="POST">

    <input type="hidden" name="id"
    value="<?= $data['oferta']->getId() ?>">

    <h3>Título</h3>

    <input type="text" size="40" name="titulo"
    value="<?= $data['oferta']->getTitulo() ?>">

    <h3>Imagen</h3>

    <input type="hidden" name="imagenAnterior"
    value="<?= $data['oferta']->getImagen() ?>">

    <img src="../View/images/<?= $data['oferta']->getImagen() ?>"
    alt="" width="150px">

    <br>

    <input type="file" id="imagen" name="imagen">

    <h3>Descripción</h3>

    <textarea name="descripcion" cols="60" rows="6">
    <?= $data['oferta']->getDescripcion() ?>
    </textarea>

    <hr>

    <input type="submit" value="Aceptar">

</form>
```

### Elementos importantes

- `id`: campo oculto que conserva el identificador de la oferta.
- `titulo`: muestra el título actual.
- `imagenAnterior`: guarda el nombre de la imagen anterior.
- `img`: muestra la imagen actual.
- `imagen`: permite seleccionar una nueva imagen.
- `descripcion`: muestra la descripción actual.

**¿Por qué se utiliza `imagenAnterior`?**

Porque el usuario puede modificar una oferta sin querer cambiar su imagen.

Si no selecciona otra imagen, el controlador utilizará el nombre de la imagen anterior para conservarla.

---

# 5. El Controlador (Controller)

## 5.1. ¿Qué es el controlador?

El controlador es la parte del MVC encargada de coordinar el funcionamiento de la aplicación.

Contiene la lógica de negocio y conecta el modelo con la vista.

Sus funciones principales son:

- Recibir los datos enviados por los formularios.
- Llamar a los métodos del modelo.
- Preparar los datos que necesita la vista.
- Incluir las vistas correspondientes.
- Gestionar operaciones con la base de datos.
- Gestionar archivos, sesiones y cookies.
- Redirigir al usuario cuando sea necesario.

Todos los controladores se guardan en la carpeta `Controller`.

Los controladores deben incluir las clases del modelo mediante `require_once` cuando necesiten utilizarlas.

## 5.2. Controlador index.php

Es el encargado de obtener todas las ofertas y mostrarlas en la vista principal.

```php
<?php

require_once '../Model/Oferta.php';

// Obtiene todas las ofertas
$data['ofertas'] = Oferta::getOfertas();

// Carga la vista de listado
include '../View/index_view.php';

?>
```

**Funcionamiento:**

1. Incluye la clase `Oferta`.
2. Llama al método estático `getOfertas()`.
3. Guarda el resultado en `$data['ofertas']`.
4. Incluye la vista `index_view.php`.

De esta forma, la vista recibe todas las ofertas y puede mostrarlas.

## 5.3. Controlador nuevaOferta.php

Su única función es mostrar el formulario para crear una nueva oferta.

```php
<?php

// Carga la vista del formulario de alta
include '../View/nuevaOferta_view.php';

?>
```

No necesita acceder al modelo porque simplemente muestra un formulario vacío.

## 5.4. Controlador grabaOferta.php

Recoge los datos del formulario de creación y los guarda en la base de datos.

```php
<?php

require_once '../Model/Oferta.php';

// Sube la imagen al servidor
move_uploaded_file(
    $_FILES["imagen"]["tmp_name"],
    "../View/images/" . $_FILES["imagen"]["name"]
);

// Inserta la oferta en la base de datos
$ofertaAux = new Oferta(
    "",
    $_POST['titulo'],
    $_FILES["imagen"]["name"],
    $_POST['descripcion']
);

$ofertaAux->insert();

header("Location: ../Controller/index.php");

?>
```

**Funcionamiento:**

1. Incluye la clase `Oferta`.
2. Sube la imagen al servidor mediante `move_uploaded_file()`.
3. Recupera los datos del formulario.
4. Crea un objeto `Oferta`.
5. Ejecuta el método `insert()` para guardar la información.
6. Redirige al usuario al listado principal.

### Conceptos importantes

- `$_POST`: permite recuperar los datos enviados mediante POST.
- `$_FILES`: permite recuperar información de los archivos enviados.
- `move_uploaded_file()`: mueve un archivo subido al servidor.
- `header("Location: ...")`: redirige a otra página.

## 5.5. Controlador actualizaOferta.php

Se encarga de recuperar los datos de una oferta concreta para mostrarlos en el formulario de modificación.

```php
<?php

require_once '../Model/Oferta.php';

$data['oferta'] = Oferta::getOfertaById($_REQUEST['id']);

// Carga la vista del formulario
include '../View/actualizaOferta_view.php';

?>
```

**Funcionamiento:**

1. Incluye la clase `Oferta`.
2. Recupera el identificador recibido mediante `$_REQUEST['id']`.
3. Busca la oferta utilizando `getOfertaById()`.
4. Guarda el objeto en `$data['oferta']`.
5. Incluye la vista de modificación.

Así, el formulario aparece con los datos de la oferta seleccionada.

## 5.6. Controlador updateOferta.php

Recoge los datos enviados por el formulario de modificación y actualiza la oferta en la base de datos.

```php
<?php

require_once '../Model/Oferta.php';

$oferta = Oferta::getOfertaById($_REQUEST['id']);

$imagen = $_REQUEST['imagenAnterior'];

if ($_FILES["imagen"]["name"] != "") {

    // Sube la imagen nueva
    move_uploaded_file(
        $_FILES["imagen"]["tmp_name"],
        "../View/images/" . $_FILES["imagen"]["name"]
    );

    $imagen = $_FILES["imagen"]["name"];
}

// Actualiza la oferta
$ofertaAux = new Oferta(
    $_REQUEST['id'],
    $_POST['titulo'],
    $imagen,
    $_POST['descripcion']
);

$ofertaAux->update();

header("Location: ../Controller/index.php");

?>
```

**Funcionamiento:**

1. Incluye la clase `Oferta`.
2. Busca la oferta que se quiere modificar.
3. Recupera el nombre de la imagen anterior.
4. Comprueba si el usuario ha seleccionado una imagen nueva.
5. Si ha seleccionado una nueva, la sube al servidor y actualiza el nombre de la imagen.
6. Si no ha seleccionado ninguna, conserva la imagen anterior.
7. Crea un nuevo objeto `Oferta` con los datos actualizados.
8. Ejecuta el método `update()`.
9. Redirige al listado principal.

**Importante:** la condición:

```php
if ($_FILES["imagen"]["name"] != "")
```

permite comprobar si se ha seleccionado una imagen nueva.

## 5.7. Controlador borraOferta.php

Se encarga de eliminar una oferta de la base de datos.

```php
<?php

require_once '../Model/Oferta.php';

$ofertaAux = new Oferta($_GET['id']);

$ofertaAux->delete();

header("Location: ../Controller/index.php");

?>
```

**Funcionamiento:**

1. Incluye la clase `Oferta`.
2. Recupera el identificador recibido mediante `$_GET['id']`.
3. Crea un objeto Oferta con ese identificador.
4. Ejecuta el método `delete()`.
5. Redirige al listado principal.

---

# 6. Funcionamiento completo del MVC

Para entender el MVC es importante saber cómo se comunican las tres partes.

El proceso general es:

```text
        USUARIO
           |
           v
      CONTROLADOR
           |
           v
        MODELO
           |
           v
     BASE DE DATOS
           |
           v
        MODELO
           |
           v
      CONTROLADOR
           |
           v
         VISTA
           |
           v
      USUARIO
```

### Ejemplo: mostrar todas las ofertas

1. El usuario entra en la página principal.
2. Se ejecuta `Controller/index.php`.
3. El controlador llama a `Oferta::getOfertas()`.
4. El modelo consulta la base de datos.
5. El modelo devuelve un array de objetos Oferta.
6. El controlador guarda los objetos en `$data['ofertas']`.
7. El controlador incluye `index_view.php`.
8. La vista recorre el array y muestra las ofertas.

### Ejemplo: crear una oferta

1. El usuario pulsa el enlace «Nueva oferta».
2. Se ejecuta `nuevaOferta.php`.
3. El controlador carga `nuevaOferta_view.php`.
4. El usuario rellena el formulario y lo envía.
5. Se ejecuta `grabaOferta.php`.
6. El controlador recoge los datos mediante `$_POST` y `$_FILES`.
7. Se guarda la imagen en el servidor.
8. Se crea un objeto Oferta.
9. Se llama al método `insert()`.
10. El modelo guarda la oferta en la base de datos.
11. El controlador redirige al listado principal.

### Ejemplo: modificar una oferta

1. Se solicita una oferta mediante su identificador.
2. El controlador llama a `getOfertaById($id)`.
3. El modelo recupera los datos de la base de datos.
4. El controlador almacena el objeto en `$data['oferta']`.
5. Se carga `actualizaOferta_view.php`.
6. El usuario modifica los campos.
7. El formulario envía los datos a `updateOferta.php`.
8. El controlador comprueba si se ha seleccionado una imagen nueva.
9. Se crea un objeto Oferta con los datos modificados.
10. Se llama a `update()`.
11. El modelo actualiza el registro.
12. Se redirige al listado principal.

### Ejemplo: borrar una oferta

1. El usuario pulsa el enlace «Borrar».
2. Se envía el identificador mediante GET.
3. Se ejecuta `borraOferta.php`.
4. El controlador crea un objeto Oferta.
5. Se llama al método `delete()`.
6. El modelo elimina el registro de la base de datos.
7. Se redirige al listado principal.

---

# 7. Ejercicios de la unidad

El tema propone cinco ejercicios para practicar el patrón MVC.

## Ejercicio 1. Crear un blog

Se debe desarrollar un blog siguiendo el patrón MVC.

Características:

- El blog tendrá una cabecera.
- Tendrá una serie de artículos.
- Tendrá un pie de página.
- Los artículos se almacenarán en una base de datos.
- Cada artículo tendrá:
  - Identificador único autonumérico.
  - Título.
  - Fecha de publicación o fecha y hora.
  - Contenido.

Clases necesarias:

**BlogDB:**

- Se encargará de establecer la conexión con la base de datos.
- Permitirá aislar los datos de conexión.

**Articulo:**

- Tendrá los mismos atributos que los campos de la tabla `articulo`.
- Tendrá un constructor.
- Podrá tener getters y setters.
- Tendrá el método `insert()` para insertar artículos.
- Tendrá el método `delete()` para eliminar artículos.
- Tendrá el método estático `getArticulos()` para devolver un array con todos los artículos.

La fecha de publicación se puede obtener directamente del sistema cuando se inserta un artículo.

## Ejercicio 2. Mejorar el blog

Se debe ampliar el ejercicio anterior.

Cambios que hay que realizar:

- Permitir modificar los artículos.
- Añadir estilos CSS para mejorar el aspecto visual de la aplicación.

## Ejercicio 3. Carrito de la compra utilizando MVC

Se debe realizar una aplicación de carrito de la compra siguiendo el patrón MVC.

Los productos se almacenarán en una tabla con los siguientes campos:

| Campo | Descripción |
|---|---|
| `codigo` | Identificador autonumérico. |
| `nombre` | Nombre del producto. |
| `precio` | Precio del producto. |
| `stock` | Unidades disponibles. |

La clase de los productos tendrá:

- Constructor.
- Método `reponer()`.
- Método `vender()`.
- Getters de los atributos.

### Comprobaciones del carrito

- Al añadir un producto al carrito, se debe comprobar si hay existencias.
- También hay que comprobar la cantidad que ya hay en la cesta.
- Si no hay existencias suficientes, no se debe añadir el producto.
- El botón «Comprar» debe vaciar el carrito.
- Al comprar, se deben restar del stock las unidades vendidas.

### Mejoras opcionales

- Añadir confirmación antes de borrar un producto.
- Generar una factura o ticket en un fichero.
- Abrir la factura o ticket en otra pestaña al finalizar la compra.

## Ejercicio 4. Aplicación web de gestión de alumnos y asignaturas

Se debe crear una aplicación PHP siguiendo MVC.

### Página principal

Debe mostrar un listado de alumnos con los siguientes datos:

- Matrícula.
- Nombre.
- Apellidos.
- Curso.

Debe existir un botón «Añadir Alumno» que permita acceder a un formulario para registrar nuevos alumnos.

También debe haber un botón «Asignaturas» que permita acceder a otra página.

### Gestión de asignaturas

La página de asignaturas debe mostrar:

- Código autonumérico.
- Nombre de la asignatura.

Debe permitir añadir y eliminar asignaturas.

### Detalles de los alumnos

Junto a cada alumno debe aparecer un botón «Detalles».

Al pulsarlo, se mostrará una página con las asignaturas en las que está matriculado ese alumno.

Junto a cada asignatura habrá un botón para desmatricular al alumno.

También debe existir un formulario con un desplegable que muestre todas las asignaturas disponibles para poder matricular al alumno en una nueva asignatura.

### Base de datos

Se debe crear una base de datos llamada `escuela` con tres tablas:

**Tabla alumno**

| Campo |
|---|
| `matricula` |
| `nombre` |
| `apellidos` |
| `curso` |

**Tabla asignatura**

| Campo |
|---|
| `codigo` (autonumérico) |
| `nombre` |

**Tabla alumno-asignatura**

| Campo |
|---|
| `matricula` |
| `codigo-asignatura` |

La tabla `alumno-asignatura` relaciona los alumnos con las asignaturas en las que están matriculados.

## Ejercicio 5. Carrito de la compra con usuarios y roles

Se debe realizar el ejercicio 3, pero añadiendo un sistema de acceso mediante usuarios registrados.

Existirán dos perfiles:

- `admin`
- `cliente`

### Usuario administrador

El administrador no podrá comprar.

Sus funciones serán administrar los productos y el stock:

- Añadir productos.
- Eliminar productos.
- Añadir stock.

### Usuario cliente

El cliente solamente podrá comprar productos.

Una característica importante es que los productos del carrito no se guardarán en una sesión PHP, sino directamente en una tabla de la base de datos.

De esta manera, cuando un usuario vuelva a iniciar sesión desde otro dispositivo, seguirá teniendo los productos guardados en su cesta.

### Tablas adicionales de la base de datos

Se deben añadir dos tablas:

**Tabla usuario**

| Campo | Descripción |
|---|---|
| `id` | Identificador autonumérico. |
| `nombre` | Nombre del usuario. |
| `pass` | Contraseña. |
| `rol` | Perfil: admin o cliente. |

**Tabla cesta**

| Campo | Descripción |
|---|---|
| `id_cliente` | Identificador del cliente. |
| `cod_producto` | Código del producto. |
| `cantidad` | Cantidad de unidades del producto. |

Cada fila representa un producto de la cesta de un usuario y su cantidad correspondiente.

### Funcionamiento

- La aplicación debe exigir iniciar sesión para acceder.
- Se utilizarán vistas diferentes dependiendo del perfil.
- El administrador podrá gestionar los productos y el stock.
- El cliente podrá comprar.
- La cesta del cliente se almacenará en la base de datos para conservarla entre dispositivos.

---

# 8. Conceptos fundamentales de la unidad

Esta última parte recoge las reglas generales que se deben seguir al desarrollar una aplicación mediante MVC.

## 8.1. Modelo

El modelo se encuentra en la carpeta `Model`.

Debe contener:

**1. Una clase de conexión a la base de datos**

- Su nombre será el de la base de datos seguido de `DB` (por ejemplo, `PizzeriaDB`).
- Tendrá un método estático `connectDB()`.
- Este método devolverá la conexión a la base de datos.

**2. Una clase por cada tabla de la base de datos**

Cada clase tendrá los atributos correspondientes a las columnas de su tabla.

También tendrá los siguientes métodos:

| Método | Función |
|---|---|
| `insert()` | Inserta un registro. |
| `delete()` | Elimina un registro. |
| `update()` | Modifica un registro. |
| Otros métodos necesarios | Realizan las operaciones específicas de la aplicación. |
| `getElementos()` | Método estático que devuelve un array con todos los objetos de la tabla. |
| `getElementoById($id)` | Método estático que devuelve el objeto correspondiente al identificador indicado. |

## 8.2. Vista

La vista se encuentra en la carpeta `View`.

Características principales:

- Genera la interfaz HTML que se muestra al usuario.
- Puede contener CSS, JavaScript, imágenes y otros recursos.
- No se encarga de recuperar los datos ni de ejecutar la lógica de negocio.
- Recibe los datos mediante el array asociativo `$data[]`.
- Las vistas nunca se enlazan directamente: siempre se incluyen desde los controladores.
- Los atributos HTML `href` y `action` deben apuntar a controladores, incluso cuando estos solamente incluyen una vista.
- Para organizar mejor el código, los archivos relacionados con la interfaz se guardan en esta carpeta y sus correspondientes subcarpetas.
- Como regla general, la vista tendrá el mismo nombre que el controlador que la incluye, añadiendo `_view`.

Ejemplo:

```text
index.php
index_view.php
```

Si una vista es utilizada por varios controladores, se le puede asignar otro nombre.

## 8.3. Controlador

El controlador se encuentra en la carpeta `Controller`.

Características principales:

- Coordina el funcionamiento de la aplicación.
- Realiza llamadas a los métodos del modelo.
- Prepara los datos que necesitan las vistas.
- Incluye las vistas que se deben mostrar.
- Contiene la lógica de la aplicación.
- Gestiona el acceso a bases de datos y ficheros.
- Gestiona sesiones y cookies.
- Incluye las clases del modelo mediante `include` o `require_once` para poder utilizarlas.

En la raíz del proyecto suele existir un archivo `index.php` cuya única función es redirigir al archivo principal:

```text
index.php
    |
    v
Controller/index.php
```

---

# 9. Tabla comparativa: Modelo, Vista y Controlador

| Característica | Modelo | Vista | Controlador |
|---|---|---|---|
| Carpeta | `Model` | `View` | `Controller` |
| Función | Gestionar los datos | Mostrar información | Coordinar la aplicación |
| Contiene | Clases y consultas SQL | HTML, CSS, JS | Lógica de negocio |
| Acceso a BD | Sí | No | A través del modelo |
| Recibe datos | De la BD | Del controlador | Del usuario y del modelo |
| Devuelve | Objetos y arrays | Interfaz visual | Carga vistas y coordina operaciones |
| Ejemplos | `Oferta.php` | `index_view.php` | `grabaOferta.php` |

---

# 10. Conceptos y funciones PHP que debes conocer

Estos son los elementos que aparecen en los ejemplos y que conviene saber identificar.

| Elemento | Función |
|---|---|
| `require_once` | Incluye un archivo evitando incluirlo más de una vez. |
| `include` | Incluye otro archivo PHP. |
| `new` | Crea un objeto de una clase. |
| `__construct()` | Constructor de una clase. |
| `private` | Define un atributo o método privado. |
| `public` | Define un atributo o método público. |
| `static` | Permite utilizar un método sin crear un objeto. |
| `$this` | Hace referencia al objeto actual. |
| `PDO` | Permite conectarse y trabajar con bases de datos. |
| `exec()` | Ejecuta consultas SQL que no devuelven un conjunto de resultados, como INSERT, UPDATE o DELETE. |
| `query()` | Ejecuta consultas SQL y devuelve un resultado. |
| `fetchObject()` | Recupera un registro como objeto. |
| `rowCount()` | Devuelve el número de filas afectadas o devueltas, según la operación y el controlador. |
| `$_POST` | Recupera datos enviados mediante POST. |
| `$_GET` | Recupera datos enviados mediante GET. |
| `$_REQUEST` | Recupera variables de petición. |
| `$_FILES` | Recupera información de archivos enviados. |
| `move_uploaded_file()` | Mueve un archivo subido al servidor. |
| `header()` | Permite enviar cabeceras HTTP, por ejemplo, para redirigir. |
| `foreach` | Recorre los elementos de un array. |
| `return` | Devuelve un valor desde un método o función. |
| `null` | Representa la ausencia de valor. |
| `false` | Representa un valor falso. |

---

# 11. Lo más importante para el examen

Si tienes que estudiar esta unidad, estos son los conceptos que debes dominar:

1. **Qué es MVC:** un patrón que divide la aplicación en Modelo, Vista y Controlador.
2. **Modelo:** gestiona los datos y el acceso a la base de datos.
3. **Clase de conexión:** contiene los datos de conexión y el método estático `connectDB()`.
4. **Clase por tabla:** representa una tabla de la base de datos y contiene sus atributos y métodos.
5. **Métodos CRUD:**
   - `insert()` → insertar.
   - `getElementos()` → consultar todos.
   - `getElementoById($id)` → consultar uno.
   - `update()` → modificar.
   - `delete()` → eliminar.
6. **Vista:** genera la interfaz HTML y recibe los datos mediante `$data[]`.
7. **Controlador:** recibe peticiones, llama al modelo, prepara los datos e incluye las vistas.
8. **Comunicación:** las vistas se incluyen desde los controladores, no se enlazan directamente.
9. **Formularios:** los atributos `action` deben apuntar a controladores.
10. **Subida de imágenes:** se utiliza `$_FILES` y `move_uploaded_file()`.
11. **Redirecciones:** se utiliza `header("Location: ...")`.
12. **Separación de responsabilidades:** cada parte debe encargarse exclusivamente de su función.

## Esquema final para memorizar

```text
                 PATRÓN MVC
                     |
        +------------+------------+
        |            |            |
        v            v            v
      MODELO     CONTROLADOR     VISTA
        |            |            |
        |            |            |
     Model       Controller      View
        |            |            |
        v            v            v
    Base de      Lógica de     Interfaz
     datos       negocio        HTML
        |            |            |
        +------------+------------+
                     |
                   USUARIO
```

**Regla de oro para recordar:**

- **Modelo = datos.**
- **Vista = presentación.**
- **Controlador = lógica y coordinación.**

Si entiendes esta separación y sabes explicar cómo funcionan `insert()`, `delete()`, `update()`, `getOfertas()` y `getOfertaById()`, tendrás dominada la parte fundamental de esta unidad.
