
-- =====================================================
-- CONFIGURACION GENERAL UTF-8
-- =====================================================

CREATE DATABASE IF NOT EXISTS ejercicios
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE ejercicios;

SET NAMES utf8mb4;


-- =====================================================
-- TABLA CLIENTES - EJERCICIOS 1 Y 2
-- =====================================================

CREATE TABLE IF NOT EXISTS CLIENTES (
    dni VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200),
    telefono VARCHAR(15)
)
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

INSERT IGNORE INTO CLIENTES
(dni, nombre, direccion, telefono) VALUES
('12345678A', 'Juan Perez', 'Calle Mayor 10', '600123456'),
('87654321B', 'Maria Lopez', 'Avenida Sevilla 25', '611223344'),
('11223344C', 'Pedro Garcia', 'Calle Sol 5', '622334455');


-- =====================================================
-- TABLA PRODUCTOS - EJERCICIO 3
-- =====================================================

CREATE TABLE IF NOT EXISTS PRODUCTOS (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0
)
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

INSERT IGNORE INTO PRODUCTOS
(nombre, precio, stock) VALUES
('Keyboard', 49.99, 15),
('Mouse', 19.95, 20),
('Laptop', 599.99, 10),
('Movile Phone', 299.99, 12),
('Headphones', 49.99, 25);


-- =====================================================
-- TABLA ALMACEN - EJERCICIO 4
-- =====================================================

CREATE TABLE IF NOT EXISTS almacen (
    codigo VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(150) NOT NULL,
    precio_compra DECIMAL(10,2) NOT NULL CHECK (precio_compra >= 0),
    precio_venta DECIMAL(10,2) NOT NULL CHECK (precio_venta >= 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0)
)
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

INSERT IGNORE INTO almacen
(codigo, descripcion, precio_compra, precio_venta, stock) VALUES
('ART-001', 'Teclado mecánico RGB inalámbrico', 45.50, 79.99, 25),
('ART-002', 'Ratón ergonómico óptico 16000 DPI', 18.00, 34.95, 40),
('ART-003', 'Monitor IPS 27 pulgadas 144Hz', 165.00, 249.00, 12),
('ART-004', 'Auriculares gaming con micrófono 7.1', 28.75, 54.50, 18),
('ART-005', 'Alfombrilla XL antideslizante', 6.20, 14.90, 60),
('ART-006', 'Webcam Full HD 1080p con enfoque automático', 22.00, 42.00, 15),
('ART-007', 'Disco SSD NVMe 1TB PCIe 4.0', 58.00, 89.90, 30),
('ART-008', 'Silla ergonómica de oficina negra', 110.00, 179.95, 8),
('ART-009', 'Hub USB-C multipuerto 7 en 1', 14.50, 29.99, 35),
('ART-010', 'Cable HDMI 2.1 trenzado 2 metros', 4.10, 11.50, 100);


-- =====================================================
-- TABLA HORARIO - EJERCICIO 6
-- =====================================================

CREATE TABLE IF NOT EXISTS horario (
    dia VARCHAR(15) PRIMARY KEY,
    primera VARCHAR(50),
    segunda VARCHAR(50),
    tercera VARCHAR(50),
    cuarta VARCHAR(50),
    quinta VARCHAR(50),
    sexta VARCHAR(50)
)
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

INSERT IGNORE INTO horario
(dia, primera, segunda, tercera, cuarta, quinta, sexta) VALUES
('Lunes', 'Matemáticas', 'Lengua', 'Inglés', 'Historia', 'Física', 'Educación Física'),
('Martes', 'Biología', 'Matemáticas', 'Química', 'Lengua', 'Inglés', 'Filosofía'),
('Miércoles', 'Historia', 'Física', 'Matemáticas', 'Educación Física', 'Lengua', 'Inglés'),
('Jueves', 'Inglés', 'Química', 'Biología', 'Matemáticas', 'Filosofía', 'Historia'),
('Viernes', 'Lengua', 'Historia', 'Física', 'Inglés', 'Biología', 'Matemáticas');