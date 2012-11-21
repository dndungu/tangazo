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
    </style>
    <title>Week Spending</title>
  </head>
  <?php
  	require_once 'panel-include.php';
  ?>
  <body <?php print 'style="width:'.($width ? strval($width).'px' : '100%').';"'?>>
  		<?php if($width){?>
  		<div class="row header">
  			<div class="column four">Station</div>
  			<div class="column two">Week Totals</div>
  			<?php foreach($headerRecords as $headerRecord){?>
  				<div class="column three" style="text-transform:capitalize;"><?php print strtolower($headerRecord['name'])?></div>
  			<?php }?>	  			
  		</div>
  		<?php foreach($contentRecords as $mediaCode => $contentRecord){?>
  		<div class="row">
  			<div class="column four" style="text-transform:capitalize;">
  				<?php 
  				print (strlen($mediaRecords[$mediaCode]) < 4 ? $mediaRecords[$mediaCode] : strtolower($mediaRecords[$mediaCode]));
  				?>
  			</div>
  			<div class="column two">
  				<?php
  				$total = 0	;
  				foreach($contentRecord as $brandRecord){
  					$total += $brandRecord['total'];
  				}
  				print number_format($total, 2);
  				?>
  			</div>
  			<?php foreach($headerRecords as $headerRecord){?>
  				<div class="column three">
  					<?php 
  					print @number_format($contentRecords[$mediaCode][$headerRecord['brandCode']]['total'], 2);
  					?>
  				</div>
  			<?php }?>
  		</div>
  		<?php }
  		}else{
			print 'There are no records for ' . getString('company');
			switch(getString('filter')){
				case 'week':
					print ' this week.';
					break;
				case 'month ':
					print 'this month.';
					break;
				case 'year':
					print ' this year.';
					break;
			}  		
  		}?>
  </body>
</html>  