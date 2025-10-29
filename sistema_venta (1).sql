-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-10-2025 a las 05:46:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_venta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `detalle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `detalle`) VALUES
(1, 'Electrónica', 'Dispositivos y gadgets electrónicos'),
(2, 'Alimentos', 'Productos comestibles y bebidas'),
(3, 'Libros', 'Libros y material de lectura'),
(4, 'Ropa', 'Prendas de vestir y accesorios'),
(5, 'Hogar', 'Artículos para el hogar'),
(6, 'Electrónica', 'Dispositivos y gadgets electrónicos'),
(7, 'Alimentos', 'Productos comestibles y bebidas'),
(8, 'Libros', 'Libros y material de lectura'),
(9, 'Ropa', 'Prendas de vestir y accesorios'),
(10, 'Hogar', 'Artículos para el hogar'),
(11, 'Electrónica', 'Dispositivos y gadgets electrónicos'),
(12, 'Alimentos', 'Productos comestibles y bebidas'),
(13, 'Libros', 'Libros y material de lectura'),
(14, 'Ropa', 'Prendas de vestir y accesorios'),
(15, 'Hogar', 'Artículos para el hogar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(5) DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT NULL,
  `id_trabajador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `id_producto`, `cantidad`, `precio`, `id_trabajador`) VALUES
(1, 1, 20, 1100.00, 5),
(2, 2, 200, 1.20, 5),
(3, 3, 50, 7.50, 5),
(4, 4, 100, 10.00, 5),
(5, 5, 30, 20.00, 5),
(6, 1, 20, 1100.00, 5),
(7, 2, 200, 1.20, 5),
(8, 3, 50, 7.50, 5),
(9, 4, 100, 10.00, 5),
(10, 5, 30, 20.00, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `monto` decimal(6,2) DEFAULT NULL,
  `metodo_pago` varchar(20) DEFAULT NULL,
  `estado` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(11) NOT NULL,
  `nro_identidad` varchar(11) NOT NULL,
  `razon_social` varchar(130) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `departamento` varchar(20) DEFAULT NULL,
  `provincia` varchar(30) DEFAULT NULL,
  `distrito` varchar(50) DEFAULT NULL,
  `cod_postal` int(5) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `rol` varchar(15) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `estado` int(1) NOT NULL DEFAULT 1,
  `fecha_reg` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id`, `nro_identidad`, `razon_social`, `telefono`, `correo`, `departamento`, `provincia`, `distrito`, `cod_postal`, `direccion`, `rol`, `password`, `estado`, `fecha_reg`) VALUES
(2, '12345678', 'Juan Perez', '987654321', 'juan@example.com', 'Lima', 'Lima', 'Miraflores', 15074, 'Calle 1 #123', 'cliente', NULL, 1, '2025-09-29 09:00:00'),
(3, '87654321', 'Maria Lopez', '123456789', 'maria@example.com', 'Lima', 'Lima', 'San Isidro', 15073, 'Av 2 #456', 'cliente', NULL, 1, '2025-09-29 10:00:00'),
(4, '11223344', 'Pedro Gomez', '555666777', 'pedro@example.com', 'Lima', 'Lima', 'Surco', 15039, 'Jr 3 #789', 'vendedor', 'hashed_password1', 1, '2025-09-28 08:00:00'),
(5, '44332211', 'Ana Ramirez', '888999000', 'ana@example.com', 'Lima', 'Lima', 'La Molina', 15024, 'Calle 4 #101', 'vendedor', 'hashed_password2', 1, '2025-09-28 09:00:00'),
(6, '55667788', 'Admin System', '111222333', 'admin@example.com', 'Lima', 'Lima', 'Lima', 15001, 'Plaza Mayor', 'administrador', 'hashed_admin_pass', 1, '2025-09-27 07:00:00'),
(7, '99887766', 'Tech Suppliers S.A.', '987654322', 'tech@suppliers.com', 'Lima', 'Lima', 'Miraflores', 15074, 'Av Tecnologica 100', 'proveedor', NULL, 1, '2025-09-27 08:30:00'),
(8, '77665544', 'Food Distributors Inc.', '123456788', 'food@dist.com', 'Lima', 'Lima', 'San Isidro', 15073, 'Calle Alimentos 200', 'proveedor', NULL, 1, '2025-09-27 09:30:00'),
(9, '55443322', 'Book Wholesalers Ltd.', '555666778', 'books@whole.com', 'Lima', 'Lima', 'Surco', 15039, 'Jr Libros 300', 'proveedor', NULL, 1, '2025-09-27 10:30:00'),
(10, '33221100', 'Clothing Co. S.A.', '444555666', 'clothing@co.com', 'Lima', 'Lima', 'Lima', 15001, 'Av Moda 400', 'proveedor', NULL, 1, '2025-09-27 11:00:00'),
(11, '22110099', 'Home Essentials Ltd.', '333444555', 'home@essentials.com', 'Lima', 'Lima', 'San Borja', 15036, 'Calle Hogar 500', 'proveedor', NULL, 1, '2025-09-27 12:00:00'),
(12, '12345678', 'Juan Perez', '987654321', 'juan@example.com', 'Lima', 'Lima', 'Miraflores', 15074, 'Calle 1 #123', 'cliente', NULL, 1, '2025-09-29 09:00:00'),
(13, '87654321', 'Maria Lopez', '123456789', 'maria@example.com', 'Lima', 'Lima', 'San Isidro', 15073, 'Av 2 #456', 'cliente', NULL, 1, '2025-09-29 10:00:00'),
(14, '11223344', 'Pedro Gomez', '555666777', 'pedro@example.com', 'Lima', 'Lima', 'Surco', 15039, 'Jr 3 #789', 'vendedor', 'hashed_password1', 1, '2025-09-28 08:00:00'),
(15, '44332211', 'Ana Ramirez', '888999000', 'ana@example.com', 'Lima', 'Lima', 'La Molina', 15024, 'Calle 4 #101', 'vendedor', 'hashed_password2', 1, '2025-09-28 09:00:00'),
(16, '55667788', 'Admin System', '111222333', 'admin@example.com', 'Lima', 'Lima', 'Lima', 15001, 'Plaza Mayor', 'administrador', 'hashed_admin_pass', 1, '2025-09-27 07:00:00'),
(17, '99887766', 'Tech Suppliers S.A.', '987654322', 'tech@suppliers.com', 'Lima', 'Lima', 'Miraflores', 15074, 'Av Tecnologica 100', 'proveedor', NULL, 1, '2025-09-27 08:30:00'),
(18, '77665544', 'Food Distributors Inc.', '123456788', 'food@dist.com', 'Lima', 'Lima', 'San Isidro', 15073, 'Calle Alimentos 200', 'proveedor', NULL, 1, '2025-09-27 09:30:00'),
(19, '55443322', 'Book Wholesalers Ltd.', '555666778', 'books@whole.com', 'Lima', 'Lima', 'Surco', 15039, 'Jr Libros 300', 'proveedor', NULL, 1, '2025-09-27 10:30:00'),
(20, '33221100', 'Clothing Co. S.A.', '444555666', 'clothing@co.com', 'Lima', 'Lima', 'Lima', 15001, 'Av Moda 400', 'proveedor', NULL, 1, '2025-09-27 11:00:00'),
(21, '22110099', 'Home Essentials Ltd.', '333444555', 'home@essentials.com', 'Lima', 'Lima', 'San Borja', 15036, 'Calle Hogar 500', 'proveedor', NULL, 1, '2025-09-27 12:00:00'),
(22, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 1, '2025-09-30 22:44:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `detalle` varchar(100) DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT NULL,
  `stock` int(5) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `codigo`, `nombre`, `detalle`, `precio`, `stock`, `id_categoria`, `fecha_vencimiento`, `imagen`, `id_proveedor`) VALUES
(1, 'PROD001', 'Laptop XYZ', 'Laptop de 15 pulgadas con 16GB RAM', 1500.00, 20, 1, '2026-12-31', 'laptop.jpg', 6),
(2, 'PROD002', 'Chocolate Bar', 'Barra de chocolate oscuro 100g', 2.50, 200, 2, '2025-12-31', 'chocolate.jpg', 7),
(3, 'PROD003', 'Libro Aventura', 'Novela de aventura best-seller', 10.00, 50, 3, NULL, 'libro.jpg', 8),
(4, 'PROD004', 'Camiseta Básica', 'Camiseta de algodón talla M', 15.00, 100, 4, NULL, 'camiseta.jpg', 9),
(5, 'PROD005', 'Lámpara LED', 'Lámpara de mesa LED 10W', 25.00, 30, 5, NULL, 'lampara.jpg', 10),
(6, 'PROD001', 'Laptop XYZ', 'Laptop de 15 pulgadas con 16GB RAM', 1500.00, 20, 1, '2026-12-31', 'laptop.jpg', 6),
(7, 'PROD002', 'Chocolate Bar', 'Barra de chocolate oscuro 100g', 2.50, 200, 2, '2025-12-31', 'chocolate.jpg', 7),
(8, 'PROD003', 'Libro Aventura', 'Novela de aventura best-seller', 10.00, 50, 3, NULL, 'libro.jpg', 8),
(9, 'PROD004', 'Camiseta Básica', 'Camiseta de algodón talla M', 15.00, 100, 4, NULL, 'camiseta.jpg', 9),
(10, 'PROD005', 'Lámpara LED', 'Lámpara de mesa LED 10W', 25.00, 30, 5, NULL, 'lampara.jpg', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `fecha_hora_inicio` datetime DEFAULT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `token` varchar(20) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `estado` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_trabajador` (`id_trabajador`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_trabajador`) REFERENCES `persona` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `persona` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `persona` (`id`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `persona` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
