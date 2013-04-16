<?php
require_once('includes.php');
$companies = dbFetch(dbQuery(sprintf("SELECT * FROM `accounts` WHERE `id` = '%s' LIMIT 1", getString('id'))));
$width = 0;
if(!is_null($companies)){
	switch(getString('filter')){
		case 'weekly':
			$timeQuery = "AND `week` = WEEKOFYEAR(NOW())";
			break;
		case 'monthly':
			$timeQuery = "AND YEAR(`startDate`) = YEAR(NOW()) AND MONTH(`startDate`) = MONTH(NOW())";
			break;
		case 'yearly':
			$timeQuery = "AND YEAR(`startDate`) = YEAR(NOW())";
			break;
		default:
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
	print (implode(" ", $contentQuery));die();
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