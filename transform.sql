DROP TABLE IF EXISTS `Entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title`varchar(255) NOT NULL,
  `from` date not NULL,
  `to` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `affected` datetime DEFAULT NULL,
  `type` varchar(4) not NULL default 'show',
  `body_is` text DEFAULT NULL,
  `body_en` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `Entry` 
select 
	`id`, 
    `name` as `title`, 
    `f_date` as `from`, 
    `t_date` as `to`, 
    `c_date` as `created`, 
    `a_date` as `affected`,
    'show' as `type`,
    trim(concat(COALESCE(`artist_is`, ''), '\n\n',  COALESCE(`info_is`, ''))) as `body_is`,
    trim(concat(COALESCE(`artist_en`, ''), '\n\n', COALESCE(`info_en`, ''))) as `body_en`
from kob_archive;

DROP TABLE IF EXISTS `Author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name`varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `affected` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `Author` (`id`, `name`)
select id as `id`, art_name as `name` from kob_archive;

DROP TABLE IF EXISTS `Entry_has_Author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Entry_has_Author` (
  `entry_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`entry_id`, `author_id`),
  CONSTRAINT `fk_Entry_has_Author_Entry` FOREIGN KEY (`entry_id`) REFERENCES `Entry` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Entry_has_Author_Author` FOREIGN KEY (`author_id`) REFERENCES `Author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `Entry_has_Author` (`entry_id`, `author_id`)
select id as `entry_id`, id as `author_id` from kob_archive;

DROP TABLE IF EXISTS `Image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name`varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `size` int NOT NULL DEFAULT 0,
  `width` int NOT NULL DEFAULT 0,
  `height` int NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `affected` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `Image`
select 
	`id` as `id`, 
    `img` as `name`,
    trim(concat(COALESCE(`info_is`, ''), '\n\n', COALESCE(`info_en`, ''))) as `description`,
    `size` as `size`,
    `w` as `width`,
    `h` as `height`,
    `c_date` as `created`,
    `a_date` as `affected`
from kob_img;


DROP TABLE IF EXISTS `Entry_has_Image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Entry_has_Image` (
  `image_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`image_id`, `entry_id`),
  CONSTRAINT `fk_Entry_has_Poster_Image` FOREIGN KEY (`image_id`) REFERENCES `Image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Entry_has_Poster_Item` FOREIGN KEY (`entry_id`) REFERENCES `Entry` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `Entry_has_Image` (`image_id`, `entry_id`, `type`)
select I.`id` as `image_id`, I.`owner_id` as `entry_id`, I.`stat` as `type` from kob_archive A
	join kob_img I on (A.id = I.owner_id);


DROP TABLE IF EXISTS `Manifesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Manifesto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) not NULL,
  `body_is` text DEFAULT NULL,
  `body_en` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  unique index (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `Manifesto`
select id,  'manifesto' as `type`,
`info_is` as body_is,
`info_en` as body_en
from kob_manifesto;


DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` char(38) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` tinyint not null DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

insert into `User`
select `user_id` as `id`, `username` as `name`, `passwd` as `password`, `user_type` as `type` from kob_user;




