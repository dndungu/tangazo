<?php
require_once('includes.php');
$companies = dbFetch(dbQuery(sprintf("SELECT * FROM `company` WHERE MATCH(`name`) AGAINST('%s') LIMIT 1", getString('company'))));
$width = 0;
if(!is_null($companies)){
	switch(getString('filter')){
		case 'week':
			$timeQuery = "AND `week` = WEEKOFYEAR(NOW())";
			break;
		case 'month':
			$timeQuery = "AND YEAR(`startDate`) = YEAR(NOW()) AND MONTH(`startDate`) = MONTH(NOW())";
			break;
		case 'year':
			$timeQuery = "AND YEAR(`startDate`) = YEAR(NOW())";
			break;
		default:
			$timeQuery = "AND 1 = 1";
			break;
	}	
	$contentQuery[] = "SELECT `media`.`name` AS `media`, `brand`.`name` AS `brand`, `campaign`.`mediaCode` AS `mediaCode`, `campaign`.`brandCode` AS `brandCode`, SUM(`amount`) AS `total`, `week` FROM `campaign`";
	$contentQuery[] = "LEFT JOIN `media` ON `campaign`.`mediaCode` = `media`.`code`";
	$contentQuery[] = "LEFT JOIN `brand` ON `campaign`.`brandCode` = `brand`.`code`";
	$contentQuery[] = "LEFT JOIN `company` ON `campaign`.`companyCode` = `company`.`code`";
	$contentQuery[] = "WHERE `amount` > 0";
	$contentQuery[] = $timeQuery;
	$contentQuery[] = sprintf("AND `campaign`.`companyCode` = %d", $companies[0]['code']);
	$contentQuery[] = "GROUP BY `mediaCode`, `brandCode`";
	$contentQuery[] = "ORDER BY `total` DESC";
	error_log(implode(" ", $contentQuery));
	$contentResults = dbQuery(implode(" ", $contentQuery));
	if($contentResults->num_rows) {
		while($row = $contentResults->fetch_assoc()){
			$mediaRecords[$row['mediaCode']] = $row['media'];
			$contentRecords[$row['mediaCode']][$row['brandCode']] = $row;
		}
		$headerQuery[] = "SELECT `campaign`.`brandCode`, `brand`.`name`, `campaign`.`mediaCode` FROM `campaign`";
		$headerQuery[] = "LEFT JOIN `brand` ON `campaign`.`brandCode` = `brand`.`code`";
		$headerQuery[] = "LEFT JOIN `company` ON `campaign`.`companyCode` = `company`.`code`";
		$contentQuery[] = $timeQuery;
		$headerQuery[] = "WHERE `amount` > 0";
		$headerQuery[] = sprintf("AND `campaign`.`companyCode` = %d", $companies[0]['code']);
		$headerQuery[] = "GROUP BY `brandCode`";
		$headerRecords = dbFetch(dbQuery(implode(" ", $headerQuery)));
		$width = ((count($headerRecords)) * 174) + 300;
	}
}
?>