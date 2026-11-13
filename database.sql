-- Base de datos de la tienda de zapatillas.
-- Importar desde phpMyAdmin (pestaña Importar). Borra y recrea todo.

DROP DATABASE IF EXISTS tienda_zapatillas;
CREATE DATABASE tienda_zapatillas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE tienda_zapatillas;

-- Usuarios de la aplicación (administradores y clientes).
CREATE TABLE usuarios (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL UNIQUE,
    password  VARCHAR(255) NOT NULL,          -- hash generado con password_hash()
    telefono  VARCHAR(20)  NULL,              -- opcional
    direccion VARCHAR(255) NULL,              -- opcional
    rol       ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente'
);

-- Artículos (zapatillas) que se venden en la tienda.
CREATE TABLE productos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    marca       VARCHAR(50)   NOT NULL,
    precio      DECIMAL(8, 2) NOT NULL,
    descripcion TEXT          NULL,
    imagen_url  VARCHAR(1000) NULL            -- si está vacía se muestra una imagen por defecto
);

-- Cabecera de cada compra confirmada. Un usuario puede tener muchos pedidos (1:N).
CREATE TABLE pedidos (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT           NOT NULL,
    fecha      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total      DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Líneas de cada pedido. Resuelve la relación N:M entre pedidos y productos.
-- Se guarda el precio del momento de la compra para que no cambie si luego se edita el producto.
CREATE TABLE pedido_detalle (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id   INT           NOT NULL,
    producto_id INT           NULL,
    cantidad    INT           NOT NULL,
    precio      DECIMAL(8, 2) NOT NULL,
    FOREIGN KEY (pedido_id)   REFERENCES pedidos(id)   ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

-- Datos de prueba. Contraseñas: admin123 y cliente123.
INSERT INTO usuarios (nombre, email, password, telefono, direccion, rol) VALUES
('Administrador', 'admin@tienda.com',   '$2y$10$rMMklhhNQhWF/H6ilrtRA.GCOjWhKmNuFZXmpI6GDoaC7DWqmW00G', NULL, NULL, 'admin'),
('Cliente Prueba', 'cliente@tienda.com', '$2y$10$lsiRGWbnuV0B5t2u7GOs0OO0J647ApAkvTVNFyLns6EEbW.A/.KOO', '600000000', 'Calle Mayor 1', 'cliente');

INSERT INTO productos (nombre, marca, precio, descripcion, imagen_url) VALUES
('Air Runner',   'Nike',        89.99, 'Zapatilla ligera para running diario.', 'img/air-runner.avif'),
('Ultra Boost',  'Adidas',     129.95, 'Amortiguación para largas distancias.', 'img/adidas-ultraboost.webp'),
('Gel Trail',    'Asics',       99.50, 'Suela con agarre para montaña.',        'img/get-trail.webp'),
('Classic Court','Puma',        64.00, 'Zapatilla urbana de estilo clásico.',   'img/classic-court.jpg');
