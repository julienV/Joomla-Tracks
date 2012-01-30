ALTER TABLE `#__tracks_individuals` ADD `sponsor_other` VARCHAR( 255 ) NULL DEFAULT NULL;
ALTER TABLE `#__tracks_individuals` ADD `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ADD `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  ADD `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  ADD `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  ADD `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  ADD `hits` int(10) unsigned NOT NULL DEFAULT '0',
  ADD `last_hit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
