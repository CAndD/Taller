-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 29, 2011 at 09:16 AM
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

DROP PROCEDURE IF EXISTS `agregarProfesor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `agregarProfesor`(rutProfesor INT, nombreProfesor VARCHAR(50))
BEGIN
  INSERT INTO Profesor(Rut_Profesor,Nombre) VALUES (rutProfesor,nombreProfesor);
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

DROP PROCEDURE IF EXISTS `horarioSeccion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `horarioSeccion`(codigoCarrera VARCHAR(10), codigoRamo VARCHAR(6), codigoSemestre INT)
BEGIN
  SELECT s.Horario_Inicio,s.Horario_Termino
   FROM Seccion AS s
  WHERE s.Codigo_Carrera = codigoCarrera AND s.Codigo_Ramo = codigoRamo AND s.Codigo_Semestre = codigoSemestre;
END$$

DROP PROCEDURE IF EXISTS `impartirRamo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `impartirRamo`(codigoCarrera VARCHAR(9), codigoRamo VARCHAR(6), codigoSemestre INT, impartir INT)
BEGIN

  INSERT INTO ramos_impartidos(codigo_carrera,codigo_ramo,codigo_semestre,impartido) VALUES(codigoCarrera,codigoRamo,codigoSemestre,impartir);

END$$

DROP PROCEDURE IF EXISTS `modificarSolicitud`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `modificarSolicitud`(idSolicitud INT, numeroVacantes INT)
BEGIN
  UPDATE Solicitud SET Vacantes = numeroVacantes WHERE Id = idSolicitud AND Estado = 1;
END$$

DROP PROCEDURE IF EXISTS `obtenerHorarioActual`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerHorarioActual`(codigoSeccion INT)
BEGIN
  SELECT s.Horario_Inicio,s.Horario_Termino
   FROM Seccion AS s
  WHERE s.NRC = codigoSeccion;
END$$

DROP PROCEDURE IF EXISTS `obtenerHorarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerHorarios`(codigoCarrera VARCHAR(9))
BEGIN
  SELECT m.Modulo,m.Regimen,m.Inicio,m.Termino 
   FROM Modulo AS m 
   INNER JOIN Carrera AS c ON c.Codigo = codigoCarrera 
  WHERE m.Regimen = c.Regimen;
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

DROP PROCEDURE IF EXISTS `ramoDictado`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ramoDictado`(codigoCarrera VARCHAR(9), codigoRamo VARCHAR(6), codigoSemestre INT)
BEGIN

  SELECT ri.Codigo_Ramo,ri.Impartido

   FROM ramos_impartidos AS ri

  WHERE ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Ramo = codigoRamo AND ri.Codigo_Semestre = codigoSemestre;

END$$

DROP PROCEDURE IF EXISTS `ramosSemestre`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ramosSemestre`(codigoCarrera VARCHAR(10), codigoRamo VARCHAR(6), codigoSemestre INT, semestreRamo INT)
BEGIN
  SELECT ri.Codigo_Ramo
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramos_Impartidos AS ri ON ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Ramo = ctr.Codigo_Ramo AND ri.Codigo_Semestre = codigoSemestre AND ri.Impartido = 1
  WHERE ctr.Codigo_Carrera = codigoCarrera AND ctr.Semestre = semestreRamo AND ctr.Codigo_Ramo != codigoRamo;
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

DROP PROCEDURE IF EXISTS `semestreRamo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `semestreRamo`(codigoCarrera VARCHAR(10), codigoRamo VARCHAR(6))
BEGIN
  SELECT ctr.Semestre
   FROM Carrera_Tiene_Ramos AS ctr
  WHERE ctr.Codigo_Carrera = codigoCarrera AND ctr.Codigo_Ramo = codigoRamo;
END$$

DROP PROCEDURE IF EXISTS `solicitarVacantes`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `solicitarVacantes`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoCarreraSolicitante VARCHAR(9), numeroVacantes INT, codigoSemestre INT, fechaEnvio DATETIME)
BEGIN
  INSERT INTO Solicitud(Codigo_Ramo,Carrera,Carrera_Solicitante,Vacantes,Codigo_Semestre,Fecha_Envio,Fecha_Respuesta,Estado) VALUES (codigoRamo,codigoCarrera,codigoCarreraSolicitante,numeroVacantes,codigoSemestre,fechaEnvio,NULL,1);
END$$

DROP PROCEDURE IF EXISTS `verRamosImpartidos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verRamosImpartidos`(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN

  SELECT r.Codigo,r.Nombre,r.Tipo,ctr.Semestre,c.Periodo

   FROM ramos_impartidos AS ri

   INNER JOIN ramo AS r ON r.Codigo = ri.Codigo_Ramo

   INNER JOIN carrera_tiene_ramos AS ctr ON ctr.Codigo_Carrera = ri.Codigo_Carrera AND ctr.Codigo_Ramo = ri.Codigo_Ramo

   INNER JOIN carrera AS c ON c.Codigo = ctr.Codigo_Carrera

  WHERE ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Semestre = codigoSemestre AND ri.Impartido = 1 ORDER BY ctr.Semestre,r.Codigo;

END$$

DROP PROCEDURE IF EXISTS `verSeccionesCreadas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verSeccionesCreadas`(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN

  SELECT s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.RUT_Profesor,s.Horario_Inicio,s.Horario_Termino,s.Codigo_Semestre

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
  `Regimen` varchar(1) NOT NULL COMMENT 'D = Diurno, V = Vespertino.',
  `Numero` int(2) NOT NULL COMMENT 'Duración de la carrera en semestres o trimestres.',
  PRIMARY KEY (`Codigo`),
  KEY `NombreUsuario_JC` (`NombreUsuario_JC`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carrera`
--

INSERT INTO `carrera` (`Codigo`, `NombreUsuario_JC`, `Nombre_Carrera`, `Periodo`, `Regimen`, `Numero`) VALUES
('DER1000', 'david', 'Derecho', 1, 'D', 10),
('INF1000', NULL, 'Redes', 1, 'D', 0),
('INF1200', 'cri.flores', 'Ingenieria', 2, 'D', 0),
('UNAB11500', 'david', 'IngenierÃ­a en ComputaciÃ³n e InformÃ¡tica ', 2, 'D', 8),
('UNAB11550', 'david', 'IngenierÃ­a en Telecomunicaciones', 2, 'D', 8),
('UNAB11560', 'dav2', 'EnfermerÃ­a', 2, 'D', 10),
('UNAB15000', NULL, 'Periodismo', 1, 'D', 8),
('UNAB65000', 'dav', 'Medicina', 1, 'D', 18);

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
('UNAB11550', 'FMM230', 3),
('UNAB11550', 'IET090', 4),
('UNAB11550', 'IET091', 5),
('UNAB11550', 'IET100', 1),
('UNAB11550', 'INF090', 1),
('UNAB11560', 'FIS110', 1),
('UNAB11500', 'INF090', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clase`
--

DROP TABLE IF EXISTS `clase`;
CREATE TABLE IF NOT EXISTS `clase` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Clase_Tipo` varchar(20) NOT NULL COMMENT 'Tipo de la clase.',
  `Seccion_Id` int(11) NOT NULL COMMENT 'Id de la sección a la cual pertenece la clase.',
  `RUT_Profesor` int(10) DEFAULT NULL COMMENT 'Rut del profesor que dicta la clase.',
  `Modulo_Inicio` int(11) DEFAULT NULL COMMENT 'Módulo de inicio de la clase.',
  `Modulo_Termino` int(11) DEFAULT NULL COMMENT 'Módulo de término de la clase.',
  `Dia` varchar(12) DEFAULT NULL COMMENT 'Día de la clase.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Codigo del semestre al cual pertenece la clase.',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `clase`
--

INSERT INTO `clase` (`Id`, `Clase_Tipo`, `Seccion_Id`, `RUT_Profesor`, `Modulo_Inicio`, `Modulo_Termino`, `Dia`, `Codigo_Semestre`) VALUES
(1, 'Teoria', 1, 164827607, 3, 4, 'Martes', 201125),
(2, 'Teoria', 1, 164827608, 3, 4, 'Martes', 201125),
(3, 'Ayudantia', 1, 164827608, NULL, NULL, NULL, 201125),
(4, 'Teoria', 2, NULL, NULL, NULL, NULL, 201125),
(5, 'Teoria', 2, NULL, NULL, NULL, NULL, 201125),
(6, 'Ayudantia', 2, NULL, NULL, NULL, NULL, 201125),
(7, 'Teoria', 3, NULL, NULL, NULL, NULL, 201125),
(8, 'Teoria', 3, NULL, NULL, NULL, NULL, 201125),
(9, 'Ayudantia', 3, NULL, NULL, NULL, NULL, 201125),
(10, 'Teoria', 4, NULL, NULL, NULL, NULL, 201125),
(11, 'Teoria', 4, NULL, NULL, NULL, NULL, 201125),
(12, 'Teoria', 4, NULL, NULL, NULL, NULL, 201125),
(13, 'Ayudantia', 4, NULL, NULL, NULL, NULL, 201125),
(14, 'Laboratorio', 4, NULL, NULL, NULL, NULL, 201125),
(15, 'Taller', 4, NULL, NULL, NULL, NULL, 201125),
(16, 'Teoria', 5, NULL, NULL, NULL, NULL, 201125),
(17, 'Teoria', 5, NULL, NULL, NULL, NULL, 201125),
(18, 'Ayudantia', 5, NULL, NULL, NULL, NULL, 201125),
(19, 'Laboratorio', 5, NULL, NULL, NULL, NULL, 201125),
(20, 'Teoria', 6, 164827608, NULL, NULL, NULL, 201125),
(21, 'Teoria', 6, NULL, NULL, NULL, NULL, 201125),
(22, 'Ayudantia', 6, NULL, NULL, NULL, NULL, 201125),
(23, 'Teoria', 7, NULL, NULL, NULL, NULL, 201125),
(24, 'Teoria', 7, NULL, NULL, NULL, NULL, 201125),
(25, 'Ayudantia', 7, NULL, NULL, NULL, NULL, 201125),
(26, 'Teoria', 8, NULL, NULL, NULL, NULL, 201125),
(27, 'Teoria', 8, NULL, NULL, NULL, NULL, 201125),
(28, 'Ayudantia', 8, NULL, NULL, NULL, NULL, 201125),
(29, 'Teoria', 9, NULL, NULL, NULL, NULL, 201125),
(30, 'Teoria', 9, NULL, NULL, NULL, NULL, 201125),
(31, 'Ayudantia', 9, NULL, NULL, NULL, NULL, 201125),
(32, 'Teoria', 10, NULL, NULL, NULL, NULL, 201125),
(33, 'Teoria', 10, NULL, NULL, NULL, NULL, 201125),
(34, 'Ayudantia', 10, NULL, NULL, NULL, NULL, 201125),
(35, 'Laboratorio', 10, NULL, NULL, NULL, NULL, 201125),
(36, 'Teoria', 11, NULL, NULL, NULL, NULL, 201125),
(37, 'Teoria', 11, NULL, NULL, NULL, NULL, 201125),
(38, 'Ayudantia', 11, NULL, NULL, NULL, NULL, 201125),
(39, 'Teoria', 12, NULL, NULL, NULL, NULL, 201125),
(40, 'Teoria', 12, NULL, NULL, NULL, NULL, 201125),
(41, 'Ayudantia', 12, NULL, NULL, NULL, NULL, 201125);

-- --------------------------------------------------------

--
-- Table structure for table `clase_tipo`
--

DROP TABLE IF EXISTS `clase_tipo`;
CREATE TABLE IF NOT EXISTS `clase_tipo` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del tipo de clase.',
  `Tipo` varchar(50) NOT NULL COMMENT 'Tipo del profesor.',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `clase_tipo`
--


-- --------------------------------------------------------

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE IF NOT EXISTS `modulo` (
  `Modulo` int(11) NOT NULL COMMENT 'Número de módulo.',
  `Regimen` varchar(1) NOT NULL COMMENT 'D = Diurno, V = Vespertino.',
  `Inicio` time NOT NULL COMMENT 'Hora de inicio de módulo.',
  `Termino` time NOT NULL COMMENT 'Hora de término de módulo.'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modulo`
--

INSERT INTO `modulo` (`Modulo`, `Regimen`, `Inicio`, `Termino`) VALUES
(1, 'D', '08:30:00', '09:15:00'),
(2, 'D', '09:25:00', '10:10:00'),
(3, 'D', '10:20:00', '11:05:00'),
(4, 'D', '11:15:00', '12:00:00'),
(5, 'D', '12:10:00', '12:55:00'),
(6, 'D', '13:05:00', '13:50:00'),
(7, 'D', '14:00:00', '14:45:00'),
(8, 'D', '14:55:00', '15:40:00'),
(9, 'D', '15:50:00', '16:35:00'),
(10, 'D', '16:45:00', '17:30:00'),
(11, 'D', '17:40:00', '18:25:00'),
(12, 'D', '18:35:00', '19:20:00'),
(13, 'D', '19:30:00', '20:15:00'),
(14, 'D', '20:25:00', '21:10:00'),
(1, 'V', '19:00:00', '19:45:00'),
(2, 'V', '19:46:00', '20:30:00'),
(3, 'V', '20:40:00', '21:25:00'),
(4, 'V', '21:26:00', '22:10:00'),
(5, 'V', '22:20:00', '23:05:00'),
(6, 'V', '23:06:00', '23:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `presupuesto`
--

DROP TABLE IF EXISTS `presupuesto`;
CREATE TABLE IF NOT EXISTS `presupuesto` (
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera a la que le pertenece el presupuesto.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Código del semestre en el que es válido el presupuesto.',
  `Presupuesto` int(11) DEFAULT NULL COMMENT 'Monto.',
  KEY `Codigo_Carrera` (`Codigo_Carrera`,`Codigo_Semestre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `presupuesto`
--

INSERT INTO `presupuesto` (`Codigo_Carrera`, `Codigo_Semestre`, `Presupuesto`) VALUES
('UNAB11500', 201125, 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `profesor`
--

DROP TABLE IF EXISTS `profesor`;
CREATE TABLE IF NOT EXISTS `profesor` (
  `RUT_Profesor` int(10) NOT NULL COMMENT 'Rut del profesor.',
  `Nombre` varchar(50) NOT NULL COMMENT 'Nombre del profesor.',
  `Profesor_Grado` int(11) NOT NULL COMMENT 'Grado del profesor.',
  PRIMARY KEY (`RUT_Profesor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profesor`
--

INSERT INTO `profesor` (`RUT_Profesor`, `Nombre`, `Profesor_Grado`) VALUES
(164827607, 'David Miranda', 1),
(164827608, 'David M. Atenas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profesor_grado`
--

DROP TABLE IF EXISTS `profesor_grado`;
CREATE TABLE IF NOT EXISTS `profesor_grado` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del grado.',
  `Grado` varchar(40) NOT NULL COMMENT 'Grado.',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `profesor_grado`
--

INSERT INTO `profesor_grado` (`Id`, `Grado`) VALUES
(1, 'Egresado o estudiante'),
(2, 'Titulado'),
(3, 'Magister'),
(4, 'Doctorado');

-- --------------------------------------------------------

--
-- Table structure for table `profesor_tiene_ramos`
--

DROP TABLE IF EXISTS `profesor_tiene_ramos`;
CREATE TABLE IF NOT EXISTS `profesor_tiene_ramos` (
  `RUT_Profesor` int(10) NOT NULL COMMENT 'Rut profesor que dicta ramo.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Ramo dictado por el profesor.',
  PRIMARY KEY (`RUT_Profesor`),
  KEY `Codigo_Ramo` (`Codigo_Ramo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profesor_tiene_ramos`
--


-- --------------------------------------------------------

--
-- Table structure for table `ramo`
--

DROP TABLE IF EXISTS `ramo`;
CREATE TABLE IF NOT EXISTS `ramo` (
  `Codigo` varchar(6) NOT NULL COMMENT 'Código identificador de cada ramo.',
  `Nombre` varchar(50) NOT NULL COMMENT 'Nombre del ramo.',
  `Teoria` int(2) NOT NULL COMMENT 'Horas teoricas.',
  `Tipo` int(1) NOT NULL COMMENT 'Tipo del ramo, C = carrera, F = depto. física, Q = depto. química, M = depto. matemáticas, I = inglés, O = formación general y P = formación profesional. ',
  `Periodo` int(1) NOT NULL COMMENT '1 = Semestral, 2 = Trimestral.',
  `Ayudantia` int(2) NOT NULL COMMENT 'Horas de ayudantia.',
  `Laboratorio` int(2) NOT NULL COMMENT 'Horas de laboratorio.',
  `Taller` int(2) NOT NULL COMMENT 'Horas de taller.',
  `Creditos` int(2) NOT NULL COMMENT 'Creditos del ramo.',
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ramo`
--

INSERT INTO `ramo` (`Codigo`, `Nombre`, `Teoria`, `Tipo`, `Periodo`, `Ayudantia`, `Laboratorio`, `Taller`, `Creditos`) VALUES
('FIS110', 'FÃ­sica I', 4, 2, 1, 2, 0, 0, 6),
('FIS115', 'EducaciÃ³n FÃ­sica', 2, 2, 1, 2, 2, 0, 6),
('FIS116', 'EducaciÃ³n FÃ­sica II', 2, 2, 1, 2, 0, 0, 6),
('FIS119', 'FÃ­sica III', 4, 2, 0, 2, 0, 0, 6),
('FIS120', 'FÃ­sica II', 4, 2, 1, 2, 0, 0, 6),
('FIS121', 'FÃ­sica IV', 4, 2, 1, 2, 0, 0, 6),
('FMM020', 'Algebra Nuclear I', 4, 1, 1, 4, 2, 20, 0),
('FMM021', 'Algebral Nuclear II', 10, 6, 1, 4, 4, 2, 20),
('FMM030', 'CÃ¡lculo I', 4, 6, 1, 2, 0, 0, 6),
('FMM130', 'CÃ¡lculo II', 4, 6, 1, 2, 0, 0, 6),
('FMM230', 'CÃ¡lculo III', 4, 6, 1, 2, 0, 0, 6),
('IET090', 'Redes I', 4, 1, 1, 2, 0, 0, 0),
('IET091', 'Redes II', 4, 1, 1, 2, 2, 0, 6),
('IET100', 'Elementos de la ComputaciÃ³n', 6, 1, 1, 2, 2, 2, 6),
('IET110', 'Elementos', 0, 1, 1, 0, 0, 0, 0),
('IET120', 'Computacion', 0, 1, 1, 0, 0, 0, 0),
('INF090', 'Historia de la computaciÃ³n', 4, 1, 1, 2, 0, 0, 6),
('INF110', 'Levantar II', 4, 1, 1, 2, 0, 0, 6),
('INF111', 'Levantar', 0, 1, 1, 0, 0, 0, 0),
('INF112', 'Levantar III', 4, 1, 1, 2, 0, 0, 6),
('INF114', 'Levantar IV', 6, 1, 1, 4, 2, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ramo_tipo`
--

DROP TABLE IF EXISTS `ramo_tipo`;
CREATE TABLE IF NOT EXISTS `ramo_tipo` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del tipo.',
  `Tipo` varchar(50) NOT NULL COMMENT 'Tipo del ramo.',
  `Abreviacion` varchar(3) NOT NULL COMMENT 'Abreviación del tipo de ramo.',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ramo_tipo`
--

INSERT INTO `ramo_tipo` (`Id`, `Tipo`, `Abreviacion`) VALUES
(1, 'Carrera', 'C'),
(2, 'Fisica', 'F'),
(3, 'Ingles', 'I'),
(4, 'Formacion general', 'O'),
(5, 'Formacion profesional', 'P'),
(6, 'Matematicas', 'M'),
(7, 'Quimica', 'Q'),
(8, 'Asdf', 'A'),
(9, 'Asdf', 'AB'),
(10, 'Asdf', 'AA');

-- --------------------------------------------------------

--
-- Table structure for table `ramos_impartidos`
--

DROP TABLE IF EXISTS `ramos_impartidos`;
CREATE TABLE IF NOT EXISTS `ramos_impartidos` (
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera en la cual se imparte el ramo.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Codigo del ramo impartido.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Semestre o trimestre en el cual se imparte.',
  `Impartido` int(1) NOT NULL COMMENT '1 = Impartido, 2 = No impartido.',
  KEY `Codigo_Ramo` (`Codigo_Ramo`),
  KEY `Codigo_Semestre` (`Codigo_Semestre`),
  KEY `Codigo_Carrera` (`Codigo_Carrera`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ramos_impartidos`
--

INSERT INTO `ramos_impartidos` (`Codigo_Carrera`, `Codigo_Ramo`, `Codigo_Semestre`, `Impartido`) VALUES
('DER1000', 'INF111', 201410, 1),
('DER1000', 'INF111', 201420, 1),
('DER1000', 'IET100', 201420, 1),
('DER1000', 'FIS110', 201420, 1),
('DER1000', 'FMM230', 201420, 1),
('DER1000', 'IET120', 201420, 1),
('DER1000', 'FMM130', 201420, 1),
('DER1000', 'FMM030', 201420, 1),
('UNAB11500', 'IET091', 201420, 1),
('UNAB11500', 'FMM230', 201420, 1),
('UNAB11500', 'FMM130', 201420, 1),
('UNAB11500', 'FIS110', 201420, 1),
('UNAB11500', 'FMM030', 201420, 1),
('UNAB11500', 'IET100', 201420, 1),
('UNAB11500', 'INF110', 201420, 1),
('UNAB11500', 'IET100', 201125, 1),
('UNAB11500', 'FMM130', 201125, 1),
('UNAB11500', 'IET091', 201125, 1),
('UNAB11500', 'INF111', 201125, 1),
('UNAB11500', 'INF110', 201125, 1),
('UNAB11500', 'FIS110', 201125, 1),
('UNAB11500', 'IET090', 201125, 1),
('UNAB11550', 'IET100', 201125, 1),
('UNAB11500', 'FIS120', 201125, 1),
('UNAB11560', 'IET120', 201125, 1),
('UNAB11560', 'FIS115', 201125, 1),
('UNAB11560', 'FIS116', 201125, 1),
('UNAB65000', 'FIS110', 201420, 1),
('UNAB11550', 'FMM230', 201125, 1),
('UNAB11550', 'FIS120', 201125, 2),
('UNAB11550', 'IET091', 201125, 1),
('UNAB11560', 'FIS110', 201125, 1),
('UNAB11500', 'FMM030', 201125, 1),
('UNAB11500', 'FMM230', 201125, 1),
('UNAB11500', 'INF112', 201125, 1),
('UNAB11500', 'INF090', 201125, 1),
('DER1000', 'FMM030', 201410, 1),
('UNAB11550', 'FMM030', 201125, 1),
('UNAB11550', 'INF090', 201125, 1),
('UNAB11550', 'IET090', 201125, 1),
('UNAB11550', 'FIS110', 201125, 1),
('DER1000', 'IET100', 201410, 1),
('DER1000', 'FMM130', 201410, 1);

-- --------------------------------------------------------

--
-- Table structure for table `seccion`
--

DROP TABLE IF EXISTS `seccion`;
CREATE TABLE IF NOT EXISTS `seccion` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la sección.',
  `Numero_Seccion` int(11) NOT NULL COMMENT 'Número de la sección.',
  `NRC` int(11) NOT NULL COMMENT 'Código identificador de cada sección.',
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Código del ramo al cual pertenece la sección.',
  `Codigo_Carrera` varchar(9) NOT NULL COMMENT 'Código de la carrera a la cual le pertenece esta sección.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Semestre al que pertenece la sección.',
  `Regimen` varchar(1) NOT NULL COMMENT 'D = Diurno, V = Vespertino.',
  `Vacantes` int(11) NOT NULL COMMENT 'Vacantes de la sección.',
  `Vacantes_utilizadas` int(11) NOT NULL COMMENT 'Cantidad de vacantes utilizadas por el jefe de carrera.',
  PRIMARY KEY (`Id`),
  KEY `Codigo_Ramo` (`Codigo_Ramo`),
  KEY `Numero_Seccion` (`Numero_Seccion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `seccion`
--

INSERT INTO `seccion` (`Id`, `Numero_Seccion`, `NRC`, `Codigo_Ramo`, `Codigo_Carrera`, `Codigo_Semestre`, `Regimen`, `Vacantes`, `Vacantes_utilizadas`) VALUES
(1, 1, 1524, 'IET090', 'UNAB11500', 201125, 'D', 60, 0),
(2, 2, 1524, 'IET090', 'UNAB11500', 201125, 'D', 60, 0),
(3, 3, 1524, 'IET090', 'UNAB11500', 201125, 'D', 60, 0),
(4, 1, 1524, 'IET100', 'UNAB11500', 201125, 'D', 60, 0),
(5, 1, 1524, 'IET091', 'UNAB11500', 201125, 'D', 60, 0),
(6, 1, 1524, 'INF090', 'UNAB11500', 201125, 'D', 60, 0),
(7, 2, 1524, 'INF090', 'UNAB11500', 201125, 'D', 60, 0),
(8, 3, 1524, 'INF090', 'UNAB11500', 201125, 'D', 60, 0),
(9, 4, 1524, 'INF090', 'UNAB11500', 201125, 'D', 60, 0),
(10, 2, 1524, 'IET091', 'UNAB11500', 201125, 'D', 60, 0),
(11, 1, 1524, 'INF112', 'UNAB11500', 201125, 'D', 60, 0),
(12, 2, 1524, 'INF112', 'UNAB11500', 201125, 'D', 60, 0);

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
(201320, 2, 2013, '2011-10-09 23:04:58', '2011-10-09 23:24:31'),
(201410, 1, 2014, '2011-10-27 15:09:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
CREATE TABLE IF NOT EXISTS `solicitud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Codigo_Ramo` varchar(6) NOT NULL COMMENT 'Código del ramo pedido.',
  `Seccion_asignada` int(11) DEFAULT NULL COMMENT 'Sección a la cual se le asignó la cantidad de vacantes.',
  `Carrera` varchar(9) NOT NULL COMMENT 'Carrera dueña del ramo.',
  `Carrera_Solicitante` varchar(9) NOT NULL COMMENT 'Carrera solicitante de vacantes.',
  `Vacantes` int(11) NOT NULL COMMENT 'Número de vacantes solicitadas.',
  `Vacantes_Asignadas` int(11) DEFAULT NULL COMMENT 'Cantidad de vacantes asignadas.',
  `Codigo_Semestre` int(11) NOT NULL COMMENT 'Código del semestre al cual pertenece esta solicitud.',
  `Fecha_Envio` datetime NOT NULL COMMENT 'Fecha en la que se envío esta solicitud.',
  `Fecha_Respuesta` datetime DEFAULT NULL COMMENT 'Fecha en la cual se respondio a la solicitud.',
  `Estado` int(11) NOT NULL COMMENT '1 = Esperando, 2 = Aceptada y 3 = Denegada.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `solicitud`
--


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
(3, 'Jefe de carrera + administrador'),
(4, 'Usuario departamento');

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
('depto', '164827607', 'Jorge Atenas', '040b7cf4a55014e185813e0644502ea9', 4),
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
  ADD CONSTRAINT `seccion_ibfk_3` FOREIGN KEY (`Codigo_Ramo`) REFERENCES `ramo` (`Codigo`);
