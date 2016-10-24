<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-10">
		<div class="panel panel-default">
            <div class="panel-heading"><h1><?php echo $this->_('REGISTER_TITLE') ?></h1></div>
            <div class="panel-body">

				<div class="form-group">
				
				    <?php if (isset($this->_['errmessage']) AND strlen($this->_['errmessage'])>0 ){ ?>
				        <div class="alert alert-danger"><?php echo $this->_['errmessage']; ?></div>
				    <?php } ?>
				
					<form action="register" method="POST">
					<!--  http://localhost/Mem-n-Learn/register -->
						<div class="form-group">
							<label for="txtUser"><?php echo $this->_('REGISTER_USERNAME') ?>:</label> <input type="text"
								class="form-control" id="txtUser" name="user">
						</div>
						<div class="form-group">
							<label for="txtPwd"><?php echo $this->_('REGISTER_PASSWORD') ?>:</label> <input type="password"
								class="form-control" id="txtPwd" name="password">
						</div>
						<button type="submit" class="btn btn-default"><?php echo $this->_('REGISTER_SUBMIT') ?></button>
					</form>
					
					<a href="index"><?php echo $this->_('REGISTER_TO_LOGIN') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>