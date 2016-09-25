<div align="center">

    <?php if (isset($this->_['errmessage']) AND strlen($this->_['errmessage'])>0 ){ ?>
        <span style="color: #ffffff; background-color: #ff0000"><?php echo $this->_['errmessage']; ?></span>
    <?php } ?>
    
            <h1>Registrieren</h1>

            <form name="createuser" action="register" method="POST">

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
