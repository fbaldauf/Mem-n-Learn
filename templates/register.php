<div class="form-group">

    <?php if (isset($this->_['errmessage']) AND strlen($this->_['errmessage'])>0 ){ ?>
        <div class="alert alert-danger"><?php echo $this->_['errmessage']; ?></div>
    <?php } ?>
    
            <h1><?php echo $this->_('REGISTER_TITLE') ?></h1>

	<form action="register" method="POST">

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
</div>
<!-- 
                <table>

                    <tr>
                        <td>
                            Username: 
                        </td>
                        <td>
                            <input type="text" name="user" value="" size="30" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Passwort:
                        </td>
                        <td>
                            <input type="password" name="password" value="" size="30" />
                        </td>
                    </tr> 

                    <tr>
                        <td>

                        </td>
                        <td>
                            <input type="submit" value="Create" name="createuser" />
                            <input type="reset" value="Reset" name="reset" />
                        </td>

                    </tr>

                </table>
            </form>     
        </div>
 -->