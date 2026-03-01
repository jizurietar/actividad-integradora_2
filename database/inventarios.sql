-- Sistema de Inventario y Ventas
-- Base de datos: inventario_ventas

-- Crear base de datos si no existe
DROP DATABASE IF EXISTS `inventario_ventas`;

CREATE DATABASE IF NOT EXISTS `inventario_ventas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- crear usuario de la base de dato

DROP USER IF EXISTS 'user_iv'@'%';

CREATE USER 'user_iv'@'%' IDENTIFIED BY 'inventario';

GRANT ALL PRIVILEGES ON inventario_ventas.* TO 'user_iv'@'%';

FLUSH PRIVILEGES;

-- Usar la base de datos
USE `inventario_ventas`;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(1) NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`),
  KEY `idx_nombre` (`nombre`),
  KEY `idx_stock` (`stock`),
  KEY `idx_fecha_creacion` (`fecha_creacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_venta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_producto_id` (`producto_id`),
  KEY `idx_fecha_venta` (`fecha_venta`),
  CONSTRAINT `fk_venta_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo
INSERT INTO `productos` (`nombre`, `descripcion`, `stock`, `precio`,`deleted`) VALUES
('Laptop Dell Inspiron', 'Laptop de 15 pulgadas, Intel i5, 8GB RAM, 512GB SSD', 15, 899.99,0),
('Mouse Logitech MX Master', 'Mouse inalámbrico ergonómico con rueda de desplazamiento', 45, 89.99,0),
('Teclado Mecánico RGB', 'Teclado mecánico con retroiluminación RGB, switches azules', 30, 129.99,0),
('Monitor Samsung 24"', 'Monitor LED 24 pulgadas, Full HD, 75Hz', 25, 189.99,0),
('Auriculares Bluetooth', 'Auriculares inalámbricos con cancelación de ruido', 40, 149.99,0),
('Webcam HD 1080p', 'Webcam USB Full HD con micrófono integrado', 35, 59.99,0),
('Disco Duro Externo 1TB', 'Disco duro externo USB 3.0, 1TB de capacidad', 20, 79.99,0),
('Memoria USB 64GB', 'Memoria USB 3.0, 64GB, alta velocidad', 100, 12.99,0),
('Hub USB 4 Puertos', 'Hub USB con 4 puertos, compatible con USB 3.0', 50, 24.99,0),
('Cargador Portátil', 'Cargador portátil universal 20000mAh', 60, 39.99,0);

-- Insertar algunas ventas de ejemplo
INSERT INTO `ventas` (`producto_id`, `cantidad`, `precio_unitario`, `total`, `fecha_venta`) VALUES
(1, 2, 899.99, 1799.98, '2025-01-20 10:30:00'),
(2, 5, 89.99, 449.95, '2025-01-20 11:15:00'),
(3, 3, 129.99, 389.97, '2025-01-20 14:20:00'),
(4, 1, 189.99, 189.99, '2025-01-21 09:45:00'),
(5, 2, 149.99, 299.98, '2025-01-21 10:30:00'),
(6, 4, 59.99, 239.96, '2025-01-21 15:10:00'),
(7, 1, 79.99, 79.99, '2025-01-22 08:20:00'),
(8, 10, 12.99, 129.90, '2025-01-22 11:30:00'),
(9, 3, 24.99, 74.97, '2025-01-22 13:45:00'),
(10, 2, 39.99, 79.98, '2025-01-23 10:00:00');
