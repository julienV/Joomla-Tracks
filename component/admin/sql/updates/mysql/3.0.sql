RENAME TABLE `#__tracks_subroundtypes` TO `#__tracks_eventtypes`;
RENAME TABLE `#__tracks_projects_subrounds` TO `#__tracks_events`;

ALTER TABLE `#__tracks_rounds_results` CHANGE `subround_id` `event_id` int(11) NOT NULL,
	DROP KEY `pr_ind`,
	ADD UNIQUE KEY `event_individual` (`individual_id`,`event_id`);
RENAME TABLE `#__tracks_rounds_results` TO `#__tracks_events_results`;

RENAME TABLE `#__tracks_projects_individuals` TO `#__tracks_participants`;
