-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2025 a las 20:14:13
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.30

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
(1, 1, '2025-03-04 18:00:00', NULL),
(2, 1, '2025-03-15 18:00:00', ''),
(3, 1, '2025-03-19 18:00:00', ''),
(4, 1, '2025-03-20 18:00:00', ''),
(5, 1, '2025-03-21 18:00:00', ''),
(6, 1, '2025-03-26 18:00:00', ''),
(7, 1, '2025-04-02 18:00:00', ''),
(8, 1, '2025-04-01 18:00:00', ''),
(9, 1, '2025-04-11 18:00:00', ''),
(10, 1, '2025-04-25 18:00:00', ''),
(11, 1, '2025-05-09 18:00:00', ''),
(12, 1, '2025-05-13 18:00:00', ''),
(13, 1, '2025-05-15 18:00:00', ''),
(14, 1, '2025-05-16 18:00:00', ''),
(15, 1, '2025-05-23 18:00:00', ''),
(16, 1, '2025-05-30 18:00:00', ''),
(17, 1, '2025-06-05 18:00:00', ''),
(18, 1, '2025-06-06 18:00:00', ''),
(19, 1, '2025-06-11 18:00:00', ''),
(20, 1, '2025-06-19 18:00:00', ''),
(21, 1, '2025-06-23 18:00:00', ''),
(22, 1, '2025-07-09 18:00:00', ''),
(23, 1, '2025-07-18 18:00:00', ''),
(24, 1, '2025-07-21 18:00:00', ''),
(25, 1, '2025-07-23 18:00:00', ''),
(26, 1, '2025-07-25 18:00:00', ''),
(27, 1, '2025-07-30 18:00:00', ''),
(28, 1, '2025-07-31 18:00:00', ''),
(29, 1, '2025-08-01 18:00:00', ''),
(30, 1, '2025-08-08 18:00:00', ''),
(31, 1, '2025-08-07 18:00:00', ''),
(32, 1, '2025-08-12 18:00:00', ''),
(33, 1, '2025-08-14 18:00:00', ''),
(34, 1, '2025-08-15 18:00:00', ''),
(35, 1, '2025-08-28 18:00:00', ''),
(36, 1, '2025-09-04 18:00:00', ''),
(37, 1, '2025-09-11 18:00:00', ''),
(38, 1, '2025-09-13 18:00:00', ''),
(39, 1, '2025-09-22 18:00:00', ''),
(40, 1, '2025-09-24 18:00:00', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `requisiciones`
--
ALTER TABLE `requisiciones`
  ADD CONSTRAINT `requisiciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
