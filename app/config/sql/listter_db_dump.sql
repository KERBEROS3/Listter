-- MySQL dump 10.11
--
-- Host: localhost    Database: list_com
-- ------------------------------------------------------
-- Server version	5.0.51b-community

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
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `follows` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `follow_user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_id_and_follow_user_id_unique` (`user_id`,`follow_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=304 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `pieces`
--

DROP TABLE IF EXISTS `pieces`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pieces` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_id` int(10) unsigned NOT NULL,
  `comment` varchar(300) NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `supports`
--

DROP TABLE IF EXISTS `supports`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `supports` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_id` int(10) unsigned NOT NULL,
  `supporter_user_id` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `comment` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `comment_modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task` varchar(250) default NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `completed` tinyint(1) default '0',
  PRIMARY KEY  USING BTREE (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `timelines`
--

DROP TABLE IF EXISTS `timelines`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `timelines` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `task_id` int(10) unsigned NOT NULL,
  `progress` smallint(3) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `modified` datetime NOT NULL,
  `newer` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` char(50) default NULL,
  `password` char(50) default NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `twitter_user` varchar(20) NOT NULL,
  `twitter_password` varchar(500) NOT NULL,
  `twitter_enabled` tinyint(1) NOT NULL,
  `email` varchar(255) default NULL,
  `realname` varchar(40) NOT NULL,
  `url` varchar(600) NOT NULL,
  `description` varchar(180) NOT NULL,
  `token` varchar(32) default NULL,
  `location` varchar(70) default NULL COMMENT 'foreget password',
  `oauth_token` varchar(60) default NULL,
  `oauth_token_secret` varchar(50) default NULL,
  `follow_mail_enabled` tinyint(1) unsigned NOT NULL default '1',
  `point_mail_enabled` tinyint(3) unsigned NOT NULL default '1',
  `comment_mail_enabled` tinyint(1) unsigned NOT NULL default '1',
  `complete_show_flag` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username_unique` (`username`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-08-24  9:15:55
