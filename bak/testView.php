<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//DE">
<html>
	<head>
		<title>Mem'n'Learn</title>

		    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="includes/jQuery/jquery-3.1.js"></script>
    <script src="includes/jQuery/jquery.flip.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="includes/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
		$(document).ready(function(){
		    $('.card-container').flip();
		});
    </script>

		<link href="includes/bootstrap/css/bootstrap.min.css" rel="stylesheet"></link>
		<link rel="stylesheet" type="text/css" href="templates/css/main.css"></link>
	</head>
<body>
	<div class="container header">
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#">Mem-n-Learn</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">
				<li><a href="#"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>  Neues Spiel</a></li>
				<li><a href="#"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>  Meine Statistiken</a></li>

				<li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sprache <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="#"><img alt="German" src="templates/img/flags/png/Germany-01.png" height="20px"> German</a></li>
		            <li><a href="#"><img alt="English" src="templates/img/flags/png/United Kingdom-01.png" height="20px"> English</a></li>
		            <li><a href="#"><img alt="French" src="templates/img/flags/png/France-01.png" height="20px"> French</a></li>
		            <li><a href="#"><img alt="Spanish" src="templates/img/flags/png/Spain-01.png" height="20px"> Spanish</a></li>
		            <li role="separator" class="divider"></li>
		            <li><a href="#">More...</a></li>
		          </ul>
		        </li>
			  </ul>
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
	</div>

<div id="thumb-wrap" class="container">
<?php for ($i=0;$i<30;$i++):?>
	<div id="thumb-container" class="card-container">
		<div class="front">
        	<div class="thumb-dummy"></div>
        	<div class="thumb-element" style="background-color: <?php echo ($i % 2 == 0) ? '#eaebe6' : '#f57882';?>">
				<span class="thumbnail card" style=" margin: 0 auto; background-image: url('templates/img/cards/vogel.png'); background-position: inherit; background-size: contain;" /> </span>
			</div>
        </div>
        <div class="back">
        	<div class="thumb-dummy"></div>
        	<div class="thumb-element" style="background-color: <?php echo ($i % 2 == 0) ? '#eaebe6' : '#f57882';?>">
				<span class="thumbnail card" style=" margin: 0 auto; background-image: url('templates/img/cards/auto.png'); background-position: inherit; background-size: contain;" /></span>
			</div>
        </div>
    </div>
<?php endfor; ?>
</div>
</body>