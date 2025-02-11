<?php

function getUpdates23_12_00(): array {
	$curTime = time();
	return [
		/*'name' => [
			 'title' => '',
			 'description' => '',
			 'continueOnError' => false,
			 'sql' => [
				 ''
			 ]
		 ], //name*/

		//mark - ByWater

		'disable_circulation_actions' => [
			'title' => 'Disable Circulation Actions',
			'description' => 'Add an option to disable circulation actions for a user.',
			'continueOnError' => false,
			'sql' => [
				'ALTER TABLE user ADD COLUMN disableCirculationActions TINYINT(1) DEFAULT 0'
			]
		], //disable_circulation_actions
		'createPalaceProjectModule' => [
			'title' => 'Create Palace Project module',
			'description' => 'Setup module for Palace Project Integration',
			'sql' => [
				"INSERT INTO modules (name, indexName, backgroundProcess,logClassPath,logClassName,settingsClassPath,settingsClassName) VALUES ('Palace Project', 'grouped_works', 'palace_project_export','/sys/PalaceProject/PalaceProjectLogEntry.php', 'PalaceProjectLogEntry','/sys/PalaceProject/PalaceProjectSetting.php', 'PalaceProjectSetting')",
			],
		], //createPalaceProjectModule

		'createPalaceProjectSettingsAndScopes' => [
			'title' => 'Create settings and scopes for Palace Project',
			'description' => 'Create settings and scopes for Palace Project',
			'sql' => [
				"CREATE TABLE IF NOT EXISTS palace_project_settings(
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					apiUrl VARCHAR(255),
					libraryId VARCHAR(50),
					regroupAllRecords TINYINT(1) DEFAULT 0,
					runFullUpdate TINYINT(1) DEFAULT 0,
					lastUpdateOfChangedRecords INT(11) DEFAULT 0,
					lastUpdateOfAllRecords INT(11) DEFAULT 0
				) ENGINE = InnoDB",
				'CREATE TABLE IF NOT EXISTS palace_project_scopes (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(50) NOT NULL,
					settingId INT(11)
				) ENGINE = InnoDB',
			],
		], //createPalaceProjectSettingsAndScopes

		'library_location_palace_project_scoping' => [
			'title' => 'Library and Location Scoping of Palace Project',
			'description' => 'Add information about how to scope hoopla records',
			'sql' => [
				'ALTER TABLE library ADD COLUMN palaceProjectScopeId INT(11) default -1',
				'ALTER TABLE location ADD COLUMN palaceProjectScopeId INT(11) default -1',
			],
		], //library_location_palace_project_scoping

		'palace_project_exportLog' => [
			'title' => 'Palace Project export log',
			'description' => 'Create log for Palace Project export.',
			'sql' => [
				"CREATE TABLE IF NOT EXISTS palace_project_export_log(
				  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The id of log',
				  `startTime` int(11) NOT NULL COMMENT 'The timestamp when the run started',
				  `endTime` int(11) DEFAULT NULL COMMENT 'The timestamp when the run ended',
				  `lastUpdate` int(11) DEFAULT NULL COMMENT 'The timestamp when the run last updated (to check for stuck processes)',
				  `notes` mediumtext COLLATE utf8mb4_general_ci COMMENT 'Additional information about the run',
				  `numProducts` int(11) DEFAULT '0',
				  `numErrors` int(11) DEFAULT '0',
				  `numAdded` int(11) DEFAULT '0',
				  `numDeleted` int(11) DEFAULT '0',
				  `numUpdated` int(11) DEFAULT '0',
				  `numSkipped` int(11) DEFAULT '0',
				  `numChangedAfterGrouping` int(11) DEFAULT '0',
				  `numRegrouped` int(11) DEFAULT '0',
				  `numInvalidRecords` int(11) DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
			],
		], //palace_project_exportLog

		'palace_project_permissions' => [
			'title' => 'Palace Project permissions',
			'description' => 'Create permissions for Palace Project administration.',
			'sql' => [
				"INSERT INTO permissions (sectionName, name, requiredModule, weight, description) VALUES 
						('Cataloging & eContent', 'Administer Palace Project', 'Palace Project', 155, 'Allows the user configure Palace Project integration for all libraries.')",
				"INSERT INTO role_permissions(roleId, permissionId) VALUES ((SELECT roleId from roles where name='opacAdmin'), (SELECT id from permissions where name='Administer Palace Project'))",
			],
		], //palace_project_permissions

		'palace_project_titles' => [
			'title' => 'Palace Project Titles',
			'description' => 'Create table to store information about titles exported from Palace Project',
			'sql' => [
				"CREATE TABLE `palace_project_title` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `palaceProjectId` VARCHAR(50) NOT NULL,
				  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
				  `rawChecksum` bigint(20) DEFAULT NULL,
				  `rawResponse` mediumblob,
				  `dateFirstDetected` bigint(20) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `palaceProjectId` (`palaceProjectId`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
			],
		], //palace_project_titles

		'palace_project_identifier_length' => [
			'title' => 'Increase Palace Project ID Length',
			'description' => 'Increase Palace Project ID Length',
			'sql' => [
				'ALTER TABLE palace_project_title CHANGE COLUMN palaceProjectId palaceProjectId VARCHAR(125)'
			],
		], //palace_project_identifier_length

		'grouped_work_primary_identifier_length' => [
			'title' => 'Increase Grouped Work Primary Identifier Length',
			'description' => 'Increase Grouped Work Primary Identifier Length',
			'sql' => [
				'ALTER TABLE grouped_work_primary_identifiers CHANGE COLUMN identifier identifier VARCHAR(150)'
			],
		], //grouped_work_primary_identifier_length

		'search_options' => [
			'title' => 'Create search options to tweak search results',
			'description' => 'Create search options to tweak search results',
			'sql' => [
				'ALTER TABLE grouped_work_display_settings ADD COLUMN searchSpecVersion TINYINT(1) DEFAULT 2',
				'ALTER TABLE grouped_work_display_settings ADD COLUMN limitBoosts TINYINT(1) DEFAULT 1',
				'ALTER TABLE grouped_work_display_settings ADD COLUMN maxTotalBoost INT DEFAULT 500',
				'ALTER TABLE grouped_work_display_settings ADD COLUMN maxPopularityBoost INT DEFAULT 25',
				'ALTER TABLE grouped_work_display_settings ADD COLUMN maxFormatBoost INT DEFAULT 25',
				'ALTER TABLE grouped_work_display_settings ADD COLUMN maxHoldingsBoost INT DEFAULT 25',
			]
		], //search_options

		'update_default_boost_limits' => [
			'title' => 'Update default boosting limits',
			'description' => 'Update default boosting limits',
			'sql' => [
				'UPDATE grouped_work_display_settings SET maxPopularityBoost = 100, maxFormatBoost = 100, maxHoldingsBoost = 100'
			]
		], //update_default_boost_limits

		'evergreen_extract_options' => [
			'title' => 'Evergreen Extract Options',
			'description' => 'Add options for controlling Evergreen extract',
			'sql' => [
				'ALTER TABLE indexing_profiles ADD COLUMN numRetriesForBibLookups TINYINT DEFAULT 2',
				'ALTER TABLE indexing_profiles ADD COLUMN numMillisecondsToPauseAfterBibLookups INT DEFAULT 0',
			]
		], //evergreen_indexing_options

		'evergreen_extract_number_of_threads' => [
			'title' => 'Evergreen Extract Number of Threads',
			'description' => 'Add options for controlling Evergreen extract threads',
			'sql' => [
				'ALTER TABLE indexing_profiles ADD COLUMN numExtractionThreads TINYINT DEFAULT 10',
			]
		], //evergreen_extract_number_of_threads

		'email_stats' => [
			'title' => 'Add email stat tracking',
			'description' => 'Add tracking of emails sent successfully and emails that failed',
			'sql' => [
				"ALTER TABLE aspen_usage ADD COLUMN emailsSent INT DEFAULT 0",
				"ALTER TABLE aspen_usage ADD COLUMN emailsFailed INT DEFAULT 0",
			]
		], //email_stats

		//kirstien - ByWater

		//kodi - ByWater
		'rename_axis360_permission' => [
			'title' => 'Rename Permission: Administer Axis 360',
			'description' => 'Rename permission "Administer Axis 360" to "Administer Boundless"',
			'continueOnError' => true,
			'sql' => [
				"UPDATE permissions SET description = 'Allows the user configure Boundless integration for all libraries.' WHERE name = 'Administer Axis 360'",
				"UPDATE permissions SET name = 'Administer Boundless' WHERE name = 'Administer Axis 360'",
			]
		], //rename_axis360_permission
		'rename_boundless_module' => [
			'title' => 'Rename Boundless Module',
			'description' => 'Revert change where Axis 360 module was renamed to Boundless',
			'continueOnError' => true,
			'sql' => [
				"UPDATE modules SET name = 'Axis 360' WHERE name = 'Boundless'",
			]
		], //rename_boundless_module
		'readerName2' => [
			'title' => 'Libby Reader Name',
			'description' => 'Name of Libby product to display to patrons. Default is "Libby"',
			'continueOnError' => true,
			'sql' => [
				"ALTER TABLE overdrive_scopes DROP COLUMN libbySora",
				"ALTER TABLE overdrive_scopes ADD COLUMN readerName varchar(25) DEFAULT 'Libby'",
			],
		],
		//readerName
		'rename_overdrive_permission' => [
			'title' => 'Rename Permission: Administer OverDrive',
			'description' => 'Rename permission "Administer OverDrive" to "Administer Libby/Sora"',
			'continueOnError' => true,
			'sql' => [
				"UPDATE permissions SET description = 'Allows the user configure Libby/Sora integration for all libraries.' WHERE name = 'Administer OverDrive'",
				"UPDATE permissions SET name = 'Administer Libby/Sora' WHERE name = 'Administer OverDrive'",
			]
		], //rename_overdrive_permission

		//lucas - Theke
		'show_quick_poll_results' => [
			'title' => 'Display Quick Poll Results',
			'description' => 'Allows the user to show the results of quick polls to those patrons who are not logged in, as well as to choose whether to show graphs, tables or both.',
			'continueOnError' => true,
			'sql' => [
				'ALTER TABLE  web_builder_quick_poll ADD COLUMN showResultsToPatrons TINYINT(1) DEFAULT 0',
			],
		], // show_quick_poll_results

		//alexander - PTFS Europe
		'library_show_language_and_display_in_header' => [
			'title' => 'Library Show Language and Display in Header',
			'description' => 'Add option to allow the language and display settings to be shown in the page header',
			'sql' => [
				"ALTER TABLE library ADD languageAndDisplayInHeader INT(1) DEFAULT 1",
			],
		], //library_show_language_and_display_in_header
		'location_show_language_and_display_in_header' => [
			'title' => 'Location Show Language and Display in Header',
			'description' => 'Add option to allow the language and display settings to be shown in the page header',
			'sql' => [
				"ALTER TABLE location ADD languageAndDisplayInHeader INT(1) DEFAULT 1",
			],
		], //location_show_language_and_display_in_header
	];
}
