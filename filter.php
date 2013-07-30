<?php
require_once 'panel-include.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="default.css" media="all" rel="stylesheet" type="text/css" />
<title>Filtered Spending</title>
<script type="text/javascript">
    	var filter = {id: "<?php print $id?>", filter: "<?php print $filter?>", offset: <?php print $offset?>};
    </script>
</head>
<body <?php print 'style="width:'.($width ? strval($width).'px' : '100%').';"'?>>
	<div class="row header" style="padding: 10px 0;width:100%;">
		<form action="filter.php" method="GET" style="padding:0 0 0 12px;">
			<select name="filter">
				<option value="yearly">Yearly</option>
				<option value="monthly">Monthly</option>
				<option value="weekly">Weekly</option>
			</select>
			&nbsp;&nbsp;
			<input type="text" name="keywords" size="16" value="<?php echo getString('keywords')?>"/>
			&nbsp;&nbsp;
			<select name="type">
				<option value="company">Filter By Company</option>
				<option value="brand">Filter By Brand</option>
				<option value="media">Filter By Media</option>
			</select>
			<button type="submit" name="submit">Filter</button>
		</form>
	</div>
	<div class="row header">
		<div class="clearfix">
			<div class="column four">
				<a class="navigation" href="panel.php?filter=<?php print $filter?>&id=<?php print $id?>&offset=<?php print ($offset - 1)?>"><img src="images/previous.png" /> </a> <span style="display: inline-block;"><?php print $title?> </span>
				<?php if($offset < 0){?>
				<a class="navigation" href="panel.php?filter=<?php print $filter?>&id=<?php print $id?>&offset=<?php print ($offset + 1)?>"><img src="images/next.png" /> </a>
				<?php }?>
			</div>
			<?php if($width){?>
			<div class="column two">Period Total</div>
			<?php foreach($brandRecords as $brandRecord){?>
			<div class="column three" style="text-transform: capitalize;">
				<?php print strtolower($brandRecord['name'])?>
			</div>
			<?php }?>
			<?php } else {
				print '<div class="column four">There is no data for this period.</div>';
	  			}?>
		</div>
	</div>
	<?php if($width){?>
	<?php foreach($contentRecords as $contentRecord){?>
	<div class="row">
		<div class="column four" style="text-transform: capitalize;">
			<?php 
			print $contentRecord['medianame'];
			?>
		</div>
		<div class="column two">
			<?php
			print number_format($contentRecord['rowtotal'], 2);
			?>
		</div>
		<?php foreach($brandRecords as $brandRecord){?>
		<div class="column three">
			<?php 
			print @number_format($contentRecord[$brandRecord['brandCode']]['total'], 2);
			?>
		</div>
		<?php }?>
	</div>
	<?php }
	?>
	<div class="row" style="font-weight: 900;">
		<div class="column four" style="text-transform: capitalize;">Totals</div>
		<div class="column two">
			<?php print number_format($rowsTotal, 2);?>
		</div>
		<?php foreach($brandRecords as $brandRecord){?>
		<div class="column three">
			<?php print number_format($brandRecord['rowtotal'], 2)?>
		</div>
		<?php }?>
	</div>
	<?php
	  			}
	  			?>
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="js/panel.js"></script>
	<script type="text/javascript">
    	$(document).ready(function(){
    		panel.init();
        });
    </script>
</body>
</html>
