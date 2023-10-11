-- MariaDB dump 10.19  Distrib 10.5.18-MariaDB, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: bootstrap
-- ------------------------------------------------------
-- Server version	10.5.18-MariaDB-0+deb11u1

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
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `parent_answer_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `is_selected` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DADD4A25A76ED395` (`user_id`),
  KEY `IDX_DADD4A25727ACA70` (`parent_id`),
  KEY `IDX_DADD4A257A19A357` (`contributor_id`),
  KEY `IDX_DADD4A25FE5E5FBD` (`contribution_id`),
  KEY `IDX_DADD4A255B7867E9` (`parent_answer_id`),
  CONSTRAINT `FK_DADD4A255B7867E9` FOREIGN KEY (`parent_answer_id`) REFERENCES `answer` (`id`),
  CONSTRAINT `FK_DADD4A25727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `answer` (`id`),
  CONSTRAINT `FK_DADD4A257A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_DADD4A25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_DADD4A25FE5E5FBD` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer`
--

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block`
--

DROP TABLE IF EXISTS `block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `template` varchar(255) NOT NULL,
  `style` varchar(255) DEFAULT NULL,
  `block_collection_id` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_831B9722A76ED395` (`user_id`),
  KEY `IDX_831B9722727ACA70` (`parent_id`),
  KEY `IDX_831B97222884355F` (`block_collection_id`),
  CONSTRAINT `FK_831B97222884355F` FOREIGN KEY (`block_collection_id`) REFERENCES `block_collection` (`id`),
  CONSTRAINT `FK_831B9722727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `block` (`id`),
  CONSTRAINT `FK_831B9722A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block`
--

LOCK TABLES `block` WRITE;
/*!40000 ALTER TABLE `block` DISABLE KEYS */;
/*!40000 ALTER TABLE `block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block_collection`
--

DROP TABLE IF EXISTS `block_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `entity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_91A06E59A76ED395` (`user_id`),
  KEY `IDX_91A06E59727ACA70` (`parent_id`),
  CONSTRAINT `FK_91A06E59727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `block_collection` (`id`),
  CONSTRAINT `FK_91A06E59A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_collection`
--

LOCK TABLES `block_collection` WRITE;
/*!40000 ALTER TABLE `block_collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block_collection_translation`
--

DROP TABLE IF EXISTS `block_collection_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_collection_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_collection_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_3D12769E2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_3D12769E2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `block_collection` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_collection_translation`
--

LOCK TABLES `block_collection_translation` WRITE;
/*!40000 ALTER TABLE `block_collection_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_collection_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block_translation`
--

DROP TABLE IF EXISTS `block_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `text` longtext DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_6E6410B42C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_6E6410B42C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `block` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_translation`
--

LOCK TABLES `block_translation` WRITE;
/*!40000 ALTER TABLE `block_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution`
--

DROP TABLE IF EXISTS `contribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `contribution_status_id` int(11) DEFAULT NULL,
  `contribution_type_id` int(11) DEFAULT NULL,
  `contribution_object_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `date_begin` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EA351E15A76ED395` (`user_id`),
  KEY `IDX_EA351E15727ACA70` (`parent_id`),
  KEY `IDX_EA351E157A19A357` (`contributor_id`),
  KEY `IDX_EA351E156BF700BD` (`status_id`),
  KEY `IDX_EA351E15E913D6AF` (`contribution_status_id`),
  KEY `IDX_EA351E15846DFC52` (`contribution_type_id`),
  KEY `IDX_EA351E15A1C98039` (`contribution_object_id`),
  CONSTRAINT `FK_EA351E156BF700BD` FOREIGN KEY (`status_id`) REFERENCES `contribution_status` (`id`),
  CONSTRAINT `FK_EA351E15727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contribution` (`id`),
  CONSTRAINT `FK_EA351E157A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_EA351E15846DFC52` FOREIGN KEY (`contribution_type_id`) REFERENCES `contribution_type` (`id`),
  CONSTRAINT `FK_EA351E15A1C98039` FOREIGN KEY (`contribution_object_id`) REFERENCES `contribution_object` (`id`),
  CONSTRAINT `FK_EA351E15A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_EA351E15E913D6AF` FOREIGN KEY (`contribution_status_id`) REFERENCES `contribution_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution`
--

LOCK TABLES `contribution` WRITE;
/*!40000 ALTER TABLE `contribution` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution_object`
--

DROP TABLE IF EXISTS `contribution_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8DA70A47A76ED395` (`user_id`),
  KEY `IDX_8DA70A47727ACA70` (`parent_id`),
  CONSTRAINT `FK_8DA70A47727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contribution_object` (`id`),
  CONSTRAINT `FK_8DA70A47A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution_object`
--

LOCK TABLES `contribution_object` WRITE;
/*!40000 ALTER TABLE `contribution_object` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution_object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution_object_translation`
--

DROP TABLE IF EXISTS `contribution_object_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution_object_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contribution_object_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_901E07DA2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_901E07DA2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `contribution_object` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution_object_translation`
--

LOCK TABLES `contribution_object_translation` WRITE;
/*!40000 ALTER TABLE `contribution_object_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution_object_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution_status`
--

DROP TABLE IF EXISTS `contribution_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5E0AC4B7A76ED395` (`user_id`),
  KEY `IDX_5E0AC4B7727ACA70` (`parent_id`),
  CONSTRAINT `FK_5E0AC4B7727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contribution_status` (`id`),
  CONSTRAINT `FK_5E0AC4B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution_status`
--

LOCK TABLES `contribution_status` WRITE;
/*!40000 ALTER TABLE `contribution_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution_status_translation`
--

DROP TABLE IF EXISTS `contribution_status_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution_status_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contribution_status_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_566012252C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_566012252C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `contribution_status` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution_status_translation`
--

LOCK TABLES `contribution_status_translation` WRITE;
/*!40000 ALTER TABLE `contribution_status_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution_status_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution_type`
--

DROP TABLE IF EXISTS `contribution_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4F0C2371A76ED395` (`user_id`),
  KEY `IDX_4F0C2371727ACA70` (`parent_id`),
  CONSTRAINT `FK_4F0C2371727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contribution_type` (`id`),
  CONSTRAINT `FK_4F0C2371A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution_type`
--

LOCK TABLES `contribution_type` WRITE;
/*!40000 ALTER TABLE `contribution_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution_type_translation`
--

DROP TABLE IF EXISTS `contribution_type_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution_type_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contribution_type_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_5152FF842C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_5152FF842C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `contribution_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution_type_translation`
--

LOCK TABLES `contribution_type_translation` WRITE;
/*!40000 ALTER TABLE `contribution_type_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution_type_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributor`
--

DROP TABLE IF EXISTS `contributor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contributor_type_id` int(11) DEFAULT NULL,
  `parent_contributor_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `salt` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `has_agreed` tinyint(1) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_contactable` longtext DEFAULT NULL COMMENT '(DC2Type:array)',
  `contributor_grade_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DA6F9793A76ED395` (`user_id`),
  KEY `IDX_DA6F9793727ACA70` (`parent_id`),
  KEY `IDX_DA6F9793834C39` (`contributor_type_id`),
  KEY `IDX_DA6F979364C4D75C` (`parent_contributor_id`),
  KEY `IDX_DA6F9793C8DF249E` (`contributor_grade_id`),
  CONSTRAINT `FK_DA6F979364C4D75C` FOREIGN KEY (`parent_contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_DA6F9793727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_DA6F9793834C39` FOREIGN KEY (`contributor_type_id`) REFERENCES `contributor_type` (`id`),
  CONSTRAINT `FK_DA6F9793A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_DA6F9793C8DF249E` FOREIGN KEY (`contributor_grade_id`) REFERENCES `contributor_grade` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributor`
--

LOCK TABLES `contributor` WRITE;
/*!40000 ALTER TABLE `contributor` DISABLE KEYS */;
/*!40000 ALTER TABLE `contributor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributor_grade`
--

DROP TABLE IF EXISTS `contributor_grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributor_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C8ED8AF6A76ED395` (`user_id`),
  KEY `IDX_C8ED8AF6727ACA70` (`parent_id`),
  CONSTRAINT `FK_C8ED8AF6727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contributor_grade` (`id`),
  CONSTRAINT `FK_C8ED8AF6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributor_grade`
--

LOCK TABLES `contributor_grade` WRITE;
/*!40000 ALTER TABLE `contributor_grade` DISABLE KEYS */;
/*!40000 ALTER TABLE `contributor_grade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributor_grade_translation`
--

DROP TABLE IF EXISTS `contributor_grade_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributor_grade_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contributor_grade_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_1529F6EF2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_1529F6EF2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `contributor_grade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributor_grade_translation`
--

LOCK TABLES `contributor_grade_translation` WRITE;
/*!40000 ALTER TABLE `contributor_grade_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `contributor_grade_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributor_type`
--

DROP TABLE IF EXISTS `contributor_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributor_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F960267A76ED395` (`user_id`),
  KEY `IDX_5F960267727ACA70` (`parent_id`),
  CONSTRAINT `FK_5F960267727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `contributor_type` (`id`),
  CONSTRAINT `FK_5F960267A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributor_type`
--

LOCK TABLES `contributor_type` WRITE;
/*!40000 ALTER TABLE `contributor_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `contributor_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributor_type_translation`
--

DROP TABLE IF EXISTS `contributor_type_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributor_type_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contributor_type_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_8F2CBDDE2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_8F2CBDDE2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `contributor_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributor_type_translation`
--

LOCK TABLES `contributor_type_translation` WRITE;
/*!40000 ALTER TABLE `contributor_type_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `contributor_type_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `from_label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E7927C74A76ED395` (`user_id`),
  KEY `IDX_E7927C74727ACA70` (`parent_id`),
  CONSTRAINT `FK_E7927C74727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `email` (`id`),
  CONSTRAINT `FK_E7927C74A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email`
--

LOCK TABLES `email` WRITE;
/*!40000 ALTER TABLE `email` DISABLE KEYS */;
INSERT INTO `email` VALUES (1,1,NULL,'2023-09-09 19:49:06','2023-09-09 20:27:05','2023-09-09 19:49:06',0,0,0,0,'retrieve_password',NULL,NULL),(2,1,NULL,'2023-09-09 21:27:47','2023-09-09 23:41:19','2023-09-09 21:27:47',0,0,0,0,'registration',NULL,NULL);
/*!40000 ALTER TABLE `email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_translation`
--

DROP TABLE IF EXISTS `email_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `text` longtext DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_A2A939D82C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_A2A939D82C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `email` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_translation`
--

LOCK TABLES `email_translation` WRITE;
/*!40000 ALTER TABLE `email_translation` DISABLE KEYS */;
INSERT INTO `email_translation` VALUES (1,1,'Nouveau mot de passe','Bonjour,<br />vous avez demand&eacute; &agrave; g&eacute;n&eacute;rer un mot de passe pour votre acc&egrave;s contributeur : %s','fr'),(2,2,'Confirmation de votre demande d\'inscription','Bonjour %s %s,<br /><br />Votre demande d&rsquo;inscription a &eacute;t&eacute; enregistr&eacute;e et est actuellement en cours de traitement.<br />Vous recevrez un email d&egrave;s que celle-ci sera trait&eacute;e. <br />En cas de probl&egrave;me, merci d&rsquo;&eacute;crire &agrave; <a href=\"mailto:email@address.com\">email@address.com </a><br /><br />&Agrave; bient&ocirc;t sur UI5 !','fr');
/*!40000 ALTER TABLE `email_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `date_begin` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `time_begin` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7A76ED395` (`user_id`),
  KEY `IDX_3BAE0AA7727ACA70` (`parent_id`),
  KEY `IDX_3BAE0AA77A19A357` (`contributor_id`),
  CONSTRAINT `FK_3BAE0AA7727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_3BAE0AA77A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_3BAE0AA7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_translation`
--

DROP TABLE IF EXISTS `event_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_1FE096EF2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_1FE096EF2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `event` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_translation`
--

LOCK TABLES `event_translation` WRITE;
/*!40000 ALTER TABLE `event_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keyword`
--

DROP TABLE IF EXISTS `keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A93713BA76ED395` (`user_id`),
  KEY `IDX_5A93713B727ACA70` (`parent_id`),
  CONSTRAINT `FK_5A93713B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `keyword` (`id`),
  CONSTRAINT `FK_5A93713BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keyword`
--

LOCK TABLES `keyword` WRITE;
/*!40000 ALTER TABLE `keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keyword_translation`
--

DROP TABLE IF EXISTS `keyword_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keyword_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_7D2310AA2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_7D2310AA2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `keyword` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keyword_translation`
--

LOCK TABLES `keyword_translation` WRITE;
/*!40000 ALTER TABLE `keyword_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `keyword_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_block_collection_page`
--

DROP TABLE IF EXISTS `link_block_collection_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_block_collection_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `block_collection_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3A44B80BA76ED395` (`user_id`),
  KEY `IDX_3A44B80B727ACA70` (`parent_id`),
  KEY `IDX_3A44B80B2884355F` (`block_collection_id`),
  KEY `IDX_3A44B80BC4663E4` (`page_id`),
  CONSTRAINT `FK_3A44B80B2884355F` FOREIGN KEY (`block_collection_id`) REFERENCES `block_collection` (`id`),
  CONSTRAINT `FK_3A44B80B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_block_collection_page` (`id`),
  CONSTRAINT `FK_3A44B80BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3A44B80BC4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_block_collection_page`
--

LOCK TABLES `link_block_collection_page` WRITE;
/*!40000 ALTER TABLE `link_block_collection_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_block_collection_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_block_media`
--

DROP TABLE IF EXISTS `link_block_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_block_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `block_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60BDEBB9A76ED395` (`user_id`),
  KEY `IDX_60BDEBB9727ACA70` (`parent_id`),
  KEY `IDX_60BDEBB9E9ED820C` (`block_id`),
  KEY `IDX_60BDEBB9EA9FDD75` (`media_id`),
  CONSTRAINT `FK_60BDEBB9727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_block_media` (`id`),
  CONSTRAINT `FK_60BDEBB9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_60BDEBB9E9ED820C` FOREIGN KEY (`block_id`) REFERENCES `block` (`id`),
  CONSTRAINT `FK_60BDEBB9EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_block_media`
--

LOCK TABLES `link_block_media` WRITE;
/*!40000 ALTER TABLE `link_block_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_block_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_contribution_keyword`
--

DROP TABLE IF EXISTS `link_contribution_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_contribution_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `keyword_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_71990B44A76ED395` (`user_id`),
  KEY `IDX_71990B44727ACA70` (`parent_id`),
  KEY `IDX_71990B44FE5E5FBD` (`contribution_id`),
  KEY `IDX_71990B44115D4552` (`keyword_id`),
  CONSTRAINT `FK_71990B44115D4552` FOREIGN KEY (`keyword_id`) REFERENCES `keyword` (`id`),
  CONSTRAINT `FK_71990B44727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_contribution_keyword` (`id`),
  CONSTRAINT `FK_71990B44A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_71990B44FE5E5FBD` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_contribution_keyword`
--

LOCK TABLES `link_contribution_keyword` WRITE;
/*!40000 ALTER TABLE `link_contribution_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_contribution_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_contribution_resource`
--

DROP TABLE IF EXISTS `link_contribution_resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_contribution_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7C0092C1A76ED395` (`user_id`),
  KEY `IDX_7C0092C1727ACA70` (`parent_id`),
  KEY `IDX_7C0092C1FE5E5FBD` (`contribution_id`),
  KEY `IDX_7C0092C189329D25` (`resource_id`),
  CONSTRAINT `FK_7C0092C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_contribution_resource` (`id`),
  CONSTRAINT `FK_7C0092C189329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `FK_7C0092C1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_7C0092C1FE5E5FBD` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_contribution_resource`
--

LOCK TABLES `link_contribution_resource` WRITE;
/*!40000 ALTER TABLE `link_contribution_resource` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_contribution_resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_contribution_topic`
--

DROP TABLE IF EXISTS `link_contribution_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_contribution_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD7469A76ED395` (`user_id`),
  KEY `IDX_B6BD7469727ACA70` (`parent_id`),
  KEY `IDX_B6BD7469FE5E5FBD` (`contribution_id`),
  KEY `IDX_B6BD74691F55203D` (`topic_id`),
  CONSTRAINT `FK_B6BD74691F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`),
  CONSTRAINT `FK_B6BD7469727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_contribution_topic` (`id`),
  CONSTRAINT `FK_B6BD7469A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B6BD7469FE5E5FBD` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_contribution_topic`
--

LOCK TABLES `link_contribution_topic` WRITE;
/*!40000 ALTER TABLE `link_contribution_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_contribution_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_contributor_grade_page`
--

DROP TABLE IF EXISTS `link_contributor_grade_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_contributor_grade_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contributor_grade_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_34352B2FA76ED395` (`user_id`),
  KEY `IDX_34352B2F727ACA70` (`parent_id`),
  KEY `IDX_34352B2FC8DF249E` (`contributor_grade_id`),
  KEY `IDX_34352B2FC4663E4` (`page_id`),
  CONSTRAINT `FK_34352B2F727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_contributor_grade_page` (`id`),
  CONSTRAINT `FK_34352B2FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_34352B2FC4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_34352B2FC8DF249E` FOREIGN KEY (`contributor_grade_id`) REFERENCES `contributor_grade` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_contributor_grade_page`
--

LOCK TABLES `link_contributor_grade_page` WRITE;
/*!40000 ALTER TABLE `link_contributor_grade_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_contributor_grade_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_contributor_topic`
--

DROP TABLE IF EXISTS `link_contributor_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_contributor_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_86D56E3BA76ED395` (`user_id`),
  KEY `IDX_86D56E3B727ACA70` (`parent_id`),
  KEY `IDX_86D56E3B7A19A357` (`contributor_id`),
  KEY `IDX_86D56E3B1F55203D` (`topic_id`),
  CONSTRAINT `FK_86D56E3B1F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`),
  CONSTRAINT `FK_86D56E3B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_contributor_topic` (`id`),
  CONSTRAINT `FK_86D56E3B7A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_86D56E3BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_contributor_topic`
--

LOCK TABLES `link_contributor_topic` WRITE;
/*!40000 ALTER TABLE `link_contributor_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_contributor_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_event_contributor`
--

DROP TABLE IF EXISTS `link_event_contributor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_event_contributor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FBC4D0F1A76ED395` (`user_id`),
  KEY `IDX_FBC4D0F1727ACA70` (`parent_id`),
  KEY `IDX_FBC4D0F171F7E88B` (`event_id`),
  KEY `IDX_FBC4D0F17A19A357` (`contributor_id`),
  CONSTRAINT `FK_FBC4D0F171F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_FBC4D0F1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_event_contributor` (`id`),
  CONSTRAINT `FK_FBC4D0F17A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_FBC4D0F1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_event_contributor`
--

LOCK TABLES `link_event_contributor` WRITE;
/*!40000 ALTER TABLE `link_event_contributor` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_event_contributor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_event_keyword`
--

DROP TABLE IF EXISTS `link_event_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_event_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `keyword_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AA5CD488A76ED395` (`user_id`),
  KEY `IDX_AA5CD488727ACA70` (`parent_id`),
  KEY `IDX_AA5CD48871F7E88B` (`event_id`),
  KEY `IDX_AA5CD488115D4552` (`keyword_id`),
  CONSTRAINT `FK_AA5CD488115D4552` FOREIGN KEY (`keyword_id`) REFERENCES `keyword` (`id`),
  CONSTRAINT `FK_AA5CD48871F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_AA5CD488727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_event_keyword` (`id`),
  CONSTRAINT `FK_AA5CD488A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_event_keyword`
--

LOCK TABLES `link_event_keyword` WRITE;
/*!40000 ALTER TABLE `link_event_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_event_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_event_media`
--

DROP TABLE IF EXISTS `link_event_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_event_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E6B79C73A76ED395` (`user_id`),
  KEY `IDX_E6B79C73727ACA70` (`parent_id`),
  KEY `IDX_E6B79C7371F7E88B` (`event_id`),
  KEY `IDX_E6B79C73EA9FDD75` (`media_id`),
  CONSTRAINT `FK_E6B79C7371F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_E6B79C73727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_event_media` (`id`),
  CONSTRAINT `FK_E6B79C73A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_E6B79C73EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_event_media`
--

LOCK TABLES `link_event_media` WRITE;
/*!40000 ALTER TABLE `link_event_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_event_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_event_topic`
--

DROP TABLE IF EXISTS `link_event_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_event_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_11DBE364A76ED395` (`user_id`),
  KEY `IDX_11DBE364727ACA70` (`parent_id`),
  KEY `IDX_11DBE36471F7E88B` (`event_id`),
  KEY `IDX_11DBE3641F55203D` (`topic_id`),
  CONSTRAINT `FK_11DBE3641F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`),
  CONSTRAINT `FK_11DBE36471F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_11DBE364727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_event_topic` (`id`),
  CONSTRAINT `FK_11DBE364A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_event_topic`
--

LOCK TABLES `link_event_topic` WRITE;
/*!40000 ALTER TABLE `link_event_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_event_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_event_venue`
--

DROP TABLE IF EXISTS `link_event_venue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_event_venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1D0A2672A76ED395` (`user_id`),
  KEY `IDX_1D0A2672727ACA70` (`parent_id`),
  KEY `IDX_1D0A267271F7E88B` (`event_id`),
  KEY `IDX_1D0A267240A73EBA` (`venue_id`),
  CONSTRAINT `FK_1D0A267240A73EBA` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`id`),
  CONSTRAINT `FK_1D0A267271F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `FK_1D0A2672727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_event_venue` (`id`),
  CONSTRAINT `FK_1D0A2672A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_event_venue`
--

LOCK TABLES `link_event_venue` WRITE;
/*!40000 ALTER TABLE `link_event_venue` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_event_venue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_page_block`
--

DROP TABLE IF EXISTS `link_page_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_page_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `block_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B210A801A76ED395` (`user_id`),
  KEY `IDX_B210A801727ACA70` (`parent_id`),
  KEY `IDX_B210A801C4663E4` (`page_id`),
  KEY `IDX_B210A801E9ED820C` (`block_id`),
  CONSTRAINT `FK_B210A801727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_page_block` (`id`),
  CONSTRAINT `FK_B210A801A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B210A801C4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_B210A801E9ED820C` FOREIGN KEY (`block_id`) REFERENCES `block` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_page_block`
--

LOCK TABLES `link_page_block` WRITE;
/*!40000 ALTER TABLE `link_page_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_page_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_page_media`
--

DROP TABLE IF EXISTS `link_page_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_page_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5B279E2FA76ED395` (`user_id`),
  KEY `IDX_5B279E2F727ACA70` (`parent_id`),
  KEY `IDX_5B279E2FC4663E4` (`page_id`),
  KEY `IDX_5B279E2FEA9FDD75` (`media_id`),
  CONSTRAINT `FK_5B279E2F727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_page_media` (`id`),
  CONSTRAINT `FK_5B279E2FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_5B279E2FC4663E4` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_5B279E2FEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_page_media`
--

LOCK TABLES `link_page_media` WRITE;
/*!40000 ALTER TABLE `link_page_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_page_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_resource_keyword`
--

DROP TABLE IF EXISTS `link_resource_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_resource_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `keyword_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_406E98C8A76ED395` (`user_id`),
  KEY `IDX_406E98C8727ACA70` (`parent_id`),
  KEY `IDX_406E98C889329D25` (`resource_id`),
  KEY `IDX_406E98C8115D4552` (`keyword_id`),
  CONSTRAINT `FK_406E98C8115D4552` FOREIGN KEY (`keyword_id`) REFERENCES `keyword` (`id`),
  CONSTRAINT `FK_406E98C8727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_resource_keyword` (`id`),
  CONSTRAINT `FK_406E98C889329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `FK_406E98C8A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_resource_keyword`
--

LOCK TABLES `link_resource_keyword` WRITE;
/*!40000 ALTER TABLE `link_resource_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_resource_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_resource_media`
--

DROP TABLE IF EXISTS `link_resource_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_resource_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_55E31F7DA76ED395` (`user_id`),
  KEY `IDX_55E31F7D727ACA70` (`parent_id`),
  KEY `IDX_55E31F7D89329D25` (`resource_id`),
  KEY `IDX_55E31F7DEA9FDD75` (`media_id`),
  CONSTRAINT `FK_55E31F7D727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_resource_media` (`id`),
  CONSTRAINT `FK_55E31F7D89329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `FK_55E31F7DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_55E31F7DEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_resource_media`
--

LOCK TABLES `link_resource_media` WRITE;
/*!40000 ALTER TABLE `link_resource_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_resource_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_resource_topic`
--

DROP TABLE IF EXISTS `link_resource_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_resource_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A28F606AA76ED395` (`user_id`),
  KEY `IDX_A28F606A727ACA70` (`parent_id`),
  KEY `IDX_A28F606A89329D25` (`resource_id`),
  KEY `IDX_A28F606A1F55203D` (`topic_id`),
  CONSTRAINT `FK_A28F606A1F55203D` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`),
  CONSTRAINT `FK_A28F606A727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_resource_topic` (`id`),
  CONSTRAINT `FK_A28F606A89329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `FK_A28F606AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=359 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_resource_topic`
--

LOCK TABLES `link_resource_topic` WRITE;
/*!40000 ALTER TABLE `link_resource_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_resource_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_venue_media`
--

DROP TABLE IF EXISTS `link_venue_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link_venue_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7F172088A76ED395` (`user_id`),
  KEY `IDX_7F172088727ACA70` (`parent_id`),
  KEY `IDX_7F17208840A73EBA` (`venue_id`),
  KEY `IDX_7F172088EA9FDD75` (`media_id`),
  CONSTRAINT `FK_7F17208840A73EBA` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`id`),
  CONSTRAINT `FK_7F172088727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `link_venue_media` (`id`),
  CONSTRAINT `FK_7F172088A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_7F172088EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_venue_media`
--

LOCK TABLES `link_venue_media` WRITE;
/*!40000 ALTER TABLE `link_venue_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `link_venue_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6A2CA10CA76ED395` (`user_id`),
  KEY `IDX_6A2CA10C727ACA70` (`parent_id`),
  CONSTRAINT `FK_6A2CA10C727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `media` (`id`),
  CONSTRAINT `FK_6A2CA10CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_translation`
--

DROP TABLE IF EXISTS `media_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_430137FC2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_430137FC2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_translation`
--

LOCK TABLES `media_translation` WRITE;
/*!40000 ALTER TABLE `media_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('DoctrineMigrations\\Version20230906085842','2023-09-06 10:58:43',1762),('DoctrineMigrations\\Version20230906090222','2023-09-06 11:02:23',2440),('DoctrineMigrations\\Version20230906090415','2023-09-06 11:04:16',872),('DoctrineMigrations\\Version20230906090504','2023-09-06 11:05:05',85),('DoctrineMigrations\\Version20230907072951','2023-09-07 09:29:53',194),('DoctrineMigrations\\Version20230907203008','2023-09-07 22:30:09',1950),('DoctrineMigrations\\Version20230908131640','2023-09-08 15:16:42',2725),('DoctrineMigrations\\Version20230908133246','2023-09-08 15:32:47',1561),('DoctrineMigrations\\Version20230908195357','2023-09-08 21:53:58',3715),('DoctrineMigrations\\Version20230908200109','2023-09-08 22:01:10',2761),('DoctrineMigrations\\Version20230908200129','2023-09-08 22:02:11',1256),('DoctrineMigrations\\Version20230908224100','2023-09-09 00:41:01',10768),('DoctrineMigrations\\Version20230908224505','2023-09-09 00:45:05',151),('DoctrineMigrations\\Version20230909140036','2023-09-09 16:00:38',1896),('DoctrineMigrations\\Version20230909181816','2023-09-09 20:18:17',137),('DoctrineMigrations\\Version20230909210924','2023-09-09 23:09:25',83),('DoctrineMigrations\\Version20230910143946','2023-09-10 16:39:48',135),('DoctrineMigrations\\Version20230918152110','2023-09-18 17:21:11',29811),('DoctrineMigrations\\Version20231004085350','2023-10-04 10:53:52',7375),('DoctrineMigrations\\Version20231004090102','2023-10-04 11:01:03',14571),('DoctrineMigrations\\Version20231004090401','2023-10-04 11:04:03',453),('DoctrineMigrations\\Version20231004090439','2023-10-04 11:04:40',589),('DoctrineMigrations\\Version20231004090759','2023-10-04 11:08:01',1844),('DoctrineMigrations\\Version20231005110329','2023-10-05 13:03:31',1143),('DoctrineMigrations\\Version20231005110434','2023-10-05 13:04:35',527),('DoctrineMigrations\\Version20231005111856','2023-10-05 13:18:58',77),('DoctrineMigrations\\Version20231005122935','2023-10-05 14:29:36',201),('DoctrineMigrations\\Version20231005145118','2023-10-05 16:51:19',55),('DoctrineMigrations\\Version20231005152147','2023-10-05 17:21:48',109),('DoctrineMigrations\\Version20231006082453','2023-10-06 10:24:55',1503),('DoctrineMigrations\\Version20231006085528','2023-10-06 10:55:29',107),('DoctrineMigrations\\Version20231006090801','2023-10-06 11:08:02',537),('DoctrineMigrations\\Version20231006091119','2023-10-06 11:11:20',3282),('DoctrineMigrations\\Version20231006102136','2023-10-06 12:21:37',1074),('DoctrineMigrations\\Version20231009140119','2023-10-09 16:01:21',1440);
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `template` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `menu` varchar(255) DEFAULT NULL,
  `is_dir` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `helper` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_140AB620A76ED395` (`user_id`),
  KEY `IDX_140AB620727ACA70` (`parent_id`),
  CONSTRAINT `FK_140AB620727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_140AB620A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (1,'blocks','index',NULL,'accueil',9,NULL,'0',1,NULL,'2020-11-23 00:50:16','2023-10-11 13:30:16','2020-11-23 00:50:16',0,0,NULL,NULL);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_translation`
--

DROP TABLE IF EXISTS `page_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `html` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_A3D51B1D2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_A3D51B1D2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_translation`
--

LOCK TABLES `page_translation` WRITE;
/*!40000 ALTER TABLE `page_translation` DISABLE KEYS */;
INSERT INTO `page_translation` VALUES (1,1,'fr','Accueil',NULL);
/*!40000 ALTER TABLE `page_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `param`
--

DROP TABLE IF EXISTS `param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A4FA7C89A76ED395` (`user_id`),
  KEY `IDX_A4FA7C89727ACA70` (`parent_id`),
  CONSTRAINT `FK_A4FA7C89727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `param` (`id`),
  CONSTRAINT `FK_A4FA7C89A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `param`
--

LOCK TABLES `param` WRITE;
/*!40000 ALTER TABLE `param` DISABLE KEYS */;
INSERT INTO `param` VALUES (1,1,NULL,'2023-09-07 22:34:18','2023-10-11 13:25:51','2023-09-07 22:34:18',0,0,0,0,'site_name'),(2,1,NULL,'2023-09-07 22:35:03','2023-09-07 22:35:20','2023-09-07 22:35:03',0,0,0,0,'meta_description'),(3,1,NULL,'2023-09-07 22:42:08','2023-09-07 22:42:08','2023-09-07 22:42:08',0,0,0,0,'meta_author'),(4,1,NULL,'2023-09-07 23:01:41','2023-09-07 23:03:54','2023-09-07 23:01:41',0,0,0,0,'facebook_url'),(5,1,NULL,'2023-09-07 23:02:19','2023-09-07 23:03:45','2023-09-07 23:02:19',0,0,0,0,'instagram_url'),(6,1,NULL,'2023-09-07 23:03:29','2023-10-11 13:26:10','2023-09-07 23:03:29',0,0,0,0,'youtube_url'),(7,1,NULL,'2023-09-09 20:07:56','2023-09-09 20:07:56','2023-09-09 20:07:56',0,0,0,0,'sender_email_address'),(8,1,NULL,'2023-09-10 22:58:33','2023-10-05 17:57:13','2023-09-10 22:58:33',0,0,0,0,'results_limit'),(9,1,NULL,'2023-09-19 20:37:55','2023-09-19 20:37:55','2023-09-19 20:37:55',0,0,0,0,'contact_email_address');
/*!40000 ALTER TABLE `param` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `param_translation`
--

DROP TABLE IF EXISTS `param_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `param_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `param_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_5DA4AD642C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_5DA4AD642C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `param` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `param_translation`
--

LOCK TABLES `param_translation` WRITE;
/*!40000 ALTER TABLE `param_translation` DISABLE KEYS */;
INSERT INTO `param_translation` VALUES (1,1,'UI5','fr'),(2,2,'Description du site pour les moteurs de recherche','fr'),(3,3,'Timothée Rolin','fr'),(4,4,'https://www.facebook.com/','fr'),(5,5,'https://www.instagram.com/','fr'),(7,7,'contact@metaproject.net','fr'),(8,8,'20','fr'),(9,9,'contact@metaproject.net','fr'),(10,6,'https://www.youtube.com','fr');
/*!40000 ALTER TABLE `param_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource`
--

DROP TABLE IF EXISTS `resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BC91F416A76ED395` (`user_id`),
  KEY `IDX_BC91F416727ACA70` (`parent_id`),
  KEY `IDX_BC91F4167A19A357` (`contributor_id`),
  CONSTRAINT `FK_BC91F416727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `FK_BC91F4167A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_BC91F416A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource`
--

LOCK TABLES `resource` WRITE;
/*!40000 ALTER TABLE `resource` DISABLE KEYS */;
/*!40000 ALTER TABLE `resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resource_translation`
--

DROP TABLE IF EXISTS `resource_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resource_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_B0D27B3D2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_B0D27B3D2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `resource` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resource_translation`
--

LOCK TABLES `resource_translation` WRITE;
/*!40000 ALTER TABLE `resource_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `resource_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selection`
--

DROP TABLE IF EXISTS `selection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `selection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `contribution_id` int(11) DEFAULT NULL,
  `answer_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_96A50CD7A76ED395` (`user_id`),
  KEY `IDX_96A50CD7727ACA70` (`parent_id`),
  KEY `IDX_96A50CD77A19A357` (`contributor_id`),
  KEY `IDX_96A50CD7FE5E5FBD` (`contribution_id`),
  KEY `IDX_96A50CD7AA334807` (`answer_id`),
  CONSTRAINT `FK_96A50CD7727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `selection` (`id`),
  CONSTRAINT `FK_96A50CD77A19A357` FOREIGN KEY (`contributor_id`) REFERENCES `contributor` (`id`),
  CONSTRAINT `FK_96A50CD7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_96A50CD7AA334807` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`),
  CONSTRAINT `FK_96A50CD7FE5E5FBD` FOREIGN KEY (`contribution_id`) REFERENCES `contribution` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selection`
--

LOCK TABLES `selection` WRITE;
/*!40000 ALTER TABLE `selection` DISABLE KEYS */;
/*!40000 ALTER TABLE `selection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9D40DE1BA76ED395` (`user_id`),
  KEY `IDX_9D40DE1B727ACA70` (`parent_id`),
  CONSTRAINT `FK_9D40DE1B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `topic` (`id`),
  CONSTRAINT `FK_9D40DE1BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic`
--

LOCK TABLES `topic` WRITE;
/*!40000 ALTER TABLE `topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic_translation`
--

DROP TABLE IF EXISTS `topic_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `topic_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_6F17DF172C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_6F17DF172C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic_translation`
--

LOCK TABLES `topic_translation` WRITE;
/*!40000 ALTER TABLE `topic_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `topic_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `salt` varchar(255) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `is_dir` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8D93D649A76ED395` (`user_id`),
  KEY `IDX_8D93D649727ACA70` (`parent_id`),
  CONSTRAINT `FK_8D93D649727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8D93D649A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'root',0,'MP3ZsTibrQcBooRgIxgSng==','root@ui-cms.com','3rhs1+IWNtC8jPvlRD9RH1Gw53vNgFCanPj63TlD','[\"ROLE_SUPER_ADMIN\",\"ROLE_USER\"]','0',1,NULL,NULL,NULL,NULL,0,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venue`
--

DROP TABLE IF EXISTS `venue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `is_concealed` tinyint(1) NOT NULL,
  `is_locked` tinyint(1) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_91911B0DA76ED395` (`user_id`),
  KEY `IDX_91911B0D727ACA70` (`parent_id`),
  CONSTRAINT `FK_91911B0D727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `venue` (`id`),
  CONSTRAINT `FK_91911B0DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venue`
--

LOCK TABLES `venue` WRITE;
/*!40000 ALTER TABLE `venue` DISABLE KEYS */;
/*!40000 ALTER TABLE `venue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venue_translation`
--

DROP TABLE IF EXISTS `venue_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venue_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `translatable_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `venue_translation_unique_translation` (`translatable_id`,`locale`),
  KEY `IDX_A2B005A2C2AC5D3` (`translatable_id`),
  CONSTRAINT `FK_A2B005A2C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `venue` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venue_translation`
--

LOCK TABLES `venue_translation` WRITE;
/*!40000 ALTER TABLE `venue_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `venue_translation` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-11 13:30:53
