<?php
$langs = [ 
		'german' => 'Germany-01.png',
		'english' => 'United Kingdom-01.png',
		'french' => 'France-01.png',
		'spanish' => 'Spain-01.png',
		'pashto' => 'Pakistan-01.png' 
];
?>
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<h1>Dashboard</h1>
		<p>Willkommen <?php echo ucfirst($this->_['username']); ?></p>
	</div>
</div>




<div class="row" style="margin-top: 1vw">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<div class="panel panel-default">
			<div class="panel-heading">Sprache auswählen</div>
			<div class="panel-body">
				<p>Deine aktuelle Sprache ist: <?php echo ucfirst($_SESSION['config']->getLanguage()) ?></p>
		<?php
		foreach ( $langs as $l => $img ) :
			?>
			<a href="lang-<?php echo $l; ?>">
					<div
						class="col-xs-4 col-sm-3 col-md-1 <?php echo ($_SESSION['config']->getLanguage() == $l) ?  'alert-success' : '';?>">
						<img class="img-responsive center-block"
							src="templates/img/flags/png/<?php echo $img; ?>"
							alt="<?php echo $l; ?>">

					</div>
				</a> 
			<?php endforeach;?>
		</div>
		</div>
	</div>
</div>
