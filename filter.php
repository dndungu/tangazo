<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style.css" media="all" rel="stylesheet" type="text/css" />
<style type="text/css">
.report-content {
	float:left;
	background-color:#FFF;
	display:block;
}
.report-content .header .column, .column:first-child {
	font-weight:bold;
	font-size:.75em;
}
.report-content .row {
	border-bottom:1px solid #DDD;
}

.report-content .column {
	overflow:hidden;
	margin-bottom:-9999px;
	padding-bottom:9999px;
	width: 120px;
	text-align:left;
	border-left:1px solid #DDD;
}
.report-content .column:first-child {
	width:240px;
	border-left:0 none;
}
</style>
<title>Media Spending :: Company Report</title>
</head>
<body>
	<div class="header row">
		<h1 class="column grid10of10">Media Spending</h1>
		<ul class="column grid10of10">
			<li><a href="index.php">Upload</a></li>
			<li><a href="filter.php" class="current">Filter</a></li>
			<li><a href="spending.php">Spending</a></li>
			<li><a href="companies.php">Companies</a></li>
			<li><a href="brands.php">Brands</a></li>
			<li><a href="media.php">Media</a></li>
			<li><a href="sections.php">Sections</a></li>
			<li><a href="subsections.php">Sub Sections</a></li>
		</ul>
	</div>
	<?php
	require_once('includes.php');
	$query[] = 'SELECT SUM(`msa_campaign`.`amount`) AS `amount`, `accounts`.`name` AS `company`, `companyCode`, `msa_media`.`name` AS `media`, `mediaCode`';
	$query[] = 'FROM `msa_campaign`';
	$query[] = 'JOIN `accounts` ON (`msa_campaign`.`companyCode` = `accounts`.`code`)';
	$query[] = 'JOIN `msa_media` ON (`msa_campaign`.`mediaCode` = `msa_media`.`code`)';
	$query[] = 'WHERE `msa_campaign`.`amount` > 0';
	$query[] = 'GROUP BY `companyCode`, `mediaCode`';
	$query[] = 'ORDER BY `amount` DESC';
	$query[] = 'LIMIT 100';
	$records = dbFetch(dbQuery(implode(' ', $query)));
	foreach($records as $record){
		$companies[$record['companyCode']] = $record['company'];
		$media[$record['mediaCode']] = $record['media'];
		$spending[$record['companyCode']][$record['mediaCode']] = $record['amount'];
	}
	$columns = count($media);
	?>
	<div class="report-content" style="width:<?php print (200 + ($columns * 220))?>px;">
		<div class="row header">
			<div class="column">&nbsp;</div>
			<?php foreach($media as $outlet){?>
			<div class="column">
				<?php print $outlet?>
			</div>
			<?php }?>
		</div>
		<?php foreach($companies as $companyCode => $company){?>
			<div class="row">
				<div class="column">
					<?php print $company?>
				</div>
				<?php foreach($media as $mediaCode => $outlet){?>
				<div class="column"><?php @print number_format($spending[$companyCode][$mediaCode])?></div>
				<?php }?>
			</div>
		<?php }?>
	</div>
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
</body>
</html>
