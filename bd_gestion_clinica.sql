-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-10-2025 a las 08:55:01
-- Versión del servidor: 8.0.18
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion_clinica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `motivo_consulta` text COLLATE utf8mb4_general_ci,
  `estado` enum('pendiente','atendida','cancelada') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_paciente`, `id_medico`, `fecha_cita`, `hora_cita`, `motivo_consulta`, `estado`) VALUES
(23, 18, 20, '2025-07-14', '07:00:00', 'Tengo mareos , vómitos y dolor de cabeza', 'pendiente'),
(24, 4, 20, '2025-07-14', '07:30:00', 'Dolor de cuello en exceso. Mareos y visión nublosa', 'atendida'),
(25, 5, 17, '2025-07-14', '07:00:00', 'Granitos con pus y dolor', 'atendida'),
(26, 4, 24, '2025-07-18', '07:00:00', 'Dolor de estomago y vomitos. Menor de 6 años', 'atendida'),
(27, 5, 24, '2025-07-18', '07:30:00', 'Malestar estomacal ', 'pendiente'),
(35, 24, 24, '2025-07-18', '08:00:00', 'prueba sintomas', 'pendiente'),
(36, 26, 20, '2025-08-29', '10:30:00', 'dolor de garganta', 'pendiente'),
(37, 26, 23, '2025-10-03', '16:00:00', 'Problemas familiares', 'pendiente'),
(38, 4, 20, '2025-10-14', '10:00:00', 'Dolor de estomago', 'pendiente'),
(39, 4, 26, '2025-10-03', '12:30:00', 'Dolor de estomago', 'atendida'),
(40, 26, 20, '2025-10-14', '14:30:00', 'Dolor de cabeza', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `clave` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`clave`, `valor`) VALUES
('correo', 'info@clinicavida.com'),
('horario_atencion', 'Lunes a Viernes, 7am - 9pm'),
('nombre_clinica', 'Clínica Salud '),
('telefono', '987654321');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad_medica`
--

CREATE TABLE `disponibilidad_medica` (
  `id_disponibilidad` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') COLLATE utf8mb4_general_ci NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad_medica`
--

INSERT INTO `disponibilidad_medica` (`id_disponibilidad`, `id_medico`, `fecha`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(6, 20, '2025-08-29', 'Lunes', '07:00:00', '17:00:00'),
(7, 17, '2025-10-08', 'Lunes', '07:00:00', '16:00:00'),
(8, 20, '2025-10-14', 'Lunes', '07:00:00', '18:00:00'),
(9, 17, '2025-07-14', 'Lunes', '07:00:00', '18:00:00'),
(10, 24, '2025-07-18', 'Lunes', '07:00:00', '18:00:00'),
(12, 25, '2025-10-03', 'Lunes', '10:00:00', '15:00:00'),
(13, 23, '2025-10-03', 'Lunes', '14:30:00', '19:30:00'),
(14, 26, '2025-10-03', 'Lunes', '10:30:00', '20:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`) VALUES
(23, 'Medicina General'),
(24, 'Dermatología'),
(25, 'Psicología'),
(26, 'Pediatría'),
(28, 'Ginecología'),
(29, 'Odontologia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historias_clinicas`
--

CREATE TABLE `historias_clinicas` (
  `id_historia` int(11) NOT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `id_paciente` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `motivo_consulta` text COLLATE utf8mb4_general_ci NOT NULL,
  `diagnostico` text COLLATE utf8mb4_general_ci NOT NULL,
  `tratamiento` text COLLATE utf8mb4_general_ci NOT NULL,
  `id_medico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historias_clinicas`
--

INSERT INTO `historias_clinicas` (`id_historia`, `id_cita`, `id_paciente`, `fecha_registro`, `motivo_consulta`, `diagnostico`, `tratamiento`, `id_medico`) VALUES
(26, 23, 18, '2025-07-13', 'Tengo mareos , vómitos y dolor de cabeza', '', '', 20),
(27, 24, 4, '2025-07-13', 'Dolor de cuello en exceso. Mareos y visión nublosa', 'Golpe', 'Pomada', 20),
(28, 25, 5, '2025-07-13', 'Granitos con pus y dolor', 'Infección', 'Crema', 17),
(34, NULL, 26, '2025-08-29', 'dolor de garganta', '', '', 20),
(35, 37, 26, '2025-10-03', 'Problemas familiares', '', '', 23),
(36, 38, 4, '2025-10-03', 'Dolor de estomago', '', '', 20),
(37, 39, 4, '2025-10-03', 'Dolor de estomago', 'intoxicación alimenticia', 'Comer porciones más pequeñas con más frecuencia', 26),
(39, NULL, 26, '2025-10-14', 'Dolor de cabeza', '', '', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_consultas`
--

CREATE TABLE `logs_consultas` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_accion` enum('consulta','registro','edicion','eliminacion') COLLATE utf8mb4_general_ci NOT NULL,
  `tabla_afectada` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_afectado` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id_medico` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cmp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id_medico`, `id_usuario`, `nombres`, `apellidos`, `cmp`, `id_especialidad`, `telefono`, `direccion`, `correo`) VALUES
(17, 1, 'Pedro', 'Torres Balvin', '1314', 24, '999555666', 'Av Las garnolias Mz K lt 15', 'pedro_doctor@hotmail.com'),
(20, 1, 'Anita Lusmila', 'Perez de la Torre', '1214', 23, '985687458', 'Av las magnolias151699', 'anadoctora@hotmail.com'),
(22, 1, 'Carlos Andrés', 'Curtillo Agnes', '5859', 23, '900300210', 'Av los frescos Mz L, La Molina', 'carlosdoctor@gmail.com'),
(23, 1, 'Amy Linn', 'Yun U', '8963', 25, '985689326', 'Av las begonias,Mz F, Lote 16, Surco', 'amydoctora@gmail.com'),
(24, 1, 'Pedro Rafael', 'Perez Gómez', '48596', 26, '985687458', 'Av las casuarinas Mz L Miraflores', 'pedrodoctor@gmail.com'),
(25, 1, 'Jorge Gabriel', 'Velez Armas', '8965', 23, '954361087', 'Jr. las aguilas 2025', 'jorgedoctor@gmail.com'),
(26, 1, 'Mario ', 'Rojas Melendez', '5782', 23, '963147524', 'Jr. velazquez 1223 ', 'mariodoctor@gmail.com'),
(27, 1, 'Gabriel Jose', 'Martinez Santos', '4578', 29, '963145338', 'Jr. los girasoles 2546', 'gabodoctor@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `dni` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('M','F') COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `dni`, `nombres`, `apellidos`, `fecha_nacimiento`, `sexo`, `direccion`, `telefono`, `correo`, `id_usuario`) VALUES
(4, '72516784', 'Marco Juan', 'Prado Portillo', '1998-05-15', 'M', 'AV LOS TERCEROS', '952647158', 'marco111@gmail.com', NULL),
(5, '78548154', 'Juan Manuel', 'Prado Manillo', '2025-05-14', 'F', 'Av Las Begonias Mz C Lote 20 a 2 cuadras antes del parque Pablo Patrón', '957589039', 'estrella111@hotmail.com', NULL),
(18, '16124295', 'Cornelia Anita', 'Mendoza Gutierrez', '1980-09-17', 'F', 'Av. Los nogales 145 Santa Anita', '987855468', 'corneliapaciente@gmail.com', 14),
(24, '77788899', 'Prueba', 'Prueba Prueba', '1996-01-22', 'F', 'dirección prueba', '999888555', 'prueba@gmail.com', 17),
(25, '75842015', 'Maribel', 'Perez Cueva', '1997-01-15', 'F', 'Prueba direccion', '985698789', 'maribelpaciente@gmail.com', 18),
(26, '72279251', 'Christian Manuel', 'Rojas Julcarima', '1999-11-30', 'M', 'Jr. las guayabas 2075 block b 508, Los olivos', '987358906', 'rojaschristian222@gmail.com', 19),
(27, '21276883', 'Clelia Haydee', 'Julcarima Martinez', '1965-07-11', 'F', 'Jr. Micaela 2579', '934512079', 'clelia11@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id_reporte` int(11) NOT NULL,
  `titulo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contenido` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_generado` datetime NOT NULL,
  `generado_por` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('admin','paciente','medico','administrativo') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena`, `rol`) VALUES
(1, 'admin', '1234', 'admin'),
(4, 'marita@hotmail.com', '123456', 'paciente'),
(13, 'marcopacizumilla@hotmail.com', '123456', 'paciente'),
(14, 'corneliapaciente@gmail.com', '123456', 'paciente'),
(17, 'prueba@gmail.com', '123456', 'paciente'),
(18, 'maribelpaciente@gmail.com', '123456', 'paciente'),
(19, 'rojaschristian222@gmail.com', '123456', 'paciente'),
(20, 'jvelez', '12345', 'medico'),
(21, 'admin2', '1234', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_medico` (`id_medico`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `disponibilidad_medica`
--
ALTER TABLE `disponibilidad_medica`
  ADD PRIMARY KEY (`id_disponibilidad`),
  ADD KEY `id_medico` (`id_medico`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `historias_clinicas`
--
ALTER TABLE `historias_clinicas`
  ADD PRIMARY KEY (`id_historia`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_medico` (`id_medico`),
  ADD KEY `fk_historia_cita` (`id_cita`);

--
-- Indices de la tabla `logs_consultas`
--
ALTER TABLE `logs_consultas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id_medico`),
  ADD UNIQUE KEY `cmp` (`cmp`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `fk_usuario` (`id_usuario`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `generado_por` (`generado_por`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `disponibilidad_medica`
--
ALTER TABLE `disponibilidad_medica`
  MODIFY `id_disponibilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `historias_clinicas`
--
ALTER TABLE `historias_clinicas`
  MODIFY `id_historia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `logs_consultas`
--
ALTER TABLE `logs_consultas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`);

--
-- Filtros para la tabla `disponibilidad_medica`
--
ALTER TABLE `disponibilidad_medica`
  ADD CONSTRAINT `disponibilidad_medica_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`);

--
-- Filtros para la tabla `historias_clinicas`
--
ALTER TABLE `historias_clinicas`
  ADD CONSTRAINT `fk_historia_cita` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  ADD CONSTRAINT `historias_clinicas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `historias_clinicas_ibfk_2` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`);

--
-- Filtros para la tabla `logs_consultas`
--
ALTER TABLE `logs_consultas`
  ADD CONSTRAINT `logs_consultas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `fk_paciente_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`generado_por`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
