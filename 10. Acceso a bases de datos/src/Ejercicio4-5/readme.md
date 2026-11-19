# 📦 Ejercicio 4: GESTISIMAL (Gestión Simplificada de Almacén)

## 🎯 Objetivo del Proyecto

Desarrollar el programa **GESTISIMAL** (**GESTI**ón **SIM**plificada de **AL**macén) para llevar el control integral del inventario y los movimientos de artículos en un almacén.

---

## 📋 Modelo de Datos: Artículo

De cada artículo registrado en el sistema se debe almacenar y gestionar la siguiente información:

| Atributo | Tipo Sugerido | Descripción |
| :--- | :--- | :--- |
| **`codigo`** | `String` / `INT` | Identificador único del artículo *(Clave Primaria)* |
| **`descripcion`** | `String` / `VARCHAR` | Nombre o detalle descriptivo del producto |
| **`precio_compra`** | `Double` / `DECIMAL` | Coste de adquisición del artículo |
| **`precio_venta`** | `Double` / `DECIMAL` | Precio de venta al público (PVP) |
| **`stock`** | `INT` | Número de unidades disponibles actualmente en el almacén |

---

## ⚙️ Funcionalidades Principales

El programa debe contar con un menú interactivo (o interfaz) que permita realizar, como mínimo, las siguientes operaciones:

1. **📄 Listado:** Mostrar todos los artículos dados de alta en el almacén junto con sus datos y stock actual.
2. **➕ Alta:** Registrar un nuevo artículo en el sistema (controlando que el código no esté duplicado).
3. **➖ Baja:** Eliminar un artículo existente a partir de su código.
4. **✏️ Modificación:** Actualizar los datos de un artículo ya registrado (descripción, precio de compra, precio de venta, etc.).
5. **📥 Entrada de mercancía:** Incrementar el `stock` de un artículo determinado tras recibir nuevas unidades.
6. **📤 Salida de mercancía:** Decrementar el `stock` de un artículo determinado tras una venta o retirada.

---

## 🛡️ Reglas de Negocio y Validaciones

* **Control de Stock Negativo:** En la operación de **salida de mercancía**, es obligatorio validar que la cantidad a retirar **no supere el stock disponible** actualmente en el almacén. Si no hay suficientes unidades, la operación debe cancelarse y mostrar un aviso al usuario.
* **Integridad de Códigos:** No se debe permitir dar de alta dos artículos con el mismo `codigo`, ni realizar operaciones (baja, modificación, entrada/salida) sobre códigos inexistentes.

---

## 🧭 Estructura Sugerida del Menú

```text
=========================================
   GESTISIMAL - Gestión de Almacén
=========================================
1. Listado de artículos
2. Alta de nuevo artículo
3. Baja de artículo
4. Modificación de artículo
5. Entrada de mercancía (+)
6. Salida de mercancía (-)
7. Salir
=========================================
Seleccione una opción: _
```