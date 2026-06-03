-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2025 a las 21:06:18
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_g3`
--

CREATE TABLE `direccion_g3` (
  `id_persona` int(11) NOT NULL,
  `provincia` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `localidad` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `calle` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `altura` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `direccion_g3`
--

INSERT INTO `direccion_g3` (`id_persona`, `provincia`, `localidad`, `calle`, `altura`) VALUES
(8, 'CABA', 'Caballito', 'RIVADAVIA', 5896),
(9, 'CABA', 'Caballito', 'Rivadavia', 5170),
(10, 'CABA', 'Caballito', 'RIVADAVIA', 5897),
(23, 'CABA', 'Caballito', 'La Pampa', 1244),
(26, 'CABA', 'Caballito', 'RIVADAVIA', 1234);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascota_g3`
--

CREATE TABLE `mascota_g3` (
  `id_mascota` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_de_nacimiento` datetime NOT NULL,
  `edad` int(11) NOT NULL,
  `raza` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tamanio` enum('pequeño','mediano','grande','') COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `imagen_url` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mascota_g3`
--

INSERT INTO `mascota_g3` (`id_mascota`, `id_persona`, `nombre`, `fecha_de_nacimiento`, `edad`, `raza`, `tamanio`, `color`, `imagen_url`) VALUES
(3, 8, 'Luna', '2020-09-09 00:00:00', 5, 'Bombay', 'mediano', 'negro', ''),
(4, 8, 'Copito', '2008-08-18 16:00:00', 17, 'Labrador', 'mediano', 'dorado', NULL),
(5, 8, 'Olivia', '2007-08-18 00:00:00', 18, 'labrador', 'grande', 'rubio', 'http://localhost/proyecto_adiestramiento_tahito/uploads/mascotas/Olivia_8_1762107447.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona_g3`
--

CREATE TABLE `persona_g3` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_de_usuario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `rol` enum('cliente','trabajador','admin','') COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `persona_g3`
--

INSERT INTO `persona_g3` (`id_persona`, `nombre`, `apellido`, `nombre_de_usuario`, `correo`, `password`, `rol`, `telefono`) VALUES
(8, 'Valeria', 'Moreno', 'valeria', 'valeria@gmail.com', '$2y$10$AerHgsL7KS/EbH9uNImIVedOJqgwsVFBABxIsTOfSgmNbo32czmpq', 'cliente', '1136915571'),
(9, 'Pepito', 'Perez', 'pepito_perez', 'pepito@gmail.com', '$2y$10$1XnaHFP7s195YR.C47osjeIeGhqRNBFktw9mNmT8EhFXlQyGqzrWy', 'trabajador', '1122334455'),
(10, 'admin1', 'admin1', 'admin1', 'valeriavable2000@gmail.com', '$2y$10$4/y3JLeE.7QLeEuh50YqROA7O4Dp6AFSMUE62ID3Yj8sruEMxBz8O', 'admin', '1136915572'),
(23, 'Arlesa', 'Frosa', 'arlesa', 'arlesafrosa@gmail.com', '$2y$10$4X6jZ3TwTZbxlPeFx0yglOyrP5gnF1/YMFpNXcm29e8CumvdJy/BS', 'trabajador', '1136915572'),
(26, 'Perencejo', 'Perez', 'perencejo', 'valengaming3020@gmail.com', '$2y$10$q0DPJAn2eWMr5e.2eZFu2urqogQMnxLdIrkUCZ6wRNcvX.e0EjjZG', 'cliente', '1122334455');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones_g3`
--

CREATE TABLE `postulaciones_g3` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `puesto_aplicado` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `cv_nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `cv_contenido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_postulacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `cv_ruta` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `postulaciones_g3`
--

INSERT INTO `postulaciones_g3` (`id`, `nombre`, `apellido`, `correo`, `puesto_aplicado`, `cv_nombre`, `cv_contenido`, `fecha_postulacion`, `cv_ruta`) VALUES
(1, 'Yuskeyli', 'Avila', 'yavila@buquebus.com', 'Diseñador Gráfico Senior', 'P_1760095986575.pdf', '', '2025-10-12 22:48:33', 'uploads/cv/cv_1760320113_68ec5a7148920.pdf'),
(2, 'Yuskeyli', 'Avila', 'yavila@buquebus.com', 'Diseñador Gráfico Senior', 'P_1760095986575.pdf', '', '2025-10-12 22:52:46', '../uploads/cv/cv_1760320366_68ec5b6eb93a5.pdf'),
(3, 'Valeria', 'Moreno', 'valeriavable2000@gmail.com', 'Desarrollador Web Junior', 'Valeria Moreno CV.pdf', '', '2025-10-13 20:00:51', '../uploads/cv/cv_1760396451_68ed84a3cceeb.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_g3`
--

CREATE TABLE `servicio_g3` (
  `id_servicio` int(11) NOT NULL,
  `tipo_de_servicio` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `id_trabajador` int(11) NOT NULL,
  `horario` datetime NOT NULL,
  `comentarios` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `monto` decimal(10,0) NOT NULL,
  `pagado` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `servicio_g3`
--

INSERT INTO `servicio_g3` (`id_servicio`, `tipo_de_servicio`, `id_mascota`, `id_trabajador`, `horario`, `comentarios`, `monto`, `pagado`) VALUES
(36, 'Adiestramiento canino', 3, 9, '2025-09-23 10:00:00', 'N/A', '0', 1),
(37, 'Paseo canino', 3, 9, '2025-10-02 09:00:00', 'N/A', '0', 1),
(38, 'Adiestramiento canino', 4, 9, '2025-10-05 11:00:00', 'Jamon', '0', 1),
(39, 'Adiestramiento canino', 3, 9, '2025-10-06 10:00:00', 'N/A', '0', 0),
(40, 'Adiestramiento canino', 4, 9, '2025-10-14 11:00:00', 'no', '0', 0),
(41, 'Adiestramiento canino', 4, 9, '2025-10-18 15:00:00', 'd', '0', 0),
(42, 'Adiestramiento canino', 3, 9, '2025-10-18 16:00:00', 'arroz', '0', 0),
(43, 'Adiestramiento canino', 3, 9, '2025-10-18 17:00:00', 'queso y jamon', '0', 0),
(44, 'Baño y peluquería', 4, 9, '2025-10-21 09:00:00', 'no', '0', 0),
(45, 'Paseo canino', 4, 9, '2025-10-25 16:00:00', 'a', '4551', 0),
(46, 'Baño y peluquería', 5, 9, '2025-10-25 17:00:00', 'a', '4551', 0),
(47, 'Paseo canino', 3, 22, '2025-10-28 10:00:00', 'N/A', '4551', 1),
(48, 'Baño y peluquería', 3, 23, '2025-10-30 10:00:00', 'N/A', '4551', 1),
(49, 'Adiestramiento canino', 3, 9, '2025-11-02 16:00:00', 'a', '6751', 0),
(50, 'Adiestramiento canino', 5, 9, '2025-11-02 17:00:00', 'a', '6751', 1),
(51, 'Paseo canino', 3, 23, '2025-11-03 09:00:00', 'N/A', '4551', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_de_servicio_g3`
--

CREATE TABLE `tipo_de_servicio_g3` (
  `id_tipo_servicio` int(11) NOT NULL,
  `tipo_de_servicio` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `precio_servicio` float NOT NULL,
  `imagen_servicio` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_de_servicio_g3`
--

INSERT INTO `tipo_de_servicio_g3` (`id_tipo_servicio`, `tipo_de_servicio`, `descripcion`, `precio_servicio`, `imagen_servicio`) VALUES
(5, 'Paseo canino', 'Servicio a domicilio de paseo canino. Horarios flexibles', 4550.75, 'paseo_img.png'),
(6, 'Adiestramiento canino', 'Servicio de adiestramiento canino a domicilio. Modalidad de suscripción y cita disponibles.', 6750.99, 'dog-training.png'),
(7, 'Baño y peluquería', 'Servicio de baño, secado y corte a domicilio', 5599.99, 'banio_img.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores_g3`
--

CREATE TABLE `trabajadores_g3` (
  `id_persona` int(11) NOT NULL,
  `rol` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_de_servicio` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pass_app` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo_host` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `trabajadores_g3`
--

INSERT INTO `trabajadores_g3` (`id_persona`, `rol`, `tipo_de_servicio`, `pass_app`, `correo_host`) VALUES
(9, 'trabajador', 'Adiestramiento canino', NULL, NULL),
(10, 'admin', NULL, 'B6UVDn@3pX', 'grupos@serviciosya.com.ar'),
(23, 'trabajador', 'Paseo canino', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `direccion_g3`
--
ALTER TABLE `direccion_g3`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `mascota_g3`
--
ALTER TABLE `mascota_g3`
  ADD PRIMARY KEY (`id_mascota`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `persona_g3`
--
ALTER TABLE `persona_g3`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `postulaciones_g3`
--
ALTER TABLE `postulaciones_g3`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicio_g3`
--
ALTER TABLE `servicio_g3`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `id_persona` (`id_trabajador`);

--
-- Indices de la tabla `tipo_de_servicio_g3`
--
ALTER TABLE `tipo_de_servicio_g3`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- Indices de la tabla `trabajadores_g3`
--
ALTER TABLE `trabajadores_g3`
  ADD PRIMARY KEY (`id_persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mascota_g3`
--
ALTER TABLE `mascota_g3`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `persona_g3`
--
ALTER TABLE `persona_g3`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `postulaciones_g3`
--
ALTER TABLE `postulaciones_g3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio_g3`
--
ALTER TABLE `servicio_g3`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `tipo_de_servicio_g3`
--
ALTER TABLE `tipo_de_servicio_g3`
  MODIFY `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
