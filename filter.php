<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style.css" media="all" rel="stylesheet" type="text/css" />
<style type="text/css">
.content {
	overflow:auto;
}
.content .header .column, .column:first-child {
	font-weight:bold;
	font-size:.75em;
}
.content .row {
	border-bottom:1px solid #DDD;
}
.content .column {
	width: 90px;
	border-right:1px solid #DDD;
}
.content .column:first-child {
	width:180px;
}
</style>
<title>Media Spending :: Imports</title>
</head>
<body>
	<div class="container">
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
		$query[] = 'GROUP BY `companyCode`, `mediaCode` LIMIT 10';
		$records = dbFetch(dbQuery(implode(' ', $query)));
		foreach($records as $record){
			$companies[$record['companyCode']] = $record['company'];
			$media[$record['mediaCode']] = $record['media'];
			$spending[$record['companyCode']][$record['mediaCode']] = $record['amount'];
		}
		$columns = count($media);
		?>
		<div class="content">
			<div style="display:block;width:<?php print (200 + ($columns * 110))?>px;">
				<div class="row header">
					<div class="column">&nbsp;</div>
					<?php foreach($media as $outlet){?>
					<div class="column">
						<?php print $outlet?>
					</div>
					<?php }?>
				</div>
				<?php foreach($companies as $company){?>
					<div class="row">
						<div class="column">
							<?php print $company?>
						</div>
						<?php foreach($media as $outlet){?>
						<div class="column">0.00</div>
						<?php }?>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
</body>
</html>
