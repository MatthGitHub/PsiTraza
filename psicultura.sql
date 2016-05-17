-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2016 a las 04:37:14
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `psicultura`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `direccion` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
(1, 'Truchas Bariloche', 'Centro Cultivo Ruta 237 km 1570', '0111564259497', 'truchasbariloche@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `depositos`
--

CREATE TABLE IF NOT EXISTS `depositos` (
  `iDeposito` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `vencimiento` date NOT NULL,
  `cantidad` decimal(7,2) NOT NULL,
  PRIMARY KEY (`iDeposito`),
  KEY `idLote` (`idLote`,`tipoProceso`),
  KEY `tipoProceso` (`tipoProceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `depositos`
--

INSERT INTO `depositos` (`iDeposito`, `idLote`, `tipoProceso`, `fecha`, `vencimiento`, `cantidad`) VALUES
(1, 120611, 8, '2016-05-14', '2017-05-14', '500.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas`
--

CREATE TABLE IF NOT EXISTS `entregas` (
  `idEntrega` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fichaExpedicion` varchar(15) CHARACTER SET latin1 NOT NULL,
  `idLote` int(11) NOT NULL,
  `cantidad` decimal(7,2) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`idEntrega`),
  KEY `cliente` (`cliente`,`tipoProceso`,`idLote`),
  KEY `tipoProceso` (`tipoProceso`),
  KEY `idLote` (`idLote`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `entregas`
--

INSERT INTO `entregas` (`idEntrega`, `cliente`, `tipoProceso`, `fichaExpedicion`, `idLote`, `cantidad`, `fecha`) VALUES
(1, 1, 5, '1', 134631, '775.00', '2016-05-14'),
(2, 1, 8, '1432', 120611, '500.00', '2016-05-14'),
(3, 1, 8, '1234', 120611, '43.00', '2016-05-14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `idIngreso` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` decimal(7,2) NOT NULL,
  PRIMARY KEY (`idIngreso`),
  KEY `idLote` (`idLote`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`idIngreso`, `idLote`, `fecha`, `cantidad`) VALUES
(1, 134631, '2016-05-14', '1499.00'),
(2, 1631, '2016-05-14', '1000.00'),
(3, 120611, '2016-05-14', '1000.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE IF NOT EXISTS `lotes` (
  `id_lote` int(11) NOT NULL,
  `proveedor` int(11) NOT NULL,
  PRIMARY KEY (`id_lote`),
  KEY `proveedor` (`proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id_lote`, `proveedor`) VALUES
(120611, 1),
(1631, 3),
(134631, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE IF NOT EXISTS `procesos` (
  `idProceso` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` decimal(7,2) NOT NULL,
  PRIMARY KEY (`idProceso`),
  KEY `idLote` (`idLote`),
  KEY `tipoProceso` (`tipoProceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `procesos`
--

INSERT INTO `procesos` (`idProceso`, `idLote`, `tipoProceso`, `fecha`, `cantidad`) VALUES
(2, 134631, 5, '2016-05-14', '775.00'),
(4, 120611, 8, '2016-05-14', '500.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE IF NOT EXISTS `proveedores` (
  `idProveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `direccion` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idProveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idProveedor`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
(1, 'Manila', 'Centro Cultivo Ruta 237 km 1571,9', '2944605915', 'info@manilapatagonia.com'),
(3, 'Truchas Bariloche', 'Centro Cultivo Ruta 237 km 1570', '0111564259497', 'truchasbariloche@gmail.com'),
(4, 'Truchas Sayhueque', 'Centro Cultivo Ruta 237 km 1567,6', '154501411', 'sayhuequetruchas@yahoo.com.ar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposprocesos`
--

CREATE TABLE IF NOT EXISTS `tiposprocesos` (
  `idTipoProceso` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`idTipoProceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `tiposprocesos`
--

INSERT INTO `tiposprocesos` (`idTipoProceso`, `descripcion`) VALUES
(1, 'Desespinada Fresca'),
(2, 'Desesperada Congelada'),
(3, 'Eviscerada Fresca'),
(4, 'Eviscerada Congelada'),
(5, 'Fileteada Fresca'),
(6, 'Fileteada Congelada'),
(7, 'Fileteada Congelada IQF'),
(8, 'Fileteada Congelada Block'),
(9, 'Sin Procesar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(180) DEFAULT NULL,
  `password` varchar(180) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL,
  `id_extreme` varchar(180) DEFAULT NULL,
  `permisos` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `username`, `password`, `email`, `id_extreme`, `permisos`) VALUES
(1, 'mbenditti', '090c36e3bb39377468363197afb3e91b', 'matias@gmail.com.ar', NULL, 1),
(4, 'aslica', 'fdf169558242ee051cca1479770ebac3', 'agustina@manilapatagonia.com', NULL, 1),
(5, 'mslica', '0804048efcb1f0b3c2f18a4412b1016c', 'mariano@manilapatagonia.com', NULL, 0);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `depositos`
--
ALTER TABLE `depositos`
  ADD CONSTRAINT `depositos_ibfk_1` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`),
  ADD CONSTRAINT `depositos_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`);

--
-- Filtros para la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `entregas_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entregas_ibfk_3` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entregas_ibfk_4` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`idCliente`);

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`);

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `lotes_ibfk_1` FOREIGN KEY (`proveedor`) REFERENCES `proveedores` (`idProveedor`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `procesos`
--
ALTER TABLE `procesos`
  ADD CONSTRAINT `procesos_ibfk_1` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`),
  ADD CONSTRAINT `procesos_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
