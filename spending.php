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
				<li><a href="spending.php" class="current">Spending</a></li>
				<li><a href="companies.php">Companies</a></li>
				<li><a href="brands.php">Brands</a></li>
				<li><a href="media.php">Media</a></li>
				<li><a href="sections.php">Sections</a></li>
				<li><a href="subsections.php">Sub Sections</a></li>
			</ul>
		</div>
		<div class="content">
			<div class="gridColumns">
				<div class="row">
					<div class="column grid1of10 align-center">
						<input type="button" id="deleteRecords" name="campaign" value="DELETE"/>
					</div>
				</div>			
				<div class="column grid1of10 align-center">
					<input name="campaign" id="selectall" type="checkbox"/>
				</div>
				<div class="column grid2of10">
					Company
				</div>
				<div class="column grid2of10">
					Brand
				</div>
				<div class="column grid2of10">
					Media
				</div>
				<div class="column grid1of10">
					Section
				</div>
				<div class="column grid1of10">
					Week
				</div>
				<div class="column grid1of10">
					Amount
				</div>
			</div>
			<?php
			require_once('includes.php');
			$page = getInteger('p');
			$import = getInteger('i');
			$page = $page ? $page : 1;
			$offset = (($page - 1) * $config['PAGE_SIZE']);
			$query[] = "SELECT `campaign`.`ID` AS `ID`, `company`.`name` AS `company`, `brand`.`name` AS `brand`, `media`.`name` AS `media`, `section`.`name` AS `section`, `amount`, `week` FROM `campaign`";
			$query[] = "LEFT JOIN `company` ON (`campaign`.`companyCode` = `company`.`code`)";
			$query[] = "LEFT JOIN `brand` ON (`campaign`.`brandCode` = `brand`.`code`)";
			$query[] = "LEFT JOIN `media` ON (`campaign`.`mediaCode` = `media`.`code`)";
			$query[] = "LEFT JOIN `section` ON (`campaign`.`sectionCode` = `section`.`code`)";
			if($import){
				$query[] = sprintf("WHERE `campaign`.`importID` = %d", $import);
			}
			$query[] = "ORDER BY `company`.`name` ASC, `campaign`.`ID` DESC";
			if(!$import){
				$query[] = sprintf("LIMIT %d, %d", $offset, $config['PAGE_SIZE']);
			}
			$recordsCount = dbFetch(dbQuery("SELECT COUNT(*) AS `count` FROM `campaign` {$query[1]} {$query[2]} {$query[3]} {$query[4]}"));
			$pages = $recordsCount[0]['count'] / $config['PAGE_SIZE'];
			$records = dbFetch(dbQuery(implode(" ", $query))); 
			?>
			<div class="row gridRecords">
				<?php foreach($records as $record){?>
				<div class="row">
					<div class="column grid1of10 align-center">
						<input name="selectone" class="selectone" type="checkbox" value="<?php print $record['ID']?>"/>
					</div>
					<div class="column grid2of10">
						<?php print $record['company']?>
					</div>
					<div class="column grid2of10">
						<?php print $record['brand']?>
					</div>
					<div class="column grid2of10">
						<?php print $record['media']?>
					</div>
					<div class="column grid1of10">
						<?php print $record['section']?>
					</div>
					<div class="column grid1of10">
						<?php print $record['week']?>
					</div>
					<div class="column grid1of10">
						<?php print $record['amount']?>
					</div>					
				</div>
				<?php }?>
			</div>
			<div class="row gridFooter">
				<?php if(!$import){?>
				<div class="column grid10of10">
					<?php 
					if($pages > 1 && $page > 1){
						print '<a href="?p='.($page-1).'">PREVIOUS</a>';
					}
					?>
					<?php print $offset?> to <?php print count($records)?> of <?php print $recordsCount[0]['count']?>
					<?php
					if($pages > 1){
						if($page < $pages) {
							print '<a href="?p='.($page+1).'">NEXT</a>';
						}
					}
					?>
				</div>
				<?php }?>
			</div>
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