CREATE TABLE IF NOT EXISTS `user_submit` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL,
  `email` varchar(320) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(50) NOT NULL,
  `captcha` varchar(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;