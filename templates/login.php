<div align="center">
    
    <?php if (isset($this->_['errmessage']) AND strlen($this->_['errmessage'])>0 ){ ?>
        <span style="color: #ffffff; background-color: #ff0000"><?php echo $this->_['errmessage']; ?></span>
    <?php } ?>
    
            <p>Login</p> 

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
