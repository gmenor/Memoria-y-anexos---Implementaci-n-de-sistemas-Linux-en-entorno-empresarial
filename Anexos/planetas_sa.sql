/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: planetas_sa
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ausencias`
--

DROP TABLE IF EXISTS `ausencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ausencias` (
  `id_ausencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `motivo` text NOT NULL,
  `justificada` tinyint(1) DEFAULT 0,
  `id_empleado_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ausencia`),
  KEY `idx_empleado_ausencias` (`id_empleado`,`fecha_inicio`,`fecha_fin`),
  KEY `fk_ausencias_modificador` (`id_empleado_modificador`),
  CONSTRAINT `ausencias_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  CONSTRAINT `fk_ausencias_modificador` FOREIGN KEY (`id_empleado_modificador`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ausencias`
--

LOCK TABLES `ausencias` WRITE;
/*!40000 ALTER TABLE `ausencias` DISABLE KEYS */;
INSERT INTO `ausencias` VALUES
(1,1,'2025-06-20','2025-06-20','Concierto de dellafuente',1,1);
/*!40000 ALTER TABLE `ausencias` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER validar_fechas_ausencias
BEFORE INSERT ON ausencias
FOR EACH ROW
BEGIN
    IF NEW.fecha_fin < NEW.fecha_inicio THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: fecha_fin no puede ser menor que fecha_inicio en ausencias';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER registrar_modificacion_ausencia
AFTER UPDATE ON ausencias
FOR EACH ROW
BEGIN
    
    IF OLD.justificada = FALSE AND NEW.justificada = TRUE THEN
        INSERT INTO modificaciones_ausencias (id_ausencia, id_empleado_modificador)
        VALUES (NEW.id_ausencia, NEW.id_empleado_modificador);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `correcciones_fichaje`
--

DROP TABLE IF EXISTS `correcciones_fichaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `correcciones_fichaje` (
  `id_correccion` int(11) NOT NULL AUTO_INCREMENT,
  `id_fichaje` int(11) NOT NULL,
  `id_empleado_modificador` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `tipo_antiguo` enum('entrada','salida') DEFAULT NULL,
  `tipo_nuevo` enum('entrada','salida') DEFAULT NULL,
  `hora_antigua` time DEFAULT NULL,
  `hora_nueva` time DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_correccion`),
  KEY `correcciones_fichaje_ibfk_1` (`id_fichaje`),
  KEY `correcciones_fichaje_ibfk_2` (`id_empleado_modificador`),
  CONSTRAINT `correcciones_fichaje_ibfk_1` FOREIGN KEY (`id_fichaje`) REFERENCES `fichajes` (`id_fichaje`) ON DELETE CASCADE,
  CONSTRAINT `correcciones_fichaje_ibfk_2` FOREIGN KEY (`id_empleado_modificador`) REFERENCES `empleados` (`id_empleado`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correcciones_fichaje`
--

LOCK TABLES `correcciones_fichaje` WRITE;
/*!40000 ALTER TABLE `correcciones_fichaje` DISABLE KEYS */;
INSERT INTO `correcciones_fichaje` VALUES
(1,1,1,'Modificación de hora de fichaje',NULL,NULL,'19:29:57','09:00:00','2025-06-05 22:55:08');
/*!40000 ALTER TABLE `correcciones_fichaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `id_departamento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_departamento`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `fk_responsable` (`id_responsable`),
  CONSTRAINT `fk_responsable` FOREIGN KEY (`id_responsable`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES
(1,'RRHH',1),
(4,'Direccion',NULL),
(5,'IT',NULL),
(6,'Finanzas',NULL),
(7,'Marketing',NULL),
(8,'Producción',NULL),
(9,'Logistica',NULL),
(10,'Calidad',NULL);
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `id_turno` int(11) DEFAULT NULL,
  `puesto` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `uid_ldap` varchar(100) NOT NULL,
  `cuenta_pidgin` varchar(100) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `dias_vacaciones_disponibles` int(11) DEFAULT 30,
  PRIMARY KEY (`id_empleado`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `uid_ldap` (`uid_ldap`),
  UNIQUE KEY `cuenta_pidgin` (`cuenta_pidgin`),
  KEY `fk_departamento` (`id_departamento`),
  KEY `fk_turno` (`id_turno`),
  CONSTRAINT `fk_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`) ON DELETE CASCADE,
  CONSTRAINT `fk_turno` FOREIGN KEY (`id_turno`) REFERENCES `turnos` (`id_turno`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES
(1,'Romeo','Menor','00000000A',1,1,'Secretario Ejecutivo','617020424','romeo','romeo@planetas.sa','2025-06-02',14),
(2,'Guillermo','Lopez','48157144C',1,1,'Director General','624228561','guillermo','guillermo@planetas.sa','2025-03-01',30),
(8,'Maria','Acedo','11111111A',1,2,'Asistente de Dirección','111223344','maria','maria@planetas.sa','2025-06-02',30),
(10,'Nerea','Mulero','12345678A',1,1,'Técnico de Nóminas','612345678','nerea','nerea@planetas.sa','2025-06-02',30),
(11,'Jose','Prieto','23456789B',5,2,'Administrador de Sistemas','623456789','jose','jose@planetas.sa','2025-06-02',30),
(12,'David','Mulero','34567890C',5,2,'Desarrollador Web','634567890','david','david@planetas.sa','2025-06-02',30),
(13,'Santiago','Martin','45678901D',6,1,'Contable','645678901','santi','santi@planetas.sa','2025-06-02',30),
(14,'Estela','Alvarez','56789012E',6,2,'Analista Financiero','656789012','estela','estela@planetas.sa','2025-06-02',30),
(15,'Javier','Torre67890123F','67890123F',7,1,'Responsable de Marketing Digital','678901234','javier','javier@planetas.sa','2025-06-02',30),
(16,'Justo','Mulero','89012345H',7,2,'Diseñador Gráfico','601234567','justo','justo@planetas.sa','2025-06-02',30),
(17,'Orujo','Prieto','90123456J',8,1,'Jefe de Producción','722345678','orujo','orujo@planetas.sa','2025-06-02',30),
(18,'Julia','Muñoz','13579246L',8,2,'Operaria de Línea','733456789','julia','julia@planetas.sa','2025-06-02',30),
(19,'Carolina','Castillo','24681357M',9,1,'Responsable de Almacén','755678901','carolina','carolina@planetas.sa','2025-06-02',30),
(20,'Roberto','Mendoza','46813579P',9,2,'Supervisor de Transporte','766789012','roberto','roberto@planetas.sa','2025-06-02',30),
(21,'Elena','Navarro','57924680Q',10,1,'Inspectora de Control de Calidad','777890123','elena','elena@planetas.sa','2025-06-02',30),
(22,'Alberto','Rios','02479135X',10,2,'Auditor de Procesos','721234567','alberto','alberto@planetas.sa','2025-06-02',30);
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fichajes`
--

DROP TABLE IF EXISTS `fichajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fichajes` (
  `id_fichaje` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `fichaje_modificado` tinyint(1) DEFAULT 0,
  `id_empleado_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_fichaje`),
  KEY `idx_empleado_fichajes` (`id_empleado`,`fecha`),
  CONSTRAINT `fichajes_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fichajes`
--

LOCK TABLES `fichajes` WRITE;
/*!40000 ALTER TABLE `fichajes` DISABLE KEYS */;
INSERT INTO `fichajes` VALUES
(1,1,'2025-06-02','09:00:00','entrada',1,1),
(2,1,'2025-06-02','19:31:38','salida',0,NULL),
(10,11,'2025-06-06','21:31:13','entrada',0,NULL);
/*!40000 ALTER TABLE `fichajes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER registrar_incidente_entrada
AFTER INSERT ON fichajes
FOR EACH ROW
BEGIN
    DECLARE turno_inicio TIME;
    DECLARE flexible BOOLEAN;
    
    
    SELECT hora_inicio, horario_flexible INTO turno_inicio, flexible
    FROM turnos 
    WHERE id_turno = (SELECT id_turno FROM empleados WHERE id_empleado = NEW.id_empleado);

    
    IF NEW.tipo = 'entrada' AND NEW.hora > ADDTIME(turno_inicio, '00:03:00') THEN
        IF flexible = FALSE OR TIMESTAMPDIFF(HOUR, turno_inicio, NEW.hora) > 2 THEN
            INSERT INTO incidencias_fichaje (id_fichaje, descripcion)
            VALUES (NEW.id_fichaje, CONCAT('Fichaje tardío a las ', NEW.hora));
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER registrar_incidente_salida
AFTER INSERT ON fichajes
FOR EACH ROW
BEGIN
    DECLARE turno_fin TIME;
    DECLARE flexible BOOLEAN;

    
    SELECT hora_fin, horario_flexible INTO turno_fin, flexible
    FROM turnos 
    WHERE id_turno = (SELECT id_turno FROM empleados WHERE id_empleado = NEW.id_empleado);

    
    IF NEW.tipo = 'salida' AND NEW.hora < turno_fin THEN
        IF flexible = FALSE OR TIMESTAMPDIFF(HOUR, NEW.hora, turno_fin) > 2 THEN
            INSERT INTO incidencias_fichaje (id_fichaje, descripcion)
            VALUES (NEW.id_fichaje, CONCAT('Salida temprana a las ', NEW.hora));
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER bloquear_cambio_fichaje_modificado
BEFORE UPDATE ON fichajes
FOR EACH ROW
BEGIN
    
    IF OLD.fichaje_modificado = TRUE AND NEW.fichaje_modificado = FALSE THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: No puedes cambiar fichaje_modificado de TRUE a FALSE.';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER registrar_correccion_fichaje
AFTER UPDATE ON fichajes
FOR EACH ROW
BEGIN
    
    IF OLD.tipo <> NEW.tipo AND OLD.hora = NEW.hora THEN
        INSERT INTO correcciones_fichaje (id_fichaje, id_empleado_modificador, motivo, tipo_antiguo, tipo_nuevo, hora_antigua, hora_nueva)
        VALUES (OLD.id_fichaje, NEW.id_empleado_modificador, 'Modificación de tipo de fichaje', OLD.tipo, NEW.tipo, NULL, NULL);
    END IF;

    IF OLD.hora <> NEW.hora AND OLD.tipo = NEW.tipo THEN
        INSERT INTO correcciones_fichaje (id_fichaje, id_empleado_modificador, motivo, tipo_antiguo, tipo_nuevo, hora_antigua, hora_nueva)
        VALUES (OLD.id_fichaje, NEW.id_empleado_modificador, 'Modificación de hora de fichaje', NULL, NULL, OLD.hora, NEW.hora);
    END IF;

    IF OLD.tipo <> NEW.tipo AND OLD.hora <> NEW.hora THEN
        INSERT INTO correcciones_fichaje (id_fichaje, id_empleado_modificador, motivo, tipo_antiguo, tipo_nuevo, hora_antigua, hora_nueva)
        VALUES (OLD.id_fichaje, NEW.id_empleado_modificador, 'Modificación de tipo y hora de fichaje', OLD.tipo, NEW.tipo, OLD.hora, NEW.hora);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `incidencias_fichaje`
--

DROP TABLE IF EXISTS `incidencias_fichaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incidencias_fichaje` (
  `id_incidencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_fichaje` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id_incidencia`),
  KEY `incidencias_fichaje_ibfk_1` (`id_fichaje`),
  CONSTRAINT `incidencias_fichaje_ibfk_1` FOREIGN KEY (`id_fichaje`) REFERENCES `fichajes` (`id_fichaje`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incidencias_fichaje`
--

LOCK TABLES `incidencias_fichaje` WRITE;
/*!40000 ALTER TABLE `incidencias_fichaje` DISABLE KEYS */;
INSERT INTO `incidencias_fichaje` VALUES
(1,1,'Fichaje tardío a las 19:29:57'),
(5,10,'Fichaje tardío a las 21:31:13');
/*!40000 ALTER TABLE `incidencias_fichaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modificaciones_ausencias`
--

DROP TABLE IF EXISTS `modificaciones_ausencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modificaciones_ausencias` (
  `id_modificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_ausencia` int(11) NOT NULL,
  `id_empleado_modificador` int(11) NOT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_modificacion`),
  KEY `id_ausencia` (`id_ausencia`),
  KEY `id_empleado_modificador` (`id_empleado_modificador`),
  CONSTRAINT `modificaciones_ausencias_ibfk_1` FOREIGN KEY (`id_ausencia`) REFERENCES `ausencias` (`id_ausencia`),
  CONSTRAINT `modificaciones_ausencias_ibfk_2` FOREIGN KEY (`id_empleado_modificador`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modificaciones_ausencias`
--

LOCK TABLES `modificaciones_ausencias` WRITE;
/*!40000 ALTER TABLE `modificaciones_ausencias` DISABLE KEYS */;
INSERT INTO `modificaciones_ausencias` VALUES
(1,1,1,'2025-06-06 17:56:33');
/*!40000 ALTER TABLE `modificaciones_ausencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modificaciones_estado_vacaciones`
--

DROP TABLE IF EXISTS `modificaciones_estado_vacaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modificaciones_estado_vacaciones` (
  `id_modificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `id_empleado_modificador` int(11) NOT NULL,
  `estado_anterior` enum('pendiente','aprobada','rechazada') NOT NULL,
  `estado_nuevo` enum('pendiente','aprobada','rechazada') NOT NULL,
  `motivo` text NOT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_modificacion`),
  KEY `id_solicitud` (`id_solicitud`),
  KEY `id_empleado_modificador` (`id_empleado_modificador`),
  CONSTRAINT `modificaciones_estado_vacaciones_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_vacaciones` (`id_solicitud`),
  CONSTRAINT `modificaciones_estado_vacaciones_ibfk_2` FOREIGN KEY (`id_empleado_modificador`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modificaciones_estado_vacaciones`
--

LOCK TABLES `modificaciones_estado_vacaciones` WRITE;
/*!40000 ALTER TABLE `modificaciones_estado_vacaciones` DISABLE KEYS */;
INSERT INTO `modificaciones_estado_vacaciones` VALUES
(1,1,1,'pendiente','aprobada','Cambio de estado registrado automáticamente','2025-06-05 23:31:10'),
(2,1,1,'pendiente','aprobada','Solicitud aprobada por RRHH','2025-06-05 23:31:10');
/*!40000 ALTER TABLE `modificaciones_estado_vacaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes_vacaciones`
--

DROP TABLE IF EXISTS `solicitudes_vacaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_vacaciones` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `id_empleado` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado_solicitud` enum('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
  `id_empleado_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`),
  KEY `idx_empleado_vacaciones` (`id_empleado`,`fecha_inicio`,`fecha_fin`),
  CONSTRAINT `solicitudes_vacaciones_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_vacaciones`
--

LOCK TABLES `solicitudes_vacaciones` WRITE;
/*!40000 ALTER TABLE `solicitudes_vacaciones` DISABLE KEYS */;
INSERT INTO `solicitudes_vacaciones` VALUES
(1,1,'2025-08-04','2025-08-20','aprobada',1);
/*!40000 ALTER TABLE `solicitudes_vacaciones` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER validar_fechas_vacaciones
BEFORE INSERT ON solicitudes_vacaciones
FOR EACH ROW
BEGIN
    IF NEW.fecha_fin < NEW.fecha_inicio THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: fecha_fin no puede ser menor que fecha_inicio en solicitudes de vacaciones';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER validar_dias_vacaciones
BEFORE INSERT ON solicitudes_vacaciones
FOR EACH ROW
BEGIN
    
    IF (SELECT dias_vacaciones_disponibles FROM empleados WHERE id_empleado = NEW.id_empleado) < 
       (DATEDIFF(NEW.fecha_fin, NEW.fecha_inicio) + 1) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: No tienes suficientes días de vacaciones disponibles';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER actualizar_vacaciones
AFTER UPDATE ON solicitudes_vacaciones
FOR EACH ROW
BEGIN
    IF NEW.estado_solicitud = 'aprobada' THEN
        UPDATE empleados 
        SET dias_vacaciones_disponibles = dias_vacaciones_disponibles - DATEDIFF(NEW.fecha_fin, NEW.fecha_inicio) 
        WHERE id_empleado = NEW.id_empleado;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`ADMIN_DB`@`localhost`*/ /*!50003 TRIGGER registrar_modificacion_estado_vacaciones
AFTER UPDATE ON solicitudes_vacaciones
FOR EACH ROW
BEGIN
    
    IF OLD.estado_solicitud <> NEW.estado_solicitud THEN
        
        INSERT INTO modificaciones_estado_vacaciones (id_solicitud, id_empleado_modificador, estado_anterior, estado_nuevo, motivo)
        VALUES (OLD.id_solicitud, NEW.id_empleado_modificador, OLD.estado_solicitud, NEW.estado_solicitud, 'Cambio de estado registrado automáticamente');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `turnos`
--

DROP TABLE IF EXISTS `turnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `turnos` (
  `id_turno` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `dias_semana` set('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') NOT NULL,
  `horario_flexible` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_turno`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turnos`
--

LOCK TABLES `turnos` WRITE;
/*!40000 ALTER TABLE `turnos` DISABLE KEYS */;
INSERT INTO `turnos` VALUES
(1,'Turno de mañana no flexible','09:00:00','17:00:00','Lunes,Martes,Miércoles,Jueves,Viernes',0),
(2,'10 a 18','10:00:00','18:00:00','Lunes,Martes,Miércoles,Jueves,Viernes',0),
(5,'Horario de mañana flexible','08:30:00','16:30:00','Lunes,Martes,Miércoles,Jueves,Viernes',1);
/*!40000 ALTER TABLE `turnos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-08  0:50:38
