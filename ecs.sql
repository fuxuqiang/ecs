CREATE DATABASE `ecs` CHARACTER SET utf8;
USE `ecs`;

CREATE TABLE `shop_config` (
  `code` varchar(11) PRIMARY KEY,
  `value` text NOT NULL
) ENGINE=MyISAM;