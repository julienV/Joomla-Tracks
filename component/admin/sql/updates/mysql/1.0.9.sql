ALTER TABLE `#__tracks_subroundtypes` ADD  `count_points` tinyint(2) NOT NULL;

UPDATE `#__tracks_subroundtypes` SET `count_points` = 1 WHERE CHAR_LENGTH(`points_attribution`) > 0;