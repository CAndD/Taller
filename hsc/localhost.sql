-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 11, 2011 at 10:49 PM
-- Server version: 5.1.53
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hsc`
--
DROP DATABASE `hsc`;
CREATE DATABASE `hsc` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `hsc`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `admin_login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_login`(username VARCHAR(40),password VARCHAR(32))
BEGIN
  SELECT u.Nombre_Usuario,u.Nombre
   FROM Usuario AS u
  WHERE u.Nombre_Usuario = username AND u.Password = password AND u.Id_tipo = 2;
END$$

DROP PROCEDURE IF EXISTS `agregar_carrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `agregar_carrera`(cod VARCHAR(9),nombre VARCHAR(100),period INT,semestre INT)
BEGIN
  INSERT INTO Carrera(Codigo,Nombre_Carrera,Periodo,Numero) VALUES (cod,nombre,period,semestre);
END$$

DROP PROCEDURE IF EXISTS `agregar_carrera2`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `agregar_carrera2`(cod VARCHAR(7),nombre VARCHAR(100))
BEGIN
  INSERT INTO Carrera(Codigo,RUT_Jefe_Carrera,Nombre_Carrera) VALUES (cod,'0',nombre);
END$$

DROP PROCEDURE IF EXISTS `agregar_jefe_carrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `agregar_jefe_carrera`(rut VARCHAR(10),nombre VARCHAR(40),nombreusuario VARCHAR(40),pass VARCHAR(32))
BEGIN
  INSERT INTO Usuario(Nombre_Usuario,RUT,Nombre,Password,Id_Tipo) VALUES (nombreusuario,rut,nombre,pass,1);
END$$

DROP PROCEDURE IF EXISTS `agregar_ramo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `agregar_ramo`(codigoRamo VARCHAR(6),nombreRamo VARCHAR(50),hTeoricas INT,hAyudantia INT,hLaboratorio INT,hTaller INT,credito INT)
BEGIN
  INSERT INTO Ramo(Codigo,Nombre,Teoria,Ayudantia,Laboratorio,Taller,Creditos) VALUES (codigoRamo,nombreRamo,hTeoricas,hAyudantia,hLaboratorio,hTaller,credito);
END$$

DROP PROCEDURE IF EXISTS `asignar_jdc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `asignar_jdc`(codigoCarrera VARCHAR(9),nombreUsuario VARCHAR(40))
BEGIN
  UPDATE Carrera AS c SET c.NombreUsuario_JC = nombreUsuario WHERE c.Codigo = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `cambiar_jdc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `cambiar_jdc`(codigoCarrera VARCHAR(9),nombreUsuario VARCHAR(40))
BEGIN
  UPDATE Carrera AS c SET c.NombreUsuario_JC = nombreUsuario WHERE c.Codigo = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `eliminar_jdc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminar_jdc`(nombreUsuario VARCHAR(40))
BEGIN
  IF ((SELECT COUNT(c.nombreUsuario_JC) FROM carrera AS c WHERE c.nombreUsuario_JC = nombreUsuario) > 0) THEN
    UPDATE Carrera AS c SET c.NombreUsuario_JC = NULL WHERE c.nombreUsuario_JC = nombreUsuario;
    DELETE FROM usuario WHERE Nombre_Usuario = nombreUsuario;
  ELSE
    DELETE FROM usuario WHERE Nombre_Usuario = nombreUsuario;
  END IF;
END$$

DROP PROCEDURE IF EXISTS `jdc_carreras`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `jdc_carreras`(nombreUsuario VARCHAR(40))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera
   FROM Carrera AS c
  WHERE c.NombreUsuario_JC = nombreUsuario;
END$$

DROP PROCEDURE IF EXISTS `presupuesto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `presupuesto`(codigo_carrera VARCHAR(7))
BEGIN
  SELECT presupuesto
   FROM semestre;
END$$

DROP PROCEDURE IF EXISTS `prof_asignados`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `prof_asignados`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT p.Rut_Profesor, p.Nombre
   FROM Profesor AS p
  WHERE p.codigo_carrera = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `prof_asignados_sc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `prof_asignados_sc`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT p.Rut_Profesor, p.Nombre
   FROM Profesor AS p
  WHERE p.codigo_carrera = codigoCarrera AND p.Rut_Profesor NOT IN (SELECT s.Rut_Profesor FROM Seccion AS s WHERE s.Rut_Profesor IS NOT NULL);
END$$

DROP PROCEDURE IF EXISTS `relacionar_cramos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `relacionar_cramos`(codigoRamo VARCHAR(6),codigoCarrera VARCHAR(9),semest INT)
BEGIN
  IF((SELECT ctr.Codigo_Ramo FROM carrera_tiene_ramos AS ctr WHERE ctr.Codigo_Carrera = codigoCarrera AND ctr.Codigo_Ramo = codigoRamo) IS NULL) THEN
    INSERT INTO carrera_tiene_ramos (Codigo_Carrera,Codigo_Ramo,Semestre) VALUES (codigoCarrera,codigoRamo,semest);
    SELECT 1;
  ELSE
    SELECT 0;
  END IF;
END$$

DROP PROCEDURE IF EXISTS `seccion_sprofe`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `seccion_sprofe`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT r.Codigo,r.Nombre,s.NRC
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramo AS r ON r.Codigo = ctr.Codigo_Ramo
   INNER JOIN Seccion AS s ON s.Codigo_Ramo = r.Codigo AND s.RUT_Profesor IS NULL
  WHERE ctr.Codigo_Carrera = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `select_carreras`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_carreras`()
BEGIN
DECLARE Nombre_JC VARCHAR(40);
DECLARE Rut_JC VARCHAR(10);
SET Nombre_JC = 'No asignado';
SET Rut_JC = 'No asignado';
  (SELECT c.Codigo,c.Nombre_Carrera AS nombreCarrera,c.NombreUsuario_JC,c.Periodo,c.Numero,u.Nombre,u.RUT
    FROM Carrera AS c
    INNER JOIN Usuario AS u ON u.Nombre_Usuario = c.NombreUsuario_JC AND (u.Id_Tipo = 1 OR u.Id_Tipo = 3))
  UNION
  (SELECT c.Codigo,c.Nombre_Carrera AS nombreCarrera,c.NombreUsuario_JC,c.Periodo,c.Numero,Nombre_JC,Rut_JC
    FROM Carrera AS c
   WHERE c.NombreUsuario_JC IS NULL) ORDER BY nombreCarrera;
END$$

DROP PROCEDURE IF EXISTS `select_ccarreras`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_ccarreras`()
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera
   FROM Carrera AS c
  ORDER BY c.Nombre_Carrera;
END$$

DROP PROCEDURE IF EXISTS `select_cramos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_cramos`()
BEGIN
  SELECT r.Codigo,r.Nombre
   FROM Ramo AS r
  ORDER BY r.Nombre;
END$$

DROP PROCEDURE IF EXISTS `select_jefe_carrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_jefe_carrera`()
BEGIN
  SELECT u.Nombre_Usuario,u.RUT,u.Nombre
   FROM Usuario AS u
  WHERE u.Id_Tipo = 1 OR u.Id_Tipo = 3 ORDER BY u.Nombre;
END$$

DROP PROCEDURE IF EXISTS `select_ramos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_ramos`()
BEGIN
  SELECT r.Codigo,r.Nombre,r.Teoria,r.Ayudantia,r.Laboratorio,r.Taller,r.Creditos
   FROM Ramo AS r
  ORDER by r.Codigo;
END$$

DROP PROCEDURE IF EXISTS `select_ramoscarreras`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_ramoscarreras`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT r.Codigo,r.Nombre,ctr.Semestre
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramo AS r ON r.Codigo = ctr.Codigo_Ramo
  WHERE ctr.Codigo_Carrera = codigoCarrera ORDER BY ctr.Semestre,r.Codigo;
END$$

DROP PROCEDURE IF EXISTS `sol_pedidas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sol_pedidas`(nombreUsuario VARCHAR(40))
BEGIN
  SELECT sl.Numero_Solicitud,sl.CodigoCarrera_JC,sl.NRC_Seccion,sl.Cantidad_Vacantes
   FROM solicitud AS sl
  WHERE sl.NombreUsuario_JC = nombreUsuario;
END$$

DROP PROCEDURE IF EXISTS `sol_pidieron`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sol_pidieron`(codigo_carrera VARCHAR(9))
BEGIN
  SELECT sl.Numero_Solicitud,sl.NombreUsuario_JC,sl.NRC_Seccion,sl.Cantidad_Vacantes
   FROM solicitud AS sl
  WHERE sl.CodigoCarrera_JC = codigo_carrera;
END$$

DROP PROCEDURE IF EXISTS `user_login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login`(username VARCHAR(40),password VARCHAR(32))
BEGIN
  SELECT u.RUT,u.Nombre,u.Id_Tipo
   FROM Usuario AS u
  WHERE u.Nombre_Usuario = username AND u.Password = password;
END$$

DROP PROCEDURE IF EXISTS `ver_malla`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ver_malla`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT ctr.Codigo_Ramo,r.Nombre,ctr.Semestre
   FROM carrera_tiene_ramos AS ctr
   INNER JOIN Ramo AS r ON ctr.Codigo_Ramo = r.Codigo
  WHERE ctr.Codigo_Carrera = codigoCarrera ORDER BY ctr.Semestre;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `carrera`
--

DROP TABLE IF EXISTS `carrera`;
CREATE TABLE IF NOT EXISTS `carrera` (
  `Codigo` varchar(9) NOT NULL,
  `NombreUsuario_JC` varchar(40) DEFAULT NULL COMMENT 'Nombre de usuario del jefe de carrera.',
  `Nombre_Carrera` varchar(100) NOT NULL COMMENT 'Nombre de la carrera.',
  `Periodo` int(1) NOT NULL COMMENT '1 = Semestral, 2 = Trimestral.',
  `Numero` int(2) NOT NULL COMMENT 'Duración de la carrera en semestres o trimestres.',
  PRIMARY KEY (`Codigo`),
  KEY `NombreUsuario_JC` (`NombreUsuario_JC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carrera`
--

INSERT INTO `carrera` (`Codigo`, `NombreUsuario_JC`, `Nombre_Carrera`, `Periodo`, `Numero`) VALUES
('DER1000', 'david', 'Derecho', 1, 10),
('INF1000', NULL, 'Redes', 1, 0),
('INF1200', 'cri.flores', 'Ingenieria', 0, 0),
('UNAB11500', 'david', 'IngenierÃ­a en ComputaciÃ³n e InformÃ¡tica ', 0, 8),
('UNAB11550', 'david', 'IngenierÃ­a en Telecomunicaciones', 0, 8),
('UNAB11560', 'usuario2', 'EnfermerÃ­a', 0, 10),
('UNAB65000', 'dav', 'Medicina', 1, 18);

-- --------------------------------------------------------

--
-- Table structure for table `carrera_tiene_ramos`
--

DROP TABLE IF EXISTS `carrera_tiene_ramos`;
CREATE TABLE IF NOT EXISTS `carrera_tiene_ramos` (
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Código del ramo que pertenece a la carrera.',
  `Semestre` int(2) NOT NULL COMMENT 'Semestre o trimestre en el que se imparte el ramo.',
  KEY `Codigo_Carrera` (`Codigo_Carrera`),
  KEY `Codigo_Ramo` (`Codigo_Ramo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carrera_tiene_ramos`
--

INSERT INTO `carrera_tiene_ramos` (`Codigo_Carrera`, `Codigo_Ramo`, `Semestre`) VALUES
('DER1000', 'INF111', 1),
('UNAB11500', 'IET100', 1),
('UNAB11500', 'FMM030', 1),
('UNAB11500', 'IET090', 1),
('UNAB11500', 'FIS110', 1),
('UNAB11500', 'FMM130', 2),
('DER1000', 'IET100', 2),
('UNAB65000', 'INF111', 1);

-- --------------------------------------------------------

--
-- Table structure for table `horario`
--

DROP TABLE IF EXISTS `horario`;
CREATE TABLE IF NOT EXISTS `horario` (
  `Codigo_Horario` int(4) NOT NULL COMMENT 'Identificador de horario.',
  `Codigo_Carrera` varchar(7) NOT NULL COMMENT 'Código de la carrera a la cual pertenece el horario.',
  `Codigo_Semestre` int(4) NOT NULL COMMENT 'Código del semestre al cual pertenece este horario.',
  PRIMARY KEY (`Codigo_Horario`),
  KEY `Codigo_Carrera` (`Codigo_Carrera`),
  KEY `Codigo_Semestre` (`Codigo_Semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `horario`
--


-- --------------------------------------------------------

--
-- Table structure for table `horario_tiene_secciones`
--

DROP TABLE IF EXISTS `horario_tiene_secciones`;
CREATE TABLE IF NOT EXISTS `horario_tiene_secciones` (
  `Codigo_Horario` int(4) NOT NULL COMMENT 'Codigo del horario al cual pertenece la sección.',
  `NRC_Seccion` int(4) NOT NULL COMMENT 'Sección que pertenece al horario.',
  KEY `Codigo_Horario` (`Codigo_Horario`),
  KEY `NRC_Seccion` (`NRC_Seccion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `horario_tiene_secciones`
--


-- --------------------------------------------------------

--
-- Table structure for table `profesor`
--

DROP TABLE IF EXISTS `profesor`;
CREATE TABLE IF NOT EXISTS `profesor` (
  `RUT_Profesor` varchar(10) NOT NULL COMMENT 'Rut del profesor.',
  `Nombre` varchar(50) NOT NULL COMMENT 'Nombre del profesor.',
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera a la que pertenece el profesor.',
  PRIMARY KEY (`RUT_Profesor`),
  KEY `Codigo_Carrera` (`Codigo_Carrera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profesor`
--

INSERT INTO `profesor` (`RUT_Profesor`, `Nombre`, `Codigo_Carrera`) VALUES
('16482760-7', 'Cristian Olivares', 'INF1200'),
('16482760-8', 'Oscar Pinto', 'INF1200'),
('8545216-8', 'Otto Pettersen', 'UNAB11500');

-- --------------------------------------------------------

--
-- Table structure for table `ramo`
--

DROP TABLE IF EXISTS `ramo`;
CREATE TABLE IF NOT EXISTS `ramo` (
  `Codigo` varchar(6) NOT NULL COMMENT 'Código identificador de cada ramo.',
  `Nombre` varchar(50) NOT NULL COMMENT 'Nombre del ramo.',
  `Teoria` int(2) NOT NULL COMMENT 'Horas teoricas.',
  `Ayudantia` int(2) NOT NULL COMMENT 'Horas de ayudantia.',
  `Laboratorio` int(2) NOT NULL COMMENT 'Horas de laboratorio.',
  `Taller` int(2) NOT NULL COMMENT 'Horas de taller.',
  `Creditos` int(2) NOT NULL COMMENT 'Creditos del ramo.',
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ramo`
--

INSERT INTO `ramo` (`Codigo`, `Nombre`, `Teoria`, `Ayudantia`, `Laboratorio`, `Taller`, `Creditos`) VALUES
('FIS110', 'FÃ­sica I', 4, 2, 0, 0, 6),
('FIS120', 'FÃ­sica II', 4, 2, 0, 0, 6),
('FMM030', 'CÃ¡lculo I', 4, 2, 0, 0, 6),
('FMM130', 'CÃ¡lculo II', 4, 2, 0, 0, 6),
('FMM230', 'CÃ¡lculo III', 4, 2, 0, 0, 6),
('IET090', 'Redes I', 0, 0, 0, 0, 0),
('IET100', 'Elementos de la ComputaciÃ³n', 4, 2, 2, 0, 6),
('IET110', 'Elementos', 0, 0, 0, 0, 0),
('IET120', 'Computacion', 0, 0, 0, 0, 0),
('INF090', 'Historia de la computaciÃ³n', 4, 2, 0, 0, 6),
('INF110', 'Levantar II', 4, 2, 0, 0, 6),
('INF111', 'Levantar', 0, 0, 0, 0, 0),
('INF112', 'Levantar III', 4, 2, 0, 0, 6),
('INF114', 'Levantar IV', 6, 4, 2, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `seccion`
--

DROP TABLE IF EXISTS `seccion`;
CREATE TABLE IF NOT EXISTS `seccion` (
  `NRC` int(4) NOT NULL COMMENT 'Código identificador de cada sección.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Código del ramo al cual pertenece la sección.',
  `RUT_Profesor` varchar(10) DEFAULT NULL COMMENT 'RUT del profesor que dicta la sección.',
  PRIMARY KEY (`NRC`),
  KEY `Codigo_Ramo` (`Codigo_Ramo`),
  KEY `RUT_Profesor` (`RUT_Profesor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `seccion`
--

INSERT INTO `seccion` (`NRC`, `Codigo_Ramo`, `RUT_Profesor`) VALUES
(1523, 'IET100', NULL),
(1524, 'IET090', NULL),
(1540, 'IET120', '16482760-8'),
(1541, 'IET110', NULL),
(1542, 'IET120', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `Codigo_Semestre` int(4) NOT NULL COMMENT 'Código del semestre.',
  `Numero` int(1) NOT NULL COMMENT 'Número del semestre, 1 o 2.',
  `Anho` int(4) NOT NULL COMMENT 'Año en que tuvo lugar el esmestre.',
  `Presupuesto` int(9) NOT NULL COMMENT 'Cantidad de dinero que docencia pone a disposición para programar ramos y secciones.',
  PRIMARY KEY (`Codigo_Semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `semestre`
--


-- --------------------------------------------------------

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
CREATE TABLE IF NOT EXISTS `solicitud` (
  `Numero_Solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `NombreUsuario_JC` varchar(40) NOT NULL COMMENT 'Nombre de usuario del jefe de carrera que realizó la solicitud.',
  `CodigoCarrera_JC` varchar(9) NOT NULL COMMENT 'Carrera la cual requiere de vacantes.',
  `NRC_Seccion` int(4) NOT NULL COMMENT 'NRC de la sección en la cual se requieren vacantes.',
  `Cantidad_Vacantes` int(11) NOT NULL COMMENT 'Cantidad de vacantes pedidas.',
  PRIMARY KEY (`Numero_Solicitud`),
  KEY `NRC_Seccion` (`NRC_Seccion`),
  KEY `NombreUsuario_JC` (`NombreUsuario_JC`),
  KEY `CodigoCarrera_JC` (`CodigoCarrera_JC`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `solicitud`
--

INSERT INTO `solicitud` (`Numero_Solicitud`, `NombreUsuario_JC`, `CodigoCarrera_JC`, `NRC_Seccion`, `Cantidad_Vacantes`) VALUES
(2, 'cri.flores', 'INF1200', 1542, 0),
(3, 'usuario', 'UNAB11500', 1523, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `Id` int(1) NOT NULL,
  `Tipo` varchar(32) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`Id`, `Tipo`) VALUES
(1, 'Jefe de carrera'),
(2, 'Administrador'),
(3, 'Jefe de carrera + administrador');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `Nombre_Usuario` varchar(40) NOT NULL,
  `RUT` varchar(10) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Id_Tipo` int(1) NOT NULL,
  PRIMARY KEY (`Nombre_Usuario`),
  KEY `Id_Tipo` (`Id_Tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`Nombre_Usuario`, `RUT`, `Nombre`, `Password`, `Id_Tipo`) VALUES
('cri.flores', '1654821', 'Cristian Flores Cerda', '040b7cf4a55014e185813e0644502ea9', 1),
('dav', '16482760-7', 'David', '040b7cf4a55014e185813e0644502ea9', 1),
('dav miranda', '16482760-7', 'David Miranda', '040b7cf4a55014e185813e0644502ea9', 2),
('david', '16482760-7', 'David Miranda Atenas', '040b7cf4a55014e185813e0644502ea9', 3),
('usuario', '16482760-7', 'David Miranda', '040b7cf4a55014e185813e0644502ea9', 3),
('usuario2', '1659584-7', 'John Goodman', '040b7cf4a55014e185813e0644502ea9', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrera`
--
ALTER TABLE `carrera`
  ADD CONSTRAINT `carrera_ibfk_1` FOREIGN KEY (`NombreUsuario_JC`) REFERENCES `usuario` (`Nombre_Usuario`);

--
-- Constraints for table `carrera_tiene_ramos`
--
ALTER TABLE `carrera_tiene_ramos`
  ADD CONSTRAINT `carrera_tiene_ramos_ibfk_1` FOREIGN KEY (`Codigo_Carrera`) REFERENCES `carrera` (`Codigo`),
  ADD CONSTRAINT `carrera_tiene_ramos_ibfk_2` FOREIGN KEY (`Codigo_Ramo`) REFERENCES `ramo` (`Codigo`);

--
-- Constraints for table `horario_tiene_secciones`
--
ALTER TABLE `horario_tiene_secciones`
  ADD CONSTRAINT `horario_tiene_secciones_ibfk_1` FOREIGN KEY (`Codigo_Horario`) REFERENCES `horario` (`Codigo_Horario`),
  ADD CONSTRAINT `horario_tiene_secciones_ibfk_2` FOREIGN KEY (`NRC_Seccion`) REFERENCES `seccion` (`NRC`);

--
-- Constraints for table `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`Codigo_Carrera`) REFERENCES `carrera` (`Codigo`);

--
-- Constraints for table `seccion`
--
ALTER TABLE `seccion`
  ADD CONSTRAINT `seccion_ibfk_1` FOREIGN KEY (`Codigo_Ramo`) REFERENCES `ramo` (`Codigo`),
  ADD CONSTRAINT `seccion_ibfk_2` FOREIGN KEY (`RUT_Profesor`) REFERENCES `profesor` (`RUT_Profesor`);

--
-- Constraints for table `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_2` FOREIGN KEY (`NRC_Seccion`) REFERENCES `seccion` (`NRC`),
  ADD CONSTRAINT `solicitud_ibfk_3` FOREIGN KEY (`NombreUsuario_JC`) REFERENCES `usuario` (`Nombre_Usuario`),
  ADD CONSTRAINT `solicitud_ibfk_4` FOREIGN KEY (`CodigoCarrera_JC`) REFERENCES `carrera` (`Codigo`);
