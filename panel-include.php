<?php
require_once('includes.php');
$id = getString('id');
$companies = dbFetch(dbQuery(sprintf("SELECT * FROM `accounts` WHERE `id` = '%s' LIMIT 1", $id)));
$width = 0;
if(!is_null($companies)){
	$offset = (Integer) getString('offset');
	$filter = getString('filter');
	$startYear = dbFetch(dbQuery("SELECT YEAR(`startDate`) AS `Y` FROM `msa_campaign` HAVING `Y` > 0 ORDER BY `Y` ASC LIMIT 1"));
	$startYear = $startYear[0]['Y'];
	$currentYear = date('Y');
	switch($filter){
		case 'weekly':
			$t = (time() + ($offset * (7*24*60*60)));
			$currentWeek = date('W');
			$navigator[] = '<span class="navigator">Week<select name="week" default="'.$currentWeek.'" class="jumpto">';
			for($i = 52; $i >= 1; $i--){
				$selected = $i == intval(date('W', $t)) ? ' selected="selected"' : '';
				$navigator[] = '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			}
			$navigator[] = '</select></span>';
						
			$navigator[] = '<span class="navigator">Year<select name="year" default="'.$currentYear.'" class="jumpto">';
			for($i = $currentYear; $i >= $startYear; $i--){
				$selected = $i == date('Y', $t) ? ' selected="selected"' : '';
				$navigator[] = '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			}
			$navigator[] = '</select></span>';
						
			$title = implode("\n", $navigator);
			$timeQuery = sprintf("AND `week` = WEEKOFYEAR(FROM_UNIXTIME(%d)) AND YEAR(`startDate`) = YEAR(FROM_UNIXTIME(%d))", $t, $t);
			break;
		case 'monthly':
			$t = (time() + ($offset * (31*24*60*60)));
			$currentMonth = date('n');
			$navigator[] = '<span class="navigator">Month<select name="month" default="'.$currentMonth.'" class="jumpto">';
			for($i = 12; $i >= 1; $i--){
				$selected = $i == date('n', $t) ? ' selected="selected"' : '';
				$navigator[] = '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			}
			$navigator[] = '</select></span>';
			$navigator[] = '<span class="navigator">Year<select name="year" default="'.$currentYear.'" class="jumpto">';
			for($i = $currentYear; $i >= $startYear; $i--){
				$selected = $i == date('Y', $t) ? ' selected="selected"' : '';
				$navigator[] = '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			}
			$navigator[] = '</select></span>';
			$title = implode("\n", $navigator);
			$timeQuery = sprintf("AND YEAR(`startDate`) = YEAR(FROM_UNIXTIME(%d)) AND MONTH(`startDate`) = MONTH(FROM_UNIXTIME(%d))", $t, $t);
			break;
		case 'yearly':
			$t = (time() + ($offset * (365*24*60*60)));
			$navigator[] = '<span class="navigator">Year<select name="year" default="'.$currentYear.'" class="jumpto">';
			for($i = $currentYear; $i >= $startYear; $i--){
				$selected = $i == date('Y', $t) ? ' selected="selected"' : '';
				$navigator[] = '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
			}
			$navigator[] = '</select></span>';			
			$title = implode("\n", $navigator);
			$timeQuery = sprintf("AND YEAR(`startDate`) = YEAR(FROM_UNIXTIME(%d))", $t);
			break;
		default:
			$title = 'All Time';
			$timeQuery = "AND 1 = 1";
			break;
	}	
	$contentQuery[] = "SELECT `msa_media`.`name` AS `media`, `msa_brand`.`name` AS `brand`, `msa_campaign`.`mediaCode` AS `mediaCode`, `msa_campaign`.`brandCode` AS `brandCode`, SUM(`amount`) AS `total`, `week` FROM `msa_campaign`";
	$contentQuery[] = "LEFT JOIN `msa_media` ON `msa_campaign`.`mediaCode` = `msa_media`.`code`";
	$contentQuery[] = "LEFT JOIN `msa_brand` ON `msa_campaign`.`brandCode` = `msa_brand`.`code`";
	$contentQuery[] = "LEFT JOIN `accounts` ON `msa_campaign`.`companyCode` = `accounts`.`code`";
	$contentQuery[] = "WHERE `amount` > 0";
	$contentQuery[] = $timeQuery;
	$contentQuery[] = sprintf("AND `msa_campaign`.`companyCode` = %d", $companies[0]['code']);
	$contentQuery[] = "GROUP BY `mediaCode`, `brandCode`";
	$contentQuery[] = "HAVING `total` > 0";
	$contentQuery[] = "ORDER BY `total` DESC";
	$contentResults = dbQuery(implode(" ", $contentQuery));
	if($contentResults->num_rows) {
		while($row = $contentResults->fetch_assoc()){
			$mediaRecords[$row['mediaCode']] = $row['media'];
			$contentRecords[$row['mediaCode']][$row['brandCode']] = $row;
		}
		$headerQuery[] = "SELECT `msa_campaign`.`brandCode`, `msa_brand`.`name` FROM `msa_campaign`";
		$headerQuery[] = "LEFT JOIN `msa_brand` ON (`msa_campaign`.`brandCode` = `msa_brand`.`code`)";
		$headerQuery[] = "LEFT JOIN `accounts` ON (`msa_campaign`.`companyCode` = `accounts`.`code`)";
		$contentQuery[] = $timeQuery;
		$headerQuery[] = "WHERE `amount` > 0";
		$headerQuery[] = sprintf("AND `msa_campaign`.`companyCode` = %d", $companies[0]['code']);
		$headerQuery[] = "GROUP BY `brandCode`";
		$headerRecords = dbFetch(dbQuery(implode(" ", $headerQuery)));
		$width = ((count($headerRecords)) * 180) + 600;
	}
}

$rowsTotal = 0;
$records = array();
function totalSort($a, $b){
	if($b['rowtotal'] == $a['rowtotal']) return 0;
	return $a['rowtotal'] > $b['rowtotal'] ? -1 : 1;
}
if(isset($contentRecords)){
	foreach($contentRecords as $mediaCode => $contentRecord){
		$total = 0	;
		foreach($contentRecord as $brandRecord){
			$total += $brandRecord['total'];
		}
		$rowsTotal += $total;
		$contentRecord['rowtotal'] = $total;
		$contentRecord['medianame'] = (strlen($mediaRecords[$mediaCode]) < 4 ? $mediaRecords[$mediaCode] : strtolower($mediaRecords[$mediaCode]));
		$records[] = $contentRecord;
		foreach($headerRecords as $headerRecordKey => $headerRecord){
			@$headerRecords[$headerRecordKey]['rowtotal'] += $contentRecord[$headerRecord['brandCode']]['total'];
		}
	}
	$contentRecords = $records;
	usort($contentRecords, "totalSort");
	usort($headerRecords, "totalSort");
}
?>