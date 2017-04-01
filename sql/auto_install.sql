CREATE TABLE IF NOT EXISTS `civicrm_membership_period` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` date DEFAULT NULL COMMENT 'Beginning of current uninterrupted membership period.',
  `end_date` date DEFAULT NULL COMMENT 'Current membership period expire date.',
  `membership_id` int(10) unsigned NOT NULL COMMENT 'FK to Membership table',
  `contribution_id` int(10) unsigned DEFAULT NULL COMMENT 'FK to contribution table.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UI_contribution_membership` (`contribution_id`,`membership_id`),
  CONSTRAINT `FK_civicrm_membership_period_contribution_id` FOREIGN KEY (`contribution_id`) REFERENCES `civicrm_contribution` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_civicrm_membership_period_membership_id` FOREIGN KEY (`membership_id`) REFERENCES `civicrm_membership` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
