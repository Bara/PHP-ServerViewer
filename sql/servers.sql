SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `lastscan`;
CREATE TABLE `lastscan` (
  `last` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`last`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lastscan` VALUES ('0');

DROP TABLE IF EXISTS `servers`;
CREATE TABLE `servers` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) DEFAULT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '27015',
  `countrycode` varchar(16) NOT NULL,
  `countryname` varchar(64) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `data` text,
  `players` text,
  `count` tinyint(1) NOT NULL,
  `display` tinyint(1) NOT NULL,
  `description` varchar(255) NOT NULL,
  `lastscan` varchar(255) DEFAULT NULL,
  `lastSuccessScan` varchar(255) DEFAULT NULL,
  `sourceBans` varchar(255) DEFAULT NULL,
  `gameME` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
