# Host: localhost  (Version: 5.5.5-10.1.16-MariaDB)
# Date: 2016-10-26 03:38:50
# Generator: MySQL-Front 5.3  (Build 4.271)

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

INSERT INTO `auth_assignment` VALUES ('admin',2,1462346066),('admin',3,NULL),('theCreator',6,NULL);

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

INSERT INTO `migration` VALUES ('m000000_000000_base',1461076913),('m130524_201442_init',1461076918),('m141022_115823_user',1461076919),('m141022_115912_rbac',1461076929),('m141022_115922_session',1461076923),('m150104_153617_article',1461076928),('m161015_120838_create_tbl_book_lookup_table',1476547763);

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

INSERT INTO `session` VALUES ('11cctede9h6obb3ekd7749svv6',1477465277,X'5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A33363A222F72656E74616C2F61646D696E2F67756573742F676F746F636172743F6C616E673D656E223B5F5F69647C693A323B'),('3s176m9pun9c745d1dg54pa6i4',1477365342,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('9usarm68ataqli8vgia56aagj3',1477467570,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('b3p0sjuip75g0o9cg62u7hceg0',1477408605,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('b8sq7hbv89nvk2f1l0nbveb3h0',1477465198,X'5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A37333A222F72656E74616C2F61646D696E2F67756573742F6D6170626F6F6B696E673F66726F6D3D31302532463139253246323031362B313025334135352B504D26746F3D266C616E673D656E223B5F5F69647C693A323B'),('e0o4s10mkm67gkulk8kcfh3ss5',1477364392,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('gl85f40dm9fosgtp3mb4n6vus0',1477380900,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('ids3pseucfad10q1f0kid224r1',1477408912,X'5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A33383A222F72656E74616C2F61646D696E2F67756573742F6D6170626F6F6B696E673F6C616E673D656E223B5F5F69647C693A323B'),('mirl30jgb89552ulgukud7g3s4',1477439026,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('o4odrffavt9uaplru5qmqqeei1',1477468868,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B'),('triidf16kuj73c8if6atlu8ms2',1477397486,X'5F5F666C6173687C613A303A7B7D5F5F69647C693A323B');

#
# Structure for table "tbl_book"
#

DROP TABLE IF EXISTS `tbl_book`;
CREATE TABLE `tbl_book` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `sunshadeseat` varchar(100) DEFAULT NULL,
  `checkin` varchar(255) DEFAULT NULL,
  `checkout` varchar(11) DEFAULT NULL,
  `servicetype` tinyint(3) DEFAULT '1',
  `price` int(11) DEFAULT '0',
  `paidprice` int(11) DEFAULT NULL,
  `mainprice` int(11) DEFAULT '0',
  `tax` int(11) DEFAULT '0',
  `supplement` int(11) DEFAULT '0',
  `guests` int(11) DEFAULT '1',
  `bookstate` varchar(50) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `bookedtime` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_book"
#

INSERT INTO `tbl_book` VALUES (136,'B10','2016-05-10','2016-08-17',4,820,380,750,100,70,4,'booked',NULL,'2016-10-26 00:07:23'),(137,'E16','2016-10-12','2016-10-12',1,36,12,20,16,0,6,'booked',NULL,'2016-10-26 01:29:16');

#
# Structure for table "tbl_bookhistory"
#

DROP TABLE IF EXISTS `tbl_bookhistory`;
CREATE TABLE `tbl_bookhistory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `guestId` int(11) NOT NULL,
  `historyDate` varchar(20) DEFAULT NULL,
  `historyTitle` varchar(20) DEFAULT NULL,
  `historyIP` varchar(20) DEFAULT NULL,
  `sunshadeseat` varchar(10) DEFAULT NULL,
  `bookId` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `guests` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "tbl_bookhistory"
#


#
# Structure for table "tbl_bookinfo"
#

DROP TABLE IF EXISTS `tbl_bookinfo`;
CREATE TABLE `tbl_bookinfo` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `bookstate` varchar(255) DEFAULT NULL,
  `seat` varchar(255) DEFAULT NULL,
  `bookId` int(11) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `bookingdate` varchar(255) DEFAULT NULL,
  `booktoken` varchar(255) DEFAULT NULL,
  `milestonegroupId` varchar(255) DEFAULT NULL,
  `booktype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_bookinfo"
#

INSERT INTO `tbl_bookinfo` VALUES (1,389,3287,'available','A1',0,0,'',NULL,'0','sunshade'),(2,725,3291,'available','A2',0,0,'',NULL,'0','sunshade'),(3,1001,3295,'available','A3',0,0,'',NULL,'0','sunshade'),(4,1409,3295,'available','A4',0,0,'',NULL,'0','sunshade'),(5,1765,3291,'available','A5',0,0,'',NULL,'0','sunshade'),(6,2165,3275,'available','A6',0,0,'',NULL,'0','sunshade'),(7,2561,3275,'available','A7',0,0,'',NULL,'0','sunshade'),(8,2925,3271,'available','A8',0,0,'',NULL,'0','sunshade'),(9,3377,3275,'available','A9',0,0,'',NULL,'0','sunshade'),(10,3693,3319,'available','A10',0,0,'',NULL,'0','sunshade'),(11,4033,3323,'available','A11',0,0,'',NULL,'0','sunshade'),(12,4289,3303,'available','A12',0,0,'',NULL,'0','sunshade'),(13,4529,3283,'available','A13',0,0,'',NULL,'0','sunshade'),(14,4845,3279,'available','A14',0,0,'',NULL,'0','sunshade'),(15,5209,3279,'available','A15',0,0,'',NULL,'0','sunshade'),(16,5551,3284,'available','A16',0,0,'',NULL,'0','sunshade'),(17,6331,3235,'available','A18',0,0,'',NULL,'0','sunshade'),(18,6711,3251,'available','A19',0,0,'',NULL,'0','sunshade'),(19,7171,3259,'available','A20',0,0,'',NULL,'0','sunshade'),(20,7503,3279,'available','A21',0,0,'',NULL,'0','sunshade'),(21,7863,3287,'available','A22',0,0,'',NULL,'0','sunshade'),(22,8183,3307,'available','A23',0,0,'',NULL,'0','sunshade'),(23,8539,3303,'available','A24',0,0,'',NULL,'0','sunshade'),(24,8911,3303,'available','A25',0,0,'',NULL,'0','sunshade'),(25,9315,3291,'available','A26',0,0,'',NULL,'0','sunshade'),(26,9615,3299,'available','A27',0,0,'',NULL,'0','sunshade'),(27,9991,3279,'available','A28',0,0,'',NULL,'0','sunshade'),(28,10378,3287,'available','A29',0,0,'',NULL,'0','sunshade'),(29,10686,3271,'available','A30',0,0,'',NULL,'0','sunshade'),(30,11026,3263,'available','A31',0,0,'',NULL,'0','sunshade'),(31,11410,3227,'available','A32',0,0,'',NULL,'0','sunshade'),(32,11742,3229,'available','A33',0,0,'',NULL,'0','sunshade'),(33,12070,3227,'available','A34',0,0,'',NULL,'0','sunshade'),(34,12450,3255,'available','A35',0,0,'',NULL,'0','sunshade'),(35,361,3447,'available','B1',0,0,'',NULL,'0','sunshade'),(36,697,3465,'available','B2',0,0,'',NULL,'0','sunshade'),(37,1045,3475,'available','B3',0,0,'',NULL,'0','sunshade'),(38,1381,3491,'available','B4',0,0,'',NULL,'0','sunshade'),(39,1737,3479,'available','B5',0,0,'',NULL,'0','sunshade'),(40,2221,3491,'available','B6',0,0,'',NULL,'0','sunshade'),(41,2601,3511,'available','B7',0,0,'',NULL,'0','sunshade'),(42,3021,3504,'available','B8',0,0,'',NULL,'0','sunshade'),(43,3357,3523,'available','B9',0,0,'',NULL,'0','sunshade'),(44,3673,3559,'booked','B10',0,0,'','','0','sunshade'),(45,4005,3559,'available','B11',0,0,'','4z2KKKIpjXuet_oW3n_abhouffWJWfCr_1476433845','0','sunshade'),(46,4517,3475,'available','B13',0,0,'',NULL,'0','sunshade'),(47,4877,3451,'available','B14',0,0,'',NULL,'0','sunshade'),(48,5245,3447,'available','B15',0,0,'',NULL,'0','sunshade'),(49,5563,3443,'available','B16',0,0,'',NULL,'0','sunshade'),(50,5899,3399,'available','B17',0,0,'',NULL,'0','sunshade'),(51,6299,3435,'available','B18',0,0,'',NULL,'0','sunshade'),(52,6711,3433,'available','B19',0,0,'',NULL,'0','sunshade'),(53,7175,3447,'available','B20',0,0,'',NULL,'0','sunshade'),(54,7487,3579,'available','B21',0,0,'',NULL,'0','sunshade'),(55,7835,3555,'available','B22',0,0,'',NULL,'0','sunshade'),(56,8191,3563,'available','B23',0,0,'',NULL,'0','sunshade'),(57,8515,3575,'available','B24',0,0,'',NULL,'0','sunshade'),(58,8903,3563,'available','B25',0,0,'',NULL,'0','sunshade'),(59,9267,3529,'available','B26',0,0,'',NULL,'0','sunshade'),(60,9627,3523,'available','B27',0,0,'',NULL,'0','sunshade'),(61,10011,3511,'available','B28',0,0,'',NULL,'0','sunshade'),(62,10394,3511,'available','B29',0,0,'',NULL,'0','sunshade'),(63,10710,3515,'available','B30',0,0,'',NULL,'0','sunshade'),(64,11018,3499,'available','B31',0,0,'',NULL,'0','sunshade'),(65,11410,3459,'available','B32',0,0,'',NULL,'0','sunshade'),(66,11778,3463,'available','B33',0,0,'',NULL,'0','sunshade'),(67,12102,3455,'available','B34',0,0,'',NULL,'0','sunshade'),(68,12462,3448,'available','B35',0,0,'',NULL,'0','sunshade'),(69,333,3681,'available','C1',0,0,'',NULL,'0','sunshade'),(70,653,3687,'available','C2',0,0,'',NULL,'0','sunshade'),(71,1029,3699,'available','C3',0,0,'',NULL,'0','sunshade'),(72,1341,3707,'available','C4',0,0,'',NULL,'0','sunshade'),(73,1721,3711,'available','C5',0,0,'',NULL,'0','sunshade'),(74,2217,3739,'available','C6',0,0,'',NULL,'0','sunshade'),(75,2613,3759,'available','C7',0,0,'',NULL,'0','sunshade'),(76,3025,3775,'available','C8',0,0,'',NULL,'0','sunshade'),(77,3333,3767,'available','C9',0,0,'',NULL,'0','sunshade'),(78,3621,3775,'available','C10',0,0,'',NULL,'0','sunshade'),(79,3997,3787,'available','C11',0,0,'','RWXj-5kfbyGygCq6E4Rj0WwhMl5nuWPA_1476433474','0','sunshade'),(80,4513,3699,'available','C13',0,0,'',NULL,'0','sunshade'),(81,4861,3703,'available','C14',0,0,'','','0','sunshade'),(82,5201,3679,'available','C15',0,0,'',NULL,'0','sunshade'),(83,5561,3663,'available','C16',0,0,'',NULL,'0','sunshade'),(84,5889,3659,'available','C17',0,0,'',NULL,'0','sunshade'),(85,6269,3667,'available','C18',0,0,'',NULL,'0','sunshade'),(86,6705,3675,'available','C19',0,0,'',NULL,'0','sunshade'),(87,7201,3671,'available','C20',0,0,'',NULL,'0','sunshade'),(88,7501,3844,'available','C21',0,0,'',NULL,'0','sunshade'),(89,7821,3844,'available','C22',0,0,'',NULL,'0','sunshade'),(90,8173,3827,'available','C23',0,0,'',NULL,'0','sunshade'),(91,8553,3835,'available','C24',0,0,'',NULL,'0','sunshade'),(92,8921,3819,'available','C25',0,0,'',NULL,'0','sunshade'),(93,9601,3799,'available','C27',0,0,'',NULL,'0','sunshade'),(94,9982,3787,'available','C28',0,0,'',NULL,'0','sunshade'),(95,10386,3771,'available','C29',0,0,'',NULL,'0','sunshade'),(96,10733,3787,'available','C30',0,0,'',NULL,'0','sunshade'),(97,11002,3727,'available','C31',0,0,'',NULL,'0','sunshade'),(98,11426,3707,'available','C32',0,0,'',NULL,'0','sunshade'),(99,11814,3667,'available','C33',0,0,'',NULL,'0','sunshade'),(100,12118,3667,'available','C34',0,0,'',NULL,'0','sunshade'),(101,12462,3659,'available','C35',0,0,'',NULL,'0','sunshade'),(102,12806,3663,'available','C36',0,0,'',NULL,'0','sunshade'),(103,301,3935,'available','D1',0,0,'',NULL,'0','sunshade'),(104,661,3963,'available','D2',0,0,'',NULL,'0','sunshade'),(105,1045,3987,'available','D3',0,0,'',NULL,'0','sunshade'),(106,1333,3975,'available','D4',0,0,'',NULL,'0','sunshade'),(107,1733,3995,'available','D5',0,0,'',NULL,'0','sunshade'),(108,2197,4035,'available','D6',0,0,'',NULL,'0','sunshade'),(109,2597,4047,'available','D7',0,0,'',NULL,'0','sunshade'),(110,3013,4063,'available','D8',0,0,'',NULL,'0','sunshade'),(111,3333,4024,'available','D9',0,0,'',NULL,'0','sunshade'),(112,3609,4039,'available','D10',0,0,'',NULL,'0','sunshade'),(113,3989,4055,'available','D11',0,0,'',NULL,'0','sunshade'),(114,4501,3943,'available','D13',0,0,'',NULL,'0','sunshade'),(115,4853,3947,'available','D14',0,0,'',NULL,'0','sunshade'),(116,5209,3935,'available','D15',0,0,'',NULL,'0','sunshade'),(117,5571,3955,'available','D16',0,0,'',NULL,'0','sunshade'),(118,5919,3935,'available','D17',0,0,'','','0','sunshade'),(119,6295,3947,'available','D18',0,0,'','','0','sunshade'),(120,6695,3943,'available','D19',0,0,'',NULL,'0','sunshade'),(121,7231,3947,'available','D20',0,0,'',NULL,'0','sunshade'),(122,7526,4067,'available','D21',0,0,'',NULL,'0','sunshade'),(123,8558,4055,'available','D24',0,0,'',NULL,'0','sunshade'),(124,8954,4019,'available','D25',0,0,'',NULL,'0','sunshade'),(125,9262,4023,'available','D26',0,0,'',NULL,'0','sunshade'),(126,9566,4035,'available','D27',0,0,'',NULL,'0','sunshade'),(127,10002,4035,'available','D28',0,0,'',NULL,'0','sunshade'),(128,10374,4087,'available','D29',0,0,'',NULL,'0','sunshade'),(129,10722,4075,'available','D30',0,0,'',NULL,'0','sunshade'),(130,10990,4039,'available','D31',0,0,'',NULL,'0','sunshade'),(131,11418,4041,'available','D32',0,0,'',NULL,'0','sunshade'),(132,11806,4041,'available','D33',0,0,'',NULL,'0','sunshade'),(133,12130,4023,'available','D34',0,0,'',NULL,'0','sunshade'),(134,12450,4007,'available','D35',0,0,'',NULL,'0','sunshade'),(135,321,4255,'available','E1',0,0,'',NULL,'0','sunshade'),(136,677,4287,'available','E2',0,0,'',NULL,'0','sunshade'),(137,1089,4291,'available','E3',0,0,'',NULL,'0','sunshade'),(138,1373,4291,'available','E4',0,0,'',NULL,'0','sunshade'),(139,1761,4323,'available','E5',0,0,'',NULL,'0','sunshade'),(140,2185,4329,'available','E6',0,0,'',NULL,'0','sunshade'),(141,2577,4351,'available','E7',0,0,'',NULL,'0','sunshade'),(142,2973,4343,'available','E8',0,0,'','','0','sunshade'),(143,3321,4323,'available','E9',0,0,'','','0','sunshade'),(144,3601,4329,'available','E10',0,0,'','','0','sunshade'),(145,3973,4287,'available','E11',0,0,'',NULL,'0','sunshade'),(146,4481,4227,'available','E13',0,0,'',NULL,'0','sunshade'),(147,4841,4223,'available','E14',0,0,'',NULL,'0','sunshade'),(148,5611,4219,'booked','E16',0,0,'','','0','sunshade'),(149,5911,4263,'available','E17',0,0,'',NULL,'0','sunshade'),(150,6287,4263,'available','E18',0,0,'',NULL,'0','sunshade'),(151,6703,4267,'available','E19',0,0,'',NULL,'0','sunshade'),(152,7239,4243,'available','E20',0,0,'',NULL,'0','sunshade'),(153,7521,4283,'available','E21',0,0,'',NULL,'0','sunshade'),(154,7823,4303,'available','E22',0,0,'',NULL,'0','sunshade'),(155,8207,4279,'available','E23',0,0,'',NULL,'0','sunshade'),(156,8603,4259,'available','E24',0,0,'',NULL,'0','sunshade'),(157,8919,4255,'available','E25',0,0,'',NULL,'0','sunshade'),(158,9239,4259,'available','E26',0,0,'',NULL,'0','sunshade'),(159,9563,4267,'available','E27',0,0,'',NULL,'0','sunshade'),(160,10018,4303,'available','E28',0,0,'',NULL,'0','sunshade'),(161,10374,4335,'available','E29',0,0,'',NULL,'0','sunshade'),(162,10734,4331,'available','E30',0,0,'',NULL,'0','sunshade'),(163,10986,4327,'available','E31',0,0,'',NULL,'0','sunshade'),(164,11442,4319,'available','E32',0,0,'',NULL,'0','sunshade'),(165,11834,4319,'available','E33',0,0,'',NULL,'0','sunshade'),(166,12142,4307,'available','E34',0,0,'',NULL,'0','sunshade'),(167,12470,4287,'available','E35',0,0,'',NULL,'0','sunshade'),(168,-100,-100,'available','101',0,0,'',NULL,'0','room'),(169,-100,-100,'available','102',0,0,'',NULL,'0','room'),(170,-100,-100,'available','103',0,0,'',NULL,'0','room'),(171,-100,-100,'available','104',0,0,'',NULL,'0','room'),(172,-100,-100,'available','105',0,0,'',NULL,'0','room'),(173,-100,-100,'available','106',0,0,'',NULL,'0','room');

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
  `phonenumber` varchar(20) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zipcode` varchar(100) DEFAULT NULL,
  `paymentId` int(11) DEFAULT NULL,
  `create_time` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `recurringcount` bigint(20) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_guest"
#

INSERT INTO `tbl_guest` VALUES (10,'tt','t','test@te.tt','Italy','t','t','t','t',NULL,'26/10/2016 00:03:47','Booked',0),(11,'t','t','test@te.t','Italy','t','t','t','t',NULL,'26/10/2016 01:29:16','Booked',2);

#
# Structure for table "tbl_book_lookup"
#

DROP TABLE IF EXISTS `tbl_book_lookup`;
CREATE TABLE `tbl_book_lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sunshade` varchar(20) NOT NULL,
  `bookinfo_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `milestonegroup_id` int(11) NOT NULL,
  `bookstate` varchar(20) NOT NULL,
  `booktoken` varchar(255) NOT NULL,
  `deleted` int(11) DEFAULT '0',
  `booked_at` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-tbl_book_lookup-bookinfo_id` (`bookinfo_id`),
  KEY `idx-tbl_book_lookup-book_id` (`book_id`),
  KEY `idx-tbl_book_lookup-guest_id` (`guest_id`),
  KEY `idx-tbl_book_lookup-milestonegroup_id` (`milestonegroup_id`),
  CONSTRAINT `fk-tbl_book_lookup-book_id` FOREIGN KEY (`book_id`) REFERENCES `tbl_book` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `fk-tbl_book_lookup-bookinfo_id` FOREIGN KEY (`bookinfo_id`) REFERENCES `tbl_bookinfo` (`Id`) ON DELETE CASCADE,
  CONSTRAINT `fk-tbl_book_lookup-guest_id` FOREIGN KEY (`guest_id`) REFERENCES `tbl_guest` (`Id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_book_lookup"
#

INSERT INTO `tbl_book_lookup` VALUES (1,'B10',44,136,11,1477440443,'available','',0,'2016-10-26 00:07:23'),(2,'E16',148,137,11,1477445356,'available','',0,'2016-10-26 01:29:16');

#
# Structure for table "tbl_guestmilestone"
#

DROP TABLE IF EXISTS `tbl_guestmilestone`;
CREATE TABLE `tbl_guestmilestone` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sunshadeseat` varchar(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `period` varchar(100) DEFAULT NULL,
  `achieved` tinyint(3) DEFAULT NULL,
  `bookId` int(11) DEFAULT NULL,
  `guestId` int(11) DEFAULT NULL,
  `createddate` varchar(100) DEFAULT NULL,
  `milestonegroupId` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_guestmilestone"
#

INSERT INTO `tbl_guestmilestone` VALUES (78,'B10',234,'2016-10-06',1,136,11,'2016-10-26 00:42:58','1477440443'),(79,'B10',34,'2016-10-06',1,136,11,'2016-10-26 00:42:58','1477440443'),(87,'E16',12,'2016-10-05',1,137,11,'2016-10-26 01:33:12','1477445356'),(88,'E16',12,'2016-10-05',1,137,11,'2016-10-26 01:36:35','1477445584'),(89,'E16',12,'2016-10-05',1,137,11,'2016-10-26 01:36:50','1477445584');

#
# Structure for table "tbl_notification"
#

DROP TABLE IF EXISTS `tbl_notification`;
CREATE TABLE `tbl_notification` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `title_it` varchar(255) DEFAULT NULL,
  `sunshadeseat` varchar(11) DEFAULT NULL,
  `readstate` varchar(11) DEFAULT NULL,
  `create_date` varchar(50) DEFAULT NULL,
  `lookupid` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "tbl_notification"
#


#
# Structure for table "tbl_notificationreminder"
#

DROP TABLE IF EXISTS `tbl_notificationreminder`;
CREATE TABLE `tbl_notificationreminder` (
  `Id` int(11) NOT NULL,
  `reminderdate` varchar(20) DEFAULT NULL,
  `adminIP` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "tbl_notificationreminder"
#

INSERT INTO `tbl_notificationreminder` VALUES (1,'2016-10-25 21:29:33','::1'),(2,'2016-10-25 22:07:49','::1');

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
  `servicename_it` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_servicetype"
#

INSERT INTO `tbl_servicetype` VALUES (1,'1 day','1 giorno'),(2,'7 days','7 giorni'),(3,'31 days','31 giorni'),(4,'All season','Intera Stagione'),(5,'Rooms','Stanze');

#
# Structure for table "tbl_price"
#

DROP TABLE IF EXISTS `tbl_price`;
CREATE TABLE `tbl_price` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `rowid` varchar(100) NOT NULL,
  `servicetype_Id` int(11) DEFAULT NULL,
  `mainprice` int(11) DEFAULT '0',
  `tax` int(11) DEFAULT '0',
  `supplement` int(11) DEFAULT '0',
  `maxguests` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`),
  KEY `servicetype_Id` (`servicetype_Id`),
  CONSTRAINT `tbl_price_ibfk_1` FOREIGN KEY (`servicetype_Id`) REFERENCES `tbl_servicetype` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_price"
#

INSERT INTO `tbl_price` VALUES (2,'B',4,750,100,70,4),(3,'C',4,750,100,50,4),(4,'C',3,530,70,0,4),(5,'C',2,150,20,0,4),(6,'C',1,20,4,0,2),(7,'D',4,750,100,0,4),(8,'D',3,530,70,0,4),(9,'D',2,150,20,0,4),(10,'D',1,20,4,0,2),(11,'F',1,20,4,0,2),(12,'E',4,750,100,0,4),(13,'E',3,530,70,0,4),(14,'E',2,150,20,0,4),(16,'F',4,750,100,0,4),(17,'F',3,530,70,0,4),(18,'F',2,150,20,0,4),(19,'G',1,20,4,0,2),(20,'G',4,750,100,0,4),(21,'G',3,530,70,0,4),(22,'G',2,150,20,0,4),(23,'E',1,20,4,0,2),(25,'A',4,750,100,100,4),(27,'F',2,123,2,2,1),(28,'103',5,123,23,1,1),(29,'101',5,777,1,1,1),(30,'106',5,888,12,23,5);

#
# Structure for table "tbl_setting"
#

DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `propertytitle` varchar(50) NOT NULL DEFAULT '',
  `footertitle` varchar(255) NOT NULL DEFAULT '',
  `phonenumber` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "tbl_setting"
#

INSERT INTO `tbl_setting` VALUES (1,'Beach Club Ippocampo ','Beach Club Ippocampo ___','390884571292',' info@beachclubippocampo.it');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "user"
#

INSERT INTO `user` VALUES (2,'admin','imobilegang@gmail.com','$2y$13$5zaU1M9RRCt4UnaejxwcCeEg3jr8s76JEFlozadfBn0y5m0yXTPdq',10,'b5JHmbmzaWvuqz8a70s0j42uVoSqinQx',NULL,NULL,1462346065,1472782724);
