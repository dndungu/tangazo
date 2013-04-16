<?php

set_time_limit(0);

ignore_user_abort(true);

global $start;

$start = microtime(true);

require_once('includes.php');

try {
	switch(postString('do')){
		case 'postsize':
			header('content-type: text/json');
			echo doGetMaxSize();
			break;
		case 'upload':
			echo doHandleUpload();
			break;
		case 'delete':
			echo doDeleteRecords();
			break;
		default:
			echo doBrowseCompanies();
			break;
	}
	exit;
}catch(Exception $e){
	echo $e->getMessage();
}
function doGetMaxSize(){
	$setting = ini_get('post_max_size');
	switch(strtolower($setting[strlen($setting)-1])){
		case 'g':
			return ($setting * 1024 * 1024 * 1024);
			break;
		case 'm':
			return ($setting * 1024 * 1024);
			break;
		case 'k':
			return ($setting * 1024 * 1024);
			break;
	}
}

function doHandleUpload(){
	try {
		foreach($_FILES as $upload){
			$cwd = getcwd();
			$name = $upload['name'];
			$source = $upload['tmp_name'];
			$xlsfile = strtolower("{$cwd}/uploads/{$name}");
			if(!move_uploaded_file($source, $xlsfile)) {
				throw new Exception('Could not move uploaded file');
			}			
			$instruction = "unoconv -v --server 127.0.0.1 --port 5050 -f csv '{$xlsfile}'";
			$exec_result = shell_exec($instruction);
			error_log($instruction);
			$csvfile = str_replace('.xls', '.csv', str_replace('.xlsx', '.csv', $xlsfile));
			if(!file_exists($csvfile)){
				throw new Exception($instruction);
			}
			dbQuery(sprintf("INSERT INTO `msa_import` (`source`, `companies`, `brands`, `sections`, `subSections`, `media`, `campaigns`, `latency`, `creationTime`) VALUES ('%s', 0, 0, 0, 0, 0, 0, 0, %d)", $name, time()));
			$importID = dbInsertId();
			$insert = doStoreRecords($xlsfile, $importID);
			dbQuery(sprintf("UPDATE `msa_import` SET `companies` = %d, `brands` = %d, `sections` = %d, `subSections` = %d, `media` = %d, `campaigns` = %d, `latency` = %d WHERE `ID` = %d", $insert['company'], $insert['brand'], $insert['section'], $insert['subSection'], $insert['media'], $insert['campaign'], getLatency(), $importID));
			$insert['source'] = $name;
			$insert['creationTime'] = date('r');
			$results[] = $insert;
		}
		return json_encode(array('latency' => getLatency(), 'inserts' => $results));
	}catch(Exception $e){
		throw new Exception($e->getMessage());
	}
}

function getLatency(){
	global $start;
	return (microtime(true) - $start) * 1000;
}

function doStoreRecords($xlsfile, $importID){
	$csvfile = str_replace('.xls', '.csv', str_replace('.xlsx', '.csv', $xlsfile));
	$lines = file($csvfile);
	foreach($lines as $index => $line){
		$record = explode(",",  $line);
		if($index < 3) continue;
		if(count($record) < 15) {
			throw new Exception(count($record).' columns instead of 15.');
		}
		$companies[$record[1]] = doCompanyQuery($record, $importID);
		$brands[$record[3]] = doBrandQuery($record, $importID);
		$sections[$record[5]] = doSectionQuery($record, $importID);
		$subSections[$record[7]] = doSubSectionQuery($record, $importID); 
		$media[$record[9]] = doMediaQuery($record, $importID); 
		$campaigns[$record[14]] = doCampaignQuery($record, $importID);
	}
	if(!isset($companies)) return NULL;
	$result['import'] = $importID;
	dbQuery(sprintf("INSERT IGNORE INTO `accounts` (`importID`, `code`, `name`, `creationTime`) VALUES %s", implode(", ", $companies)));
	$result['company'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `msa_brand` (`importID`, `code`, `name`, `creationTime`, `companyCode`) VALUES %s", implode(", ", $brands)));
	$result['brand'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `msa_section` (`importID`, `code`, `name`, `creationTime`) VALUES %s", implode(", ", $sections)));
	$result['section'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `msa_subSection` (`importID`, `code`, `name`, `creationTime`, `sectionCode`) VALUES %s", implode(", ", $subSections)));
	$result['subSection'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `msa_media` (`importID`, `code`, `name`, `creationTime`) VALUES %s", implode(", ", $media)));
	$result['media'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `msa_campaign` (`importID`, `campaignCode`, `companyCode`, `brandCode`, `sectionCode`, `subSectionCode`, `mediaCode`, `amount`, `startDate`, `endDate`, `week`, `creationTime`) VALUES %s", implode(", ", $campaigns)));
	$result['campaign'] = dbAffectedRows();
	return $result;
}

function doCompanyQuery($record, $importID){
	return sprintf("(%d, %d, '%s', UNIX_TIMESTAMP())", $importID, $record[1], dbEscapeString(removeQuotes($record[0])));
}

function doBrandQuery($record, $importID){
	return sprintf("(%d, %d, '%s', UNIX_TIMESTAMP(), %d)", $importID, $record[3], dbEscapeString(removeQuotes($record[2])), $record[1]);
}

function doSectionQuery($record, $importID){
	return sprintf("(%d, %d, '%s', UNIX_TIMESTAMP())", $importID, $record[5], dbEscapeString(removeQuotes($record[4])));
}

function doSubSectionQuery($record, $importID){
	return sprintf("(%d, %d, '%s', UNIX_TIMESTAMP(), %d)", $importID, $record[7], dbEscapeString(removeQuotes($record[6])), $record[5]);
}

function doMediaQuery($record, $importID){
	return sprintf("(%d, %d, '%s', UNIX_TIMESTAMP())", $importID, $record[9], dbEscapeString(removeQuotes($record[8])));
}

function doCampaignQuery($record, $importID){
	return sprintf("(%d, %d, %d, %d, %d, %d, %d, %f, '%s', '%s', %d, UNIX_TIMESTAMP())", $importID, trim($record[14]), trim($record[1]), trim($record[3]), trim($record[5]), trim($record[7]), trim($record[9]), trim($record[10]), getStartDate($record[11]), getEndDate($record[12]), trim($record[13]));
}

function getStartDate($startDate){
	$startDate = removeQuotes($startDate);
	$record = explode('-', $startDate);
	if(count($record) <> 3) {
		$record = explode('/', $startDate);
	}
	if(count($record) <> 3) {
		return $startDate;
	}
	$record[2] = strlen($record[2]) == 4 ? $record[2] : '20'.$record[2];
	$record[2] = str_replace('19', '20', strval($record[2]));
	$month = $record[1];
	$day = $record[0];
	$record[0] = $month;
	$record[1] = $day;
	return date('Y-m-d', strtotime(implode('-', $record)));
}

function getEndDate($endDate){
	$endDate = removeQuotes($endDate);
	$record = explode('-', $endDate);
	if(count($record) != 3) {
		$record = explode('/', $endDate);
	}
	if(count($record) != 3) {
		return $endDate;
	}
	$record[2] = strlen($record[2]) == 4 ? $record[2] : '20'.$record[2];
	$record[2] = str_replace('19', '20', strval($record[2]));
	$month = $record[1];
	$day = $record[0];
	$record[0] = $month;
	$record[1] = $day;
	return date('Y-m-d', strtotime(implode('-', $record)));
}

function removeQuotes($subject){
	return str_replace('"', '', trim($subject));
}

function doBrowseCompanies(){
	$limit = postInteger('limit');
	$limit = $limit ? $limit : 0;
	$offset = postInteger('offset');
	$offset = $offset ? $offset : 100;
	$orderColumn = postString('orderColumn');
	$orderColumn = $orderColumn ? $orderColumn : '`ID`';
	$orderDirection = postString('orderDirection');
	$orderDirection = $orderDirection ? $orderDirection : 'DESC';
	$query = sprintf("SELECT * FROM `accounts` ORDER BY %s %s LIMIT %d, %d", $orderColumn, $orderDirection, $limit , $offset);
	return createJSON(dbFetch(dbQuery($query)));
}

function doDeleteRecords(){
	$primaryKey = postString('table') == 'company' ? 'id' : 'ID';
	dbQuery(sprintf("DELETE FROM `%s` WHERE `%s` IN (%s)", postString('table'), $primaryKey, postString('records')));
	return dbAffectedRows();
}

dbClose();

?>