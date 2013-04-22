<?php
require_once('includes.php');
$id = getString('id');
$companies = dbFetch(dbQuery(sprintf("SELECT * FROM `accounts` WHERE `id` = '%s' LIMIT 1", $id)));
$width = 0;
if(!is_null($companies)){
	$offset = (Integer) getString('offset');
	$filter = getString('filter');
	switch($filter){
		case 'weekly':
			$t = (time() + ($offset * (7*24*60*60)));
// 			$title = 'WEEK ' . date('W, Y', $t);
			$title = '<span>Week<input type="text" name="week" value="'.weekOfMonth($t).'" size="1"/></span>';
			$title .= '<span>Month<input type="text" name="month" value="'.date('n', $t).'" size="2"/></span>';
			$title .= '<span>Year<input type="text" name="year" value="'.date('Y', $t).'" size="4"/></span>';
			$timeQuery = sprintf("AND `week` = WEEKOFYEAR(FROM_UNIXTIME(%d))", $t);
			break;
		case 'monthly':
			$t = (time() + ($offset * (31*24*60*60)));
			$title = date('M Y', $t);
			$timeQuery = sprintf("AND YEAR(`startDate`) = YEAR(FROM_UNIXTIME(%d)) AND MONTH(`startDate`) = MONTH(FROM_UNIXTIME(%d))", $t, $t);
			break;
		case 'yearly':
			$t = (time() + ($offset * (365*24*60*60)));
			$title = date('Y', $t);
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
	$contentQuery[] = "ORDER BY `total` DESC";
	$contentResults = dbQuery(implode(" ", $contentQuery));
	if($contentResults->num_rows) {
		while($row = $contentResults->fetch_assoc()){
			$mediaRecords[$row['mediaCode']] = $row['media'];
			$contentRecords[$row['mediaCode']][$row['brandCode']] = $row;
		}
		$headerQuery[] = "SELECT `msa_campaign`.`brandCode`, `msa_brand`.`name`, `msa_campaign`.`mediaCode` FROM `msa_campaign`";
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
?>