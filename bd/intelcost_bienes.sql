-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-02-2021 a las 04:09:57
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `intelcost_bienes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `savedgoods`
--

CREATE TABLE `savedgoods` (
  `savedgood_id` int(11) NOT NULL,
  `Ciudad` varchar(100) NOT NULL,
  `Codigo_Postal` varchar(10) NOT NULL,
  `Direccion` varchar(100) NOT NULL,
  `good_id` int(11) NOT NULL,
  `Precio` int(11) NOT NULL,
  `Telefono` varchar(20) NOT NULL,
  `Tipo` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `savedgoods`
--

INSERT INTO `savedgoods` (`savedgood_id`, `Ciudad`, `Codigo_Postal`, `Direccion`, `good_id`, `Precio`, `Telefono`, `Tipo`, `user_id`) VALUES
(40, 'Los Angeles', '94526-134', '347-866 Laoreet Road', 4, 16048, '997-640-8188', 'Casa de Campo', NULL),
(42, 'Orlando', '210020', '672-9576 Consectetuer Road', 6, 64370, '355-601-5749', 'Casa de Campo', NULL),
(43, 'Orlando', '16794', '549-5766 Sodales St.', 7, 2846, '557-228-6918', 'Casa', NULL),
(44, 'Washington', '70689', 'P.O. Box 847, 2589 In Av.', 8, 60951, '390-713-8687', 'Apartamento', NULL),
(45, 'Orlando', 'P0C 0G3', '175-4344 Nec, Ave', 9, 58902, '578-170-6179', 'Casa de Campo', NULL),
(46, 'New York', '85328', 'Ap #549-7395 Ut Rd.', 1, 30746, '334-052-0954', 'Casa', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `savedgoods`
--
ALTER TABLE `savedgoods`
  ADD PRIMARY KEY (`savedgood_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `savedgoods`
--
ALTER TABLE `savedgoods`
  MODIFY `savedgood_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
