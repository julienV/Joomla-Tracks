CREATE TABLE IF NOT EXISTS `#__tracks_projects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `competition_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `params` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_competitions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_seasons` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `ordering` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_teams` (
  `id` int(11) NOT NULL auto_increment,
  `club_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `short_name` varchar(20) NOT NULL,
  `acronym` varchar(6) NOT NULL,
  `picture` varchar(100) NULL,
  `picture_small` varchar(100) NULL,
  `country_code` varchar(3) NULL,
  `description` text NOT NULL,
  `admin_id` int(11) NOT NULL,
  `url` varchar(255) NULL,
  `facebook` varchar(255) NULL,
  `twitter` varchar(255) NULL,
  `googleplus` varchar(255) NULL,
  `youtube` varchar(255) NULL,
  `instagram` varchar(255) NULL,
  `pinterest` varchar(255) NULL,
  `vimeo` varchar(255) NULL,
  `vehicle_description` text NULL,
  `vehicle_picture` varchar(255) NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `club_id` (`club_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_clubs` (
  `id` int(11) NOT NULL auto_increment,
  `full_name` varchar(40) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `picture` varchar(100) NULL,
  `picture_small` varchar(100) NULL,
  `address` text NULL,
  `postcode` varchar(10) NULL,
  `city` varchar(50) NULL,
  `state` varchar(20) NULL,
  `country_code` varchar(3) NULL,
  `description` text NULL,
  `admin_id` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_individuals` (
  `id` int(11) NOT NULL auto_increment,
  `last_name` varchar(40) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `nickname` varchar(20) NOT NULL,
  `height` varchar(10) NULL,
  `weight` varchar(10) NULL,
  `dob` date NULL,
  `hometown` varchar(50) NULL,
  `country_code` varchar(3) NULL,
  `user_id` int(11) NOT NULL,
  `picture` varchar(250) NULL,
  `picture_small` varchar(250) NULL,
  `address` text NULL,
  `postcode` varchar(10) NULL,
  `city` varchar(50) NULL,
  `state` varchar(20) NULL,
  `country` varchar(20) NULL,
  `description` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_rounds` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `country` varchar(3) NULL,
  `picture` varchar(250) NULL,
  `thumbnail` varchar(250) NULL,
  `description` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_eventtypes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `alias` varchar(100) NOT NULL default '',
  `note` varchar(100) NOT NULL,
  `count_points` tinyint(2) NOT NULL,
  `points_attribution` varchar(250) NOT NULL,
  `enable_stats` tinyint(2) NOT NULL,
  `description` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_projects_rounds` (
  `id` int(11) NOT NULL auto_increment,
  `round_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `start_date` datetime NULL,
  `end_date` datetime NULL,
  `description` text NULL,
  `comment` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `round_id` (`round_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_events` (
  `id` int(11) NOT NULL auto_increment,
  `projectround_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `start_date` datetime NULL,
  `end_date` datetime NULL,
  `description` text NULL,
  `comment` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `projectround_id` (`projectround_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_participants` (
  `id` int(11) NOT NULL auto_increment,
  `individual_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `number` varchar(8) NULL,
  `initial_points` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `individual_id` (`individual_id`),
  KEY `project_id` (`project_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_projects_teams` (
  `id` int(11) NOT NULL auto_increment,
  `team_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `description` text NULL,
  `initial_points` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `team_id` (`team_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_events_results` (
  `id` int(11) NOT NULL auto_increment,
  `individual_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `number` varchar(8) NULL,
  `event_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `performance` VARCHAR(30) NOT NULL DEFAULT '',
  `bonus_points` float NOT NULL,
  `comment` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `individual_id` (`individual_id`),
  KEY `team_id` (`team_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__tracks_project_settings` (
  `id` int(11) NOT NULL auto_increment,
  `project_id` int(11) NOT NULL,
  `xml` varchar(50) NOT NULL DEFAULT '',
  `settings` text NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
