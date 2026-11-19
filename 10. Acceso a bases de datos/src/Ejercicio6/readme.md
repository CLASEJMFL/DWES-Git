# 📅 Ejercicio 6: Gestión de Horario Escolar

## 🎯 Objetivo del Proyecto
Diseñar y desarrollar una aplicación web dinámica que permita **consultar y actualizar** el horario semanal de clases conectado a una base de datos.

---

## ⚙️ Requisitos Funcionales

1. **Vista Principal (`index`):**
   * Al cargar la página, debe consultar la base de datos y renderizar una **tabla con el horario semanal** completo.
   * Cada celda correspondiente a una asignatura funcionará como un **botón interactivo**.
2. **Vista de Edición (Formulario):**
   * Al pulsar sobre cualquier asignatura de la tabla, la aplicación redirigirá a una segunda página.
   * Esta vista contendrá un formulario con un **menú desplegable (`<select>`)** que listará todas las asignaturas disponibles.
3. **Actualización y Retorno:**
   * Al seleccionar una nueva asignatura y pulsar el botón **Grabar**, se actualizará el registro correspondiente en la base de datos.
   * Tras guardar los cambios, la aplicación redirigirá automáticamente de vuelta a la **página principal** mostrando el horario actualizado.

---

## 🗄️ Estructura de la Base de Datos

* **Nombre de la base de datos:** `escuela`
* **Nombre de la tabla:** `horario`
* **Clave Primaria (`PRIMARY KEY`):** `dia` (no se puede repetir; habrá una fila por cada día lectivo de la semana).

### Esquema de la tabla `horario`

| Campo | Tipo de Dato | Restricciones | Descripción |
| :--- | :--- | :--- | :--- |
| **`dia`** | `VARCHAR` | `PRIMARY KEY` | Día de la semana (ej. Lunes, Martes...) |
| **`primera`** | `VARCHAR` | - | Asignatura de la 1ª hora |
| **`segunda`** | `VARCHAR` | - | Asignatura de la 2ª hora |
| **`tercera`** | `VARCHAR` | - | Asignatura de la 3ª hora |
| **`cuarta`** | `VARCHAR` | - | Asignatura de la 4ª hora |
| **`quinta`** | `VARCHAR` | - | Asignatura de la 5ª hora |
| **`sexta`** | `VARCHAR` | - | Asignatura de la 6ª hora |

---

## 💻 Script SQL de Creación (Referencia)

Puedes utilizar el siguiente script para inicializar rápidamente tu entorno en MySQL/MariaDB:

```sql
CREATE DATABASE IF NOT EXISTS escuela CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE escuela;

CREATE TABLE horario (
    dia VARCHAR(15) PRIMARY KEY,
    primera VARCHAR(50),
    segunda VARCHAR(50),
    tercera VARCHAR(50),
    cuarta VARCHAR(50),
    quinta VARCHAR(50),
    sexta VARCHAR(50)
);

-- Datos iniciales de ejemplo
INSERT INTO horario (dia, primera, segunda, tercera, cuarta, quinta, sexta) VALUES
('Lunes', 'Matemáticas', 'Lengua', 'Inglés', 'Historia', 'Física', 'Educación Física'),
('Martes', 'Biología', 'Matemáticas', 'Química', 'Lengua', 'Inglés', 'Filosofía'),
('Miércoles', 'Historia', 'Física', 'Matemáticas', 'Educación Física', 'Lengua', 'Inglés'),
('Jueves', 'Inglés', 'Química', 'Biología', 'Matemáticas', 'Filosofía', 'Historia'),
('Viernes', 'Lengua', 'Historia', 'Física', 'Inglés', 'Biología', 'Matemáticas');