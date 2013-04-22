<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
    	body {
    		overflow:auto;
    		margin:0;
    		padding:0;
    		background:transparent;
    		font-size:100%;
    		font-family: Verdana,Arial,sans-serif;
    	}
    	.row {
    		font-weight:400;
    		font-size:.75em;
    		float:left;
    		clear:both;
    		display:inline-block;
    		border-top:1px solid #dfdfdf;
    	}
    	.row:last-child {
    		border-bottom:1px solid #dfdfdf;
    	}
    	.column {
    		min-height:1px;
    		overflow:hidden;
    		padding:5px 10px;
    		display:inline-block;
    		float:left;
    		border-right:1px solid #dfdfdf;
    		margin-bottom:-900px;
    		padding-bottom:905px;    		
    	}
    	.column:first-child {
    		border-left:1px solid #dfdfdf;
    	}
    	.four {
    		width:200px;
    	}    	
    	.three {
    		width:150px;
    		text-align:right;
    	}
    	.two {
    		width:100px;
    		text-align:right;
    	}
    	.header {
    		background:#f0f0f0;
    	}
    	.header .column {
    		padding-bottom:900px;
    	}
    	.header .two, .header .three {
    		text-align:center;
    	}
    	.navigation {
    		width:24px;
    		height:24px;
    		display:inline-block;
    		display:none;
    	}
    	.navigation:first-child {
    		margin:0 20px 0 0;
    	}
    	.navigation:last-child {
    		margin:0 0 0 20px;
    	}
    </style>
    <title>Spending</title>
  </head>
  <?php
  	require_once 'panel-include.php';
  	$rowsTotal = 0;
  	$records = array();
  	function totalSort($a, $b){
  		if($b['rowtotal'] == $a['rowtotal']) return 0;
  		return $a['rowtotal'] > $b['rowtotal'] ? -1 : 1;
  	}
  	if(isset($contentRecords)){
		foreach($contentRecords as $mediaCode => $contentRecord){
			$total = 0	;
			foreach($contentRecord as $brandRecord){
				$total += $brandRecord['total'];
			}
			$rowsTotal += $total;
			$contentRecord['rowtotal'] = $total;
			$contentRecord['medianame'] = (strlen($mediaRecords[$mediaCode]) < 4 ? $mediaRecords[$mediaCode] : strtolower($mediaRecords[$mediaCode]));
			$records[] = $contentRecord;
			foreach($headerRecords as $headerRecordKey => $headerRecord){
				@$headerRecords[$headerRecordKey]['rowtotal'] += $contentRecord[$headerRecord['brandCode']]['total'];
			}
		}
		$contentRecords = $records;
		usort($contentRecords, "totalSort");
		usort($headerRecords, "totalSort");
  	}  	
  ?>
  <body <?php print 'style="width:'.($width ? strval($width).'px' : '100%').';"'?>>
  		<div class="row header">
  			<div class="column four">
  				<a class="navigation" href="panel.php?filter=<?php print $filter?>&id=<?php print $id?>&offset=<?php print ($offset - 1)?>"><img src="images/previous.png"/></a>
  				<span style="display:inline-block;"><?php print $title?></span>
  				<?php if($offset < 0){?>
  				<a class="navigation" href="panel.php?filter=<?php print $filter?>&id=<?php print $id?>&offset=<?php print ($offset + 1)?>"><img src="images/next.png"/></a>
  				<?php }?>
  			</div>
  			<?php if($width){?>
  			<div class="column two">Period Total</div>
  			<?php foreach($headerRecords as $headerRecord){?>
  				<div class="column three" style="text-transform:capitalize;"><?php print strtolower($headerRecord['name'])?></div>
  			<?php }?>
  			<?php } else {
  				print '<div class="column four">There is no data for '.$title.'.</div>';
  			}?>	
  		</div>
  		<?php if($width){?>
  		<?php foreach($contentRecords as $contentRecord){?>
  		<div class="row">
  			<div class="column four" style="text-transform:capitalize;">
  				<?php 
  				print $contentRecord['medianame'];
  				?>
  			</div>
  			<div class="column two">
  				<?php
  				print number_format($contentRecord['rowtotal'], 2);
  				?>
  			</div>
  			<?php foreach($headerRecords as $headerRecord){?>
  				<div class="column three">
  					<?php 
  					print @number_format($contentRecord[$headerRecord['brandCode']]['total'], 2);
  					?>
  				</div>
  			<?php }?>
  		</div>
  		<?php }
  		?>
  		<div class="row" style="font-weight:900;">
  			<div class="column four" style="text-transform:capitalize;">
  				Totals
  			</div>
  			<div class="column two">
  				<?php print number_format($rowsTotal, 2);?>
  			</div>
  			<?php foreach($headerRecords as $headerRecord){?>
  				<div class="column three">
  					<?php print number_format($headerRecord['rowtotal'], 2)?>
  				</div>
  			<?php }?>
  		</div>
  		<?php
  		}
  		?>
  </body>
</html>  