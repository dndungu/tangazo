<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style.css" media="all" rel="stylesheet" type="text/css" />
<style type="text/css">
.report-content .row {
	padding:0;
	margin:0;
}
.report-content {
	float: left;
	background-color: #FFF;
	display: block;
}

.report-content .header .column,.report-content .column:first-child {
	font-weight: bold;
	font-size: .75em;
	color:#444;
}

.report-content .row {
	border-bottom: 1px solid #DDD;
}

.report-content .column {
	padding: 10px 5px;
	overflow: hidden;
	width: 160px;
	text-align: right;
	border-left: 1px solid #DDD;
	overflow: hidden;
	margin-bottom:-999px;
	padding-bottom:999px;
}

.report-content .column:first-child {
	width: 240px;
	text-align: left;
	border-left: 0 none;
}
</style>
<title>Media Spending :: Company Report</title>
</head>
<body>
	<div class="header row">
		<?php
			$from = isset($_POST['from']) ? $_POST['from'] : date('d-m-Y', (time() - 3*31*24*60*60));
			$to = isset($_POST['to']) ? $_POST['to'] : date('d-m-Y');
		?>
		<h1 class="column grid10of10" style="text-align:left;">Spending	by	Company	Between	<input type="text" size="10" name="from" value="<?php print $from?>" placeholder="15-08-2013"/>	and	<input type="text" size="10" name="to" value="<?php print $to?>" placeholder="16-07-2013"/></h1>
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
	$query[] = sprintf("AND `startDate` BETWEEN '%s' AND '%s'", $from, $to);
	$query[] = 'GROUP BY `companyCode`, `mediaCode`';
	$query[] = 'ORDER BY `amount` DESC';
	$query[] = 'LIMIT 100';
	$records = dbFetch(dbQuery(implode(' ', $query)));
	foreach($records as $record){
		$companyCode = $record['companyCode'];
		$mediaCode = $record['mediaCode'];
		$company = $record['company'];
		$companies[$companyCode] = $company;
		$outlets[$mediaCode] = $record['media'];
		$spending[$companyCode][$mediaCode] = $record['amount'];
	}
	?>
	<div class="report-content" style="width:<?php print (251 + (count($outlets) * 171))?>px;">
		<div class="row header">
			<div class="column">&nbsp;</div>
			<?php foreach($outlets as $mediaCode => $outlet){?>
			<div class="column" mediaCode="<?php print $mediaCode?>">
				<?php print $outlet?>
			</div>
			<?php }?>
		</div>
		<?php foreach($companies as $companyCode => $company){?>
		<div class="row">
			<div class="column">
				<?php print $company?>
			</div>
			<?php foreach($outlets as $mediaCode => $outlet){?>
			<div class="column">
				<?php @print number_format($spending[$companyCode][$mediaCode])?>
			</div>
			<?php }?>
		</div>
		<?php }?>
	</div>
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
</body>
</html>
