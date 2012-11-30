<?php

require_once('includes.php');

$companies = dbFetch(dbQuery("SELECT `id`, `name` FROM `radioafrica_crmmigration`.`accounts` GROUP BY `name`"));

foreach($companies as $company){
	dbQuery(sprintf("UPDATE `radioafrica_importer`.`company` SET `sugarID` = '%s' WHERE `name` LIKE 's%'", $company['id'], $company['name']));
	echo "\n".dbAffectedRows();
}