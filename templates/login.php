<div class="form-group">
    
    <?php if (isset($this->_['errmessage']) AND strlen($this->_['errmessage'])>0 ){ ?>
        <div class="alert alert-danger"><?php echo $this->_['errmessage']; ?></div>
    <?php } ?>
    
            <h2>Login</h2> 

<form action="." method="POST">
	<div class="form-group">
   		<label for="txtUser">Benutzername:</label>
    	<input type="text" class="form-control" id="txtUser" name="user">
  	</div>
  	<div class="form-group">
    	<label for="txtPwd">Password:</label>
    	<input type="password" class="form-control" id="txtPwd" name="password">
  	</div>
  	<button type="submit" class="btn btn-default">Login</button>
</form>

<a href="register">Registrieren</button>
<!-- 
            <table>
                
                <form name="uebergabe" action="." method="POST">
                <tr>                  
                      <td>Username: </td>                   
                       <td><input type="text" name="user" value="" size="30" /></td>                    
                </tr>
                
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password" value="" size="30" /></td>
                    <td>
                        <input type="submit" value="Login" name="login" />                      
                        
                    </td>
                </tr> 
                </form>
                <tr>

                    <td>
                        <form name="newuser" action="register" method="POST">
                
                        <input type="submit" value="Sign Up" name="registration" />
                        </form>
                    </td>               
                </tr>            
            </table>
             
        </div>
 -->