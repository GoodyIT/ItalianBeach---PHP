﻿# Host: localhost  (Version 5.5.5-10.1.10-MariaDB)
# Date: 2016-05-05 07:01:50
# Generator: MySQL-Front 5.3  (Build 5.33)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "auth_rule"
#

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "auth_rule"
#

INSERT INTO `auth_rule` VALUES ('isAuthor','O:28:\"common\\rbac\\rules\\AuthorRule\":3:{s:4:\"name\";s:8:\"isAuthor\";s:9:\"createdAt\";i:1462010978;s:9:\"updatedAt\";i:1462010978;}',1462010978,1462010978);

#
# Structure for table "auth_item"
#

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "auth_item"
#

INSERT INTO `auth_item` VALUES ('admin',1,'Administrator of this application',NULL,NULL,1462010978,1462010978),('adminArticle',2,'Allows admin+ roles to manage articles',NULL,NULL,1462010978,1462010978),('createArticle',2,'Allows editor+ roles to create articles',NULL,NULL,1462010978,1462010978),('deleteArticle',2,'Allows admin+ roles to delete articles',NULL,NULL,1462010978,1462010978),('editor',1,'Editor of this application',NULL,NULL,1462010978,1462010978),('manageUsers',2,'Allows admin+ roles to manage users',NULL,NULL,1462010978,1462010978),('member',1,'Registered users, members of this site',NULL,NULL,1462010978,1462010978),('premium',1,'Premium members. They have more permissions than normal members',NULL,NULL,1462010978,1462010978),('support',1,'Support staff',NULL,NULL,1462010978,1462010978),('theCreator',1,'You!',NULL,NULL,1462010978,1462010978),('updateArticle',2,'Allows editor+ roles to update articles',NULL,NULL,1462010978,1462010978),('updateOwnArticle',2,'Update own article','isAuthor',NULL,1462010978,1462010978),('usePremiumContent',2,'Allows premium+ roles to use premium content',NULL,NULL,1462010978,1462010978);

#
# Structure for table "auth_item_child"
#

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "auth_item_child"
#

INSERT INTO `auth_item_child` VALUES ('admin','deleteArticle'),('admin','editor'),('admin','manageUsers'),('admin','updateArticle'),('editor','adminArticle'),('editor','createArticle'),('editor','support'),('editor','updateOwnArticle'),('premium','usePremiumContent'),('support','member'),('support','premium'),('theCreator','admin'),('updateOwnArticle','updateArticle');

#
# Structure for table "auth_assignment"
#

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "auth_assignment"
#

INSERT INTO `auth_assignment` VALUES ('admin',2,1462346066),('theCreator',6,NULL);

#
# Structure for table "migration"
#

DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "migration"
#

INSERT INTO `migration` VALUES ('m000000_000000_base',1461076913),('m130524_201442_init',1461076918);

#
# Structure for table "session"
#

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `expire` int(11) NOT NULL,
  `data` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "session"
#

INSERT INTO `session` VALUES ('0rkgd2ho2l6glhhoe2naere7d4',1462444230,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B');

#
# Structure for table "tbl_book"
#

DROP TABLE IF EXISTS `tbl_book`;
CREATE TABLE `tbl_book` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `sunshadeseat` varchar(100) DEFAULT NULL,
  `arrival` varchar(255) DEFAULT NULL,
  `servicetype` tinyint(3) DEFAULT '1',
  `price` int(11) DEFAULT '0',
  `mainprice` int(11) DEFAULT '0',
  `tax` int(11) DEFAULT '0',
  `supplement` int(11) DEFAULT '0',
  `guests` int(11) DEFAULT '1',
  `bookstate` varchar(50) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_book"
#

INSERT INTO `tbl_book` VALUES (88,'A2','04/30/2016',4,950,750,100,100,1,'booked',NULL),(89,'A3','04/29/2016',4,950,750,100,100,1,'booked',NULL),(90,'A1','04/29/2016',4,950,750,100,100,1,'booked',NULL),(91,'A10','04/29/2016',4,950,750,100,100,1,'booked',NULL);

#
# Structure for table "tbl_bookinfo"
#

DROP TABLE IF EXISTS `tbl_bookinfo`;
CREATE TABLE `tbl_bookinfo` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `bookstate` varchar(20) DEFAULT NULL,
  `seat` varchar(20) DEFAULT NULL,
  `bookId` int(11) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `bookingdate` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_bookinfo"
#

INSERT INTO `tbl_bookinfo` VALUES (1,0.851286939942803,0.956262425447316,'available','A1',NULL,NULL,NULL),(2,0.824594852240229,0.946322067594433,'available','A2',NULL,NULL,NULL),(3,0.7959961868446139,0.9383697813121272,'available','A3',NULL,NULL,NULL),(4,0.773117254528122,0.926441351888668,'available','A4',NULL,NULL,NULL),(5,0.745471877979028,0.912524850894632,'available','A5',NULL,NULL,NULL),(6,0.716873212583413,0.898608349900596,'available','A6',NULL,NULL,NULL),(7,0.6930409914204,0.89065606361829,'available','A7',NULL,NULL,NULL),(8,0.670162059103908,0.880715705765408,'available','A8',NULL,NULL,NULL),(9,0.646329837940896,0.870775347912525,'available','A9',NULL,NULL,NULL),(10,0.620591039084843,0.860834990059642,'available','A10',NULL,NULL,NULL),(11,0.59580552907531,0.846918489065606,'available','A11',NULL,NULL,NULL),(12,0.551000953288847,0.834990059642147,'available','A12',NULL,NULL,NULL),(13,0.5243088655862727,0.8250497017892644,'available','A13',NULL,NULL,NULL),(14,0.498570066730219,0.813121272365805,'available','A14',NULL,NULL,NULL),(15,0.476644423260248,0.803180914512923,'available','A15',NULL,NULL,NULL),(16,0.452812202097235,0.799204771371769,'available','A16',NULL,NULL,NULL),(17,0.428026692087703,0.789264413518887,'available','A17',NULL,NULL,NULL),(18,0.406101048617731,0.781312127236581,'available','A18',NULL,NULL,NULL),(19,0.379408960915157,0.773359840954274,'available','A19',NULL,NULL,NULL),(20,0.356530028598665,0.765407554671968,'available','A20',NULL,NULL,NULL),(21,0.331744518589133,0.759443339960239,'available','A21',NULL,NULL,NULL),(22,0.310772163965682,0.753479125248509,'available','A22',NULL,NULL,NULL),(23,0.289799809342231,0.743538767395626,'available','A23',NULL,NULL,NULL),(24,0.267874165872259,0.733598409542744,'available','A24',NULL,NULL,NULL),(25,0.245948522402288,0.725646123260437,'available','A25',NULL,NULL,NULL),(26,0.224976167778837,0.721669980119284,'available','A26',NULL,NULL,NULL),(27,0.205910390848427,0.715705765407555,'available','A27',NULL,NULL,NULL),(28,0.188751191611058,0.707753479125249,'available','A28',NULL,NULL,NULL),(29,0.169685414680648,0.703777335984095,'available','A29',NULL,NULL,NULL),(30,0.151572926596759,0.697813121272366,'available','A30',NULL,NULL,NULL),(31,0.133460438512869,0.69389662027833,'available','A31',NULL,NULL,NULL),(32,0.112488083889419,0.673956262425447,'available','A32',NULL,NULL,NULL),(33,0.0896091515729266,0.667992047713718,'available','A33',NULL,NULL,NULL),(34,0.0695900857959962,0.656063618290259,'available','A34',NULL,NULL,NULL),(35,0.0467111534795043,0.646123260437376,'available','A35',NULL,NULL,NULL),(36,0.0238322211630124,0.640159045725646,'available','A36',NULL,NULL,NULL),(37,0.849380362249762,0.918489065606362,'available','B1',NULL,NULL,NULL),(38,0.824594852240229,0.908548707753479,'available','B2',NULL,NULL,NULL),(39,0.801715919923737,0.898608349900596,'available','B3',NULL,NULL,NULL),(40,0.776930409914204,0.89065606361829,'available','B4',NULL,NULL,NULL),(41,0.751191611058151,0.876739562624254,'available','B5',NULL,NULL,NULL),(42,0.720686367969495,0.866799204771372,'available','B6',NULL,NULL,NULL),(43,0.697807435653003,0.860834990059642,'available','B7',NULL,NULL,NULL),(44,0.674928503336511,0.846918489065606,'available','B8',NULL,NULL,NULL),(45,0.651096282173499,0.8389662027833,'available','B9',NULL,NULL,NULL),(46,0.629170638703527,0.827037773359841,'available','B10',NULL,NULL,NULL),(47,0.598665395614871,0.823061630218688,'available','B11',NULL,NULL,NULL),(48,0.555767397521449,0.8071570576540755,'available','B12',NULL,NULL,NULL),(49,0.530028598665396,0.791252485089463,'available','B13',NULL,NULL,NULL),(50,0.506196377502383,0.779324055666004,'available','B14',NULL,NULL,NULL),(51,0.48141086749285,0.775347912524851,'available','B15',NULL,NULL,NULL),(52,0.460438512869399,0.765407554671968,'available','B16',NULL,NULL,NULL),(53,0.434699714013346,0.759443339960239,'available','B17',NULL,NULL,NULL),(54,0.414680648236416,0.749502982107356,'available','B18',NULL,NULL,NULL),(55,0.38512869399428,0.74155069582505,'available','B19',NULL,NULL,NULL),(56,0.363203050524309,0.733598409542744,'available','B20',NULL,NULL,NULL),(57,0.340324118207817,0.727634194831014,'available','B21',NULL,NULL,NULL),(58,0.319351763584366,0.719681908548708,'available','B22',NULL,NULL,NULL),(59,0.297426120114395,0.709741550695825,'available','B23',NULL,NULL,NULL),(60,0.274547187797903,0.699801192842942,'available','B24',NULL,NULL,NULL),(61,0.255481410867493,0.691848906560636,'available','B25',NULL,NULL,NULL),(62,0.235462345090562,0.685884691848907,'available','B26',NULL,NULL,NULL),(63,0.214489990467112,0.685884691848907,'available','B27',NULL,NULL,NULL),(64,0.198284080076263,0.679920477137177,'available','B28',NULL,NULL,NULL),(65,0.181124880838894,0.673956262425447,'available','B29',NULL,NULL,NULL),(66,0.162059103908484,0.664015904572565,'available','B30',NULL,NULL,NULL),(67,0.145853193517636,0.654075546719682,'available','B31',NULL,NULL,NULL),(68,0.122020972354623,0.646123260437376,'available','B32',NULL,NULL,NULL),(69,0.101048617731173,0.63817097415507,'available','B33',NULL,NULL,NULL),(70,0.0800762631077216,0.628230616302187,'available','B34',NULL,NULL,NULL),(71,0.0571973307912297,0.622266401590457,'available','B35',NULL,NULL,NULL),(72,0.0371782650142993,0.612326043737575,'available','B36',NULL,NULL,NULL),(73,0.855100095328885,0.886679920477137,'available','C1',NULL,NULL,NULL),(74,0.828408007626311,0.876739562624254,'available','C2',NULL,NULL,NULL),(75,0.803622497616778,0.858846918489066,'available','C3',NULL,NULL,NULL),(76,0.780743565300286,0.854870775347913,'available','C4',NULL,NULL,NULL),(77,0.756911344137274,0.846918489065606,'available','C5',NULL,NULL,NULL),(78,0.726406101048618,0.834990059642147,'available','C6',NULL,NULL,NULL),(79,0.7016205910390848,0.827037773359841,'available','C7',NULL,NULL,NULL),(80,0.678741658722593,0.817097415506958,'available','C8',NULL,NULL,NULL),(81,0.656816015252622,0.803180914512923,'available','C9',NULL,NULL,NULL),(82,0.632983794089609,0.795228628230616,'available','C10',NULL,NULL,NULL),(83,0.605338417540515,0.789264413518887,'available','C11',NULL,NULL,NULL),(84,0.559580552907531,0.771371769383698,'available','C12',NULL,NULL,NULL),(85,0.534795042897998,0.761431411530815,'available','C13',NULL,NULL,NULL),(86,0.510962821734986,0.749502982107356,'available','C14',NULL,NULL,NULL),(87,0.489037178265014,0.743538767395626,'available','C15',NULL,NULL,NULL),(88,0.465204957102002,0.739562624254473,'available','C16',NULL,NULL,NULL),(89,0.44232602478551,0.727634194831014,'available','C17',NULL,NULL,NULL),(90,0.421353670162059,0.723658051689861,'available','C18',NULL,NULL,NULL),(91,0.392755004766444,0.712729622266402,'available','C19',NULL,NULL,NULL),(92,0.371782650142993,0.705765407554672,'available','C20',NULL,NULL,NULL),(93,0.348903717826501,0.697813121272366,'available','C21',NULL,NULL,NULL),(94,0.32697807435653,0.68986083499006,'available','C22',NULL,NULL,NULL),(95,0.305052430886559,0.679920477137177,'available','C23',NULL,NULL,NULL),(96,0.285033365109628,0.671968190854871,'available','C24',NULL,NULL,NULL),(97,0.262154432793136,0.662027833001988,'available','C25',NULL,NULL,NULL),(98,0.244041944709247,0.658051689860835,'available','C26',NULL,NULL,NULL),(99,0.222116301239276,0.652087475149105,'available','C27',NULL,NULL,NULL),(100,0.2059103908484271,0.6461232604373757,'available','C28',NULL,NULL,NULL),(101,0.1897044804575786,0.6401590457256461,'available','C29',NULL,NULL,NULL),(102,0.172059103908484,0.636123260437376,'available','C30',NULL,NULL,NULL),(103,0.1544327931363203,0.632266401590457,'available','C31',NULL,NULL,NULL),(104,0.133460438512869,0.616302186878728,'available','C32',NULL,NULL,NULL),(105,0.111534795042898,0.606361829025845,'available','C33',NULL,NULL,NULL),(106,0.0915157292659676,0.600397614314115,'available','C34',NULL,NULL,NULL),(107,0.0676835081029552,0.594433399602386,'available','C35',NULL,NULL,NULL),(108,0.0486177311725453,0.584493041749503,'available','C36',NULL,NULL,NULL),(109,0.856053384175405,0.852882703777336,'available','D1',NULL,NULL,NULL),(110,0.834127740705434,0.842942345924453,'available','D2',NULL,NULL,NULL),(111,0.809342230695901,0.834990059642147,'available','D3',NULL,NULL,NULL),(112,0.784556720686368,0.825049701789264,'available','D4',NULL,NULL,NULL),(113,0.760724499523356,0.815109343936382,'available','D5',NULL,NULL,NULL),(114,0.7302192564347,0.801192842942346,'available','D6',NULL,NULL,NULL),(115,0.706387035271687,0.795228628230616,'available','D7',NULL,NULL,NULL),(116,0.682554814108675,0.785288270377734,'available','D8',NULL,NULL,NULL),(117,0.660629170638704,0.771371769383698,'available','D9',NULL,NULL,NULL),(118,0.637750238322212,0.761431411530815,'available','D10',NULL,NULL,NULL),(119,0.609151572926597,0.755467196819085,'available','D11',NULL,NULL,NULL),(120,0.564346997140133,0.74155069582505,'available','D12',NULL,NULL,NULL),(121,0.5386081982840801,0.731610337972167,'available','D13',NULL,NULL,NULL),(122,0.517635843660629,0.719681908548708,'available','D14',NULL,NULL,NULL),(123,0.494756911344137,0.711729622266402,'available','D15',NULL,NULL,NULL),(124,0.471877979027645,0.705765407554672,'available','D16',NULL,NULL,NULL),(125,0.449952335557674,0.699801192842942,'available','D17',NULL,NULL,NULL),(126,0.428026692087703,0.693836978131213,'available','D18',NULL,NULL,NULL),(127,0.399428026692088,0.681908548707754,'available','D19',NULL,NULL,NULL),(128,0.377502383222116,0.675944333996024,'available','D20',NULL,NULL,NULL),(129,0.356530028598665,0.667992047713718,'available','D21',NULL,NULL,NULL),(130,0.335557673975214,0.662027833001988,'available','D22',NULL,NULL,NULL),(131,0.312678741658723,0.652087475149105,'available','D23',NULL,NULL,NULL),(132,0.290753098188751,0.642147117296223,'available','D24',NULL,NULL,NULL),(133,0.271687321258341,0.634194831013916,'available','D25',NULL,NULL,NULL),(134,0.251668255481411,0.628230616302187,'available','D26',NULL,NULL,NULL),(135,0.232602478551001,0.6262425447316103,'available','D27',NULL,NULL,NULL),(136,0.2144899904671115,0.6222664015904573,'available','D28',NULL,NULL,NULL),(137,0.1973307912297426,0.614314115308151,'available','D29',NULL,NULL,NULL),(138,0.1801715919923737,0.6043737574552684,'available','D30',NULL,NULL,NULL),(139,0.1649189704480458,0.598409542743539,'available','D31',NULL,NULL,NULL),(140,0.142040038131554,0.58648111332008,'available','D32',NULL,NULL,NULL),(141,0.120114394661582,0.584493041749503,'available','D33',NULL,NULL,NULL),(142,0.100095328884652,0.578528827037773,'available','D34',NULL,NULL,NULL),(143,0.0781696854146807,0.568588469184891,'available','D35',NULL,NULL,NULL),(144,0.0600571973307912,0.560636182902585,'available','D36',NULL,NULL,NULL),(145,0.859866539561487,0.817097415506958,'available','E1',NULL,NULL,NULL),(146,0.834127740705434,0.807157057654076,'available','E2',NULL,NULL,NULL),(147,0.811248808388942,0.797216699801193,'available','E3',NULL,NULL,NULL),(148,0.787416587225929,0.78727634194831,'available','E4',NULL,NULL,NULL),(149,0.764537654909438,0.781312127236581,'available','E5',NULL,NULL,NULL),(150,0.734985700667302,0.769383697813121,'available','E6',NULL,NULL,NULL),(151,0.71210676835081,0.759443339960239,'available','E7',NULL,NULL,NULL),(152,0.689227836034318,0.753479125248509,'available','E8',NULL,NULL,NULL),(153,0.664442326024785,0.739562624254473,'available','E9',NULL,NULL,NULL),(154,0.642516682554814,0.72962226640159,'available','E10',NULL,NULL,NULL),(155,0.613918017159199,0.723658051689861,'available','E11',NULL,NULL,NULL),(156,0.569113441372736,0.711729622266402,'available','E12',NULL,NULL,NULL),(157,0.544327931363203,0.699801192842942,'available','E13',NULL,NULL,NULL),(158,0.521448999046711,0.687872763419483,'available','E14',NULL,NULL,NULL),(159,0.50047664442326,0.679920477137177,'available','E15',NULL,NULL,NULL),(160,0.476644423260248,0.675944333996024,'available','E16',NULL,NULL,NULL),(161,0.454718779790276,0.667992047713718,'available','E17',NULL,NULL,NULL),(162,0.432793136320305,0.662027833001988,'available','E18',NULL,NULL,NULL),(163,0.405147759771211,0.652087475149105,'available','E19',NULL,NULL,NULL),(164,0.38417540514776,0.646123260437376,'available','E20',NULL,NULL,NULL),(165,0.363203050524309,0.640159045725646,'available','E21',NULL,NULL,NULL),(166,0.340324118207817,0.652087475149105,'available','E22',NULL,NULL,NULL),(167,0.319351763584366,0.624254473161034,'available','E23',NULL,NULL,NULL),(168,0.296472831267874,0.618290258449304,'available','E24',NULL,NULL,NULL),(169,0.275500476644423,0.610337972166998,'available','E25',NULL,NULL,NULL),(170,0.257387988560534,0.604373757455268,'available','E26',NULL,NULL,NULL),(171,0.2411820781696854,0.5984095427435387,'available','E27',NULL,NULL,NULL),(172,0.2221163012392755,0.5944333996023857,'available','E28',NULL,NULL,NULL),(173,0.2059103908484271,0.5884691848906561,'available','E29',NULL,NULL,NULL),(174,0.186844613918017,0.5785288270377734,'available','E30',NULL,NULL,NULL),(175,0.1696854146806482,0.5745526838966203,'available','E31',NULL,NULL,NULL),(176,0.150619637750238,0.566600397614314,'available','E32',NULL,NULL,NULL),(177,0.129647283126787,0.560636182902585,'available','E33',NULL,NULL,NULL),(178,0.110581506196378,0.554671968190855,'available','E34',NULL,NULL,NULL),(179,0.0905624404194471,0.548707753479125,'available','E35',NULL,NULL,NULL),(180,0.0724499523355577,0.540755467196819,'available','E36',NULL,NULL,NULL),(181,0.859866539561487,0.785288270377734,'available','F1',NULL,NULL,NULL),(182,0.836987607244995,0.775347912524851,'available','F2',NULL,NULL,NULL),(183,0.811248808388942,0.767395626242545,'available','F3',NULL,NULL,NULL),(184,0.78932316491897,0.755467196819085,'available','F4',NULL,NULL,NULL),(185,0.765490943755958,0.745526838966203,'available','F5',NULL,NULL,NULL),(186,0.737845567206864,0.73558648111332,'available','F6',NULL,NULL,NULL),(187,0.713060057197331,0.725646123260437,'available','F7',NULL,NULL,NULL),(188,0.690181124880839,0.711729622266402,'available','F8',NULL,NULL,NULL),(189,0.668255481410868,0.701789264413519,'available','F9',NULL,NULL,NULL),(190,0.6453765490943756,0.697813121272366,'available','F10',NULL,NULL,NULL),(191,0.616777883698761,0.693836978131213,'available','F11',NULL,NULL,NULL),(192,0.573879885605338,0.681908548707754,'available','F12',NULL,NULL,NULL),(193,0.550047664442326,0.669980119284294,'available','F13',NULL,NULL,NULL),(194,0.529075309818875,0.660039761431412,'available','F14',NULL,NULL,NULL),(195,0.506196377502383,0.652087475149105,'available','F15',NULL,NULL,NULL),(196,0.483317445185891,0.646123260437376,'available','F16',NULL,NULL,NULL),(197,0.46234509056244,0.640159045725646,'available','F17',NULL,NULL,NULL),(198,0.440419447092469,0.634194831013916,'available','F18',NULL,NULL,NULL),(199,0.413727359389895,0.62624254473161,'available','F19',NULL,NULL,NULL),(200,0.389895138226883,0.618290258449304,'available','F20',NULL,NULL,NULL),(201,0.369876072449952,0.612326043737575,'available','F21',NULL,NULL,NULL),(202,0.345090562440419,0.606361829025845,'available','F22',NULL,NULL,NULL),(203,0.323164918970448,0.600397614314115,'available','F23',NULL,NULL,NULL),(204,0.304099142040038,0.590457256461233,'available','F24',NULL,NULL,NULL),(205,0.282173498570067,0.588469184890656,'available','F25',NULL,NULL,NULL),(206,0.264061010486177,0.58051689860835,'available','F26',NULL,NULL,NULL),(207,0.2478551000953289,0.5765407554671969,'available','F27',NULL,NULL,NULL),(208,0.2316491897044805,0.5705765407554672,'available','F28',NULL,NULL,NULL),(209,0.2125834127740705,0.5646123260437376,'available','F29',NULL,NULL,NULL),(210,0.1963775023832221,0.558648111332008,'available','F30',NULL,NULL,NULL),(211,0.1782650142993327,0.5526838966202783,'available','F31',NULL,NULL,NULL),(212,0.160152526215443,0.544731610337972,'available','F32',NULL,NULL,NULL),(213,0.139180171591992,0.538767395626243,'available','F33',NULL,NULL,NULL),(214,0.122974261201144,0.530815109343936,'available','F34',NULL,NULL,NULL),(215,0.105815061963775,0.524850894632207,'available','F35',NULL,NULL,NULL),(216,0.0857959961868446,0.518886679920477,'available','F36',NULL,NULL,NULL);

#
# Structure for table "tbl_guest"
#

DROP TABLE IF EXISTS `tbl_guest`;
CREATE TABLE `tbl_guest` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `phonenumber` int(1) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `ordercount` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_guest"
#

INSERT INTO `tbl_guest` VALUES (29,'David','Wilson','imobilegang@gmail.com','Italy',2147483647,'Ottawa',1),(30,'David','Wilson','imobilegang@gmail.com','Italy',2147483647,'Ottawa',1),(31,'David','Wilson','imobilegang@gmail.com','Italy',2147483647,'Ottawa',1);

#
# Structure for table "tbl_guestinfo"
#

DROP TABLE IF EXISTS `tbl_guestinfo`;
CREATE TABLE `tbl_guestinfo` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "tbl_guestinfo"
#


#
# Structure for table "tbl_rowrestriction"
#

DROP TABLE IF EXISTS `tbl_rowrestriction`;
CREATE TABLE `tbl_rowrestriction` (
  `rowId` varchar(11) NOT NULL,
  `servicetype` varchar(100) DEFAULT '4',
  PRIMARY KEY (`rowId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "tbl_rowrestriction"
#

INSERT INTO `tbl_rowrestriction` VALUES ('A','4'),('B','4'),('C','1,2,3,4'),('D','1,2,3,4'),('E','1,2,3,4'),('F','1,2,3,4'),('G','1,2,3,4');

#
# Structure for table "tbl_servicetype"
#

DROP TABLE IF EXISTS `tbl_servicetype`;
CREATE TABLE `tbl_servicetype` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `servicename` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_servicetype"
#

INSERT INTO `tbl_servicetype` VALUES (1,'1 day'),(2,'7 days'),(3,'31 days'),(4,'All season');

#
# Structure for table "tbl_price"
#

DROP TABLE IF EXISTS `tbl_price`;
CREATE TABLE `tbl_price` (
  `Id` int(11) NOT NULL,
  `rowid` varchar(100) NOT NULL,
  `servicetype_Id` int(11) DEFAULT NULL,
  `mainprice` int(11) DEFAULT '0',
  `tax` int(11) DEFAULT '0',
  `supplement` int(11) DEFAULT '0',
  `maxguests` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`),
  KEY `servicetype_Id` (`servicetype_Id`),
  CONSTRAINT `tbl_price_ibfk_1` FOREIGN KEY (`servicetype_Id`) REFERENCES `tbl_servicetype` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "tbl_price"
#

INSERT INTO `tbl_price` VALUES (1,'A',4,750,100,100,4),(2,'B',4,750,100,70,2),(3,'C',4,750,100,50,2),(4,'C',3,530,70,0,4),(5,'C',2,150,20,0,4),(6,'C',1,20,4,0,2),(7,'D',4,750,100,0,2),(8,'D',3,530,70,0,4),(9,'D',2,150,20,0,4),(10,'D',1,20,4,0,2),(11,'F',1,20,4,0,2),(12,'E',4,750,100,0,2),(13,'E',3,530,70,0,4),(14,'E',2,150,20,0,4),(16,'F',4,750,100,0,2),(17,'F',3,530,70,0,4),(18,'F',2,150,20,0,4),(19,'G',1,20,4,0,2),(20,'G',4,750,100,0,2),(21,'G',3,530,70,0,4),(22,'G',2,150,20,0,4),(23,'E',1,20,4,0,2);

#
# Structure for table "tbl_setting"
#

DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `propertytitle` varchar(50) DEFAULT NULL,
  `propertysubtitle` varchar(100) DEFAULT NULL,
  `footertitle` varchar(255) DEFAULT NULL,
  `phonenumber` int(1) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_setting"
#

INSERT INTO `tbl_setting` VALUES (1,'\r\nBeach Club Ippocampo ','Sunshade Booking','Beach Club Ippocampo',390884571,' info@beachclubippocampo.it');

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_activation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "user"
#

INSERT INTO `user` VALUES (2,'admin','info@beachclubippocampo.it','$2y$13$Ybr2nszeWxAzHR1Nptp6du.CVL/W.mt4yiNAquVf6i6KKifCbGVC2',10,'b5JHmbmzaWvuqz8a70s0j42uVoSqinQx',NULL,NULL,1462346065,1462348360),(6,'user','imobilegang@gmail.com','$2y$13$zAQy.669wO4bbb22GCRNWu5JAuDARVwq8J671YWOSP5q0yxAxVA5e',10,'mEczUs-6Nb7XKqSTNsBkSGUV8FFag3FT',NULL,NULL,1462348145,1462348145);

#
# Structure for table "article"
#

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `summary` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "article"
#
