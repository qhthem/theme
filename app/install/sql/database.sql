
    
-- MySQL dump 10.13  Distrib 5.6.50, for Linux (x86_64)
--
-- Host: localhost    Database: demo
-- ------------------------------------------------------
-- Server version	5.6.50-log

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
-- Table structure for table `qh_admin`
--

DROP TABLE IF EXISTS `qh_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_admin` (
  `adminid` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `roleid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `rolename` varchar(30) NOT NULL DEFAULT '',
  `nickname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(15) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_admin`
--

LOCK TABLES `qh_admin` WRITE;
/*!40000 ALTER TABLE `qh_admin` DISABLE KEYS */;
INSERT INTO `qh_admin` VALUES (1,'admin','6708ee53da1f65f2502af222da854fef',1,'超级管理员','欢迎查看演示','1716892803@qq.com',0,'',0);
/*!40000 ALTER TABLE `qh_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_admin_log`
--

DROP TABLE IF EXISTS `qh_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app` varchar(15) NOT NULL DEFAULT '',
  `controller` varchar(20) NOT NULL DEFAULT '',
  `querystring` text NOT NULL,
  `content` text NOT NULL,
  `adminname` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `create_times` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `logtime` (`create_times`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_admin_log`
--

LOCK TABLES `qh_admin_log` WRITE;
/*!40000 ALTER TABLE `qh_admin_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_admin_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_admin_login_log`
--

DROP TABLE IF EXISTS `qh_admin_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_admin_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(30) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(15) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `loginresult` int(10) NOT NULL DEFAULT '0' COMMENT '登录结果1为登录成功0为登录失败',
  `cause` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `admin_index` (`adminname`,`loginresult`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_admin_login_log`
--

LOCK TABLES `qh_admin_login_log` WRITE;
/*!40000 ALTER TABLE `qh_admin_login_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_admin_login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_admin_role`
--

DROP TABLE IF EXISTS `qh_admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_admin_role` (
  `roleid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rolename` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `system` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`roleid`),
  KEY `disabled` (`disabled`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_admin_role`
--

LOCK TABLES `qh_admin_role` WRITE;
/*!40000 ALTER TABLE `qh_admin_role` DISABLE KEYS */;
INSERT INTO `qh_admin_role` VALUES (1,'超级管理员','超级管理员',1,0),(2,'总编','总编',1,0),(3,'发布人员','发布人员',1,0);
/*!40000 ALTER TABLE `qh_admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_admin_role_priv`
--

DROP TABLE IF EXISTS `qh_admin_role_priv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_admin_role_priv` (
  `roleid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `typeid` int(5) NOT NULL,
  `m` char(20) NOT NULL DEFAULT '',
  `c` char(20) NOT NULL DEFAULT '',
  `a` char(30) NOT NULL DEFAULT '',
  `data` char(100) NOT NULL DEFAULT '',
  KEY `roleid` (`roleid`,`m`,`c`,`a`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_admin_role_priv`
--

LOCK TABLES `qh_admin_role_priv` WRITE;
/*!40000 ALTER TABLE `qh_admin_role_priv` DISABLE KEYS */;
INSERT INTO `qh_admin_role_priv` VALUES (2,61,'admin','Scrap','index','scrap_list'),(2,60,'admin','ArticleScrape','index','scraper_list'),(2,59,'admin','ArticleScrape','top',''),(2,54,'admin','database','backup','database_revert'),(2,53,'admin','database','copy','database_copy'),(2,52,'admin','database','top',''),(2,56,'admin','order','index','order_list'),(2,51,'admin','pay','index','pay_list'),(2,50,'admin','Group','index','group_list'),(2,49,'admin','member','index','member_list'),(2,48,'admin','member','index',''),(2,58,'admin','System','workbench',''),(2,57,'admin','index','menu',''),(2,44,'','','','mian'),(2,32,'admin','admin','login_log','adminlog_list'),(2,5,'admin','Adminlog','index','login_list'),(2,31,'admin','admin','top',''),(2,8,'admin','upload','list','upload_list'),(2,30,'admin','upload','top',''),(2,4,'admin','Manage','index','Manage_list'),(2,3,'admin','Role','index','role_list'),(2,2,'admin','Muen','index','muen_index'),(2,23,'admin','index','top',''),(2,55,'admin','comment','index','comment_list'),(2,47,'admin','field','index','field_list'),(2,46,'admin','Sitemodel','index','model_list'),(2,13,'admin','Link','index','link_list'),(2,12,'admin','Banner','index','banner_list'),(2,10,'admin','content','index','article_list'),(2,9,'admin','Category','index','category_list'),(2,7,'admin','content','top',''),(2,35,'admin','Sitemap','index','map_set'),(2,6,'admin','System','index','system_set'),(2,1,'admin','index','top','');
/*!40000 ALTER TABLE `qh_admin_role_priv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_article`
--

DROP TABLE IF EXISTS `qh_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `nickname` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(180) NOT NULL DEFAULT '',
  `color` char(9) NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `keywords` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `click` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `copyfrom` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(150) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `flag` varchar(12) NOT NULL DEFAULT '' COMMENT '1置顶,2头条,3特荐,4推荐,5热点,6幻灯,7跳转',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `groupids_view` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '阅读收费',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '收费类型',
  `is_push` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否百度推送',
  `likes` int(50) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`),
  KEY `catid` (`catid`,`status`),
  KEY `userid` (`userid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_article`
--

LOCK TABLES `qh_article` WRITE;
/*!40000 ALTER TABLE `qh_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_banan`
--

DROP TABLE IF EXISTS `qh_banan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_banan` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `inputtime` int(10) NOT NULL,
  `listorder` int(5) NOT NULL,
  `type` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_banan`
--

LOCK TABLES `qh_banan` WRITE;
/*!40000 ALTER TABLE `qh_banan` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_banan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_category`
--

DROP TABLE IF EXISTS `qh_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_category` (
  `catid` int(5) NOT NULL AUTO_INCREMENT,
  `catname` varchar(255) NOT NULL,
  `modelid` int(5) NOT NULL,
  `parentid` int(5) NOT NULL,
  `arrchildid` varchar(100) NOT NULL,
  `catdir` varchar(100) NOT NULL,
  `catimg` varchar(255) NOT NULL,
  `type` int(5) NOT NULL,
  `listorder` int(5) NOT NULL,
  `target` varchar(20) NOT NULL,
  `publish` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  `pclink` varchar(255) NOT NULL,
  `category_template` varchar(255) NOT NULL,
  `list_template` varchar(255) NOT NULL,
  `show_template` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_category`
--

LOCK TABLES `qh_category` WRITE;
/*!40000 ALTER TABLE `qh_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_collection_content`
--

DROP TABLE IF EXISTS `qh_collection_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_collection_content` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nodeid` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `inputtime` int(20) NOT NULL,
  `content` text NOT NULL,
  `inserts` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_collection_content`
--

LOCK TABLES `qh_collection_content` WRITE;
/*!40000 ALTER TABLE `qh_collection_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_collection_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_collection_node`
--

DROP TABLE IF EXISTS `qh_collection_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_collection_node` (
  `nodeid` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lasttime` int(10) NOT NULL,
  `uft` varchar(20) NOT NULL,
  `type` int(5) NOT NULL,
  `urlpage` text NOT NULL,
  `page_start` int(5) NOT NULL,
  `page_end` int(5) NOT NULL,
  `par_num` int(5) NOT NULL,
  `title_rule` text NOT NULL,
  `title_html_rule` text NOT NULL,
  `time_rule` varchar(255) NOT NULL,
  `time_html_rule` text NOT NULL,
  `content_rule` varchar(255) NOT NULL,
  `content_html_rule` text NOT NULL,
  `down_attachment` int(5) NOT NULL,
  `down_url` varchar(255) NOT NULL,
  `coll_order` int(5) NOT NULL,
  `rand_time` int(5) NOT NULL,
  `rand_time_range` varchar(255) NOT NULL,
  PRIMARY KEY (`nodeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_collection_node`
--

LOCK TABLES `qh_collection_node` WRITE;
/*!40000 ALTER TABLE `qh_collection_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_collection_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_comments`
--

DROP TABLE IF EXISTS `qh_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `replyid` int(5) NOT NULL COMMENT '被回复者',
  `aid` int(11) NOT NULL,
  `modelid` int(5) NOT NULL,
  `parentid` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `creat_time` int(11) NOT NULL,
  `ip` varchar(200) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_comments`
--

LOCK TABLES `qh_comments` WRITE;
/*!40000 ALTER TABLE `qh_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_content`
--

DROP TABLE IF EXISTS `qh_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_content` (
  `allid` int(5) NOT NULL AUTO_INCREMENT,
  `modelid` int(5) NOT NULL,
  `catid` int(5) NOT NULL,
  `id` int(5) NOT NULL,
  `userid` int(5) NOT NULL,
  `username` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `inputtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`allid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_content`
--

LOCK TABLES `qh_content` WRITE;
/*!40000 ALTER TABLE `qh_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_exp`
--

DROP TABLE IF EXISTS `qh_exp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_exp` (
  `label` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `value` int(5) NOT NULL,
  `point` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_exp`
--

LOCK TABLES `qh_exp` WRITE;
/*!40000 ALTER TABLE `qh_exp` DISABLE KEYS */;
INSERT INTO `qh_exp` VALUES ('发布文章','article',15,5),('发布评论','comment',10,5),('关注他人','follow',10,5),('文章被点赞','like',15,5),('每日签到','sign',15,5),('开通会员','vip',15,10);
/*!40000 ALTER TABLE `qh_exp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_experience`
--

DROP TABLE IF EXISTS `qh_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(11) NOT NULL,
  `userid` int(5) NOT NULL,
  `creat_time` int(10) NOT NULL,
  `msg` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_experience`
--

LOCK TABLES `qh_experience` WRITE;
/*!40000 ALTER TABLE `qh_experience` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_experience` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_favorite`
--

DROP TABLE IF EXISTS `qh_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `aid` int(10) NOT NULL,
  `modelid` int(10) NOT NULL,
  `catid` int(5) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_favorite`
--

LOCK TABLES `qh_favorite` WRITE;
/*!40000 ALTER TABLE `qh_favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_field`
--

DROP TABLE IF EXISTS `qh_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_field` (
  `fieldid` int(5) NOT NULL AUTO_INCREMENT,
  `modelid` int(5) NOT NULL,
  `field` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fieldtype` varchar(120) NOT NULL,
  `maxlength` int(5) NOT NULL,
  `defaultvalue` varchar(255) NOT NULL,
  `setting` text NOT NULL,
  `isrequired` int(5) NOT NULL,
  `listorder` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`fieldid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_field`
--

LOCK TABLES `qh_field` WRITE;
/*!40000 ALTER TABLE `qh_field` DISABLE KEYS */;
INSERT INTO `qh_field` VALUES (1,1,'likes','点赞','number',50,'0','',0,0,1);
/*!40000 ALTER TABLE `qh_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_follow`
--

DROP TABLE IF EXISTS `qh_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `followid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '被关注者id',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_follow`
--

LOCK TABLES `qh_follow` WRITE;
/*!40000 ALTER TABLE `qh_follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_group`
--

DROP TABLE IF EXISTS `qh_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_group` (
  `groupid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(21) NOT NULL DEFAULT '',
  `experience` smallint(6) unsigned NOT NULL DEFAULT '0',
  `authority` char(12) NOT NULL DEFAULT '' COMMENT '1短消息,2发表评论,3发表内容',
  `description` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_group`
--

LOCK TABLES `qh_group` WRITE;
/*!40000 ALTER TABLE `qh_group` DISABLE KEYS */;
INSERT INTO `qh_group` VALUES (1,'初来乍到',50,'0','初来乍到组'),(2,'新手上路',100,'1,2','新手上路组'),(3,'中级会员',500,'1,2,3','中级会员组'),(4,'高级会员',1000,'1,2,3','高级会员组'),(5,'金牌会员',1500,'1,2,3,4','金牌会员组');
/*!40000 ALTER TABLE `qh_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_group_auth`
--

DROP TABLE IF EXISTS `qh_group_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_group_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authname` varchar(255) NOT NULL,
  `app` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_group_auth`
--

LOCK TABLES `qh_group_auth` WRITE;
/*!40000 ALTER TABLE `qh_group_auth` DISABLE KEYS */;
INSERT INTO `qh_group_auth` VALUES (1,'权限名称'),(2,'组别权限');
/*!40000 ALTER TABLE `qh_group_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_link`
--

DROP TABLE IF EXISTS `qh_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_link` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `inputtime` int(10) NOT NULL,
  `linktype` int(5) NOT NULL,
  `listorder` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_link`
--

LOCK TABLES `qh_link` WRITE;
/*!40000 ALTER TABLE `qh_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_member`
--

DROP TABLE IF EXISTS `qh_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_member` (
  `userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sex` int(5) NOT NULL COMMENT '性别',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `qq` varchar(255) NOT NULL COMMENT 'QQ',
  `userpic` varchar(255) NOT NULL COMMENT '头像',
  `area` varchar(255) NOT NULL COMMENT '地址',
  `motto` varchar(255) NOT NULL COMMENT '个性签名',
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `loginnum` smallint(5) unsigned NOT NULL DEFAULT '0',
  `email` char(32) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL,
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金钱',
  `experience` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '经验',
  `point` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分',
  `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核,1正常,2锁定,3拒绝',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overduedate` int(10) unsigned NOT NULL DEFAULT '0',
  `email_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cover` varchar(255) NOT NULL COMMENT '封面',
  `openid` varchar(255) NOT NULL,
  `lastdate` int(20) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_member`
--

LOCK TABLES `qh_member` WRITE;
/*!40000 ALTER TABLE `qh_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_menu`
--

DROP TABLE IF EXISTS `qh_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_menu` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parentid` int(5) DEFAULT '0',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `m` char(25) NOT NULL,
  `c` char(25) NOT NULL,
  `a` char(25) NOT NULL,
  `data` varchar(255) DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_menu`
--

LOCK TABLES `qh_menu` WRITE;
/*!40000 ALTER TABLE `qh_menu` DISABLE KEYS */;
INSERT INTO `qh_menu` VALUES (1,0,'系统管理','admin','index','top','','Setting',0,1),(2,23,'菜单设置','admin','Muen','index','muen_index','',0,1),(3,23,'角色设置','admin','Role','index','role_list','',0,1),(4,23,'管员设置','admin','Manage','index','Manage_list','',0,1),(5,31,'登录日志','admin','Adminlog','index','login_list','',0,1),(6,1,'网站设置','admin','System','index','system_set','',0,1),(7,0,'内容管理','admin','content','top','','Document',0,1),(8,30,'附件管理','admin','upload','list','upload_list','',0,1),(9,7,'栏目管理','admin','Category','index','category_list','',0,1),(10,7,'内容管理','admin','content','index','article_list','',0,1),(12,7,'轮播管理','admin','Banner','index','banner_list','',0,1),(13,7,'友情链接','admin','Link','index','link_list','',0,1),(23,0,'权限管理','admin','index','top','','Open',0,1),(30,0,'素材管理','admin','upload','top','','Picture',0,1),(31,0,'系统维护','admin','admin','top','','SetUp',0,1),(32,31,'操作日志','admin','admin','login_log','adminlog_list','',0,1),(35,1,'生成地图','admin','Sitemap','index','map_set','',0,1),(44,0,'工作台','','','','mian','Monitor',1,1),(46,7,'模型管理','admin','Sitemodel','index','model_list','',0,1),(47,7,'字段管理','admin','field','index','field_list','',0,1),(48,0,'用户管理','admin','member','index','','User',0,1),(49,48,'用户列表','admin','member','index','member_list','',0,1),(50,48,'组别管理','admin','Group','index','group_list','',0,1),(51,48,'账单管理','admin','pay','index','pay_list','',0,1),(52,0,'数据管理','admin','database','top','','CopyDocument',0,1),(53,52,'数据备份','admin','database','copy','database_copy','',0,1),(54,52,'数据还原','admin','database','backup','database_revert','',0,1),(55,7,'评论列表','admin','comment','index','comment_list','',0,1),(56,48,'订单记录','admin','order','index','order_list','',0,1),(57,44,'菜单栏','admin','index','menu','','',0,0),(58,44,'中控台','admin','System','workbench','','',0,0),(59,0,'采集管理','admin','ArticleScrape','top','','ScaleToOriginal',0,1),(60,59,'采集列表','admin','ArticleScrape','index','scraper_list','',0,1),(61,59,'采集内容','admin','Scrap','index','scrap_list','',0,1),(62,0,'应用管理','admin','App','top','','Apple',0,1),(63,62,'应用风格','admin','Theme','index','theme_list','',0,1);
/*!40000 ALTER TABLE `qh_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_message`
--

DROP TABLE IF EXISTS `qh_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_from` varchar(30) NOT NULL DEFAULT '' COMMENT '发件人',
  `send_to` varchar(30) NOT NULL DEFAULT '' COMMENT '收件人',
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1正常0隐藏',
  `isread` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否读过',
  `type` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_message`
--

LOCK TABLES `qh_message` WRITE;
/*!40000 ALTER TABLE `qh_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_model`
--

DROP TABLE IF EXISTS `qh_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_model` (
  `modelid` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `isdefault` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`modelid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_model`
--

LOCK TABLES `qh_model` WRITE;
/*!40000 ALTER TABLE `qh_model` DISABLE KEYS */;
INSERT INTO `qh_model` VALUES (1,'文章模型','article','文章模型',1,1);
/*!40000 ALTER TABLE `qh_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_order`
--

DROP TABLE IF EXISTS `qh_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_order` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(200) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `userid` int(5) NOT NULL,
  `addtime` varchar(100) NOT NULL,
  `paytime` varchar(100) NOT NULL,
  `paytype` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `point` int(5) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `status` int(5) NOT NULL DEFAULT '0' COMMENT '0为未支付1为已支付',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_order`
--

LOCK TABLES `qh_order` WRITE;
/*!40000 ALTER TABLE `qh_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_pay`
--

DROP TABLE IF EXISTS `qh_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_pay` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `paytype` varchar(20) NOT NULL,
  `trade_sn` char(18) NOT NULL DEFAULT '' COMMENT '订单号',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `money` char(8) NOT NULL DEFAULT '' COMMENT '金钱或积分的量',
  `creat_time` int(10) unsigned NOT NULL DEFAULT '0',
  `msg` varchar(30) NOT NULL DEFAULT '' COMMENT '类型说明',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1支出,0收入',
  `ip` char(15) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1成功,0失败',
  `remarks` varchar(250) NOT NULL DEFAULT '' COMMENT '备注说明',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `trade_sn` (`trade_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_pay`
--

LOCK TABLES `qh_pay` WRITE;
/*!40000 ALTER TABLE `qh_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_sign`
--

DROP TABLE IF EXISTS `qh_sign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `inputtime` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `point` int(5) NOT NULL,
  `exp` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_sign`
--

LOCK TABLES `qh_sign` WRITE;
/*!40000 ALTER TABLE `qh_sign` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_sign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_tag`
--

DROP TABLE IF EXISTS `qh_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_tag` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `modelid` int(5) NOT NULL,
  `catid` int(5) NOT NULL,
  `aid` int(5) NOT NULL,
  `inputtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_tag`
--

LOCK TABLES `qh_tag` WRITE;
/*!40000 ALTER TABLE `qh_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qh_upload`
--

DROP TABLE IF EXISTS `qh_upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qh_upload` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app` char(15) NOT NULL DEFAULT '',
  `originname` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(50) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `arrtype` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `uploadtime` int(20) unsigned NOT NULL DEFAULT '0',
  `uploadip` char(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid_index` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qh_upload`
--

LOCK TABLES `qh_upload` WRITE;
/*!40000 ALTER TABLE `qh_upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `qh_upload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'demo'
--

--
-- Dumping routines for database 'demo'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-29 16:08:30
