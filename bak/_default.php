<?php
foreach($this->_['entries'] as $entry){
?>

<h2><a href="?view=entry&id=<?php echo $entry['id'] ?>"><?php echo $entry['title']; ?></a></h2>
<p><?php echo $entry['content']; ?></p>

<?php
}
?>