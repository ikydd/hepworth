CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `position` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `caption` text,
  `mini` varchar(128) DEFAULT NULL,
  `thumb` varchar(128) DEFAULT NULL,
  `medium` varchar(128) DEFAULT NULL,
  `full` varchar(128) DEFAULT NULL,
  `available` int(1) NOT NULL DEFAULT '0',
  `price` decimal(6,2) DEFAULT NULL,
  `details` text,
  `collection_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `settings` (
`key` varchar(128) NOT NULL,
`value` text,
PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL,
`pass` varchar(72) NOT NULL,
`email` varchar(128) DEFAULT NULL,
`ident` varchar(64) DEFAULT NULL,
`token` varchar(64) DEFAULT NULL,
`timeout` datetime DEFAULT NULL,
`last_fail` datetime DEFAULT NULL,
`failcount` int(11) NOT NULL DEFAULT '0',
`lockout` datetime DEFAULT NULL,
`created` datetime NOT NULL,
`modified` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` varchar(32) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `pages` (`id`, `title`, `content`) VALUES
('about', '', ''),
('terms', 'Terms & Conditions', ''),
('prints', 'Prints', ''),
('contact', 'Contact', ''),
('print-thanks', 'Thank You', 'Thank  you for your purchase - You will receive an email from me shortly confirming your order'),
('print-info', 'Print Info', ''),
('contact-thanks', 'Thanks', 'Thanks for getting in touch. Your message has been sent :)'),
('exhibitions', 'Exhbitions', ''),
('shop', '', '');