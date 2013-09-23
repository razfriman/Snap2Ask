CREATE DATABASE  IF NOT EXISTS `snap2ask` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `snap2ask`;
-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: snap2ask
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.04.1

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
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `text` varchar(512) NOT NULL,
  `rating` smallint(6) DEFAULT NULL,
<<<<<<< HEAD:snap2ask.sql
  `status` varchar(45) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_answers_1_idx` (`question_id`),
  CONSTRAINT `fk_answers_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
=======
  PRIMARY KEY (`id`),
  FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`)	
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
>>>>>>> 1f9e171051f6c920a6e2beff9c4ad4461ed1858f:Code/TestCode/snap2ask.sql
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (3,12,10,'The answer is x=-7',NULL,'pending','0000-00-00 00:00:00'),(4,22,10,'This is the answer. Post from REST Client.',NULL,'pending','0000-00-00 00:00:00'),(5,22,10,'asdf',NULL,'pending','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authentication_modes`
--

DROP TABLE IF EXISTS `authentication_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authentication_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authentication_modes`
--

LOCK TABLES `authentication_modes` WRITE;
/*!40000 ALTER TABLE `authentication_modes` DISABLE KEYS */;
INSERT INTO `authentication_modes` VALUES (1,'custom'),(2,'facebook'),(3,'google');
/*!40000 ALTER TABLE `authentication_modes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (0,'Other'),(1,'Math'),(2,'Science');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` blob NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,'1234567','');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Unanswered',
  `image_id` int(11) NOT NULL,
  `times_answered` int(11) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_questions_categories_idx` (`category_id`),
  KEY `fk_questions_answers1_idx` (`status`),
  KEY `fk_questions_images1_idx` (`image_id`),
  KEY `fk_questions_users1_idx` (`student_id`),
  KEY `fk_questions_1_idx` (`subcategory_id`),
  CONSTRAINT `fk_questions_1` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_images1` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_users1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (12,10,'what is x+10=3',1,1,'Unanswered',1,0,'0000-00-00 00:00:00'),(22,10,'What is the square root of 4 ?',1,1,'Answered',1,0,'0000-00-00 00:00:00'),(23,10,'What is the square root of 4 ?',1,1,'Unanswered',1,0,'0000-00-00 00:00:00'),(24,10,'What is the square root of 4 ?',1,1,'Unanswered',1,0,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subcategories_1_idx` (`category_id`),
  CONSTRAINT `fk_subcategories_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (1,'Calculus',1),(2,'Algebra',1),(3,'Biology',2),(4,'Chemistry',2),(5,'Other',1),(6,'Other',2);
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '10',
  `is_tutor` tinyint(4) NOT NULL DEFAULT '0',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `authentication_mode_id` int(11) NOT NULL,
  `preferred_category_id` int(11) NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_authentication_modes1_idx` (`authentication_mode_id`),
  KEY `fk_users_1_idx` (`preferred_category_id`),
  CONSTRAINT `fk_users_1` FOREIGN KEY (`preferred_category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_authentication_modes1` FOREIGN KEY (`authentication_mode_id`) REFERENCES `authentication_modes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (10,'superraz','email','$2a$05$jQ36FhMZnEAdmTelg3h5k.OvIAfWOmF7mPuuWJRctzBNhkAGCNcvK','jQ36FhMZnEAdmTelg3h5k',40,0,0,1,1,'0000-00-00 00:00:00'),(27,'aaa','bbb','$2a$05$Mw0kYUTROvddIU1diS77X.VMhQhZ.a.CTMp/3xINdhDFUgmMAmfX6','Mw0kYUTROvddIU1diS77X',40,0,0,1,1,'0000-00-00 00:00:00'),(28,'aaaa','bbbb','$2a$05$9jJrh8kWy9Ulp142YP3xi.VFbTey5MztiUCfoRQTaXNUBJOfzAbbW','9jJrh8kWy9Ulp142YP3xi',40,0,0,1,1,'0000-00-00 00:00:00'),(31,'aaaaa','bbbbb','$2a$05$eajqP4wwhKar5DVNBwqnx.uyEmnnzsXrZqelTA9WhKnIHTBGNDK4C','eajqP4wwhKar5DVNBwqnx',40,0,0,1,1,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-09-23 14:00:14
