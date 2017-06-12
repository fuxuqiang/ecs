DROP TABLE IF EXISTS `ecs_admin_user`;
CREATE TABLE `ecs_admin_user` (
  `user_name` varchar(60) PRIMARY KEY,
  `email` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `add_time` int NOT NULL
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `ecs_shop_config`;
CREATE TABLE `ecs_shop_config` (
  `code` varchar(30) PRIMARY KEY,
  `value` varchar(30) NOT NULL
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `ecs_session`;
CREATE TABLE `ecs_session` (
  `sesskey` varchar(32) PRIMARY KEY,
  `expiry` int unsigned NOT NULL,
  `data` varchar(255) NOT NULL
) ENGINE=MEMORY;