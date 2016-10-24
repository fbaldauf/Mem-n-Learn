<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<h1><?php echo $this->_('STATS_TITLE', ucfirst($this->_['username']));?></h1>
		<?php echo $this->_('STATS_FAST');?>: <?php echo $this->_ ['data'] ['fastest'] ?><br />

		<?php echo $this->_('STATS_GAMECOUNT');?>: <?php echo sizeof ( $this->_ ['data'] ['games'] ) ?><br />
        <a href="exportStatistics"><?php echo $this->_('STATS_DOWNLOAD');?></a>
	</div>
</div>
<div class="row" style="margin-top: 1vw">
	<div class="col-lg-1"></div>
	<div class="table-responsive col-lg-5 col-md-10">
		<h2>
			<?php echo $this->_('STATS_SUBTITLE');?>
		</h2>
		<table class="table table-striped table-hover table-condensed">
			<thead>
			<tr>
				<th><?php echo $this->_('STATS_TABLE_NUMBER');?></th>
				<th><?php echo $this->_('STATS_TABLE_DATE');?></th>
				<th><?php echo $this->_('STATS_TABLE_SCORE');?></th>
				<th><?php echo $this->_('STATS_TABLE_FLIPS');?></th>
			</tr>
			</thead>
			<tbody>

			<?php $anzahl = 1;foreach ( $this->_ ['data'] ['games'] as $row ): ?>
	 			<tr
					class="
					<?php echo ($row['time'] == $this->_['data']['fastest']) ? 'success' : ''; ?>">
					<td><?php echo $anzahl ?></td>
					<td><?php echo $row ['date'] ?></td>
					<td><?php echo $row ['time'] ?></td>
					<td><?php echo $row ['flips'] ?></td>
				</tr>
			 	<?php
				$anzahl = $anzahl + 1;
				endforeach;
			?>
			</tbody>
		</table>

	</div>
	<div id="container" class="col-lg-5 col-md-10"
		style="min-width: 310px; height: 400px; margin: 0 auto"></div>
	<div class="col-lg-1"></div>
</div>
<script>
$(document).ready(function() {
	initChart({
		data: <?php echo json_encode($this->_['data']); ?>,
		title: '<?php echo str_replace("'", "\'", $this->_('CHART_TITLE')); ?>',
		unit: '<?php echo $this->_('CHART_UNIT'); ?>',
		user: '<?php echo ucfirst($this->_['username']); ?>'
	});
});
</script>