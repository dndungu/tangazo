<?php

require_once('includes.php');

$companies = dbFetch(dbQuery("SELECT `id`, `name` FROM `radioafrica_crmmigration`.`accounts` GROUP BY `name`"));

foreach($companies as $company){
	dbQuery(sprintf("UPDATE `radioafrica_importer` SET `sugarID` = '%s' WHERE MATCH(`name`) AGAINST('%s')", $company['id'], $company['name']));
	echo "\n".dbAffectedRows();
}