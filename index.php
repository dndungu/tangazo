<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="style.css" media="all" rel="stylesheet" type="text/css" />
    <title>Media Spending :: Upload</title>
  </head>
  <body>
  	<div class="container">
  		<div class="header row">
  			<h1 class="column grid10of10">Media Spending</h1>
  			<ul class="column grid10of10">
  				<li><a href="index.php" class="current">Upload</a></li>
  				<li><a href="spending.php">Spending</a></li>
  				<li><a href="companies.php">Companies</a></li>
  				<li><a href="brands.php">Brands</a></li>
  				<li><a href="media.php">Media</a></li>
  				<li><a href="sections.php">Sections</a></li>
  				<li><a href="sections.php">Sub Sections</a></li>
  			</ul>
  		</div>
  		<div class="content row">
  			<form class="column grid4of10">
  				<h2>Import Form</h2>
  				<p>Synovate Media Spending Database.</p>
  				<p>Use this form to upload new data.</p>
  				<label class="errorBox"></label>
  				<label class="infoBox"></label>
  				<label>
					<input type="file" multiple name="spendfile" id="spendfile"/>
					<br/>
					<span class="progress"></span>
  				</label>
  				<p><input type="button" id="uploadspendfile" value="UPLOAD"/></p>
  			</form>  			
  			<div class="column grid6of10">
  				<h2>Latest Imports</h2>
  				<div class="processing column grid10of10"></div>
  				<div class="column grid10of10 imports">
  					<?php
  						require_once('includes.php');
  						$imports = dbFetch(dbQuery("SELECT * FROM `import` ORDER BY `ID` DESC LIMIT 0, 15"));
  					?>
  					<?php if(!is_null($imports)){?>
  						<?php foreach($imports as $import){?>
							<div class="row import">
								<div class="column grid10of10"><?php echo $import['source']?></div>
								<div class="column grid3of10">Media Expenditure</div><div class="column grid2of10"><?php echo $import['campaigns']?></div>
								<div class="column grid3of10">New Companies</div><div class="column grid2of10"><?php echo $import['companies']?></div>
								<div class="column grid3of10">New Brands</div><div class="column grid2of10"><?php echo $import['brands']?></div>
								<div class="column grid3of10">New Sections</div><div class="column grid2of10"><?php echo $import['sections']?></div>
								<div class="column grid3of10">New Sub Sections</div><div class="column grid2of10"><?php echo $import['subSections']?></div>
								<div class="column grid3of10">New Media</div><div class="column grid2of10"><?php echo $import['media']?></div>
								<div class="column grid3of10">Import Time</div><div class="column grid7of10"><?php echo date('r', $import['creationTime'])?></div>
							</div>  							
  						<?php }?>
  					<?php }?>
  				</div>
  			</div>
  		</div>
  		<div class="footer row">
  			<span class="column grid10of10">&copy; 2012 Radio Africa Group Limited</span>
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
