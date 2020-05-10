ALTER TABLE `#__tracks_individuals`
    ADD `team_id` INT(11) NOT NULL DEFAULT '0' AFTER `alias`,
    ADD KEY `team_id` (`team_id`);