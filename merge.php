<?php

set_time_limit(0);

ignore_user_abort(true);

require_once('includes.php');

$results = array();

$accounts = dbFetch(dbQuery("SELECT `name` FROM `accounts` GROUP BY `name` HAVING COUNT(*) > 1"));

foreach($accounts as $account){
	$name = $account['name'];
	$updateQuery = sprintf("UPDATE `accounts` SET `code` = (SELECT `code` FROM `accounts` WHERE `name` = '%s' AND `code` IS NOT NULL LIMIT 1) WHERE `code` IS NULL AND `name` = '%s' LIMIT 1", $name, $name);
	$results['update'][] = $updateQuery;
	//dbQuery($updateQuery);
	//$results[$name]['update'][] = dbAffectedRows();
	$deleteQuery = sprintf("DELETE FROM `accounts` WHERE `name` = '%s' AND `creationTime` > 0 LIMIT 1", $name);
	$results['delete'][] = $deleteQuery;
	//dbQuery($deleteQuery);
	//$results[$name]['delete'][] = dbAffectedRows();
}

print json_encode($results);