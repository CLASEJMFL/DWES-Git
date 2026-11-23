# Ejercicio 1 · Desarrollo de un blog

## Objetivo

Crear una aplicación de blog que permita almacenar y gestionar artículos
mediante una base de datos.

## Requisitos del proyecto

### 1. Estructura del blog

El blog debe incluir, como mínimo, los siguientes elementos:

-   **Cabecera:** identifica el blog y aparece en la parte superior.
-   **Artículos:** contiene la lista de publicaciones.
-   **Pie de página:** aparece al final de la página.

### 2. Base de datos

Los artículos deben almacenarse en una base de datos.

Cada artículo debe tener, como mínimo, estos campos:

  Campo         Descripción
  ------------- ------------------------------------------------
  `id`          Identificador único del artículo.
  `titulo`      Título de la publicación.
  `fecha`       Fecha (y, opcionalmente, hora) de publicación.
  `contenido`   Texto o contenido del artículo.

**Consideraciones:**

-   El identificador debe ser único y puede generarse automáticamente
    mediante un campo autoincremental.
-   La fecha de publicación puede obtenerse del sistema en el momento de
    guardar un artículo nuevo.

### 3. Clase `BlogDB`

Crear la clase `BlogDB` para centralizar y aislar los datos necesarios
para conectarse a la base de datos en la que se guardan los artículos.

Esta clase será la encargada de gestionar la conexión con la base de
datos.

### 4. Clase `Articulo`

Crear la clase `Articulo` con los mismos atributos que los campos
definidos en la tabla `articulo` de la base de datos.

La clase debe incluir:

-   Un **constructor** para inicializar sus atributos.
-   Los métodos **getter y setter** (opcionales), que pueden generarse
    automáticamente en el IDE mediante `Alt + Insert`.

#### Métodos obligatorios

  -----------------------------------------------------------------------
  Método                              Función
  ----------------------------------- -----------------------------------
  `insert()`                          Inserta el artículo en la base de
                                      datos.

  `delete()`                          Elimina el artículo de la base de
                                      datos.

  `getArticulos()`                    Método de clase que obtiene todos
                                      los artículos almacenados y los
                                      devuelve en un array.
  -----------------------------------------------------------------------

## Resumen de entregables

-   [ ] Estructura del blog con cabecera, artículos y pie de página.
-   [ ] Base de datos con la tabla `articulo`.
-   [ ] Identificador único y autoincremental para cada artículo.
-   [ ] Fecha de publicación generada automáticamente al guardar un
    artículo.
-   [ ] Clase `BlogDB` para gestionar la conexión.
-   [ ] Clase `Articulo` con sus atributos y constructor.
-   [ ] Método `insert()` para insertar artículos.
-   [ ] Método `delete()` para borrar artículos.
-   [ ] Método `getArticulos()` para recuperar todos los artículos en un
    array.

# Ejercicio 2 · Desarrollo de un blog

## Objetivo

Mejora el blog de tal forma que se permita la modificación de un artículo y añade estilos a la aplicación para
hacerla más atractiva