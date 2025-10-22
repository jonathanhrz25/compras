-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2025 a las 21:15:09
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
-- Base de datos: `compras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `requisicion_id` int(11) NOT NULL,
  `producto` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cantidad` varchar(200) NOT NULL,
  `estado` enum('Pendiente','En compra','Adquirido','Rechazado') DEFAULT 'Pendiente',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `requisicion_id`, `producto`, `descripcion`, `cantidad`, `estado`, `updated_at`, `created_at`, `comentarios`) VALUES
(1, 1, 'Tubo Verde', '10mm para cable de red', '50m', 'Rechazado', '2025-09-24 10:09:10', '2025-09-22 12:27:16', ''),
(2, 1, 'Conectores de codo', 'De hierro, tamaño 1/2', '15pz', 'Adquirido', '2025-09-24 10:09:04', '2025-09-22 12:27:55', ''),
(3, 1, 'Cintas de Aislar', '', '6pz', 'Adquirido', '2025-09-22 13:15:41', '2025-09-22 12:28:12', 'Ya contamos con las Cintas'),
(4, 2, 'Computadora Hp', 'Core i5, 8Gb de RAM, 256Gb almacenamiento.', '6', 'Adquirido', '2025-09-24 10:09:19', '2025-09-22 12:29:41', ''),
(5, 2, 'Laptop HP', 'Core i7, 12Gb de RAM, 500Gb almacenamiento.', '3', 'Adquirido', '2025-09-24 10:09:23', '2025-09-22 12:30:24', ''),
(6, 3, 'Memoria RAM 16gB', 'Memoria Ram para pc de escritorio', '2pz', 'Rechazado', '2025-09-22 13:13:06', '2025-09-22 12:56:23', 'Esta memoria a sido rechazada por el conta Roberto.'),
(7, 2, 'NoBreak', 'NoBreak cyberpower para área de compras', '3', 'Adquirido', '2025-09-24 10:09:26', '2025-09-22 12:59:57', ''),
(8, 4, 'NoBreak', 'Cyberpower', '5', 'En compra', '2025-09-24 10:09:30', '2025-09-24 09:50:58', ''),
(9, 4, 'Laptop Hp', 'core i7 12Gb de Ram', '2', 'Pendiente', NULL, '2025-09-24 11:40:16', NULL),
(10, 4, 'Computadora Dell', 'core i7 12Gb de Ram', '1', 'Pendiente', NULL, '2025-09-24 11:40:16', NULL),
(11, 5, 'Tubo Verde', 'Tubo de 3/4', '30', 'Pendiente', NULL, '2025-09-24 11:46:01', NULL),
(12, 5, 'Codos 3/4', 'Codo pvc de conexión ', '30', 'Pendiente', NULL, '2025-09-24 11:46:01', NULL),
(13, 5, 'Tornillos de Cruz', 'Tornilleria de cruz de 3\'', '70', 'Pendiente', NULL, '2025-09-24 11:47:01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisiciones`
--

CREATE TABLE `requisiciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `requisiciones`
--

INSERT INTO `requisiciones` (`id`, `usuario_id`, `fecha`, `nombre`) VALUES
(1, 1, '2025-09-22 18:40:00', NULL),
(2, 1, '2025-09-22 18:43:00', NULL),
(3, 1, '2025-09-22 18:56:23', NULL),
(4, 1, '2025-09-24 15:50:58', NULL),
(5, 1, '2025-09-24 17:46:01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `area` varchar(100) NOT NULL,
  `cedis` varchar(200) NOT NULL,
  `rol` enum('TI','Operador') DEFAULT 'TI'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `area`, `cedis`, `rol`) VALUES
(1, 'Jonathan', '$2y$10$Wsy3hBNnLpByTjAquHxdquCAgFc1F206oYJ/f4n5DRTsWvJgm4eoi', 'SISTEMAS', '', 'TI'),
(2, 'JonathanR', '$2y$10$l.trYKpgxQxVNbbLwiBsC.Axw6nvuW9YoQpP4v1GwK.ZHoHkCHNea', 'SISTEMAS', 'Pachuca', 'Operador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisicion_id` (`requisicion_id`);

--
-- Indices de la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`requisicion_id`) REFERENCES `requisiciones` (`id`);

--
-- Filtros para la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  ADD CONSTRAINT `requisiciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
