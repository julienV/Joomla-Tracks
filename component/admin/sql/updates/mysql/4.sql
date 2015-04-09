ALTER TABLE `#__tracks_teams` ADD INDEX `club_id` (`club_id`);

ALTER TABLE `#__tracks_projects_rounds` ADD INDEX `round_id` (`round_id`),
	ADD INDEX `project_id` (`project_id`);

ALTER TABLE `#__tracks_events` ADD INDEX `projectround_id` (`projectround_id`),
	ADD INDEX `type` (`type`);

ALTER TABLE `#__tracks_participants` ADD INDEX `individual_id` (`individual_id`),
	ADD INDEX `project_id` (`project_id`),
	ADD INDEX `team_id` (`team_id`);

ALTER TABLE `#__tracks_projects_teams` ADD INDEX `team_id` (`team_id`),
	ADD INDEX `project_id` (`project_id`);

ALTER TABLE `#__tracks_events_results` ADD INDEX `individual_id` (`individual_id`),
	ADD INDEX `team_id` (`team_id`),
	ADD INDEX `event_id` (`event_id`);

ALTER TABLE `#__tracks_project_settings` ADD INDEX `project_id` (`project_id`);
