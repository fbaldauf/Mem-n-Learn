<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<h1><?php echo $this->_('GAME_TITLE');?></h1>
	</div>
</div>

<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<!-- <button><?php echo $this->_('SOUND_MUTE');?></button>-->
		<span><?php echo $this->_('ELAPSED_TIME');?>: <span class="expired-time">00:00:00</span></span>
		<span><?php echo $this->_('GAME_FLIPS');?>: <span class="count-flips">0</span></span>
		<!-- <button id="btnTwoPlayer">2 Player</button> -->
	</div>
</div>

<div class="row">
	<div class="col-lg-1 col-md-0"></div>
	<div class="col-lg-10 col-md-12">
		<div id="thumb-wrap"></div>
	</div>
</div>

<!-- Overlay -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
  	<!-- Template für Wiederholungen -->
    <div class="modal-content" id="repeat">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $this->_('GAME_TIME_PAUSED'); ?></h4>
      </div>
      <div class="modal-body">
 		<h1 class="text-center center-block"><?php echo $this->_('GAME_REPEAT_WORD'); ?></h1>
		<span class="text-center center-block text">"<span class="word"></span>"</span>
		<br />
		<span class="image"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->_('GAME_REPEAT_CLOSE')?></button>
      </div>
    </div>
    
    <!-- Template für Ergebnisse -->
    <div class="modal-content" id="score">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $this->_('GAME_TIME_PAUSED'); ?></h4>
      </div>
      <div class="modal-body">
 		<h1 class="text-center center-block"><?php echo $this->_('GAME_OVER_TEXT'); ?></h1>
 		<span class="text-center center-block"><?php echo $this->_('GAME_OVER_SCORE'); ?>:
			<span class="score"></span>
		</span>
		<br />
		<span class="image"></span>
      </div>
      <div class="login" style="display:none">
      	<?php include 'login.php'; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->_('GAME_REPEAT_CLOSE')?></button>
      </div>
    </div>
  </div>
</div>