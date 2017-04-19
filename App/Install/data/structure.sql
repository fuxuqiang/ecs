DROP TABLE IF EXISTS `ecs_admin_user`;
CREATE TABLE `ecs_admin_user` (
  `user_id` smallint(5) unsigned AUTO_INCREMENT PRIMARY KEY,
  `user_name` varchar(60) NOT NULL UNIQUE KEY,
  `email` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `add_time` int(11) NOT NULL
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `ecs_shop_config`;
CREATE TABLE `ecs_shop_config` (
  `code` varchar(30) PRIMARY KEY,
  `value` varchar(30) NOT NULL
) ENGINE=MyISAM;