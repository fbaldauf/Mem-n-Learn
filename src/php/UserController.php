<?php
class UserController extends AppController {
	public function index() {
		$this->view = new View ();
		$this->view->setTemplate ( 'userstats' );
		
		$data = $this->getStatistics ();
		$this->view->assign ( 'data', $data );
		
		return json_encode ( [ 
				'view' => $this->renderView (),
				'data' => $data,
				'user' => $_SESSION ['username'] 
		] );
	}
	protected function getStatistics() {
		$data = [ ];
		
		$sessionuserID = $_SESSION ['ID'];
		$db = new mysqli ( $this->dbhost, $this->dbuser, $this->dbpassword, $this->db ) or die ( "Unable" );
		
		// Schnellstest Spiel
		$sql = "SELECT MIN(totaltime) as min FROM `result` WHERE F_userID = '$sessionuserID'";
		$best = mysqli_query ( $db, $sql );
		$row = mysqli_fetch_object ( $best );
		$data ['fastest'] = $row->min;
		
		// Anzahl Spiele
		$sql = "SELECT COUNT(F_userID) AS Anzahl FROM `result` WHERE F_userID = '$sessionuserID'";
		$anzahlSpiele = mysqli_query ( $db, $sql ) or die ( mysqli_error ( $db ) . '<pre>' . $sql . '</pre>' );
		
		$row = mysqli_fetch_object ( $anzahlSpiele );
		$data ['countGames'] = $row->Anzahl;
		
		// Alle Spiele
		$sql = "SELECT date, totaltime, TIME_TO_SEC(totaltime) / 60 as timeMinutes FROM `result` WHERE F_userID = '$sessionuserID' ORDER By date asc";
		
		$ergebnistabellerow = mysqli_query ( $db, $sql );
		$data ['games'] = [ ];
		while ( $row = mysqli_fetch_object ( $ergebnistabellerow ) ) {
			
			$tmpDate = new DateTime ( $row->date );
			$data ['games'] [] = [ 
					'date' => $tmpDate->format ( 'd.m.Y' ),
					'time' => $row->totaltime,
					'timeMinutes' => $row->timeMinutes 
			];
		}
		
		return $data;
	}
	public function login() {
		$check = false;
		$this->view = new View ();
		
		// Prüfe ob Login erfolgreich
		if (isset ( $this->request ['user'] ) and isset ( $this->request ['password'] )) {
			$check = $this->checkLogin ( $this->request ['user'], $this->request ['password'] );
			if (! $check) {
				$this->view->assign ( 'errmessage', 'Fehlerhafter Login' );
			}
		}
		
		if (! $check) {
			// Wenn nicht erfolgreich, zeige Formular
			$this->view->setTemplate ( 'login' );
			return $this->renderView ();
		}
		
		$this->view->setTemplate ( 'dashboard' );
		$this->view->assign ( 'username', $this->request ['user'] );
		return $this->renderView ();
	}
	public function logout() {
		$_SESSION ['eingeloggt'] = false;
		
		$this->view = new View ();
		$this->view->setTemplate ( 'login' );
		return $this->renderView ();
	}
	public function register() {
		$check = false;
		$this->view = new View ();
		
		// Prüfe ob Login erfolgreich
		if (isset ( $this->request ['user'] ) and isset ( $this->request ['password'] )) {
			$check = $this->checkRegister ( $this->request ['user'], $this->request ['password'] );
			if (! $check) {
				$this->view->assign ( 'errmessage', 'Fehlerhafte Registrierung' );
			}
		}
		
		if (! $check) {
			// Wenn nicht erfolgreich, zeige Formular
			$this->view->setTemplate ( 'register' );
			return $this->renderView ();
		}
		return $this->login ();
	}
	public function isLoggedIn() {
		if (isset ( $_SESSION ['eingeloggt'] ) and $_SESSION ['eingeloggt'] === true) {
			return true;
		}
		return false;
	}
	protected function checkLogin($user, $password) {
		try {
			$_SESSION ['eingeloggt'] = false;
			$verbindung = new pdo ( 'mysql:dbname=' . $this->db . ';host=' . $this->dbhost . ';port=3306', $this->dbuser, $this->dbpassword );
			
			$verbindung->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
			$abfrage = 'SELECT name, password, userID FROM user WHERE name = ? AND password = ?';
			
			$stmt = $verbindung->prepare ( $abfrage );
			
			$stmt->bindParam ( 1, $user, PDO::PARAM_STR );
			$stmt->bindParam ( 2, $password, PDO::PARAM_STR );
			
			$status = $stmt->execute ();
			
			if (! $status) {
				echo "Abfrage fehlgeschlagen.";
			}
			
			$row = $stmt->fetch ();
			$id = $row ['name'];
			
			// echo "name:".$id;
			
			if (empty ( $id )) {
				// $message = 'Access Error<br>';
				// echo $message;
				// echo "Falscher Benutzername oder falsches Passwort.";
			} else {
				
				$_SESSION ['eingeloggt'] = true;
				$_SESSION ['username'] = $user;
				$_SESSION ['ID'] = $row ['userID'];
				
				// echo $id;
			}
		} catch ( PDOException $e ) {
			
			// echo $e->getMessage();
			echo "Unbekannter Fehler!";
			var_dump ( $e->getMessage () );
		}
		
		return $this->isLoggedIn ();
	}
	protected function checkRegister($user, $password) {
		try {
			
			mysql_connect ( $this->dbhost, $this->dbuser, $this->dbpassword );
			$db = mysql_select_db ( $this->db );
			
			$insert = "Insert into User(name,password) values ('$user','$password')";
			
			return mysql_query ( $insert );
		} catch ( PDOException $e ) {
			// echo $e->getMessage();
			return false;
		}
	}
}
