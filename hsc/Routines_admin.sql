TRIGGER apertura de semestre

CREATE PROCEDURE agregar_carrera(cod VARCHAR(7),nombre VARCHAR(100))
BEGIN
  IF ((SELECT Codigo,Nombre_Carrera FROM Carrera WHERE Codigo = cod AND Nombre_Carrera = nombre) IS NOT NULL) THEN
    SELECT 4;
  END IF;
  
  IF ((SELECT Codigo,Nombre_Carrera FROM Carrera WHERE Codigo = cod) IS NOT NULL) THEN
    SELECT 3;
  END IF;

  IF ((SELECT Codigo,Nombre_Carrera FROM Carrera WHERE Nombre_Carrera = nombre) IS NOT NULL) THEN
    SELECT 2;
  ELSE
    SELECT 1;
  END IF;
END;//

CREATE PROCEDURE agregar_jefe_carrera(rut VARCHAR(10),nombre VARCHAR(40),nombreusuario VARCHAR(40),pass VARCHAR(32))
BEGIN
  INSERT INTO Usuario(Nombre_Usuario,RUT,Nombre,Password,Id_Tipo) VALUES (nombreusuario,rut,nombre,pass,1);
END;//

CREATE PROCEDURE agregar_ramo(codigoRamo VARCHAR(6),nombreRamo VARCHAR(50),hTeoricas INT,hAyudantia INT,hLaboratorio INT,hTaller INT,credito INT)
BEGIN
  INSERT INTO Ramo(Codigo,Nombre,Teoria,Ayudantia,Laboratorio,Taller,Creditos) VALUES (codigoRamo,nombreRamo,hTeoricas,hAyudantia,hLaboratorio,hTaller,credito);
END;//

CREATE PROCEDURE select_carreras()
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
END;//

CREATE PROCEDURE select_ramoCarrera(codigoRamo VARCHAR(6))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera
   FROM Carrera AS c
   INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Ramo = codigoRamo
  WHERE c.Codigo = ctr.Codigo_Carrera ORDER BY c.Codigo;
END;//

CREATE PROCEDURE select_ramoscarreras(codigoCarrera VARCHAR(9))
BEGIN
  SELECT r.Codigo,r.Nombre,ctr.Semestre
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramo AS r ON r.Codigo = ctr.Codigo_Ramo
  WHERE ctr.Codigo_Carrera = codigoCarrera ORDER BY ctr.Semestre,r.Codigo;
END;// 

CREATE PROCEDURE eliminar_jdc(nombreUsuario VARCHAR(40))
BEGIN
  IF ((SELECT COUNT(c.nombreUsuario_JC) FROM carrera AS c WHERE c.nombreUsuario_JC = nombreUsuario) > 0) THEN
    UPDATE Carrera AS c SET c.NombreUsuario_JC = NULL WHERE c.nombreUsuario_JC = nombreUsuario;
    DELETE FROM usuario WHERE Nombre_Usuario = nombreUsuario;
  ELSE
    DELETE FROM usuario WHERE Nombre_Usuario = nombreUsuario;
  END IF;
END;//

CREATE PROCEDURE selectCarrera(codigoCarrera VARCHAR(9))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera,c.Periodo
   FROM Carrera AS c
  WHERE c.Codigo = codigoCarrera;
END;//

CREATE PROCEDURE comenzarSemestre(codigoSemestre INT, numeroSemestre INT, annoSemestre INT, fechaInicio DATETIME)
BEGIN
  INSERT INTO Semestre(Codigo_Semestre,Numero,Anho,Fecha_Inicio,Fecha_Termino) VALUES(codigoSemestre,numeroSemestre,annoSemestre,fechaInicio,NULL);
END;//

CREATE PROCEDURE cerrarSemestre(codigoSemestre INT,fecha DATETIME)
BEGIN
  UPDATE Semestre 
   SET Fecha_Termino = fecha
  WHERE Codigo_Semestre = codigoSemestre;
END;//

CREATE PROCEDURE abrirSemestreAnterior(codigoSemestre INT, fecha DATETIME)
BEGIN
  UPDATE Semestre
   SET Fecha_Termino = NULL
  WHERE Codigo_Semestre = codigoSemestre;
END;//

CREATE PROCEDURE comenzarTrimestre(codigoTrimestre INT, numeroTrimestre INT, annoSemestre YEAR, fechaInicio DATETIME)
BEGIN
  INSERT INTO Trimestre(Codigo_Trimestre,Numero,Anho,Fecha_Inicio,Fecha_Termino) VALUES(codigoTrimestre,numeroTrimestre,annoSemestre,fechaInicio,NULL);
END;//

CREATE PROCEDURE cerrarTrimestre(codigoTrimestre INT,fecha DATETIME)
BEGIN
  UPDATE Trimestre 
   SET Fecha_Termino = fecha
  WHERE Codigo_Trimestre = codigoTrimestre;
END;//

CREATE PROCEDURE abrirTrimestreAnterior(codigoTrimestre INT, fecha DATETIME)
BEGIN
  UPDATE Trimestre
   SET Fecha_Termino = NULL
  WHERE Codigo_Trimestre = codigoTrimestre;
END;//

CREATE PROCEDURE obtenerSemestre()
BEGIN
  SELECT s.Codigo_Semestre,s.Numero,s.Anho,s.Fecha_Inicio,s.Fecha_Termino
   FROM Semestre AS s
  WHERE s.Codigo_Semestre = (SELECT MAX(s.Codigo_Semestre) FROM Semestre AS s);
END;//

CREATE PROCEDURE obtenerTrimestre()
BEGIN
  SELECT t.Codigo_Trimestre,t.Numero,t.Anho,t.Fecha_Inicio,t.Fecha_Termino
   FROM Trimestre AS t
  WHERE t.Codigo_Trimestre = (SELECT MAX(t.Codigo_Trimestre) FROM Trimestre AS t);
END;//

CREATE PROCEDURE ramoDictado(codigoCarrera VARCHAR(9), codigoRamo VARCHAR(6), codigoSemestre INT)
BEGIN
  SELECT ri.Codigo_Ramo,ri.Impartido
   FROM ramos_impartidos AS ri
  WHERE ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Ramo = codigoRamo AND ri.Codigo_Semestre = codigoSemestre;
END;//

CREATE PROCEDURE verRamosImpartidos(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT r.Codigo,r.Nombre,r.Tipo,ctr.Semestre,c.Periodo
   FROM ramos_impartidos AS ri
   INNER JOIN ramo AS r ON r.Codigo = ri.Codigo_Ramo
   INNER JOIN carrera_tiene_ramos AS ctr ON ctr.Codigo_Carrera = ri.Codigo_Carrera AND ctr.Codigo_Ramo = ri.Codigo_Ramo
   INNER JOIN carrera AS c ON c.Codigo = ctr.Codigo_Carrera
  WHERE ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Semestre = codigoSemestre AND ri.Impartido = 1 ORDER BY ctr.Semestre,r.Codigo;
END;//

CREATE PROCEDURE seccionesCreadasNumero(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT COUNT(s.NRC)
   FROM Seccion AS s
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre;
END;//

CREATE PROCEDURE seccionesCreadas(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.NRC,s.Codigo_Ramo,s.Codigo_Carrera,s.RUT_Profesor,s.Codigo_Semestre
   FROM Seccion AS s
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre;
END;//


CREATE PROCEDURE seccionesCreadasOtroNumero(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT COUNT(s.NRC)
   FROM Seccion AS s
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera != codigoCarrera AND s.Codigo_Semestre = codigoSemestre;
END;//

CREATE PROCEDURE verSeccionesCreadasOtro(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.RUT_Profesor,s.Codigo_Semestre
   FROM Seccion AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Ramo = codigoRamo AND s.Codigo_Carrera != codigoCarrera AND s.Codigo_Semestre = codigoSemestre ORDER BY s.Codigo_Carrera,s.NRC;
END;//

CREATE PROCEDURE obtenerPeriodoCarrera(codigoCarrera VARCHAR(9))
BEGIN
  SELECT c.Periodo
   FROM Carrera AS c
  WHERE c.Codigo = codigoCarrera;
END;//

CREATE PROCEDURE solicitarVacantes(codigoRamo VARCHAR(6), codigoCarrera VARCHAR(9), codigoCarreraSolicitante VARCHAR(9), numeroVacantes INT, codigoSemestre INT, fechaEnvio DATETIME)
BEGIN
  INSERT INTO Solicitud(Codigo_Ramo,Carrera,Carrera_Solicitante,Vacantes,Codigo_Semestre,Fecha_Envio,Fecha_Respuesta,Estado) VALUES (codigoRamo,codigoCarrera,codigoCarreraSolicitante,numeroVacantes,codigoSemestre,fechaEnvio,NULL,1);
END;//

CREATE PROCEDURE verSolicitudesOtros(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera_Solicitante,s.Vacantes,s.Vacantes_Asignadas,s.Fecha_Envio,s.Fecha_Respuesta,s.Estado
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera = codigoCarrera ORDER BY s.Estado,s.Fecha_Envio,s.Carrera_Solicitante,s.Codigo_Ramo;
END;//

CREATE PROCEDURE verSolicitudesMias(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera,s.Vacantes,s.Vacantes_Asignadas,s.Fecha_Envio,s.Fecha_Respuesta,s.Estado
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera_Solicitante = codigoCarrera ORDER BY s.Estado,s.Fecha_Envio,s.Carrera_Solicitante,s.Codigo_Ramo;
END;//

CREATE PROCEDURE comprobarSolicitudExiste(codigoCarreraSolicitante VARCHAR(9), codigoCarreraDestinatario VARCHAR(9), codigoSemestre INT, codigoRamo VARCHAR(6))
BEGIN
  SELECT s.Id
   FROM Solicitud AS s
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera_Solicitante = codigoCarreraSolicitante AND s.Codigo_Ramo = codigoRamo AND s.Carrera = codigoCarreraDestinatario AND s.Estado = 1;
END;//

CREATE PROCEDURE revisarSolicitud(idSolicitud INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,s.Carrera,s.Carrera_Solicitante,s.Vacantes,s.Codigo_Semestre,s.Fecha_Envio,s.Fecha_Respuesta,s.Estado
   FROM Solicitud AS s
  WHERE s.Id = idSolicitud;
END;//

CREATE PROCEDURE responderSolicitud(idSolicitud INT, respuesta INT, vacantes INT, fecharespuesta DATETIME)
BEGIN
  IF(respuesta = 2) THEN
    UPDATE Solicitud SET estado = 2, vacantes_asignadas = vacantes, fecha_respuesta = fecharespuesta WHERE id = idSolicitud;
  ELSE
    UPDATE Solicitud SET estado = 3, vacantes_asignadas = 0, fecha_respuesta = fecharespuesta WHERE id = idSolicitud;
  END IF;
END;//

CREATE PROCEDURE modificarSolicitud(idSolicitud INT, numeroVacantes INT)
BEGIN
  UPDATE Solicitud SET Vacantes = numeroVacantes WHERE Id = idSolicitud AND Estado = 1;
END;//

CREATE PROCEDURE eliminarSolicitud(idSolicitud INT)
BEGIN
  DELETE FROM Solicitud WHERE Id = idSolicitud;
END;//
CREATE PROCEDURE buscarRutProfesor(rutProfesor VARCHAR(10))
BEGIN
  SELECT p.Rut_Profesor 
   FROM Profesor AS p 
  WHERE p.Rut_Profesor = rutProfesor;
END;//

CREATE PROCEDURE agregarProfesor(rutProfesor INT, nombreProfesor VARCHAR(50))
BEGIN
  INSERT INTO Profesor(Rut_Profesor,Nombre) VALUES (rutProfesor,nombreProfesor);
END;//

CREATE PROCEDURE obtenerHorarios(codigoCarrera VARCHAR(9))
BEGIN
  SELECT m.Modulo,m.Regimen,m.Inicio,m.Termino 
   FROM Modulo AS m 
   INNER JOIN Carrera AS c ON c.Codigo = codigoCarrera 
  WHERE m.Regimen = c.Regimen;
END;//

CREATE PROCEDURE obtenerHorarioActual(codigoSeccion INT)
BEGIN
  SELECT s.Horario_Inicio,s.Horario_Termino
   FROM Seccion AS s
  WHERE s.NRC = codigoSeccion;
END;//

CREATE PROCEDURE semestreRamo(codigoCarrera VARCHAR(10), codigoRamo VARCHAR(6))
BEGIN
  SELECT ctr.Semestre
   FROM Carrera_Tiene_Ramos AS ctr
  WHERE ctr.Codigo_Carrera = codigoCarrera AND ctr.Codigo_Ramo = codigoRamo;
END;//

CREATE PROCEDURE ramosSemestre(codigoCarrera VARCHAR(10), codigoRamo VARCHAR(6), codigoSemestre INT, semestreRamo INT)
BEGIN
  SELECT ri.Codigo_Ramo
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramos_Impartidos AS ri ON ri.Codigo_Carrera = codigoCarrera AND ri.Codigo_Ramo = ctr.Codigo_Ramo AND ri.Codigo_Semestre = codigoSemestre AND ri.Impartido = 1
  WHERE ctr.Codigo_Carrera = codigoCarrera AND ctr.Semestre = semestreRamo AND ctr.Codigo_Ramo != codigoRamo;
END;//

CREATE PROCEDURE horarioSeccion(codigoCarrera VARCHAR(10), codigoRamo VARCHAR(6), codigoSemestre INT)
BEGIN
  SELECT s.Horario_Inicio,s.Horario_Termino
   FROM Seccion AS s
  WHERE s.Codigo_Carrera = codigoCarrera AND s.Codigo_Ramo = codigoRamo AND s.Codigo_Semestre = codigoSemestre;
END;//

