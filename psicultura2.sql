-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: localhost    Database: psicultura
-- ------------------------------------------------------
-- Server version	5.7.12-0ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `direccion` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (3,'armo','huh','','jfhfh@hais.com');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depositos`
--

DROP TABLE IF EXISTS `depositos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `depositos` (
  `iDeposito` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `vencimiento` date NOT NULL,
  `cantidad` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`iDeposito`),
  KEY `idLote` (`idLote`,`tipoProceso`),
  KEY `tipoProceso` (`tipoProceso`),
  CONSTRAINT `depositos_ibfk_1` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`),
  CONSTRAINT `depositos_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depositos`
--

LOCK TABLES `depositos` WRITE;
/*!40000 ALTER TABLE `depositos` DISABLE KEYS */;
/*!40000 ALTER TABLE `depositos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entregas`
--

DROP TABLE IF EXISTS `entregas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entregas` (
  `idEntrega` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fichaExpedicion` varchar(15) CHARACTER SET latin1 NOT NULL,
  `idLote` int(11) NOT NULL,
  `cantidad` decimal(7,2) DEFAULT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`idEntrega`),
  KEY `cliente` (`cliente`,`tipoProceso`,`idLote`),
  KEY `tipoProceso` (`tipoProceso`),
  KEY `idLote` (`idLote`),
  CONSTRAINT `entregas_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`) ON UPDATE CASCADE,
  CONSTRAINT `entregas_ibfk_3` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`) ON UPDATE CASCADE,
  CONSTRAINT `entregas_ibfk_4` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`idCliente`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entregas`
--

LOCK TABLES `entregas` WRITE;
/*!40000 ALTER TABLE `entregas` DISABLE KEYS */;
/*!40000 ALTER TABLE `entregas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingresos`
--

DROP TABLE IF EXISTS `ingresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingresos` (
  `idIngreso` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`idIngreso`),
  KEY `idLote` (`idLote`),
  CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresos`
--

LOCK TABLES `ingresos` WRITE;
/*!40000 ALTER TABLE `ingresos` DISABLE KEYS */;
INSERT INTO `ingresos` VALUES (30,89651,'2016-05-14',45.00);
/*!40000 ALTER TABLE `ingresos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotes`
--

DROP TABLE IF EXISTS `lotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotes` (
  `id_lote` int(11) NOT NULL,
  `proveedor` int(11) NOT NULL,
  PRIMARY KEY (`id_lote`),
  KEY `proveedor` (`proveedor`),
  CONSTRAINT `lotes_ibfk_1` FOREIGN KEY (`proveedor`) REFERENCES `proveedores` (`idProveedor`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotes`
--

LOCK TABLES `lotes` WRITE;
/*!40000 ALTER TABLE `lotes` DISABLE KEYS */;
INSERT INTO `lotes` VALUES (89651,5);
/*!40000 ALTER TABLE `lotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procesos`
--

DROP TABLE IF EXISTS `procesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procesos` (
  `idProceso` int(11) NOT NULL AUTO_INCREMENT,
  `idLote` int(11) NOT NULL,
  `tipoProceso` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`idProceso`),
  KEY `idLote` (`idLote`),
  KEY `tipoProceso` (`tipoProceso`),
  CONSTRAINT `procesos_ibfk_1` FOREIGN KEY (`idLote`) REFERENCES `lotes` (`id_lote`),
  CONSTRAINT `procesos_ibfk_2` FOREIGN KEY (`tipoProceso`) REFERENCES `tiposprocesos` (`idTipoProceso`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procesos`
--

LOCK TABLES `procesos` WRITE;
/*!40000 ALTER TABLE `procesos` DISABLE KEYS */;
INSERT INTO `procesos` VALUES (14,89651,5,'2016-05-14',12.00);
/*!40000 ALTER TABLE `procesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `idProveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `direccion` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idProveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (5,'Arnold Swazsenegger','BÂ° Frutillar - Depto 3','294-154655052','arnold@swazsenegger.com.mx'),(6,'Tito Bezzone','Diagonal Capraro 359','011-1589658741','titoelbambino@yahoo.com');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiposprocesos`
--

DROP TABLE IF EXISTS `tiposprocesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiposprocesos` (
  `idTipoProceso` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`idTipoProceso`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiposprocesos`
--

LOCK TABLES `tiposprocesos` WRITE;
/*!40000 ALTER TABLE `tiposprocesos` DISABLE KEYS */;
INSERT INTO `tiposprocesos` VALUES (1,'Desespinada Fresca'),(2,'Desesperada Congelada'),(3,'Eviscerada Fresca'),(4,'Eviscerada Congelada'),(5,'Fileteada Fresca'),(6,'Fileteada Congelada'),(7,'Fileteada Congelada IQF'),(8,'Fileteada Congelada Block'),(9,'Sin Procesar');
/*!40000 ALTER TABLE `tiposprocesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `ID` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(180) DEFAULT NULL,
  `password` varchar(180) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL,
  `id_extreme` varchar(180) DEFAULT NULL,
  `permisos` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'mbenditti','090c36e3bb39377468363197afb3e91b','matias@gmail.com.ar',NULL,1),(4,'joy','c2c8e798aecbc26d86e4805114b03c51','joy@ana.com',NULL,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-14  2:28:59
