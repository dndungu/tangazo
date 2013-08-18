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
				<li><a href="filter.php">Filter</a></li>
				<li><a href="extended-filter.php">Extended Filter</a></li>
				<li><a href="spending.php">Spending</a></li>
				<li><a href="companies.php">Companies</a></li>
				<li><a href="brands.php">Brands</a></li>
				<li><a href="media.php" class="current">Media</a></li>
				<li><a href="sections.php">Sections</a></li>
				<li><a href="subsections.php">Sub Sections</a></li>
			</ul>
		</div>
		<div class="content">
			<div class="gridColumns">
				<div class="row">
					<div class="column grid1of10 align-center">
						<input type="button" id="deleteRecords" name="media" value="DELETE"/>
					</div>
				</div>			
				<div class="column grid1of10 align-center">
					<input name="campaign" id="selectall" type="checkbox"/>
				</div>
				<div class="column grid1of10">
					Code
				</div>
				<div class="column grid6of10">
					Name
				</div>
				<div class="column grid2of10">
					Date Created
				</div>
			</div>
			<?php
			require_once('includes.php');
			$page = getInteger('p');
			$import = getInteger('i');
			$page = $page ? $page : 1;
			$offset = (($page - 1) * $config['PAGE_SIZE']);
			$query[] = "SELECT * FROM `msa_media`";
			if($import){
				$query[] = sprintf("WHERE `msa_media`.`importID` = %d", $import);
			}			
			$query[] = "ORDER BY `msa_media`.`name` ASC, `msa_media`.`ID` DESC";
			if(!$import){
				$query[] = sprintf("LIMIT %d, %d", $offset, $config['PAGE_SIZE']);
			}
			$recordsCount = dbFetch(dbQuery("SELECT COUNT(*) AS `count` FROM `msa_media`"));
			$pages = $recordsCount[0]['count'] / $config['PAGE_SIZE'];
			$records = dbFetch(dbQuery(implode(" ", $query))); 
			?>
			<div class="row gridRecords">
				<?php foreach($records as $record){?>
				<div class="row">
					<div class="column grid1of10 align-center">
						<input name="selectone" class="selectone" type="checkbox" value="<?php print $record['ID']?>"/>
					</div>
					<div class="column grid1of10">
						<?php print $record['code']?>
					</div>
					<div class="column grid6of10">
						<?php print $record['name']?>
					</div>
					<div class="column grid2of10">
						<?php print date('r', $record['creationTime'])?>
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