# Ejercicio 1. Servicio web: conversor de euros y pesetas

Crea un servicio web para pasar de euros a pesetas y de pesetas a euros. Si se pasa por la URL una cantidad en
euros, el programa lo debe convertir en pesetas y viceversa.

## 1. ¿Qué tengo que utilizar?

Para realizar este ejercicio necesitas los siguientes conceptos del Tema 12:

### 1. PHP

El servicio web lo vas a crear mediante un archivo PHP.

Por ejemplo, puedes llamarlo `index.php`.

Este archivo será el encargado de:
- Recibir la cantidad que se quiere convertir.
- Saber qué tipo de conversión se quiere realizar.
- Realizar el cálculo.
- Devolver el resultado.

### 2. Parámetros por URL: `$_GET`

El enunciado indica que se debe pasar una cantidad por la URL.

Para recoger ese dato tienes que utilizar `$_GET`.

Por ejemplo, una URL puede tener esta estructura:

```text
index.php?euros=10
```

O esta otra:

```text
index.php?pesetas=1663.86
```

Tienes que investigar cómo recoger esos parámetros desde PHP.

**Recuerda:** `$_GET` permite acceder a los parámetros que se envían mediante la URL.

### 3. Condicionales: `if` y `else`

Necesitas utilizar condicionales para distinguir qué conversión se está solicitando.

Tendrás que comprobar si se ha recibido:
- Una cantidad en euros.
- Una cantidad en pesetas.

Según el parámetro recibido, tendrás que realizar una conversión u otra.

### 4. Operaciones matemáticas

Necesitas realizar una operación matemática para convertir las cantidades.

La equivalencia que debes utilizar es:

```text
1 euro = 166,386 pesetas
```

Por tanto, tendrás que investigar qué operación matemática necesitas para cada conversión:

- De euros a pesetas.
- De pesetas a euros.

### 5. Mostrar el resultado

Puedes utilizar `echo` para devolver el resultado de la conversión.

Por ejemplo, el servicio puede devolver un texto indicando la cantidad convertida.

### 6. Probar el servicio web

Puedes probar el servicio directamente desde el navegador escribiendo la URL con los parámetros correspondientes.

También puedes utilizar **Postman**, aunque para este ejercicio puedes empezar probándolo directamente desde el navegador, ya que el enunciado indica que los datos se pasan mediante la URL.

---

## 2. Resumen de lo que tienes que estudiar

| Concepto | Para qué sirve |
|---|---|
| PHP | Crear el servicio web. |
| `$_GET` | Recibir la cantidad desde la URL. |
| `if` y `else` | Distinguir el tipo de conversión. |
| Operaciones matemáticas | Convertir euros y pesetas. |
| `echo` | Devolver el resultado. |
| Navegador | Probar las peticiones del servicio. |

## 3. Orden recomendado para hacerlo

1. Crear un archivo PHP para el servicio.
2. Pensar cómo vas a indicar en la URL si quieres convertir euros o pesetas.
3. Recoger la cantidad mediante `$_GET`.
4. Comprobar qué parámetro se ha recibido utilizando condicionales.
5. Realizar la operación matemática correspondiente.
6. Mostrar el resultado con `echo`.
7. Probar las dos conversiones desde el navegador.

## 4. ¿Qué partes del Tema 12 necesitas?

Para este ejercicio céntrate especialmente en:

- **12.2. Consumo de servicios REST:** para entender cómo se realizan peticiones mediante URL.
- **12.4. Cabeceras HTTP y códigos de estado:** no es imprescindible para la conversión básica.
- **12.6. Creación de servicios web:** especialmente el ejemplo de `listadoEmpleados.php`, donde se reciben parámetros mediante `$_GET` y se devuelve una respuesta.

**Importante:** no necesitas utilizar JSON, archivos CSV, cURL, `file_get_contents()`, métodos POST, PUT o DELETE para resolver la versión básica de este ejercicio.