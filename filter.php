<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style.css" media="all" rel="stylesheet" type="text/css" />
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
			$query[] = 'SELECT SUM(`msa_campaign`.`amount`) AS `amount`, `accounts`.`name` AS `company`, `msa_media`.`name` AS `media`';
			$query[] = 'FROM `msa_campaign`';
			$query[] = 'JOIN `accounts` ON (`msa_campaign`.`companyCode` = `accounts`.`code`)';
			$query[] = 'JOIN `msa_media` ON (`msa_campaign`.`mediaCode` = `msa_media`.`code`)';
			$records = dbFetch(dbQuery(implode(' ', $query)));
		?>
		<div class="content">
			
			<?php print_r($records);?>
			
		</div>
	</div>
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="js/tangazo.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
    		tangazo.init();
        });
    </script> 	
</body>
</html>