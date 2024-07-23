<?php

require_once ROOT_DIR . '/services/Admin/Admin.php';
require_once ROOT_DIR . '/sys/SystemLogging/AspenUsage.php';
require_once ROOT_DIR . '/sys/ILS/UserILSUsage.php';
require_once ROOT_DIR . '/sys/ILS/ILSRecordUsage.php';

class ILS_UsageGraphs extends Admin_Admin {
	function launch() {
		global $interface;
		global $enabledModules;
		global $library;
		$title = 'ILS Usage Graph';
		$stat = $_REQUEST['stat'];
		if (!empty($_REQUEST['instance'])) {
			$instanceName = $_REQUEST['instance'];
		} else {
			$instanceName = '';
		}

		$dataSeries = [];
		$columnLabels = [];
				
		switch ($stat) {
			case 'userLogins':
				$title .= ' - User Logins';
				break;
			case 'selfRegistrations':
				$title .= ' - Self Registrations';
				break;
			case 'usersWithHolds':
				$title .= ' - Users Who Placed At Least One Hold';
				break;
			case 'recordsHeld':
				$title .= ' - Records Held';
				break;
			case 'totalHolds':
				$title .= ' - Total Holds';
				break;
			case 'usersWithPdfDownloads': 
				$title .= ' - Users Who Downloaded At Least One PDF';
				break;
			case 'usersWithPdfViews':
				$title .= ' - Users Who Viewed At Least One PDF';
				break;
			case 'pdfsDownloaded':
				$title .= ' - PDFs Downloaded';
				break;
			case 'pdfsViewed':
				$title .= ' - PDFs Viewed';
				break;
			case 'usersWithSupplementalFileDownloads':
				$title .= ' - Users Who Downloaded At Least One Supplemental File';
				break;
			case 'supplementalFilesDownloaded':
				$title .= ' - Supplemental Files Downloaded';
				break;
		}
		
		// for graphs displaying data retrieved from the user_ils_usage table
		if (
			$stat == 'userLogins' ||
			$stat == 'selfRegistrations' ||
			$stat == 'usersWithPdfDownloads' ||
			$stat == 'usersWithPdfViews' ||
			$stat == 'usersWithSupplementalFileDownloads' ||
			$stat == 'usersWithHolds'
		) {
			$userILSUsage = new UserILSUsage();
			$userILSUsage->groupBy('year, month');
			if (!empty($instanceName)) {
				$userILSUsage->instance = $instanceName;
			}
			$userILSUsage->selectAdd();
			$userILSUsage->selectAdd('year');
			$userILSUsage->selectAdd('month');
			$userILSUsage->orderBy('year, month');
			if ($stat == 'userLogins') {
				$dataSeries['User Logins'] = [
					'borderColor' => 'rgba(255, 99, 132, 1)',
					'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
					'data' => [],
				];
				$userILSUsage->selectAdd('SUM(usageCount) as sumUserLogins');
			}
			if ($stat == 'selfRegistrations') {
				$dataSeries['Self Registration'] = [
					'borderColor' => 'rgba(255, 159, 64, 1)',
					'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
					'data' => [],
				];
				$userILSUsage->selectAdd('SUM(selfRegistrationCount) as sumSelfRegistrations');
			}
			if ($stat == 'usersWithPdfDownloads') {
				$dataSeries['Users Who Downloaded At Least One PDF'] = [
					'borderColor' => 'rgba(255, 206, 86, 1)',
					'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
					'data' => [],
				];
				$userILSUsage->selectAdd('SUM(IF(pdfDownloadCount>0,1,0)) as usersWithPdfDownloads');
			}
			if ($stat == 'usersWithPdfViews') {
				$dataSeries['Users Who Viewed At Least One PDF'] = [
					'borderColor' => 'rgba(255, 206, 86, 1)',
					'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
					'data' => [],
				];
				$userILSUsage->selectAdd('SUM(IF(pdfViewCount>0,1,0)) as usersWithPdfViews');
			}
			if ($stat == 'usersWithSupplementalFileDownloads') {
				$dataSeries['Users Who Downloaded At Least One Supplemental File'] = [
					'borderColor' => 'rgba(255, 206, 86, 1)',
					'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
					'data' => [],
				];
				$userILSUsage->selectAdd('SUM(IF(supplementalFileDownloadCount>0,1,0)) as usersWithSupplementalFileDownloads');
			}
			if ($stat == 'usersWithHolds') {
				$dataSeries['Users Who Placed At Least One Hold'] = [
					'borderColor' => 'rgba(0, 255, 55, 1)',
					'backgroundColor' => 'rgba(0, 255, 55, 0.2)',
					'data' => [],
				];
				$userILSUsage->selectAdd('SUM(IF(usageCount>0,1,0)) as usersWithHolds');
			}

			//Collect results
			$userILSUsage->find();
	
			while ($userILSUsage->fetch()) {
				$curPeriod = "{$userILSUsage->month}-{$userILSUsage->year}";
				$columnLabels[] = $curPeriod;
				if ($stat == 'userLogins' ) {
					/** @noinspection PhpUndefinedFieldInspection */
					$dataSeries['User Logins']['data'][$curPeriod] = $userILSUsage->sumUserLogins;
				}
				if ($stat == 'selfRegistrations' ) {
					/** @noinspection PhpUndefinedFieldInspection */
					$dataSeries['Self Registrations']['data'][$curPeriod] = $userILSUsage->sumSelfRegistrations;
				}
				if ($stat == 'usersWithPdfDownloads' ) {
					/** @noinspection PhpUndefinedFieldInspection */
					$dataSeries['Users Who Downloaded At Least One PDF']['data'][$curPeriod] = $userILSUsage->usersWithPdfDownloads;
				}
				if ($stat == 'usersWithPdfViews') {
					/** @noinspection PhpUndefinedFieldInspection */
					$dataSeries['Users Who Viewed At Least One PDF']['data'][$curPeriod] = $userILSUsage->usersWithPdfViews;	
				}
				if ($stat == 'usersWithHolds') {
					/** @noinspection PhpUndefinedFieldInspection */
					$dataSeries['Users Who Placed At Least One Hold']['data'][$curPeriod] = $userILSUsage->usersWithHolds;	
				}
				if ($stat == 'usersWithSupplementalFileDownloads') {
					/** @noinspection PhpUndefinedFieldInspection */
					$dataSeries['Users Who Downloaded At Least One Supplemental File']['data'][$curPeriod] = $userILSUsage->usersWithSupplementalFileDownloads;	
				}
			}
		}
		// for graphs displaying data retrieved from the ils_record_usage table
		if (
			$stat == 'pdfsDownloaded' ||
			$stat == 'pdfsViewed' ||
			$stat == 'supplementalFilesDownloaded' ||
			$stat == 'recordsHeld' ||
			$stat == 'totalHolds'
		) {
			$recordILSUsage = new ILSRecordUsage();
			if (!empty($instanceName)) {
				$recordILSUsage->instance = $instanceName;
			}
			$recordILSUsage->selectAdd();
			$recordILSUsage->selectAdd('year');
			$recordILSUsage->selectAdd('month');
			$recordILSUsage->orderBy('year, month');
		}
		$interface->assign('columnLabels', $columnLabels);
		$interface->assign('dataSeries', $dataSeries);
		$interface->assign('translateDataSeries', true);
		$interface->assign('translateColumnLabels', false);

		$interface->assign('graphTitle', $title);
		$this->display('usage-graph.tpl', $title);
	}
}