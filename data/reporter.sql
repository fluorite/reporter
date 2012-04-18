CREATE DATABASE  IF NOT EXISTS reporter;
USE reporter;

DROP TABLE IF EXISTS report_levels;
CREATE TABLE report_levels (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор уровня отчёта.',
  reportid int(11) NOT NULL COMMENT 'Идентификатор отчёта.',
  name varchar(256) NOT NULL COMMENT 'Название уровня.',
  number tinyint(4) NOT NULL COMMENT 'Номер уровня (1..255).',
  PRIMARY KEY (id),
  KEY FK_report_report_levels (reportid),
  CONSTRAINT FK_report_report_levels FOREIGN KEY (reportid) REFERENCES report (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Уровни отчёта.';

DROP TABLE IF EXISTS resource;
CREATE TABLE resource (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор ресурса.',
  name varchar(32) NOT NULL COMMENT 'Название ресурса',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_name (name)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Ресурсы.';

DROP TABLE IF EXISTS report_items;
CREATE TABLE report_items (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор показателя.',
  parentid int(11) DEFAULT NULL COMMENT 'Идентификатор родительского показателя.',
  levelid int(11) NOT NULL COMMENT 'Идентификатор уровня отчёта.',
  name varchar(256) NOT NULL COMMENT 'Название показателя.',
  number varchar(32) NOT NULL COMMENT 'Номер показателя по порядку.',
  isvalue int(11) NOT NULL COMMENT 'Наличие значения для показателя (1, если значение есть, и 0 в противном случае).',
  PRIMARY KEY (id),
  KEY FK_report_levels_report_items (levelid),
  KEY FK_report_items (parentid),
  CONSTRAINT FK_report_items FOREIGN KEY (parentid) REFERENCES report_items (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_report_levels_report_items FOREIGN KEY (levelid) REFERENCES report_levels (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Показатели отчёта.';

DROP TABLE IF EXISTS report;
CREATE TABLE report (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор отчета.',
  name varchar(256) NOT NULL COMMENT 'Название отчета.',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Отчеты.';

DROP TABLE IF EXISTS department;
CREATE TABLE department (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор подразделения.',
  parentid int(11) DEFAULT NULL COMMENT 'Идентификатор родительского подразделения.',
  name varchar(128) NOT NULL COMMENT 'Название подразделения.',
  shortname varchar(32) NOT NULL COMMENT 'Сокращенное название подразделения.',
  managerid int(11) DEFAULT NULL COMMENT 'Идентификатор руководителя.',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_name (name),
  UNIQUE KEY UQ_shortname (shortname),
  KEY FK_department (parentid),
  KEY FK_department_user (managerid),
  CONSTRAINT FK_department_user FOREIGN KEY (managerid) REFERENCES user (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_department FOREIGN KEY (parentid) REFERENCES department (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Подразделения.';

DROP TABLE IF EXISTS role;
CREATE TABLE role (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор роли.',
  name varchar(32) NOT NULL COMMENT 'Название роли.',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_name (name)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Роли.';

DROP TABLE IF EXISTS user;
CREATE TABLE user (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор пользователя.',
  login varchar(32) NOT NULL COMMENT 'Учетная запись пользователя.',
  password varchar(32) NOT NULL COMMENT 'Пароль пользователя.',
  firstname varchar(64) NOT NULL COMMENT 'Имя пользователя.',
  middlename varchar(64) NOT NULL COMMENT 'Отчество пользователя.',
  lastname varchar(64) NOT NULL COMMENT 'Фамилия пользователя.',
  departmentid int(11) DEFAULT NULL COMMENT 'Идентификатор подразделения.',
  postid int(11) DEFAULT NULL COMMENT 'Идентификатор должности.',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_login (login),
  KEY FK_user_department (departmentid),
  KEY FK_user_post (postid),
  CONSTRAINT FK_user_post FOREIGN KEY (postid) REFERENCES post (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_user_department FOREIGN KEY (departmentid) REFERENCES department (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Пользователи.';

DROP TABLE IF EXISTS role_privileges;
CREATE TABLE role_privileges (
  id int(11) NOT NULL AUTO_INCREMENT,
  roleid int(11) NOT NULL COMMENT 'Идентификатор роли.',
  resourceid int(11) NOT NULL COMMENT 'Идентификатор ресурса.',
  name varchar(32) NOT NULL COMMENT 'Название привилегии.',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_privileges (roleid,resourceid,name),
  KEY FK_role_privileges_role (roleid),
  KEY FK_role_privileges_resource (resourceid),
  CONSTRAINT FK_role_privileges_resource FOREIGN KEY (resourceid) REFERENCES resource (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_role_privileges_role FOREIGN KEY (roleid) REFERENCES role (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='Привилегии роли.';

DROP TABLE IF EXISTS post;
CREATE TABLE post (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор должности.',
  name varchar(32) NOT NULL COMMENT 'Название должности.',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_name (name)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Должности.';

DROP TABLE IF EXISTS report_values;
CREATE TABLE report_values (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор значения показателя.',
  itemid int(11) NOT NULL COMMENT 'Идентификатор показателя.',
  userid int(11) NOT NULL COMMENT 'Идентификатор пользователя.',
  value int(11) NOT NULL COMMENT 'Значение показателя.',
  isconfirmed int(11) NOT NULL COMMENT 'Подтверждение значения показателя (1, если значение подтверждено, и 0 в противном случае).',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_itemid_userid (itemid,userid),
  KEY FK_report_values_report_items (itemid),
  KEY FK_report_values_report_users (userid),
  CONSTRAINT FK_report_values_report_items FOREIGN KEY (itemid) REFERENCES report_items (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_report_values_report_users FOREIGN KEY (userid) REFERENCES user (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Значения показателей отчёта.';

DROP TABLE IF EXISTS user_roles;
CREATE TABLE user_roles (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор роли пользователя.',
  userid int(11) NOT NULL COMMENT 'Идентификатор пользователя.',
  roleid int(11) NOT NULL COMMENT 'Идентификатор роли.',
  PRIMARY KEY (id),
  UNIQUE KEY UQ_roles (userid,roleid),
  KEY FK_user_roles_user (userid),
  KEY FK_user_roles_role (roleid),
  CONSTRAINT FK_user_roles_role FOREIGN KEY (roleid) REFERENCES role (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT FK_user_roles_user FOREIGN KEY (userid) REFERENCES user (id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Роли пользователя.';