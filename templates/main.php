<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//DE">
<html>
<head>
<title>Mem'n'Learn</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8"> 

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="includes/jQuery/jquery-3.1.js" type="text/javascript"></script>
<script src="includes/jQuery/jquery.flip.min.js" type="text/javascript"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="includes/bootstrap/js/bootstrap.min.js"
	type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript">
$(document).data('loggedIn', <?php $u = new UserController(); echo ($u->isLoggedIn())?'true':'false'; ?>);
</script>
<script src="src/js/menu.js" type="text/javascript"></script>
<script src="src/js/game.js" type="text/javascript"></script>
<script src="src/js/init.js" type="text/javascript"></script>
<script src="src/js/charts.js" type="text/javascript"></script>

<link href="includes/bootstrap/css/bootstrap.min.css" rel="stylesheet"></link>
<link rel="stylesheet" type="text/css" href="templates/css/main.css"></link>
</head>

<body>
	<?php
	echo $this->_ ['menu'];
	?>
	<div id="content" class="container-fluid">
		<?php echo $this->_['content']; ?>
	</div>
	
	<div id="ajax-panel"></div>
	<?php echo $this->_['footer']; ?>
</body>