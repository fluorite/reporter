CREATE DATABASE  IF NOT EXISTS `reporter`;
USE `reporter`;

DROP TABLE IF EXISTS `report_levels`;
CREATE TABLE `report_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор уровня отчёта.',
  `reportid` int(11) NOT NULL COMMENT 'Идентификатор отчёта.',
  `name` varchar(256) NOT NULL COMMENT 'Название уровня.',
  `number` tinyint(4) NOT NULL COMMENT 'Номер уровня (1..255).',
  PRIMARY KEY (`id`),
  KEY `FK_report_report_levels` (`reportid`),
  CONSTRAINT `FK_report_report_levels` FOREIGN KEY (`reportid`) REFERENCES `report` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Уровни отчёта.';

LOCK TABLES `report_levels` WRITE;
INSERT INTO `report_levels` VALUES (1,1,'Раздел',1),(2,1,'Пункт',2),(3,1,'Подпункт',3),(4,2,'Тема',1),(5,2,'Подтема',2);
UNLOCK TABLES;

DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор ресурса.',
  `name` varchar(32) NOT NULL COMMENT 'Название ресурса',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Ресурсы.';

LOCK TABLES `resource` WRITE;
INSERT INTO `resource` VALUES (4,'error'),(1,'index'),(3,'report'),(7,'report-items'),(6,'report-levels'),(5,'report-values'),(2,'user');
UNLOCK TABLES;

DROP TABLE IF EXISTS `report_items`;
CREATE TABLE `report_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор показателя.',
  `parentid` int(11) DEFAULT NULL COMMENT 'Идентификатор родительского показателя.',
  `levelid` int(11) NOT NULL COMMENT 'Идентификатор уровня отчёта.',
  `name` varchar(256) NOT NULL COMMENT 'Название показателя.',
  `number` varchar(32) NOT NULL COMMENT 'Номер показателя по порядку.',
  `isvalue` int(11) NOT NULL COMMENT 'Наличие значения для показателя (1, если значение есть, и 0 в противном случае).',
  PRIMARY KEY (`id`),
  KEY `FK_report_levels_report_items` (`levelid`),
  KEY `FK_report_items` (`parentid`),
  CONSTRAINT `FK_report_items` FOREIGN KEY (`parentid`) REFERENCES `report_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_report_levels_report_items` FOREIGN KEY (`levelid`) REFERENCES `report_levels` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Показатели отчёта.';

LOCK TABLES `report_items` WRITE;
INSERT INTO `report_items` VALUES (1,NULL,1,'Научно-исследовательская работа','1',0),(2,NULL,1,'Учебно-методическая работа','2',0),(3,1,2,'Руководство диссертационным советом','1.1',1),(4,1,2,'Участие в научных конференциях','1.3',0),(5,4,3,'международные','1.3.1',1),(6,4,3,'всероссийские','1.3.2',1),(7,4,3,'региональные','1.3.3',1),(8,2,2,'Использование в учебном процессе инновационных педагогических технологий','2.1',0),(9,1,2,'Руководство практикой','1.2',1),(10,NULL,4,'Учебная работа','1',0),(11,10,5,'Лекции','1.1',1),(12,2,2,'Организация практик студентов на кафедре','2.2',1);
UNLOCK TABLES;

DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор отчета.',
  `name` varchar(256) NOT NULL COMMENT 'Название отчета.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Отчеты.';

LOCK TABLES `report` WRITE;
INSERT INTO `report` VALUES (1,'Индивидуальный отчёт ППС за 1 семестр 2012 г.'),(2,'Отчёт по научной деятельности (декабрь, 2011 г.)');
UNLOCK TABLES;

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор подразделения.',
  `parentid` int(11) DEFAULT NULL COMMENT 'Идентификатор родительского подразделения.',
  `name` varchar(128) NOT NULL COMMENT 'Название подразделения.',
  `shortname` varchar(32) NOT NULL COMMENT 'Сокращенное название подразделения.',
  `headid` int(11) DEFAULT NULL COMMENT 'Идентификатор руководителя.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_name` (`name`),
  UNIQUE KEY `UQ_shortname` (`shortname`),
  KEY `FK_department` (`parentid`),
  KEY `FK_department_user` (`headid`),
  CONSTRAINT `FK_department` FOREIGN KEY (`parentid`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_department_user` FOREIGN KEY (`headid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Подразделения.';

LOCK TABLES `department` WRITE;
INSERT INTO `department` VALUES (1,NULL,'Югорский государственный университет','ЮГУ',NULL),(2,1,'Институт (НОЦ) управления и информационных технологий','ИУИТ',NULL),(3,1,'Институт менеджмента и экономики','ИМЭК',NULL),(4,2,'Кафедра компьютерного моделирования и информационных технологий','КМиИТ',8),(5,2,'Кафедра высшей математики','ВМАТЕМ',NULL),(6,3,'Кафедра экономики','ЭКОН',NULL);
UNLOCK TABLES;

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор роли.',
  `name` varchar(32) NOT NULL COMMENT 'Название роли.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Роли.';

LOCK TABLES `role` WRITE;
INSERT INTO `role` VALUES (1,'администратор'),(2,'руководитель'),(3,'сотрудник');
UNLOCK TABLES;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор пользователя.',
  `login` varchar(32) NOT NULL COMMENT 'Учетная запись пользователя.',
  `password` varchar(32) NOT NULL COMMENT 'Пароль пользователя.',
  `firstname` varchar(64) NOT NULL COMMENT 'Имя пользователя.',
  `middlename` varchar(64) NOT NULL COMMENT 'Отчество пользователя.',
  `lastname` varchar(64) NOT NULL COMMENT 'Фамилия пользователя.',
  `departmentid` int(11) DEFAULT NULL COMMENT 'Идентификатор подразделения.',
  `postid` int(11) DEFAULT NULL COMMENT 'Идентификатор должности.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_login` (`login`),
  KEY `FK_user_department` (`departmentid`),
  KEY `FK_user_post` (`postid`),
  CONSTRAINT `FK_user_department` FOREIGN KEY (`departmentid`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_user_post` FOREIGN KEY (`postid`) REFERENCES `post` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Пользователи.';

LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (4,'tester','f5d1278e8109edd94e1e4197e04873b9','Иван','Петрович','Воробьев',NULL,NULL),(8,'ssp','97c9c694d99f729e1a48940d0b386a9b','Сергей','Петрович','Семёнов',4,NULL),(9,'ayuz','effeff8566c17857a3f09e4b3c211ee7','Антон','Юрьевич','Захаров',4,NULL),(10,'vvb','74e1e420da1716ae52ac3ed6655417d0','Владимир','Владимирович','Бурлуцкий',4,NULL);
UNLOCK TABLES;

DROP TABLE IF EXISTS `role_privileges`;
CREATE TABLE `role_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleid` int(11) NOT NULL COMMENT 'Идентификатор роли.',
  `resourceid` int(11) NOT NULL COMMENT 'Идентификатор ресурса.',
  `name` varchar(32) NOT NULL COMMENT 'Название привилегии.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_privileges` (`roleid`,`resourceid`,`name`),
  KEY `FK_role_privileges_role` (`roleid`),
  KEY `FK_role_privileges_resource` (`resourceid`),
  CONSTRAINT `FK_role_privileges_resource` FOREIGN KEY (`resourceid`) REFERENCES `resource` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_role_privileges_role` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='Привилегии роли.';

LOCK TABLES `role_privileges` WRITE;
INSERT INTO `role_privileges` VALUES (7,1,1,'index'),(18,1,2,'delete'),(10,1,2,'index'),(11,1,2,'insert'),(1,1,2,'login'),(2,1,2,'logout'),(17,1,3,'delete'),(12,1,3,'index'),(15,1,3,'insert'),(16,1,3,'update'),(20,1,4,'access'),(19,1,4,'error'),(32,1,6,'index'),(34,1,7,'delete'),(31,1,7,'index'),(33,1,7,'insert'),(8,2,1,'index'),(3,2,2,'login'),(4,2,2,'logout'),(13,2,3,'index'),(22,2,4,'access'),(21,2,4,'error'),(35,2,5,'combine'),(26,2,5,'confirm'),(25,2,5,'index'),(9,3,1,'index'),(5,3,2,'login'),(6,3,2,'logout'),(14,3,3,'index'),(24,3,4,'access'),(23,3,4,'error'),(30,3,5,'delete'),(27,3,5,'index'),(28,3,5,'insert'),(29,3,5,'update');
UNLOCK TABLES;

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор должности.',
  `name` varchar(32) NOT NULL COMMENT 'Название должности.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Должности.';

LOCK TABLES `post` WRITE;
INSERT INTO `post` VALUES (2,'доцент'),(4,'преподаватель'),(1,'профессор'),(3,'старший преподаватель');
UNLOCK TABLES;

DROP TABLE IF EXISTS `report_values`;
CREATE TABLE `report_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор значения показателя.',
  `itemid` int(11) NOT NULL COMMENT 'Идентификатор показателя.',
  `userid` int(11) NOT NULL COMMENT 'Идентификатор пользователя.',
  `value` int(11) NOT NULL COMMENT 'Значение показателя.',
  `isconfirmed` int(11) NOT NULL COMMENT 'Подтверждение значения показателя (1, если значение подтверждено, и 0 в противном случае).',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_itemid_userid` (`itemid`,`userid`),
  KEY `FK_report_values_report_items` (`itemid`),
  KEY `FK_report_values_report_users` (`userid`),
  CONSTRAINT `FK_report_values_report_items` FOREIGN KEY (`itemid`) REFERENCES `report_items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_report_values_report_users` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Значения показателей отчёта.';

LOCK TABLES `report_values` WRITE;
INSERT INTO `report_values` VALUES (6,9,9,7,0),(9,3,8,7,1),(10,12,9,6,1),(11,5,9,2,1),(12,12,10,5,0),(13,6,10,2,0),(14,7,10,6,0);
UNLOCK TABLES;

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор роли пользователя.',
  `userid` int(11) NOT NULL COMMENT 'Идентификатор пользователя.',
  `roleid` int(11) NOT NULL COMMENT 'Идентификатор роли.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_roles` (`userid`,`roleid`),
  KEY `FK_user_roles_user` (`userid`),
  KEY `FK_user_roles_role` (`roleid`),
  CONSTRAINT `FK_user_roles_role` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_user_roles_user` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Роли пользователя.';

LOCK TABLES `user_roles` WRITE;
INSERT INTO `user_roles` VALUES (1,4,1),(11,8,2),(14,8,3),(12,9,3),(13,10,3);
UNLOCK TABLES;