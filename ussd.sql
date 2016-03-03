-- MySQL dump 10.13  Distrib 5.6.12, for osx10.7 (x86_64)
--
-- Host: localhost    Database: ussd
-- ------------------------------------------------------
-- Server version	5.6.12

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
-- Current Database: `ussd`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ussd` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ussd`;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `idclient` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(100) DEFAULT NULL,
  `event_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idclient`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'test@test.com','Juan pedro','prueba@test.com','121334','2016-02-21 21:03:30');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `iditem` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `idmenu` int(11) NOT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`iditem`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (13,'sdsds',8,NULL),(14,'as',8,NULL),(27,'asa',19,1),(28,'asa',19,2),(33,'una prueba',20,1),(34,'esto es',20,2),(38,'item 2.1',17,1),(39,'item 2.2',17,2),(40,'item 2.3',17,3),(41,'item 1.1',16,1),(42,'item 1.2',16,2),(43,'item 1.3',16,3),(44,'item 1',21,1),(45,'item 2',21,2),(46,'item 3',21,3),(47,'Muy Bien',25,1),(48,'Bien',25,2),(49,'Mal',25,3),(50,'Si',26,1),(51,'Quizas',26,2),(52,'Jamas',26,3);
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `label` varchar(100) NOT NULL,
  `added_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `idcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmenu`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (16,'Opcion 1','Â¿Puede serlecionar algo de la opcion 1?','2016-02-22 15:48:44',NULL),(17,'Opcion 2','Â¿Puede serlecionar algo de la opcion 2?','2016-02-22 15:49:05',NULL),(21,'opcion 3','Selecciones algo de la opcion 3','2016-02-22 17:03:57',NULL),(25,'Pregunta 1','Â¿Como estuvo la atencion?','2016-02-23 01:56:01',NULL),(26,'Pregunta 2','Volveria a este establecimiento?','2016-02-23 01:56:42',NULL),(27,'Terminae','Muchas gracias por sus comentarios','2016-02-23 02:01:03',NULL);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reference` (
  `idreference` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(1) DEFAULT NULL,
  `goto` int(11) DEFAULT NULL,
  `idmenu` int(11) NOT NULL,
  PRIMARY KEY (`idreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reference`
--

LOCK TABLES `reference` WRITE;
/*!40000 ALTER TABLE `reference` DISABLE KEYS */;
/*!40000 ALTER TABLE `reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(20) DEFAULT NULL,
  `command` varchar(10) DEFAULT NULL,
  `ussd` varchar(10) DEFAULT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `sessionId` varchar(80) DEFAULT NULL,
  `event_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'1223333','','1','Â¿Puede serlecionar algo de la opcion 2?','*123#','9ce47b86-6c78-4ae4-878c-a1ac00faf294','2016-02-22 21:22:30'),(2,'12233','item 1.1','*123#','Â¿Puede serlecionar algo de la opcion 1?','1','8087b710-1902-402b-8202-ded0e6e0abf3','2016-02-22 21:23:01'),(3,'12233','item 2.2','*123#','Â¿Puede serlecionar algo de la opcion 2?','2','8087b710-1902-402b-8202-ded0e6e0abf3','2016-02-22 21:23:36'),(4,'12233','item 3','*123#','Selecciones algo de la opcion 3','3','8087b710-1902-402b-8202-ded0e6e0abf3','2016-02-22 21:23:37'),(5,'33432','item 1.2','*123#','Â¿Puede serlecionar algo de la opcion 1?','2','cf69494e-9796-4cde-ad12-126b837fcc11','2016-02-22 23:40:46'),(6,'33432','item 2.1','*123#','Â¿Puede serlecionar algo de la opcion 2?','1','cf69494e-9796-4cde-ad12-126b837fcc11','2016-02-22 23:40:49'),(7,'33432','item 3','*123#','Selecciones algo de la opcion 3','3','cf69494e-9796-4cde-ad12-126b837fcc11','2016-02-22 23:40:53'),(8,'2134','','3','Muchas gracias por hacer esto!','*123#','eb3a6e11-931c-4402-987d-d639523f5847','2016-02-22 23:41:16'),(9,'1223454','item 1.2','*123#','Â¿Puede serlecionar algo de la opcion 1?','2','6c0030cf-59d4-45a9-a10d-280c02cc3628','2016-02-22 23:41:37'),(10,'1223454','item 2.3','*123#','Â¿Puede serlecionar algo de la opcion 2?','3','6c0030cf-59d4-45a9-a10d-280c02cc3628','2016-02-22 23:41:38'),(11,'1223454','item 3','*123#','Selecciones algo de la opcion 3','3','6c0030cf-59d4-45a9-a10d-280c02cc3628','2016-02-22 23:41:39'),(12,'122343563','Muy Bien','*123#','Â¿Como estuvo la atencion?','1','00ff6b55-69b4-4e02-885f-ee0612d8af2d','2016-02-23 01:57:40'),(13,'1211','Muy Bien','*123#','Â¿Como estuvo la atencion?','1','a16d2316-e1f2-4096-b69a-d9be11907cf1','2016-02-23 02:01:28'),(14,'1211','Quizas','*123#','Volveria a este establecimiento?','2','a16d2316-e1f2-4096-b69a-d9be11907cf1','2016-02-23 02:01:33'),(15,'12343122','Mal','*123#','Â¿Como estuvo la atencion?','3','852968cb-20de-489e-91c2-24585cb74d6d','2016-02-24 21:46:09'),(16,'12343122','Quizas','*123#','Volveria a este establecimiento?','2','852968cb-20de-489e-91c2-24585cb74d6d','2016-02-24 21:46:11'),(17,'12121243','Bien','*123#','Â¿Como estuvo la atencion?','2','494f65a3-7987-4ae6-ac2d-9675e328878b','2016-02-24 21:46:36'),(18,'12121243','Si','*123#','Volveria a este establecimiento?','1','494f65a3-7987-4ae6-ac2d-9675e328878b','2016-02-24 21:46:38'),(19,'2323343','Muy Bien','*123#','Â¿Como estuvo la atencion?','1','816f2961-3764-478a-9965-bcfceb793805','2016-02-24 22:01:47'),(20,'2323343','Si','*123#','Volveria a este establecimiento?','1','816f2961-3764-478a-9965-bcfceb793805','2016-02-24 22:01:48'),(21,'243434','Mal','*123#','Â¿Como estuvo la atencion?','3','e68acf0a-9805-4c61-bbeb-1be351d7db4e','2016-02-25 05:39:02'),(22,'243434','Jamas','*123#','Volveria a este establecimiento?','3','e68acf0a-9805-4c61-bbeb-1be351d7db4e','2016-02-25 05:39:06'),(23,'322432','Mal','*123#','Â¿Como estuvo la atencion?','3','5f870e39-7e40-4892-bcf1-11dd65ec793e','2016-02-25 05:39:45'),(24,'322432','Jamas','*123#','Volveria a este establecimiento?','3','5f870e39-7e40-4892-bcf1-11dd65ec793e','2016-02-25 05:39:48'),(25,'4532134','Bien','*123#','Â¿Como estuvo la atencion?','2','8605bc97-1a01-4d1b-9239-23af294414db','2016-02-25 20:17:00'),(26,'4532134','Quizas','*123#','Volveria a este establecimiento?','2','8605bc97-1a01-4d1b-9239-23af294414db','2016-02-25 20:17:01'),(27,'12334','Muy Bien','*123#','Â¿Como estuvo la atencion?','1','94b99b0e-fdc3-4468-a1dc-0d7781cb5e29','2016-02-25 20:17:36'),(28,'12334','Si','*123#','Volveria a este establecimiento?','1','94b99b0e-fdc3-4468-a1dc-0d7781cb5e29','2016-02-25 20:17:38');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tree`
--

DROP TABLE IF EXISTS `tree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tree` (
  `idcode` int(11) NOT NULL,
  `idmenu` int(11) NOT NULL,
  `orden` int(11) DEFAULT NULL,
  `event_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcode`,`idmenu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tree`
--

LOCK TABLES `tree` WRITE;
/*!40000 ALTER TABLE `tree` DISABLE KEYS */;
INSERT INTO `tree` VALUES (1,17,3,'2016-02-26 01:36:46'),(1,21,4,'2016-02-26 01:36:46'),(1,25,1,'2016-02-26 01:36:46'),(1,26,2,'2016-02-26 01:36:46'),(1,27,5,'2016-02-26 01:36:46');
/*!40000 ALTER TABLE `tree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tree_menu`
--

DROP TABLE IF EXISTS `tree_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tree_menu` (
  `idtree` int(11) NOT NULL,
  `idmenu` int(11) NOT NULL,
  PRIMARY KEY (`idtree`,`idmenu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tree_menu`
--

LOCK TABLES `tree_menu` WRITE;
/*!40000 ALTER TABLE `tree_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `tree_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(100) DEFAULT NULL,
  `event_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idclient` int(11) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test@test.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','1','8888888','2016-02-21 21:04:22',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ussd_codes`
--

DROP TABLE IF EXISTS `ussd_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ussd_codes` (
  `idcode` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `event_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idclient` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ussd_codes`
--

LOCK TABLES `ussd_codes` WRITE;
/*!40000 ALTER TABLE `ussd_codes` DISABLE KEYS */;
INSERT INTO `ussd_codes` VALUES (1,'*123#','Marcacion de Prueba','2016-02-21 21:02:42',1);
/*!40000 ALTER TABLE `ussd_codes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-29  8:19:14
