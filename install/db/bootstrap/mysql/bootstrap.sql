/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.26-MariaDB, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: bootstrap
-- ------------------------------------------------------
-- Server version	10.5.26-MariaDB-0+deb11u2

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_translation`
--

LOCK TABLES `block_translation` WRITE;
/*!40000 ALTER TABLE `block_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_translation` ENABLE KEYS */;
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
  `from_email` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
INSERT INTO `migration_versions` VALUES ('DoctrineMigrations\\Version20230906085842','2023-09-06 10:58:43',1762),('DoctrineMigrations\\Version20230906090222','2023-09-06 11:02:23',2440),('DoctrineMigrations\\Version20230906090415','2023-09-06 11:04:16',872),('DoctrineMigrations\\Version20230906090504','2023-09-06 11:05:05',85),('DoctrineMigrations\\Version20230907072951','2023-09-07 09:29:53',194),('DoctrineMigrations\\Version20230907203008','2023-09-07 22:30:09',1950),('DoctrineMigrations\\Version20230908131640','2023-09-08 15:16:42',2725),('DoctrineMigrations\\Version20230908133246','2023-09-08 15:32:47',1561),('DoctrineMigrations\\Version20230908195357','2023-09-08 21:53:58',3715),('DoctrineMigrations\\Version20230908200109','2023-09-08 22:01:10',2761),('DoctrineMigrations\\Version20230908200129','2023-09-08 22:02:11',1256),('DoctrineMigrations\\Version20230908224100','2023-09-09 00:41:01',10768),('DoctrineMigrations\\Version20230908224505','2023-09-09 00:45:05',151),('DoctrineMigrations\\Version20230909140036','2023-09-09 16:00:38',1896),('DoctrineMigrations\\Version20230909181816','2023-09-09 20:18:17',137),('DoctrineMigrations\\Version20230909210924','2023-09-09 23:09:25',83),('DoctrineMigrations\\Version20230910143946','2023-09-10 16:39:48',135),('DoctrineMigrations\\Version20230918152110','2023-09-18 17:21:11',29811),('DoctrineMigrations\\Version20231004085350','2023-10-04 10:53:52',7375),('DoctrineMigrations\\Version20231004090102','2023-10-04 11:01:03',14571),('DoctrineMigrations\\Version20231004090401','2023-10-04 11:04:03',453),('DoctrineMigrations\\Version20231004090439','2023-10-04 11:04:40',589),('DoctrineMigrations\\Version20231004090759','2023-10-04 11:08:01',1844),('DoctrineMigrations\\Version20231005110329','2023-10-05 13:03:31',1143),('DoctrineMigrations\\Version20231005110434','2023-10-05 13:04:35',527),('DoctrineMigrations\\Version20231005111856','2023-10-05 13:18:58',77),('DoctrineMigrations\\Version20231005122935','2023-10-05 14:29:36',201),('DoctrineMigrations\\Version20231005145118','2023-10-05 16:51:19',55),('DoctrineMigrations\\Version20231005152147','2023-10-05 17:21:48',109),('DoctrineMigrations\\Version20231006082453','2023-10-06 10:24:55',1503),('DoctrineMigrations\\Version20231006085528','2023-10-06 10:55:29',107),('DoctrineMigrations\\Version20231006090801','2023-10-06 11:08:02',537),('DoctrineMigrations\\Version20231006091119','2023-10-06 11:11:20',3282),('DoctrineMigrations\\Version20231006102136','2023-10-06 12:21:37',1074),('DoctrineMigrations\\Version20231009140119','2023-10-09 16:01:21',1440),('DoctrineMigrations\\Version20240205174516','2024-02-05 18:45:19',155),('DoctrineMigrations\\Version20241218161405','2024-12-18 17:14:08',8373);
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
INSERT INTO `page` VALUES (1,'blocks','index',NULL,'accueil',9,NULL,'0',1,NULL,'2020-11-23 00:50:16','2024-05-31 17:13:52','2020-11-23 00:50:16',0,0,NULL,NULL);
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-18 17:27:28
