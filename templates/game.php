<div id="thumb-wrap" class="container">
<?php for ($i=0;$i<30;$i++):?>
	<div id="thumb-container" class="card-container">
		<div class="front">
        	<div class="thumb-dummy"></div>
        	<div class="thumb-element" style="background-color: <?php echo ($i % 2 == 0) ? '#ffffff' : '#ffffff';?>">
				<span class="thumbnail card" style=" margin: 0 auto; background-image: url('templates/img/yugioh-card-back.png'); background-position: inherit; background-size: contain;" /> </span>
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