<div class="container header">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
					aria-expanded="false">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="."><img src="templates/img/logo.png" alt="" style="display:inline; margin-top: -3px;" /> Mem-n-Learn</a>

			</div>


			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="new-game"> <span class="glyphicon glyphicon-plus"
							aria-hidden="true"></span> <?php echo $this->_('MENU_NEW_GAME');?>
					</a></li>
					<li><a href="statistic"><span class="glyphicon glyphicon-info-sign"
							aria-hidden="true"></span> <?php echo $this->_('MENU_USER_STATS');?></a></li>


					<li><a href="logout"><span class="glyphicon glyphicon-log-out"
							aria-hidden="true"></span> <?php echo $this->_('MENU_LOGOUT');?></a></li>
				</ul>
			</div>

			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container-fluid -->
	</nav>
</div>