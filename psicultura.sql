-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-05-2016 a las 17:52:34
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
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
  `idCliente` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
(0, 'pp', 'hjg', '', 'hbgjh@hotmail.com'),
(1, 'matute', 'lareconn', '', 'sac@cas.com.ar');

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
  `cantidad` decimal(5,2) NOT NULL,
  PRIMARY KEY (`iDeposito`),
  KEY `idLote` (`idLote`,`tipoProceso`),
  KEY `tipoProceso` (`tipoProceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_julianos`
--

CREATE TABLE IF NOT EXISTS `dias_julianos` (
  `dia` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `dias_julianos`
--

INSERT INTO `dias_julianos` (`dia`, `fecha`) VALUES
(125, '2016-05-04');

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
  `cantidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`idEntrega`),
  KEY `cliente` (`cliente`,`tipoProceso`,`idLote`),
  KEY `tipoProceso` (`tipoProceso`),
  KEY `idLote` (`idLote`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE IF NOT EXISTS `ingresos` (
  `idIngreso` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` decimal(5,2) NOT NULL,
  PRIMARY KEY (`idIngreso`),
  KEY `idLote` (`idLote`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=12 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE IF NOT EXISTS `procesos` (
  `idProceso` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`idProceso`),
  KEY `idLote` (`idLote`),
  KEY `tipoProceso` (`tipoProceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE IF NOT EXISTS `proveedores` (
  `idProveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `direccion` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`idProveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idProveedor`, `nombre`, `direccion`, `telefono`, `correo`) VALUES
(1, 'el barto', 'lalala', '', 'sam@gmoil.ar'),
(2, 'pp', 'nkl', '', 'da@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposprocesos`
--

CREATE TABLE IF NOT EXISTS `tiposprocesos` (
  `idTipoProceso` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`idTipoProceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `tiposprocesos`
--

INSERT INTO `tiposprocesos` (`idTipoProceso`, `descripcion`) VALUES
(1, 'Sin procesar'),
(2, 'Desespinado Fresco'),
(3, 'Desespinado Congelado'),
(4, 'Eviscerado Fresco'),
(5, 'Eviscerado Congelado');

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
(2, 'mbenditti', '090c36e3bb39377468363197afb3e91b', 'matiasbenditti@gmail.com', NULL, 1);

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
  ADD CONSTRAINT `entregas_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`idCliente`),
  ADD CONSTRAINT `entregas_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`),
  ADD CONSTRAINT `entregas_ibfk_3` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`);

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
