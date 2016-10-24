<?php
class UserController extends AppController {

	/**
	 * Erzeugt eine neue Instanz vom Typ UserController
	 */
	public function __construct() {
		// Falls Parameter übergeben wurden, wird ein entsprechender Konstruktor aufgerufen
		if (func_num_args() == 1) {
			parent::__construct(func_get_arg(0));
		}
	}

	/**
	 * Stellt die Seite des Benutzers dar.
	 * Hier werden Statistiken des Benutzers angezeigt
	 * {@inheritDoc}
	 * @see AppController::index()
	 */
	public function index() {
		// View festlegen
		$this->view = new View();
		$this->view->setTemplate('userstats');

		// Statistikdaten sammeln
		$data = $this->getStatistics();
		$this->view->assign('data', $data);
		$this->view->assign('username', $_SESSION['username']);

		// View rendern
		return $this->renderView();
	}

	/**
	 * Sammelt alle Statistikdaten des Benutzers
	 * @return NULL[]|DateTime[] Statistikdaten des aktuell angemeldeten Benutzers
	 */
	protected function getStatistics() {
		$data = [];

		$sessionuserID = $_SESSION['ID'];

		// Schnellstest Spiel
		$sql = "SELECT MIN(totaltime) as min FROM `result` WHERE F_userID = '$sessionuserID'";
		$row = $this->fetch_object($this->query($sql));
		$data['fastest'] = $row->min;

		// Anzahl Spiele
		$sql = "SELECT COUNT(F_userID) AS Anzahl FROM `result` WHERE F_userID = '$sessionuserID'";
		$row = $this->fetch_object($this->query($sql));
		$data['countGames'] = $row->Anzahl;

		// Alle Spiele
		$sql = "SELECT date, totaltime, TIME_TO_SEC(totaltime) / 60 as timeMinutes, flips FROM `result` WHERE F_userID = '$sessionuserID' ORDER By date asc";
		$ergebnistabellerow = $this->query($sql);
		$data['games'] = [];
			
		// Daten aufbereiten
		while ( $row = $this->fetch_object ( $ergebnistabellerow ) ) {
			$tmpDate = new DateTime ( $row->date );
			$data ['games'] [] = [
					// Datum des Spieles im deutschen Format
					'date' => $tmpDate->format ( 'd.m.Y' ),
					// Benötigte Zeit
					'time' => $row->totaltime,
					// Benötigte Zeit auf Minuten gerundet
					'timeMinutes' => $row->timeMinutes,
					// Datum des Spieles
					'dateObj' => $tmpDate,
					// Anzahl der benötigten Versuche
					'flips' => $row->flips
			];
		}

		return $data;
	}

	/**
	 * Aktion zur Authentifizierung eines Benutzers
	 * @return string View nach dem Login
	 */
	public function login() {
		$check = false;
		$this->view = new View();

		// Prüfe ob Login erfolgreich
		if (isset($this->request['user']) and isset($this->request['password'])) {
			$check = $this->checkLogin($this->request['user'], $this->request['password']);
			if (!$check) {
				// Login nicht erfolgreich
				$this->view->assign('errmessage', $this->view->_("LOGIN_ERROR"));
			}
		}

		if (!$check) {
			// Wieder Login-Formular anzeigen
			$this->view->setTemplate('login');
			return $this->renderView();
		}

		// Weiterleiten aufs Dashboard
		$this->view->setTemplate('dashboard');
		$this->view->assign('username', $this->request['user']);
		return $this->renderView();
	}

	/**
	 * Aktion zum Abmelden eines Benutzers
	 * @return string View nach dem Logout
	 */
	public function logout() {
		// Aktuelle Session zurücksetzen
		$_SESSION['eingeloggt'] = false;
		$_SESSION = null;
		session_destroy();

		// Auf Login weiterleiten
		$this->view = new View();
		$this->view->resetTranslation();
		$this->view->setTemplate('login');
		return $this->renderView();
	}

	/**
	 * Aktion zum Registrieren eines neuen Benutzers
	 * @return string View nach Registrierung
	 */
	public function register() {
		$check = false;
		$this->view = new View();

		// Prüfe ob Registrierung erfolgreich
		if (isset($this->request['user']) and isset($this->request['password'])) {
			$check = $this->checkRegister($this->request['user'], $this->request['password']);
			if (!$check) {
				// Registrierung war nicht erfolgreich
				$this->view->assign('errmessage', $this->view->_('REGISTER_ERROR'));
			}
		}

		if (!$check) {
			// Wieder auf die Registrierung weiterleiten
			$this->view->setTemplate('register');
			return $this->renderView();
		}
		
		// Bei erfolgreicher Registierung den Benutzer einloggen
		return $this->login();
	}

	/**
	 * Prüft, ob bereits ein Benutzer angemeldet ist
	 * @return boolean 
	 */
	public function isLoggedIn() {
		if (isset($_SESSION['eingeloggt']) and $_SESSION['eingeloggt'] === true) {
			return true;
		}
		return false;
	}
	
	/**
	 * Authentifiziert einen Benutzer
	 * 
	 * @param string $user
	 *        	Benutzername
	 * @param string $password
	 *        	Passwort des Benutzers
	 * @return boolean
	 */
	protected function checkLogin($user, $password) {
		$_SESSION ['eingeloggt'] = false;
		
		// Da Passwörter zunächst im Klaartext gespeichert wurden, muss zunächst das Passwort als Hash gespeichert werden
		$this->query ( 'UPDATE user SET password=\'' . $this->getHashForPassword ( $password ) . '\' WHERE password=\'' . $password . '\' AND LOWER(name)=LOWER(\'' . $user . '\')' );
		
		// Prüfe, ob es einen Benutzer mit dem angegebenen Passwortes gibt
		$abfrage = "SELECT * FROM user WHERE LOWER(name) = LOWER('$user') AND password = '" . $this->getHashForPassword ( $password ) . "'";
		
		if (! $res = $this->query ( $abfrage )) {
			echo $this->view->_ ( "LOGIN_ERROR" );
		}
		
		if (! $row = $this->fetch_object ( $res ))
			return false;
		$id = $row->name;
		
		if (! empty ( $id )) {
			// Authentifizierung erfolgreich -> In Session speichern
			$_SESSION ['eingeloggt'] = true;
			$_SESSION ['username'] = $user;
			$_SESSION ['ID'] = $row->userID;
			$this->setUserSettings($row);
		}
		
		// Ergebnis des Logins muss sein, dass nun ein Benutzer angemeldet ist
		return $this->isLoggedIn ();
	}
	
	/**
	 * Setzt die benutzerspezifischen Einstellungen
	 * @param object $user Einstellungen des Benutzers
	 */
	protected function setUserSettings($user) {
		if (strlen($user->language) > 0) {
			/** @var Configuration $conf */
			$conf = $_SESSION['config'];
			$conf->setLanguage($user->language);
		}
	}
	
	/**
	 * Registriert einen Benutzer
	 * 
	 * @param string $user
	 *        	gewünschter Benutzername
	 * @param string $password
	 *        	Gewünschtes Passwort
	 * @return resource
	 */
	protected function checkRegister($user, $password) {
		// Leerzeichen vorne und hinten abtrennen
		$user = trim ( ( string ) $user );
		$password = trim ( ( string ) $password );
		
		// Benutzername darf nicht leer sein
		if (strlen ( $user ) == 0) {
			return false;
		}
		
		// Passwort muss den Anforderungen entsprechen
		if (! $this->isValidPassword ( $password )) {
			return false;
		}
		
		// Prüfen, ob es bereits einen Benutzer mit diesem Namen gibt
		$res = $this->query("SELECT COUNT(*) as C FROM user WHERE LOWER(name) = LOWER('$user')");
		if ($this->fetch_object($res)->C > 0) {
			// Es gibt bereits einen Benutzer mit diesem Namen
			return false;
		}
		
		// Neuen Benutzer in die Datenbank schreiben
		$insert = "Insert into User(name, password) values ('$user', '" . $this->getHashForPassword ( $password ) . "')";
		
		// Ergebnis zurückgeben
		return $this->query ( $insert );
	}
	
	/**
	 * Prüft, ob das angegebene Passwort den Anforderungen der Anwendung entspricht
	 * 
	 * @param string $password
	 *        	Zu prüfendes Passwort
	 * @return boolean
	 */
	protected function isValidPassword($password) {
		$password = trim ( ( string ) $password );
		if (strlen ( $password ) === 0) {
			// Passwort darf nicht leer sein
			return false;
		}
		
		// TODO: Besprechen, ob weitere Anforderungen benötigt werden (Minimallänge, Sonderzeichen, etc...)
		return true;
	}
	
	/**
	 * Gibt den Hashwert des angegebenen Passwortes zurück
	 * 
	 * @param string $password
	 *        	Passwort, für das ein Hash erstellt werden soll
	 * @return string
	 */
	protected function getHashForPassword($password) {
		return md5 ( $password );
	}
	
	/**
	 * Exportiert die Benutzerstatistiken
	 *
	 * @return string Fehlermeldung, oder PDF-Datei
	 */
	public function exportStatistics() {
		// Statistikdaten sammeln
		$stats = $this->getStatistics ();
		$xml = $this->parseStatisticsToXML ( $stats );
		
		//$xsl = new DOMDocument ();
		//$xsl->load ( 'data/results.xsl' );
		
		// // Prozessor instanziieren und konfigurieren (für XSL, momentan nicht genutzt)
		// $proc = new XSLTProcessor();
		// $proc->importStyleSheet($xsl); // XSL Document importieren
		
		// echo $proc->transformToXML($dom);
		
		// Request an FOP Server Senden
		require 'includes/xsl-fo/HTTPPost.php';
		$httppost = new HTTPPost ();
		$pdfdata = @$httppost->post_request ( "localhost", "8087", getcwd () . "/data/export.fo", $xml->asXML () );
		
		if ($pdfdata ['status'] == 'err') {
			// Fehler beim Senden des Requests an den FO-Server
			echo '<strong>Fehler</strong><br />';
			echo $pdfdata ['error'] . '<br />';
			echo '<a href="statistic">Zurück</a>';
			return;
		}
		
		if (substr ( $pdfdata, 0, 6 ) == '<html>') {
			// Fehler beim Generieren des PDF-Dokumentes
			echo ($pdfdata);
			echo '<a href="statistic">Zurück</a>';
		} else {
			
			// PDF Ausgabe in einer Datei speichern
			// $myFile = "testFile.pdf";
			// $fh = fopen ( $myFile, 'w' ) or die ( "can't open file" );
			// fwrite ( $fh, $pdfdata );
			// fclose ( $fh );
			
			header ( "Content-Type: application/pdf" );
			header ( "Content-Disposition: attachment; filename=\"Ergebnisse.pdf\"" );
			echo $pdfdata;
			// readfile ( $myFile );
		}
	}
	
	/**
	 * Überträgt die Benutzerstatistiken in ein XML-Dokument
	 *
	 * @param array $stats
	 *        	Statistiken, die umgewandelt werden sollen
	 * @return string XML-Dokument
	 */
	protected function parseStatisticsToXML($stats) {
		$xml = new SimpleXMLElement ( '<root />' );
		
		$xml->addChild ( 'username', $_SESSION ['username'] );
		// Datum im XML-Format 2000-01-01T00:00:00
		$xml->addChild ( 'generated', date ( 'Y-m-d\TH:i:s', time () ) );
		
		$results = $xml->addChild ( 'results' );
		foreach ( $stats ['games'] as $game ) {
			$result = $results->addChild ( 'result' );
			// Datum im Format, welches von XSL-FO verstanden werden kann (2000-01-01)
			$result->addChild ( 'date', $game ['dateObj']->format ( 'Y-m-d' ) );
			$result->addChild ( 'time', $game ['time'] );
			$result->addChild ( 'flips', $game ['flips'] );
		}
		
		// XML gegen XSD validieren
		$dom = new DOMDocument ();
		$dom->loadXML ( $xml->asXML () );
		if (! $dom->schemaValidate ( 'data/results.xsd' )) {
			echo 'error';
		}
		
		return $xml;
	}
}