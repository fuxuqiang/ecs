DROP TABLE IF EXISTS `ecs_admin_user`;
CREATE TABLE `ecs_admin_user` (
  `user_id` smallint(5) unsigned AUTO_INCREMENT PRIMARY KEY,
  `user_name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `add_time` int(11) NOT NULL,
  `action_list` text NOT NULL,
  `nav_list` text NOT NULL,
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM;