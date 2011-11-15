CREATE PROCEDURE user_login(username VARCHAR(40),password VARCHAR(32))
BEGIN
  SELECT u.RUT,u.Nombre,u.Id_Tipo
   FROM Usuario AS u
  WHERE u.Nombre_Usuario = username AND u.Password = password;
END;//

-- Seleccionar ramos que se dictan




-- Seleccionar presupuesto
CREATE PROCEDURE presupuesto(codigo_carrera VARCHAR(7))
BEGIN
  SELECT presupuesto
   FROM semestre
END;//

-- Carreras de un jefe de carrera.
CREATE PROCEDURE jdc_carreras(nombreUsuario VARCHAR(40))
BEGIN
  SELECT c.Codigo,c.Nombre_Carrera,c.Periodo
   FROM Carrera AS c
  WHERE c.NombreUsuario_JC = nombreUsuario;
END;//

-- HOME Jefe de Carrera

-- 1) Ramos de una carrera
CREATE PROCEDURE ver_malla(codigoCarrera VARCHAR(9))
BEGIN
  SELECT ctr.Codigo_Ramo,r.Nombre,r.Tipo,ctr.Semestre
   FROM carrera_tiene_ramos AS ctr
   INNER JOIN Ramo AS r ON ctr.Codigo_Ramo = r.Codigo
  WHERE ctr.Codigo_Carrera = codigoCarrera ORDER BY ctr.Semestre;
END;//

-- 2) Seleccionar ramos que pidieron
CREATE PROCEDURE solicitudesPedidas(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera_Solicitante,s.Vacantes
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera = codigoCarrera AND s.Estado = 1;
END;//

-- 3) Seleccionar ramos que pido
CREATE PROCEDURE solicitudesSolicitadas(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT s.Id,s.Codigo_Ramo,r.Nombre,s.Carrera,s.Vacantes
   FROM Solicitud AS s
   INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
  WHERE s.Codigo_Semestre = codigoSemestre AND s.Carrera_Solicitante = codigoCarrera AND s.Estado = 1;
END;//

-- 5) Seleccionar profesores.
CREATE PROCEDURE prof_asignados(codigoCarrera VARCHAR(9))
BEGIN
  SELECT p.Rut_Profesor, p.Nombre
   FROM Profesor AS p
  WHERE p.codigo_carrera = codigoCarrera;
END;//

-- 6) Seleccionar profesores sin carga.
CREATE PROCEDURE prof_asignados_sc(codigoCarrera VARCHAR(9))
BEGIN
  SELECT p.Rut_Profesor, p.Nombre
   FROM Profesor AS p
  WHERE p.codigo_carrera = codigoCarrera AND p.Rut_Profesor NOT IN (SELECT s.Rut_Profesor FROM Seccion AS s WHERE s.Rut_Profesor IS NOT NULL);
END;//

-- 7) Seleccionar secciones sin profesor.
CREATE PROCEDURE verSeccionesSinProfesor(codigoCarrera VARCHAR(9), codigoSemestre INT)
BEGIN
  SELECT r.Codigo,r.Nombre,s.NRC
   FROM Carrera_Tiene_Ramos AS ctr
   INNER JOIN Ramo AS r ON r.Codigo = ctr.Codigo_Ramo
   INNER JOIN Seccion AS s ON s.Codigo_Ramo = r.Codigo AND s.Codigo_Carrera = codigoCarrera AND s.Codigo_Semestre = codigoSemestre AND s.RUT_Profesor IS NULL
  WHERE ctr.Codigo_Carrera = codigoCarrera;
END;//


