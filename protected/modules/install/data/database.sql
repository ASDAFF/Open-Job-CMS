SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `{dbPrefix}budget`;
CREATE TABLE `{dbPrefix}budget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{dbPrefix}dialog`;
CREATE TABLE `{dbPrefix}dialog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}dialog` (`id`, `sender_id`, `recipient_id`, `date_created`, `date_updated`, `status`) VALUES
  (8,	2,	2,	'2013-10-28 15:46:28',	'0000-00-00 00:00:00',	1),
  (13,	1,	2,	'2016-05-18 14:41:37',	'0000-00-00 00:00:00',	1);

DROP TABLE IF EXISTS `{dbPrefix}message`;
CREATE TABLE `{dbPrefix}message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_name` varchar(30) NOT NULL,
  `object_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}message` (`id`, `object_name`, `object_id`, `type`, `sender_id`, `recipient_id`, `email`, `body`, `date_created`, `date_updated`, `status`, `rating`, `viewed`) VALUES
  (7,	'Dialog',	7,	2,	2,	2,	'',	'Привет',	'2013-10-28 15:46:28',	'0000-00-00 00:00:00',	1,	0,	1),
  (9,	'Dialog',	7,	2,	2,	2,	'',	'Привет!',	'2013-10-28 15:49:01',	'0000-00-00 00:00:00',	1,	0,	1),
  (20,	'Project',	6,	0,	2,	2,	'',	'Как дела?',	'2013-12-20 12:05:13',	'0000-00-00 00:00:00',	1,	0,	1),
  (21,	'Dialog',	8,	0,	2,	2,	'',	'Все норм',	'2013-12-23 13:19:57',	'0000-00-00 00:00:00',	1,	0,	0),
  (22,	'Dialog',	7,	0,	2,	2,	'',	'Здесь был я',	'2013-12-23 13:20:29',	'0000-00-00 00:00:00',	1,	0,	1),
  (30,	'Dialog',	10,	0,	1,	1,	'',	'Привет как дела?',	'2013-12-24 15:33:38',	'0000-00-00 00:00:00',	1,	0,	1),
  (32,	'Dialog',	10,	0,	1,	1,	'',	'Проверь почту, есть письмо?',	'2013-12-24 15:37:46',	'0000-00-00 00:00:00',	1,	0,	1),
  (36,	'Dialog',	10,	0,	1,	1,	'',	'Проверяем',	'2013-12-24 15:49:01',	'0000-00-00 00:00:00',	1,	0,	1),
  (37,	'Dialog',	10,	0,	1,	1,	'',	'Скрипты не пройдут',	'2013-12-24 16:02:19',	'0000-00-00 00:00:00',	1,	0,	1),
  (42,	'Project',	2,	0,	1,	1,	'',	'Тестим раз',	'2016-05-16 16:12:47',	'0000-00-00 00:00:00',	0,	0,	0),
  (43,	'Project',	71,	0,	1,	1,	'',	'Тестим два',	'2016-05-19 16:34:00',	'0000-00-00 00:00:00',	1,	0,	0),
  (44,	'Project',	65,	0,	1,	1,	'',	'Это тестовый комментарий',	'2016-07-14 14:14:26',	'0000-00-00 00:00:00',	1,	0,	1),
  (45,	'Project',	65,	0,	2,	1,	'',	'Еще один',	'2016-07-14 14:15:19',	'0000-00-00 00:00:00',	1,	0,	1),
  (46,	'Dialog',	13,	0,	1,	2,	'',	'Как холодна вода в осенней луже…',	'2016-07-14 14:15:34',	'0000-00-00 00:00:00',	1,	0,	1),
  (47,	'Dialog',	13,	0,	2,	2,	'',	'Все разошлись, остались мы с посудой…',	'2016-07-14 14:15:55',	'0000-00-00 00:00:00',	1,	0,	0),
  (48,	'Dialog',	13,	0,	2,	2,	'',	'// Это однострочный комментарий в стиле c++',	'2016-07-14 14:16:05',	'0000-00-00 00:00:00',	1,	0,	0);

DROP TABLE IF EXISTS `{dbPrefix}profiles_fields`;
CREATE TABLE `{dbPrefix}profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` varchar(15) NOT NULL DEFAULT '0',
  `field_size_min` varchar(15) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
  (1,	'lastname',	'Last Name',	'VARCHAR',	'50',	'3',	0,	'',	'',	'Incorrect Last Name (length between 3 and 50 characters).',	'',	'',	'',	'',	2,	2),
  (2,	'firstname',	'First Name',	'VARCHAR',	'50',	'3',	0,	'',	'',	'Incorrect First Name (length between 3 and 50 characters).',	'',	'',	'',	'',	1,	2),
  (3,	'about_us',	'О себе',	'TEXT',	'6024',	'3',	0,	'',	'',	'',	'',	'',	'',	'',	3,	2);

DROP TABLE IF EXISTS `{dbPrefix}profiles`;
CREATE TABLE `{dbPrefix}profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `about_us` text NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `{dbPrefix}users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}profiles` (`user_id`, `lastname`, `firstname`, `about_us`) VALUES
  (1,	'Admin',	'Administrator',	'Просто админ 111'),
  (2,	'Степанов',	'Степан',	'Я обычный демо юзер');

DROP TABLE IF EXISTS `{dbPrefix}project`;
CREATE TABLE `{dbPrefix}project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `executor_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `budget_id` int(11) NOT NULL,
  `budget_agreement` tinyint(1) NOT NULL,
  `budget` int(11) NOT NULL,
  `payment_type` int(3) NOT NULL,
  `count_view` int(3) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `date_open_until` date NOT NULL,
  `skills_cache` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{dbPrefix}review`;
CREATE TABLE `{dbPrefix}review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `object_name` varchar(50) NOT NULL,
  `object_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}review` (`id`, `type`, `status`, `sender_id`, `recipient_id`, `text`, `object_name`, `object_id`, `date_created`, `date_updated`) VALUES
  (15,	0,	2,	2,	2,	'Комментарий',	'User',	2,	'2013-12-20 14:50:02',	'0000-00-00 00:00:00'),
  (16,	2,	2,	1,	2,	'Комментарий 1',	'User',	2,	'2013-12-23 12:39:46',	'0000-00-00 00:00:00'),
  (19,	0,	2,	1,	1,	'Комментарий 2',	'User',	1,	'2013-12-24 10:56:42',	'0000-00-00 00:00:00'),
  (20,	0,	2,	1,	1,	'Комментарий 3',	'User',	1,	'2016-05-25 16:51:12',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `{dbPrefix}skill`;
CREATE TABLE `{dbPrefix}skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `sort` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}skill` (`id`, `parent_id`, `status`, `name`, `description`, `sort`, `alias`) VALUES
  (1,	16,	1,	'Yii',	'<p>Yii — это высокоэффективный основанный на компонентной структуре PHP-фреймворк для\r\n    разработки масштабных веб-приложений. Он позволяет максимально применить концепцию повторного\r\n    использования кода и может существенно ускорить процесс веб-разработки. Название Yii\r\n    (произносится как Yee или [ji:]) означает простой (easy), эффективный (efficient) и расширяемый\r\n    (extensible).<br></p>',	3,	'yii'),
  (2,	16,	1,	'CodeIgniter',	'',	1,	'codeigniter'),
  (3,	16,	1,	'Zend',	'',	2,	'zend'),
  (4,	16,	1,	'Symfony',	'',	4,	'symfony'),
  (5,	16,	1,	'Kohana',	'',	5,	'kohana'),
  (6,	16,	1,	'CakePHP',	'',	6,	'cakephp'),
  (7,	16,	1,	'Akelos',	'',	8,	'akelos'),
  (8,	16,	1,	'FuelPHP',	'',	9,	'fuelphp'),
  (9,	17,	1,	'Drupal',	'',	2,	'drupal'),
  (10,	17,	1,	'WordPress',	'',	1,	'wordpress'),
  (11,	17,	1,	'Joomla',	'',	3,	'joomla'),
  (12,	17,	1,	'MODx',	'',	4,	'modx'),
  (13,	17,	1,	'1С-Битрикс',	'',	5,	'1s-bitriks'),
  (14,	17,	1,	'UMI-CMS',	'',	6,	'umi-cms'),
  (15,	17,	1,	'NetCat',	'',	7,	'netcat'),
  (16,	0,	1,	'Фреймворки',	'',	0,	'frejmvorki'),
  (17,	0,	1,	'CMS',	'',	0,	'cms'),
  (18,	0,	1,	'Языки',	'',	0,	'jazyki'),
  (19,	0,	1,	'Базы данных',	'',	0,	'bazy-dannyh'),
  (20,	18,	1,	'PHP',	'',	0,	'php'),
  (21,	18,	1,	'javascript',	'',	0,	'javascript'),
  (22,	18,	1,	'java',	'',	0,	'java'),
  (23,	18,	1,	'C#',	'',	0,	'c-sharp'),
  (24,	18,	1,	'C++',	'',	0,	'c-plus-plus'),
  (25,	18,	1,	'jQurey',	'',	0,	'jqurey'),
  (26,	18,	1,	'node.js',	'',	0,	'node-js'),
  (27,	18,	1,	'HTML',	'',	0,	'html'),
  (28,	18,	1,	'CSS',	'',	0,	'css'),
  (29,	19,	1,	'MySQL',	'',	0,	'mysql'),
  (30,	19,	1,	'PostgreSQL',	'',	0,	'postgresql'),
  (31,	19,	1,	'Sybase',	'',	0,	'sybase'),
  (32,	19,	1,	'Oracle',	'',	0,	'oracle'),
  (33,	19,	1,	'Microsoft SQL Server',	'',	0,	'microsoft-sql-server'),
  (34,	18,	1,	'IBM DB2',	'',	0,	'ibm-db2'),
  (35,	19,	1,	'MongoDB',	'',	0,	'mongodb'),
  (36,	19,	1,	'CouchDB',	'',	0,	'couchdb');

DROP TABLE IF EXISTS `{dbPrefix}skill_to_project`;
CREATE TABLE `{dbPrefix}skill_to_project` (
  `skill_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{dbPrefix}users`;
CREATE TABLE `{dbPrefix}users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `activity` tinyint(1) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `country_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `only_yii` tinyint(1) NOT NULL,
  `count_view` int(11) NOT NULL,
  `salary_per_hour` int(5) NOT NULL,
  `ava` varchar(128) NOT NULL,
  `city_name` varchar(128) NOT NULL,
  `sbs_newMess` tinyint(1) NOT NULL,
  `sbs_newRequest` tinyint(1) NOT NULL,
  `sbs_newReview` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}users` (`id`, `type`, `activity`, `username`, `password`, `email`, `activkey`, `create_at`, `lastvisit_at`, `superuser`, `status`, `country_id`, `region_id`, `city_id`, `only_yii`, `count_view`, `salary_per_hour`, `ava`, `city_name`, `sbs_newMess`, `sbs_newRequest`, `sbs_newReview`) VALUES
  (1,	1,	0,	'{adminLogin}',	'{adminPass}',	'{adminEmail}',	'e6cc1bfaaf1c520736b3fc7b3b5a3b09',	'2012-12-24 12:29:18',	'2016-07-01 13:49:46',	1,	1,	4,	107,	3348,	0,	0,	0,	'1.jpg',	'Newcastle',	1,	1,	1),
  (2,	1,	0,	'demo',	'fe01ce2a7fbac8fafaed7c982a04e229',	'demo@demo.ru',	'e6cc1bfaaf1c520736b3fc7b3b5a3b09',	'2012-12-24 12:29:18',	'2016-07-14 12:15:12',	0,	1,	1,	0,	0,	0,	0,	100,	'',	'',	1,	1,	1);

DROP TABLE IF EXISTS `{dbPrefix}user_portfolio`;
CREATE TABLE `{dbPrefix}user_portfolio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `description` varchar(255) NOT NULL,
  `img` varchar(100) NOT NULL,
  `sorter` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}user_portfolio` (`id`, `user_id`, `status`, `description`, `img`, `sorter`, `date_created`, `date_updated`) VALUES
  (41,	3,	1,	'',	'cel20.jpg',	0,	'2013-11-01 11:05:29',	'2013-11-01 11:17:13'),
  (46,	3,	1,	'',	'ava.jpg',	0,	'2013-11-01 11:06:52',	'0000-00-00 00:00:00'),
  (47,	3,	1,	'',	'855aeb.jpg',	0,	'2013-11-01 11:07:28',	'0000-00-00 00:00:00'),
  (51,	2,	1,	'',	'amazing_milky_way-1920x1080.jpg',	0,	'2013-12-20 13:39:56',	'0000-00-00 00:00:00'),
  (52,	2,	1,	'',	'atlantis_the_palm_dubai-1920x1080.jpg',	0,	'2013-12-20 13:39:56',	'0000-00-00 00:00:00'),
  (56,	1,	1,	'',	'111o11ray.jpg',	0,	'2013-12-23 11:57:53',	'2013-12-23 12:36:25'),
  (57,	2,	1,	'',	'BIG-ASDASD.JPG',	0,	'2013-12-23 14:29:08',	'0000-00-00 00:00:00'),
  (58,	2,	1,	'',	'bashmet.jpg',	0,	'2013-12-23 14:29:08',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `{dbPrefix}user_skills`;
CREATE TABLE `{dbPrefix}user_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` int(3) NOT NULL,
  `experience` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{dbPrefix}user_skills` (`id`, `user_id`, `skill_id`, `level`, `experience`) VALUES
  (9,	2,	2,	6,	72),
  (10,	2,	4,	3,	48),
  (17,	1,	1,	-1,	-1),
  (19,	1,	2,	-1,	-1);

-- 2016-07-22 13:29:48
