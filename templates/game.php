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
	</div>
</div>

<div class="row">
	<div class="col-lg-1 col-md-0"></div>
	<div class="col-lg-10 col-md-12">
		<div id="thumb-wrap"></div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
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
  </div>
</div>