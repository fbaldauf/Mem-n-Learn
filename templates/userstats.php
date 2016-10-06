<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<h1>Dies sind die Ergebnise von <?php  echo ucfirst ( $_SESSION ['username'] ) ?>!</h1>
		Dein schnellstes Spiel: <?php echo $this->_ ['data'] ['fastest'] ?><br />

		Anzahl Spiele: <?php echo sizeof ( $this->_ ['data'] ['games'] ) ?><br />

	</div>
</div>
<div class="row" style="margin-top: 1vw">
	<div class="col-lg-1"></div>
	<div class="table-responsive col-lg-10">
		<h2>
			Deine Spiele<br>
		</h2>
		<table class="table table-striped table-hover table-condensed">
			<tr>
				<th>Nr.</th>
				<th>Datum</th>
				<th>Zeit</th>
			</tr>
		
<?php $anzahl = 1;foreach ( $this->_ ['data'] ['games'] as $row ): ?>
 			<tr
				class="
				<?php echo ($row['time'] == $this->_['data']['fastest']) ? 'success' : ''; ?>">
				<td><?php echo $anzahl ?></td>
				<td><?php echo $row ['date'] ?></td>
				<td><?php echo $row ['time'] ?></td>
			</tr>
 	<?php
	$anzahl = $anzahl + 1;
endforeach
;
?>
</table>

	</div>
	<div class="col-lg-1"></div>
</div>
<div class="row">
	<div class="col-lg-1"></div>
	<div id="container" class="col-lg-10"
		style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	<div class="col-lg-1"></div>
</div>