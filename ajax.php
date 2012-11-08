<?php

date_default_timezone_set('Africa/Nairobi');

ignore_user_abort(true);

set_time_limit(0);

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
			$instruction = "unoconv -f csv '{$xlsfile}'";
			$exec_result = shell_exec($instruction);
			error_log("\n***********\n{$instruction} : $exec_result\n***********");
			$inserts = doStoreRecords($xlsfile);
			$results[] = $inserts;
			$results[0]['source'] = $name;
			$results[0]['creationTime'] = date('r');
			dbQuery(sprintf("INSERT INTO `import` (`source`, `companies`, `brands`, `sections`, `subSections`, `media`, `campaigns`, `latency`, `creationTime`) VALUES ('%s', %d, %d, %d, %d, %d, %d, %d, %d)", $name, $inserts['company'], $inserts['brand'], $inserts['section'], $inserts['subSection'], $inserts['media'], $inserts['campaign'], getLatency(), time()));
		}
		return json_encode(array('latency' => getLatency(), 'inserts' => $results), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}catch(Exception $e){
		throw new Exception($e->getMessage());
	}
}

function getLatency(){
	global $start;
	return (microtime(true) - $start) * 1000;
}

function doStoreRecords($xlsfile){
	$csvfile = str_replace('.xls', '.csv', $xlsfile);
	$lines = file($csvfile);
	foreach($lines as $index => $line){
		$record = explode(",",  $line);
		if($index < 3 || count($record) < 15) continue;
		$companies[$record[1]] = doCompanyQuery($record);
		$brands[$record[3]] = doBrandQuery($record);
		$sections[$record[5]] = doSectionQuery($record);
		$subSections[$record[7]] = doSubSectionQuery($record); 
		$media[$record[9]] = doMediaQuery($record); 
		$campaigns[$record[14]] = doCampaignQuery($record);
	}
	if(!isset($companies)) return NULL;
	dbQuery(sprintf("INSERT IGNORE INTO `company` (`code`, `name`, `creationTime`) VALUES %s", implode(", ", $companies)));
	$result['company'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `brand` (`code`, `name`, `creationTime`, `companyCode`) VALUES %s", implode(", ", $brands)));
	$result['brand'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `section` (`code`, `name`, `creationTime`) VALUES %s", implode(", ", $sections)));
	$result['section'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `subSection` (`code`, `name`, `creationTime`, `sectionCode`) VALUES %s", implode(", ", $subSections)));
	$result['subSection'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `media` (`code`, `name`, `creationTime`) VALUES %s", implode(", ", $media)));
	$result['media'] = dbAffectedRows();
	dbQuery(sprintf("INSERT IGNORE INTO `campaign`(`campaignCode`, `companyCode`, `brandCode`, `sectionCode`, `subSectionCode`, `mediaCode`, `amount`, `startDate`, `endDate`, `week`, `creationTime`) VALUES %s", implode(", ", $campaigns)));
	$result['campaign'] = dbAffectedRows();
	return $result;
}

function doCompanyQuery($record){
	return sprintf("(%d, '%s', UNIX_TIMESTAMP())", $record[1], dbEscapeString(trim($record[0])));
}

function doBrandQuery($record){
	return sprintf("(%d, '%s', UNIX_TIMESTAMP(), %d)", $record[3], dbEscapeString(trim($record[2])), $record[1]);
}

function doSectionQuery($record){
	return sprintf("(%d, '%s', UNIX_TIMESTAMP())", $record[5], dbEscapeString(trim($record[4])));
}

function doSubSectionQuery($record){
	return sprintf("(%d, '%s', UNIX_TIMESTAMP(), %d)", $record[7], dbEscapeString(trim($record[6])), $record[5]);
}

function doMediaQuery($record){
	return sprintf("(%d, '%s', UNIX_TIMESTAMP())", $record[9], dbEscapeString(trim($record[8])));
}

function doCampaignQuery($record){
	return sprintf("(%d, %d, %d, %d, %d, %d, %f, '%s', '%s', %d, UNIX_TIMESTAMP())", $record[14], $record[1], $record[3], $record[5], $record[7], $record[9], $record[10], trim($record[11]), trim($record[12]), $record[13]);
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
	$query = sprintf("SELECT * FROM `company` ORDER BY %s %s LIMIT %d, %d", $orderColumn, $orderDirection, $limit , $offset);
	return createJSON(dbFetch(dbQuery($query)));
}

function doDeleteRecords(){
	dbQuery(sprintf("DELETE FROM `%s` WHERE `ID` IN (%s)", postString('table'), postString('records')));
	return dbAffectedRows();
}

dbClose();

?>