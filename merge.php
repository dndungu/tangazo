<?php

require_once('includes.php');

$companies = dbFetch(dbQuery("SELECT `id`, `name` FROM `radioafrica_crmmigration`.`accounts` GROUP BY `name`"));

foreach($companies as $company){
	dbQuery(sprintf("UPDATE `radioafrica_importer`.`company` SET `sugarID` = '%s' WHERE MATCH(`name`) AGAINST('%s') LIMIT 1", $company['id'], $company['name']));
	echo "\n".dbAffectedRows();
}