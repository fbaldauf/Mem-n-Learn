<?php

class UserController extends AppController {

    public function index() {
        $this->view = new View();
        $this->view->setTemplate('userstats');
        return $this->renderView();
    }

    public function login() {
        $check = false;
        $this->view = new View();
        
        //Prüfe ob Login erfolgreich
        if (isset($this->request['user']) AND isset($this->request['password'])) {
            $check = $this->checkLogin($this->request['user'], $this->request['password']);
            if (!$check) {
                $this->view->assign('errmessage', 'Fehlerhafter Login');
            }
        }

        if (!$check) {
            //Wenn nicht erfolgreich, zeige Formular
            $this->view->setTemplate('login');
            return $this->renderView();
        }

        $this->view->setTemplate('dashboard');
        $this->view->assign('username', $this->request['user']);
        return $this->renderView();
    }

    public function logout() {
        $_SESSION['eingeloggt'] = false;

        $this->view = new View();
        $this->view->setTemplate('login');
        return $this->renderView();
    }

    public function register() {
        $check = false;
        $this->view = new View();
        
        //Prüfe ob Login erfolgreich
        if (isset($this->request['user']) AND isset($this->request['password'])) {
            $check = $this->checkRegister($this->request['user'], $this->request['password']);
            if (!$check) {
                $this->view->assign('errmessage', 'Fehlerhafte Registrierung');
            }
        }

        if (!$check) {
            //Wenn nicht erfolgreich, zeige Formular
            $this->view->setTemplate('register');
            return $this->renderView();
        }
        return $this->login();
    }

    public function isLoggedIn() {
        if (isset($_SESSION['eingeloggt']) AND $_SESSION['eingeloggt'] === true) {
            return true;
        }
        return false;
    }

    protected function checkLogin($user, $password) {

        try {
            $_SESSION['eingeloggt'] = false;
            $verbindung = new pdo('mysql:dbname=julian1828;host=localhost;port=3306', 'julian1828', '14dwf1_mem');

            $verbindung->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $abfrage = 'SELECT name, password FROM user WHERE name = ? AND password = ?';

            $stmt = $verbindung->prepare($abfrage);

            $stmt->bindParam(1, $user, PDO::PARAM_STR);
            $stmt->bindParam(2, $password, PDO::PARAM_STR);


            $status = $stmt->execute();

            if (!$status) {
                echo "Abfrage fehlgeschlagen.";
            }

            $id = $stmt->fetchColumn(0);
            //echo "name:".$id;

            if (empty($id)) {
                //$message = 'Access Error<br>';
                //echo $message;
                //echo "Falscher Benutzername oder falsches Passwort.";
            } else {


                $_SESSION['eingeloggt'] = true;
                $_SESSION['username'] = $user;

                // echo $id;
            }
        } catch (PDOException $e) {

            // echo $e->getMessage();
            echo "Unbekannter Fehler!";
        }

        return $this->isLoggedIn();
    }

    protected function  checkRegister($user, $password){
         try {
            

                mysql_connect('localhost', 'julian1828', '14dwf1_mem');
                $db = mysql_select_db('julian1828');

                $insert = "Insert into User(name,password) values ('$user','$password')";

                return  mysql_query($insert);

             
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return false;
        }
    }
}
