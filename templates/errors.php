<p>
	<strong>Es sind folgende Fehler aufgetreten:</strong>
</p>

<ul>
	<?php foreach($this->_['errors'] as $err): ?>
	<li><?php echo $err ; ?></li>
	<?php endforeach; ?>
</ul>