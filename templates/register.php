<div class="form-group">

    <?php if (isset($this->_['errmessage']) AND strlen($this->_['errmessage'])>0 ){ ?>
        <div class="alert alert-danger"><?php echo $this->_['errmessage']; ?></div>
    <?php } ?>
    
            <h1>Registrieren</h1>

	<form action="register" method="POST">

		<div class="form-group">
			<label for="txtUser">Benutzername:</label> <input type="text"
				class="form-control" id="txtUser" name="user">
		</div>
		<div class="form-group">
			<label for="txtPwd">Password:</label> <input type="password"
				class="form-control" id="txtPwd" name="password">
		</div>
		<button type="submit" class="btn btn-default">Registrieren</button>

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