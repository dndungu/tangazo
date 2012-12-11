<?php

set_time_limit(0);

ignore_user_abort(true);

require_once('includes.php');

dbQuery("UPDATE `radioafrica_crmmigration`.`accounts` SET `synovateCode` = (SELECT `code` FROM `radioafrica_importer`.`company` WHERE LOWER(`name`) = LOWER(`radioafrica_crmmigration`.`accounts`.`name`) LIMIT 1) WHERE `synovateCode` IS NULL;");

$companies = dbFetch(dbQuery("SELECT * FROM `radioafrica_importer`.`company` WHERE `code` NOT IN (SELECT `synovateCode` FROM `radioafrica_crmmigration`.`accounts` GROUP BY `synovateCode`)"));

if(is_null($companies)) die('no companies to update');

$query[] = "INSERT INTO `radioafrica_crmmigration` (`id`, synovateCode`, `name`, `date_entered`, `date_modified`) VALUES";

$datetime = date();

foreach($companies as $company){
	$query[] = sprintf("('%s', '%d', '%s', NOW(), NOW())", createGuid(), $company['code'], $company['name']);
}

dbQuery(implode(' ', $query));