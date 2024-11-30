-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2024 a las 01:46:46
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inmobiliaria_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristica`
--

CREATE TABLE `caracteristica` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadopropiedad`
--

CREATE TABLE `estadopropiedad` (
  `id` int(10) NOT NULL,
  `estado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estadopropiedad`
--

INSERT INTO `estadopropiedad` (`id`, `estado`) VALUES
(1, 'Venta'),
(2, 'Alquiler'),
(3, 'Alquiler Temporario'),
(4, 'Reservado'),
(5, 'Vendida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `geolocalizacion`
--

CREATE TABLE `geolocalizacion` (
  `id` int(11) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `direccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inmobiliarias`
--

CREATE TABLE `inmobiliarias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `duenioInmobiliaria` int(11) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_creacion` date NOT NULL,
  `activo` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `inmobiliarias`
--

INSERT INTO `inmobiliarias` (`id`, `nombre`, `duenioInmobiliaria`, `matricula`, `direccion`, `telefono`, `email`, `fecha_creacion`, `activo`) VALUES
(1, 'Prueba Inmobiliaria', 1, '', 'Provincias Unidas 3251', '3415802283', 'contacto@pruebainmobiliaria.com', '2024-10-31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propiedades`
--

CREATE TABLE `propiedades` (
  `id` int(11) NOT NULL,
  `id_inm` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text,
  `id_img` int(11) NOT NULL,
  `id_geo` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`, `descripcion`) VALUES
(1, 'administrador', '\"Tiene el control total de todo el sistema\"'),
(2, 'empleado', '\"Tiene acceso limitado exclusivamente para administrar estadísticas y dar soporte al cliente\"'),
(3, 'administradorInmobiliaria', '\"Es un rango que se otorga al duenio de una inmobiliaria con el fin de gestionar la misma\"'),
(4, 'corredorInmobiliario', '\"Es un rango que se asigna a un corredor inmobiliario que pertenece a una determinada inmobiliaria para que pueda hacer ABM de propiedades\" '),
(5, 'agenteInmobiliario', '\"Es un rol que permite un modo mas comercial de gestion la transaccion, este mismo solo puede realizar carga de propiedades\"'),
(6, 'cliente', '\"Es un rol que permite gestionar entre un usuario interesado en el mercado inmobiliario y alguna inmobiliaria\"');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id` int(11) NOT NULL,
  `id_propiedad` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_inmobiliaria` int(11) NOT NULL,
  `tipo` enum('venta','alquiler','reserva') NOT NULL,
  `fecha_transaccion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` int(1) NOT NULL DEFAULT '1',
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena`, `fecha_registro`, `activo`, `rol`) VALUES
(1, 'Lisandro', 'lisandrotaiel2@gmail.com', '$2y$10$UBb8z9QS0kICl0acVifEneo4u7rdAQy4mc04Pi38XnvS.xgGrXX5W', '2024-10-24 10:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas_propiedades`
--

CREATE TABLE `visitas_propiedades` (
  `id` int(11) NOT NULL,
  `id_propiedad` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_visita` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caracteristica`
--
ALTER TABLE `caracteristica`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estadopropiedad`
--
ALTER TABLE `estadopropiedad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `geolocalizacion`
--
ALTER TABLE `geolocalizacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inmobiliarias`
--
ALTER TABLE `inmobiliarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_duenio` (`duenioInmobiliaria`);

--
-- Indices de la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_inm` (`id_inm`),
  ADD KEY `id_img` (`id_img`),
  ADD KEY `id_geo` (`id_geo`),
  ADD KEY `id_car` (`id_car`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_propiedad` (`id_propiedad`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_inmobiliaria` (`id_inmobiliaria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `visitas_propiedades`
--
ALTER TABLE `visitas_propiedades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_propiedad` (`id_propiedad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caracteristica`
--
ALTER TABLE `caracteristica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estadopropiedad`
--
ALTER TABLE `estadopropiedad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `geolocalizacion`
--
ALTER TABLE `geolocalizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inmobiliarias`
--
ALTER TABLE `inmobiliarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `propiedades`
--
ALTER TABLE `propiedades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `visitas_propiedades`
--
ALTER TABLE `visitas_propiedades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inmobiliarias`
--
ALTER TABLE `inmobiliarias`
  ADD CONSTRAINT `id_duenio` FOREIGN KEY (`duenioInmobiliaria`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `propiedades`
--
ALTER TABLE `propiedades`
  ADD CONSTRAINT `propiedades_ibfk_1` FOREIGN KEY (`id_inm`) REFERENCES `inmobiliarias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `propiedades_ibfk_2` FOREIGN KEY (`id_img`) REFERENCES `imagenes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `propiedades_ibfk_3` FOREIGN KEY (`id_geo`) REFERENCES `geolocalizacion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `propiedades_ibfk_4` FOREIGN KEY (`id_car`) REFERENCES `caracteristica` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `propiedades_ibfk_5` FOREIGN KEY (`id_estado`) REFERENCES `estadopropiedad` (`id`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transacciones_ibfk_3` FOREIGN KEY (`id_inmobiliaria`) REFERENCES `inmobiliarias` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `rol` FOREIGN KEY (`rol`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `visitas_propiedades`
--
ALTER TABLE `visitas_propiedades`
  ADD CONSTRAINT `visitas_propiedades_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visitas_propiedades_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
