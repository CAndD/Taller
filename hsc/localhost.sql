-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2011 at 12:39 AM
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
DROP PROCEDURE IF EXISTS `abrirSemestreAnterior`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `abrirSemestreAnterior`(codigoSemestre INT, fecha DATETIME)
BEGIN
  UPDATE Semestre
   SET Fecha_Termino = NULL
  WHERE Codigo_Semestre = codigoSemestre;
END$$

DROP PROCEDURE IF EXISTS `abrirTrimestreAnterior`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `abrirTrimestreAnterior`(codigoTrimestre INT, fecha DATETIME)
BEGIN
  UPDATE Trimestre
   SET Fecha_Termino = NULL
  WHERE Codigo_Trimestre = codigoTrimestre;
END$$

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

DROP PROCEDURE IF EXISTS `buscarCodigoCarrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarCodigoCarrera`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT codigo
   FROM Carrera
  WHERE codigo = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `buscarCodigoRamo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarCodigoRamo`(codigoRamo VARCHAR(6))
BEGIN
  SELECT codigo
   FROM Ramo
  WHERE codigo = codigoRamo;
END$$

DROP PROCEDURE IF EXISTS `buscarNombreUsuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarNombreUsuario`(nombreUsuario VARCHAR(40))
BEGIN
  SELECT Nombre_Usuario
   FROM Usuario
  WHERE Nombre_Usuario = nombreUsuario;
END$$

DROP PROCEDURE IF EXISTS `cambiar_jdc`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `cambiar_jdc`(codigoCarrera VARCHAR(9),nombreUsuario VARCHAR(40))
BEGIN
  UPDATE Carrera AS c SET c.NombreUsuario_JC = nombreUsuario WHERE c.Codigo = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `cerrarSemestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `cerrarSemestre`(codigoSemestre INT,fecha DATETIME)
BEGIN
  UPDATE Semestre 
   SET Fecha_Termino = fecha
  WHERE Codigo_Semestre = codigoSemestre;
END$$

DROP PROCEDURE IF EXISTS `cerrarTrimestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `cerrarTrimestre`(codigoTrimestre INT,fecha DATETIME)
BEGIN
  UPDATE Trimestre 
   SET Fecha_Termino = fecha
  WHERE Codigo_Trimestre = codigoTrimestre;
END$$

DROP PROCEDURE IF EXISTS `comenzarSemestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `comenzarSemestre`(codigoSemestre INT, numeroSemestre INT, annoSemestre INT, fechaInicio DATETIME)
BEGIN
  INSERT INTO Semestre(Codigo_Semestre,Numero,Anho,Fecha_Inicio,Fecha_Termino) VALUES(codigoSemestre,numeroSemestre,annoSemestre,fechaInicio,NULL);
END$$

DROP PROCEDURE IF EXISTS `comenzarTrimestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `comenzarTrimestre`(codigoTrimestre INT, numeroTrimestre INT, annoSemestre YEAR, fechaInicio DATETIME)
BEGIN
  INSERT INTO Trimestre(Codigo_Trimestre,Numero,Anho,Fecha_Inicio,Fecha_Termino) VALUES(codigoTrimestre,numeroTrimestre,annoSemestre,fechaInicio,NULL);
END$$

DROP PROCEDURE IF EXISTS `comprobarSolicitudExiste`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `comprobarSolicitudExiste`(codigoCarreraSolicitante VARCHAR(9), codigoCarreraDestinatario VARCHAR(9), codigoSemestre INT, codigoRamo VARCHAR(6))
BEGIN
  SELECT s.Id
   FROM Solicitud AS s
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera_Solicitante = codigoCarreraSolicitante AND s.Codigo_Ramo = codigoRamo AND s.Carrera = codigoCarreraDestinatario AND s.Estado = 1;
END$$

DROP PROCEDURE IF EXISTS `crearSeccion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `crearSeccion`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  INSERT INTO Seccion(Codigo_Ramo,Codigo_Carrera,RUT_Profesor,Codigo_Semestre) VALUES(codigoRamo,codigoCarrera,NULL,codigoSemestre);
END$$

DROP PROCEDURE IF EXISTS `eliminarSolicitud`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarSolicitud`(idSolicitud INT)
BEGIN
  DELETE FROM Solicitud WHERE Id = idSolicitud;
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

DROP PROCEDURE IF EXISTS `impartirRamo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `impartirRamo`(codigoCarrera VARCHAR(9), codigoRamo VARCHAR(6), codigoSemestre INT)
BEGIN
  INSERT INTO ramos_impartidos(codigo_carrera,codigo_ramo,codigo_semestre) VALUES(codigoCarrera,codigoRamo,codigoSemestre);
END$$

DROP PROCEDURE IF EXISTS `jdc_carreras`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `jdc_carreras`(nombreUsuario VARCHAR(40))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera,c.Periodo
   FROM Carrera AS c
  WHERE c.NombreUsuario_JC = nombreUsuario;
END$$

DROP PROCEDURE IF EXISTS `modificarSolicitud`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `modificarSolicitud`(idSolicitud INT, numeroVacantes INT)
BEGIN
  UPDATE Solicitud SET Vacantes = numeroVacantes WHERE Id = idSolicitud AND Estado = 1;
END$$

DROP PROCEDURE IF EXISTS `obtenerPeriodoCarrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerPeriodoCarrera`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT c.Periodo
   FROM Carrera AS c
  WHERE c.Codigo = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `obtenerSemestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerSemestre`()
BEGIN
  SELECT s.Codigo_Semestre,s.Numero,s.Anho,s.Fecha_Inicio,s.Fecha_Termino
   FROM Semestre AS s
  WHERE s.Codigo_Semestre = (SELECT MAX(s.Codigo_Semestre) FROM Semestre AS s);
END$$

DROP PROCEDURE IF EXISTS `obtenerTrimestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerTrimestre`()
BEGIN
  SELECT t.Codigo_Trimestre,t.Numero,t.Anho,t.Fecha_Inicio,t.Fecha_Termino
   FROM Trimestre AS t
  WHERE t.Codigo_Trimestre = (SELECT MAX(t.Codigo_Trimestre) FROM Trimestre AS t);
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

DROP PROCEDURE IF EXISTS `ramoDictado`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ramoDictado`(codigoCarrera VARCHAR(9), codigoRamo VARCHAR(6), codigoSemestre INT)
BEGIN
  SELECT ri.Codigo_Ramo
   FROM ramos_impartidos AS ri
  WHERE ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Ramo = codigoRamo AND ri.Codigo_Semestre = codigoSemestre;
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

DROP PROCEDURE IF EXISTS `responderSolicitud`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `responderSolicitud`(idSolicitud INT, respuesta INT, vacantes INT, fecharespuesta DATETIME)
BEGIN
  IF(respuesta = 2) THEN
    UPDATE Solicitud SET estado = 2, vacantes_asignadas = vacantes, fecha_respuesta = fecharespuesta WHERE id = idSolicitud;
  ELSE
    UPDATE Solicitud SET estado = 3, vacantes_asignadas = 0, fecha_respuesta = fecharespuesta WHERE id = idSolicitud;
  END IF;
END$$

DROP PROCEDURE IF EXISTS `revisarSolicitud`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `revisarSolicitud`(idSolicitud INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,s.Carrera,s.Carrera_Solicitante,s.Vacantes,s.Codigo_Semestre,s.Fecha_Envio,s.Fecha_Respuesta,s.Estado
   FROM Solicitud AS s
  WHERE s.Id = idSolicitud;
END$$

DROP PROCEDURE IF EXISTS `seccionesCreadas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `seccionesCreadas`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.NRC,s.Codigo_Ramo,s.Codigo_Carrera,s.RUT_Profesor,s.Codigo_Semestre
   FROM Seccion AS s
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre;
END$$

DROP PROCEDURE IF EXISTS `seccionesCreadasNumero`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `seccionesCreadasNumero`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT COUNT(s.NRC)
   FROM Seccion AS s
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre;
END$$

DROP PROCEDURE IF EXISTS `seccionesCreadasOtroNumero`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `seccionesCreadasOtroNumero`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT COUNT(s.NRC)
   FROM Seccion AS s
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera != codigoCarrera AND s.Codigo_Semestre = codigoSemestre;
END$$

DROP PROCEDURE IF EXISTS `selectCarrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `selectCarrera`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera,c.Periodo
   FROM Carrera AS c
  WHERE c.Codigo = codigoCarrera;
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_ccarreras`(codigoRamo VARCHAR(6))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera
   FROM Carrera AS c
  WHERE c.Codigo NOT IN (SELECT Codigo_Carrera FROM Carrera_Tiene_Ramos WHERE Codigo_Ramo = codigoRamo);
END$$

DROP PROCEDURE IF EXISTS `select_cramos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_cramos`(codigoRamo VARCHAR(6))
BEGIN
  SELECT r.Codigo,r.Nombre
   FROM Ramo AS r
  WHERE r.Codigo = codigoRamo;
END$$

DROP PROCEDURE IF EXISTS `select_jefe_carrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_jefe_carrera`()
BEGIN
  SELECT u.Nombre_Usuario,u.RUT,u.Nombre
   FROM Usuario AS u
  WHERE u.Id_Tipo = 1 OR u.Id_Tipo = 3 ORDER BY u.Nombre;
END$$

DROP PROCEDURE IF EXISTS `select_ramoCarrera`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_ramoCarrera`(codigoRamo VARCHAR(6))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera
   FROM Carrera AS c
   INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Ramo = codigoRamo
  WHERE c.Codigo = ctr.Codigo_Carrera ORDER BY c.Codigo;
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

DROP PROCEDURE IF EXISTS `solicitarVacantes`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `solicitarVacantes`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoCarreraSolicitante VARCHAR(9), numeroVacantes INT, codigoSemestre INT, fechaEnvio DATETIME)
BEGIN
  INSERT INTO Solicitud(Codigo_Ramo,Carrera,Carrera_Solicitante,Vacantes,Codigo_Semestre,Fecha_Envio,Fecha_Respuesta,Estado) VALUES (codigoRamo,codigoCarrera,codigoCarreraSolicitante,numeroVacantes,codigoSemestre,fechaEnvio,NULL,1);
END$$

DROP PROCEDURE IF EXISTS `solicitudesPedidas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `solicitudesPedidas`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera_Solicitante,s.Vacantes
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera = codigoCarrera AND s.Estado = 1;
END$$

DROP PROCEDURE IF EXISTS `solicitudesSolicitadas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `solicitudesSolicitadas`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera,s.Vacantes
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera_Solicitante = codigoCarrera AND s.Estado = 1;
END$$

DROP PROCEDURE IF EXISTS `user_login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login`(username VARCHAR(40),password VARCHAR(32))
BEGIN
  SELECT u.RUT,u.Nombre,u.Id_Tipo
   FROM Usuario AS u
  WHERE u.Nombre_Usuario = username AND u.Password = password;
END$$

DROP PROCEDURE IF EXISTS `verRamosImpartidos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verRamosImpartidos`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT r.Codigo,r.Nombre,ctr.Semestre,c.Periodo
   FROM ramos_impartidos AS ri
   INNER JOIN ramo AS r ON r.Codigo = ri.Codigo_Ramo
   INNER JOIN carrera_tiene_ramos AS ctr ON ctr.Codigo_Carrera = ri.Codigo_Carrera AND ctr.Codigo_Ramo = ri.Codigo_Ramo
   INNER JOIN carrera AS c ON c.Codigo = ctr.Codigo_Carrera
  WHERE ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Semestre = codigoSemestre ORDER BY ctr.Semestre,r.Codigo;
END$$

DROP PROCEDURE IF EXISTS `verSeccionesCreadas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verSeccionesCreadas`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.RUT_Profesor,s.Codigo_Semestre
   FROM Seccion AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre ORDER BY s.NRC;
END$$

DROP PROCEDURE IF EXISTS `verSeccionesCreadasOtro`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verSeccionesCreadasOtro`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.RUT_Profesor,s.Codigo_Semestre
   FROM Seccion AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera != codigoCarrera AND s.Codigo_Semestre = codigoSemestre ORDER BY s.Codigo_Carrera,s.NRC;
END$$

DROP PROCEDURE IF EXISTS `verSeccionesSinProfesor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verSeccionesSinProfesor`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT r.Codigo,r.Nombre,s.NRC
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramo AS r ON r.Codigo = ctr.Codigo_Ramo
   INNER JOIN Seccion AS s ON s.Codigo_Ramo = r.Codigo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre AND s.RUT_Profesor IS NULL
  WHERE ctr.Codigo_Carrera = codigoCarrera;
END$$

DROP PROCEDURE IF EXISTS `verSolicitudesMias`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verSolicitudesMias`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera,s.Vacantes,s.Vacantes_Asignadas,s.Fecha_Envio,s.Fecha_Respuesta,s.Estado
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera_Solicitante = codigoCarrera ORDER BY s.Estado,s.Fecha_Envio,s.Carrera_Solicitante,s.Codigo_Ramo;
END$$

DROP PROCEDURE IF EXISTS `verSolicitudesOtros`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verSolicitudesOtros`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera_Solicitante,s.Vacantes,s.Vacantes_Asignadas,s.Fecha_Envio,s.Fecha_Respuesta,s.Estado
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera = codigoCarrera ORDER BY s.Estado,s.Fecha_Envio,s.Carrera_Solicitante,s.Codigo_Ramo;
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
('INF1200', 'cri.flores', 'Ingenieria', 2, 0),
('UNAB11500', 'david', 'IngenierÃ­a en ComputaciÃ³n e InformÃ¡tica ', 2, 8),
('UNAB11550', 'david', 'IngenierÃ­a en Telecomunicaciones', 2, 8),
('UNAB11560', 'dav2', 'EnfermerÃ­a', 2, 10),
('UNAB15000', NULL, 'Periodismo', 1, 8),
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
('UNAB65000', 'INF111', 1),
('DER1000', 'FIS110', 1),
('UNAB65000', 'FIS110', 3),
('UNAB11560', 'IET120', 1),
('UNAB11560', 'FIS115', 1),
('UNAB11560', 'FIS116', 2),
('DER1000', 'FMM030', 1),
('DER1000', 'FMM130', 2),
('DER1000', 'FMM230', 3),
('DER1000', 'IET120', 2),
('UNAB11500', 'FIS120', 2),
('UNAB11500', 'FMM230', 3),
('UNAB11500', 'IET091', 4),
('UNAB11500', 'INF111', 4),
('UNAB11500', 'INF110', 5),
('UNAB11500', 'INF112', 6),
('UNAB11550', 'FIS110', 1),
('UNAB11550', 'FIS120', 2),
('UNAB11550', 'FMM030', 1),
('UNAB11550', 'FMM130', 2),
('UNAB11550', 'FMM230', 3),
('UNAB11550', 'IET090', 4),
('UNAB11550', 'IET091', 5),
('UNAB11550', 'IET100', 1),
('UNAB11550', 'INF090', 1),
('UNAB11560', 'FIS110', 1);

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
  `Tipo` varchar(1) NOT NULL COMMENT 'Tipo del ramo, C = carrera, F = depto. física, Q = depto. química, M = depto. matemáticas, I = inglés, O = formación general y P = formación profesional. ',
  `Ayudantia` int(2) NOT NULL COMMENT 'Horas de ayudantia.',
  `Laboratorio` int(2) NOT NULL COMMENT 'Horas de laboratorio.',
  `Taller` int(2) NOT NULL COMMENT 'Horas de taller.',
  `Creditos` int(2) NOT NULL COMMENT 'Creditos del ramo.',
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ramo`
--

INSERT INTO `ramo` (`Codigo`, `Nombre`, `Teoria`, `Tipo`, `Ayudantia`, `Laboratorio`, `Taller`, `Creditos`) VALUES
('FIS110', 'FÃ­sica I', 4, 'F', 2, 0, 0, 6),
('FIS115', 'EducaciÃ³n FÃ­sica', 2, 'F', 2, 2, 0, 6),
('FIS116', 'EducaciÃ³n FÃ­sica II', 2, 'F', 2, 0, 0, 6),
('FIS120', 'FÃ­sica II', 4, 'F', 2, 0, 0, 6),
('FMM030', 'CÃ¡lculo I', 4, 'M', 2, 0, 0, 6),
('FMM130', 'CÃ¡lculo II', 4, 'M', 2, 0, 0, 6),
('FMM230', 'CÃ¡lculo III', 4, 'M', 2, 0, 0, 6),
('IET090', 'Redes I', 0, 'C', 0, 0, 0, 0),
('IET091', 'Redes II', 4, 'C', 2, 2, 0, 6),
('IET100', 'Elementos de la ComputaciÃ³n', 4, 'C', 2, 2, 0, 6),
('IET110', 'Elementos', 0, 'C', 0, 0, 0, 0),
('IET120', 'Computacion', 0, 'C', 0, 0, 0, 0),
('INF090', 'Historia de la computaciÃ³n', 4, 'C', 2, 0, 0, 6),
('INF110', 'Levantar II', 4, 'C', 2, 0, 0, 6),
('INF111', 'Levantar', 0, 'C', 0, 0, 0, 0),
('INF112', 'Levantar III', 4, 'C', 2, 0, 0, 6),
('INF114', 'Levantar IV', 6, 'C', 4, 2, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ramos_impartidos`
--

DROP TABLE IF EXISTS `ramos_impartidos`;
CREATE TABLE IF NOT EXISTS `ramos_impartidos` (
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera en la cual se imparte el ramo.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Codigo del ramo impartido.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Semestre o trimestre en el cual se imparte.',
  KEY `Codigo_Ramo` (`Codigo_Ramo`),
  KEY `Codigo_Semestre` (`Codigo_Semestre`),
  KEY `Codigo_Carrera` (`Codigo_Carrera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ramos_impartidos`
--

INSERT INTO `ramos_impartidos` (`Codigo_Carrera`, `Codigo_Ramo`, `Codigo_Semestre`) VALUES
('DER1000', 'INF111', 201410),
('DER1000', 'INF111', 201420),
('DER1000', 'IET100', 201420),
('DER1000', 'FIS110', 201420),
('DER1000', 'FMM230', 201420),
('DER1000', 'IET120', 201420),
('DER1000', 'FMM130', 201420),
('DER1000', 'FMM030', 201420),
('UNAB11500', 'IET091', 201420),
('UNAB11500', 'FMM230', 201420),
('UNAB11500', 'FMM130', 201420),
('UNAB11500', 'FIS110', 201420),
('UNAB11500', 'FMM030', 201420),
('UNAB11500', 'IET100', 201420),
('UNAB11500', 'INF110', 201420),
('UNAB11500', 'IET100', 201125),
('UNAB11500', 'FMM130', 201125),
('UNAB11500', 'IET091', 201125),
('UNAB11500', 'INF111', 201125),
('UNAB11500', 'INF110', 201125),
('UNAB11500', 'FIS110', 201125),
('UNAB11500', 'IET090', 201125),
('UNAB11550', 'FIS110', 201125),
('UNAB11550', 'IET100', 201125),
('UNAB11500', 'FIS120', 201125),
('UNAB11560', 'IET120', 201125),
('UNAB11560', 'FIS115', 201125),
('UNAB11560', 'FIS116', 201125),
('UNAB65000', 'FIS110', 201420),
('UNAB11550', 'FMM130', 201125),
('UNAB11550', 'FMM230', 201125),
('UNAB11550', 'FIS120', 201125),
('UNAB11550', 'IET091', 201125),
('UNAB11560', 'FIS110', 201125),
('UNAB11500', 'FMM030', 201125),
('UNAB11500', 'FMM230', 201125),
('UNAB11500', 'INF112', 201125);

-- --------------------------------------------------------

--
-- Table structure for table `seccion`
--

DROP TABLE IF EXISTS `seccion`;
CREATE TABLE IF NOT EXISTS `seccion` (
  `NRC` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código identificador de cada sección.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Código del ramo al cual pertenece la sección.',
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera a la cual le pertenece esta sección.',
  `RUT_Profesor` varchar(10) DEFAULT NULL COMMENT 'RUT del profesor que dicta la sección.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Semestre al que pertenece la sección.',
  PRIMARY KEY (`NRC`),
  KEY `Codigo_Ramo` (`Codigo_Ramo`),
  KEY `RUT_Profesor` (`RUT_Profesor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1574 ;

--
-- Dumping data for table `seccion`
--

INSERT INTO `seccion` (`NRC`, `Codigo_Ramo`, `Codigo_Carrera`, `RUT_Profesor`, `Codigo_Semestre`) VALUES
(1523, 'IET100', '', NULL, 0),
(1524, 'IET090', '', NULL, 0),
(1540, 'IET120', '', '16482760-8', 0),
(1541, 'IET110', '', NULL, 0),
(1542, 'IET120', '', NULL, 0),
(1543, 'FIS110', 'DER1000', NULL, 201420),
(1544, 'FIS110', 'DER1000', NULL, 201420),
(1545, 'INF111', 'DER1000', NULL, 201420),
(1546, 'IET120', 'DER1000', NULL, 201420),
(1547, 'IET120', 'DER1000', NULL, 201420),
(1548, 'FMM230', 'DER1000', NULL, 201420),
(1549, 'FMM230', 'DER1000', NULL, 201420),
(1550, 'FIS110', 'UNAB11550', NULL, 201125),
(1551, 'FIS110', 'UNAB11500', NULL, 201125),
(1552, 'FIS110', 'UNAB11500', NULL, 201125),
(1553, 'FIS110', 'UNAB11500', NULL, 201125),
(1554, 'IET100', 'UNAB11550', NULL, 201125),
(1555, 'IET100', 'UNAB11550', NULL, 201125),
(1556, 'IET100', 'UNAB11550', NULL, 201125),
(1557, 'FMM030', 'DER1000', NULL, 201420),
(1558, 'IET100', 'DER1000', NULL, 201420),
(1559, 'FMM230', 'DER1000', NULL, 201420),
(1560, 'IET091', 'UNAB11500', NULL, 201125),
(1561, 'IET091', 'UNAB11500', NULL, 201125),
(1562, 'IET091', 'UNAB11500', NULL, 201125),
(1563, 'FIS110', 'UNAB11500', NULL, 201125),
(1564, 'IET090', 'UNAB11500', NULL, 201125),
(1565, 'FIS120', 'UNAB11500', NULL, 201125),
(1566, 'FMM130', 'UNAB11500', NULL, 201125),
(1567, 'FIS120', 'UNAB11500', NULL, 201125),
(1568, 'FIS110', 'UNAB11550', NULL, 201125),
(1569, 'FIS110', 'UNAB11560', NULL, 201125),
(1570, 'FIS110', 'UNAB11560', NULL, 201125),
(1571, 'FIS110', 'UNAB11560', NULL, 201125),
(1572, 'FIS110', 'UNAB11500', NULL, 201125),
(1573, 'FIS110', 'UNAB11500', NULL, 201125);

-- --------------------------------------------------------

--
-- Table structure for table `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Código del semestre.',
  `Numero` int(1) NOT NULL COMMENT 'Número del semestre, 1 o 2.',
  `Anho` int(4) NOT NULL COMMENT 'Año en que tuvo lugar el esmestre.',
  `Fecha_Inicio` datetime NOT NULL COMMENT 'Fecha de inicio de programación de semestre.',
  `Fecha_Termino` datetime DEFAULT NULL COMMENT 'Fecha de término de programación de semestre.',
  PRIMARY KEY (`Codigo_Semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `semestre`
--

INSERT INTO `semestre` (`Codigo_Semestre`, `Numero`, `Anho`, `Fecha_Inicio`, `Fecha_Termino`) VALUES
(201120, 2, 2011, '2011-10-09 20:03:24', '2011-10-09 23:04:35'),
(201210, 1, 2012, '2011-10-09 23:04:36', '2011-10-09 23:04:38'),
(201220, 2, 2012, '2011-10-09 23:04:39', '2011-10-09 23:04:42'),
(201310, 1, 2013, '2011-10-09 23:04:43', '2011-10-09 23:04:50'),
(201320, 2, 2013, '2011-10-09 23:04:58', '2011-10-09 23:24:31');

-- --------------------------------------------------------

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
CREATE TABLE IF NOT EXISTS `solicitud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Código del ramo pedido.',
  `Carrera` varchar(9) NOT NULL COMMENT 'Carrera dueña del ramo.',
  `Carrera_Solicitante` varchar(9) NOT NULL COMMENT 'Carrera solicitante de vacantes.',
  `Vacantes` int(11) NOT NULL COMMENT 'Número de vacantes solicitadas.',
  `Vacantes_Asignadas` int(11) DEFAULT NULL COMMENT 'Cantidad de vacantes asignadas.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Código del semestre al cual pertenece esta solicitud.',
  `Fecha_Envio` datetime NOT NULL COMMENT 'Fecha en la que se envío esta solicitud.',
  `Fecha_Respuesta` datetime DEFAULT NULL COMMENT 'Fecha en la cual se respondio a la solicitud.',
  `Estado` int(11) NOT NULL COMMENT '1 = Esperando, 2 = Aceptada y 3 = Denegada.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `solicitud`
--

INSERT INTO `solicitud` (`id`, `Codigo_Ramo`, `Carrera`, `Carrera_Solicitante`, `Vacantes`, `Vacantes_Asignadas`, `Codigo_Semestre`, `Fecha_Envio`, `Fecha_Respuesta`, `Estado`) VALUES
(2, 'FIS110', 'UNAB11500', 'UNAB11550', 20, 15, 201125, '2011-10-10 23:06:53', '2011-10-19 23:33:14', 2),
(3, 'FIS120', 'UNAB11500', 'UNAB11550', 10, 0, 201125, '2011-10-10 23:59:20', NULL, 3),
(4, 'FMM130', 'UNAB11500', 'UNAB11550', 5, 0, 201125, '2011-10-10 23:59:24', NULL, 0),
(5, 'IET091', 'UNAB11500', 'UNAB11550', 15, 0, 201125, '2011-10-10 23:59:29', NULL, 3),
(7, 'FIS110', 'UNAB11560', 'UNAB11500', 10, 0, 201125, '2011-10-18 11:56:11', NULL, 2),
(8, 'FIS110', 'UNAB11500', 'UNAB11550', 10, NULL, 201125, '2011-10-20 00:07:39', NULL, 1);

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
-- Table structure for table `trimestre`
--

DROP TABLE IF EXISTS `trimestre`;
CREATE TABLE IF NOT EXISTS `trimestre` (
  `Codigo_Trimestre` int(11) NOT NULL COMMENT 'Código del trimestre.',
  `Numero` int(11) NOT NULL COMMENT 'Número del trimestre, 1,2 o 3.',
  `Anho` year(4) NOT NULL COMMENT 'Año en que tuvo lugar el trimestre.',
  `Fecha_Inicio` datetime NOT NULL COMMENT 'Fecha de inicio de programación de trimestre.',
  `Fecha_Termino` datetime DEFAULT NULL COMMENT 'Fecha de termino de programación de trimestre.',
  PRIMARY KEY (`Codigo_Trimestre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trimestre`
--

INSERT INTO `trimestre` (`Codigo_Trimestre`, `Numero`, `Anho`, `Fecha_Inicio`, `Fecha_Termino`) VALUES
(201105, 1, 2011, '2011-10-10 15:50:25', '2011-10-10 16:35:47'),
(201115, 2, 2011, '2011-10-10 16:36:18', '2011-10-10 16:36:46'),
(201125, 3, 2011, '2011-10-10 16:36:49', NULL);

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
('dav2', '16482760-7', 'David 2', '040b7cf4a55014e185813e0644502ea9', 1),
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
-- Constraints for table `ramos_impartidos`
--
ALTER TABLE `ramos_impartidos`
  ADD CONSTRAINT `ramos_impartidos_ibfk_1` FOREIGN KEY (`Codigo_Ramo`) REFERENCES `ramo` (`Codigo`),
  ADD CONSTRAINT `ramos_impartidos_ibfk_3` FOREIGN KEY (`Codigo_Carrera`) REFERENCES `carrera` (`Codigo`);

--
-- Constraints for table `seccion`
--
ALTER TABLE `seccion`
  ADD CONSTRAINT `seccion_ibfk_1` FOREIGN KEY (`Codigo_Ramo`) REFERENCES `ramo` (`Codigo`),
  ADD CONSTRAINT `seccion_ibfk_2` FOREIGN KEY (`RUT_Profesor`) REFERENCES `profesor` (`RUT_Profesor`);
