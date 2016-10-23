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
		<h1><?php echo $this->_('DASHBOARD_TITLE');?></h1>
		<p><?php echo $this->_('WELCOME',ucfirst($this->_['username']));?></p>
	</div>
</div>




<div class="row" style="margin-top: 1vw">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
    <?php //TODO entfernen
		if (isset($this->_['devErrors']) AND sizeof($this->_['devErrors'])>0) :
	?>
        <div class="panel panel-default">
            <div class="panel-heading">Fehlende Lokalisierungen und XML-Fehler:</div>
            <div class="panel-body">
                <ul>
                <?php foreach ($this->_['devErrors'] as $key => $val) : ?>
                    <li><?php echo $key; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $this->_('SELECT_LANGUAGE');?></div>
			<div class="panel-body">
				<p><?php echo $this->_('CURRENT_LANGUAGE');?>: <?php echo ucfirst($_SESSION['config']->getLanguage()) ?></p>
		<?php
		foreach ( $langs as $l => $img ) :
			?>
			<a href="lang-<?php echo $l; ?>">
					<div style="padding: 1vw"
						class="col-xs-4 col-sm-3 col-md-1 <?php echo ($_SESSION['config']->getLanguage() == $l) ?  'alert-success' : '';?>" >
						<img class="img-responsive center-block"
							src="templates/img/flags/PNG/<?php echo $img; ?>"
							alt="<?php echo $l; ?>">

					</div>
				</a>
			<?php endforeach;?>
		</div>
		</div>
	</div>
</div>
