<?php
class AppController {
	/**
	 * Enthält den aktuellen HTTP-Request
	 * @var array
	 */
	protected $request = null;
	/**
	 * Enthält das Template, welches standardmäßig nach Aktionen genutzt wird
	 * @var string
	 */
	private $template = '';
	/**
	 * Die View, die der Controller nutzt
	 * @var View
	 */
	protected $view = null;
	/**
	 * Die aktive Datenbank-Verbindung
	 * @var ressource
	 */
	private $dbConnection = null;
	/**
	 * Der Name der genutzten Datenbank
	 * @var string
	 */
	protected $db = 'julian1828';
	/**
	 * Der Benutzername füt die genutzte Datenbank
	 * @var string
	 */
	protected $dbuser = 'root';
	/**
	 * Das Passwort des Benutzers für die Datenbank
	 * @var string
	 */
	protected $dbpassword = '';
	/**
	 * Der Hostname der genutzten Datenbank
	 * @var string
	 */
	protected $dbhost = 'localhost';

	/**
	 * Konstruktor, erstellet den Controller.
	 * @param Array $request Array aus $_GET & $_POST.
	 */
	public function __construct($request) {
		if ($_SERVER['SERVER_NAME'] === 'julian1828.bplaced.net') {
			// Für den BPlaced-Webspace müssen die Verbindungsdaten für die Datenbank angepasst werden
			// TODO: Herausnehmen....
			$this->dbuser = 'julian1828';
			$this->dbpassword = '14dwf1_mem';
		}

		$this->view = new View();
		$this->request = $request;
		// Wenn eine View durch den Request angegeben wurde, den Standard "main" nutzen
		$this->template = !empty($request['view']) ? $request['view'] : 'main';

		// Applikation prüfen
		if (sizeof($errors = $this->checkApplication()) > 0) {
			// Applikation ist nicht nutzbar -> Fehlermeldungen ausgeben
			$this->view->setTemplate('errors');
			$this->view->assign('errors', $errors);
			die($this->renderView($this->view));
		}
	}

	/**
	 * Zeigt das Dashboard an
	 */
	public function index() {
		$view = new View();
		$view->setTemplate('dashboard');
		$view->assign ( 'username', isset ( $_SESSION ['username'] ) ? $_SESSION ['username'] : '' );
		$view->assign('devErrors', $this->checkLocale());
		return $this->renderView($view);
	}
	
	/**
	 * Prüft, ob alle Lokalisierungsdateien valide und vollständig sind
	 * @return string[] Fehlermeldungen aus XML-Validation
	 */
	protected function checkLocale() {
		/** @var Configuration $conf */
		$conf = $_SESSION ['config'];
		$allLocs = [ ];
		$currLoc = [ ];
		$valErrors = [ ];
		$dir = 'locale';
		foreach ( glob ( $dir . DS . '*.xml' ) as $f ) {
			if ($f !== '.' && $f != '..') {
				$fObj = fopen ( $f, 'r' );
				libxml_use_internal_errors ( true );
				libxml_clear_errors ();
				$xml = simplexml_load_file ( $f );
				
				if (sizeof ( libxml_get_errors () ) > 0) {
					foreach ( libxml_get_errors () as $error ) {
						$valErrors ['Ungültige XML Datei: ' . $f . ': ' . $error->message . 'Line: ' . $error->line] = '';
					}
					continue;
				}
				
				$dom = new DOMDocument ();
				$dom->loadXML ( $xml->asXML () );
				
				libxml_clear_errors ();
				@$dom->schemaValidate ( 'locale/locale.xsd' );
				$errors = libxml_get_errors ();
				foreach ( $errors as $error ) {
					$valErrors ['XML Validation Error: ' . $f . ': ' . $error->message . 'Line: ' . $error->line] = '';
				}
				libxml_clear_errors ();
				
				foreach ( $xml->children () as $c ) {
					$allLocs [( string ) $c->attributes ()->id] = '_';
					if (strtolower ( $f ) == strtolower ( $dir . DS . $conf->getLanguage () . '.xml' )) {
						$currLoc [( string ) $c->attributes ()->id] = '_';
					}
				}
				fclose ( $fObj );
			}
		}
		
		// Jetzt alle Einträge mit der aktuell ausgewähten Sprache vergleichen
		return array_merge ( $valErrors, array_diff_key ( $allLocs, $currLoc ) );
	}

	/**
	 * Prüft, ob die Anwendung gestartet werden kann
	 * @return string[] Alle gefundenen Fehler
	 */
	protected function checkApplication() {
		$errors = [];
		// Datenbank prüfen
		$errors = array_merge($errors, $this->checkDatabaseConnection());
		return $errors;
	}

	/**
	 * Prüft die Datenbankverbindung für die Anwendung
	 * @return string[]
	 */
	protected function checkDatabaseConnection() {
		$errors = [];
		if (!(function_exists('mysql_connect') or function_exists('mysqli_connect'))) {
			// Das MySQL-Modul ist nicht aktiviert
			$errors[] = 'MySQL ist nicht aktiviert!';
		}
		else {
			if (!$this->dbConnect()) {
				$errors[] = 'Es konnte keine Verbindung zur Datenbank hergestellt werden!';
			}
			else {
				if (!$this->checkDatabaseTables()) {
					$errors[] = 'Die Datenbank-Tabellen konnten nicht erstellt werden!';
					$errors[] = $this->dbError();
				}
			}
		}
		return $errors;
	}

	/**
	 * Erstellt die benötigte Datenbank
	 * @return bool True bei Erfolg, sonst False
	 */
	private function createDatabase() {
		return ($this->query('CREATE DATABASE ' . $this->db . ';') ? true : false);
	}

	/**
	 * Erstellt alle benötigten Datenbank-Tabellen
	 * @return bool True bei Erfolg, sonst False
	 */
	private function checkDatabaseTables() {
		$tables = [
			// Benutzer
			'user' => 'CREATE TABLE `user` (
					`userID` INT NOT NULL AUTO_INCREMENT ,
					`name` VARCHAR(255) NOT NULL,
					`password` VARCHAR(255),
					PRIMARY KEY (`userID`)
				) ENGINE = InnoDB;',

			// Ergebnisse
			'result' => 'CREATE TABLE `result` (
					`ID` INT NOT NULL AUTO_INCREMENT ,
					`F_userID` INT,
					`date` DATE,
					`totaltime` TIME,
					PRIMARY KEY (`ID`),
					FOREIGN KEY `F_results_user`(F_userID) REFERENCES user(userID) ON DELETE RESTRICT
				) ENGINE = InnoDB;'];
		foreach ($tables as $table => $sql) {
			if (!$this->query('SELECT 1 FROM ' . $table . ' LIMIT 1')) {
				// Die Tabelle existiert noch nicht -> anlegen
				if (!$this->query($sql)) {
					// Die Tabelle konnte nicht angelegt werden
					return false;
				}
			}
		}
		
		// Sprache Userabhängig ist im Nachhinein hinzugekommen
		$res = $this->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = 'user' AND COLUMN_NAME = 'language'");
		if (null === $this->fetch_object($res)) {
			// Spalte existiert noch nicht
			if (!$this->query("ALTER TABLE user ADD language VARCHAR(50) DEFAULT 'english'")) {
				// Die Tabelle konnte nicht geändert werden
				return false;
			}
		}
		
		//Spalte flips ist im Nachhinein hinzugekommen
		$res = $this->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = 'result' AND COLUMN_NAME = 'flips'");
		if (null === $this->fetch_object($res)) {
			// Spalte existiert noch nicht
			if (!$this->query("ALTER TABLE result ADD flips INT DEFAULT -1")) {
				// Die Tabelle konnte nicht geändert werden
				return false;
			}
		}
		return true;
	}

	/**
	 * Gibt die letzte Datenbank-Fehlermeldung zurück
	 * @return string Fehlermeldung der Datenbank
	 */
	protected function dbError() {
		if (function_exists('mysql_error'))
			return mysql_error();
		elseif (function_exists('mysqli_error'))
			return mysqli_error($this->dbConnection);
		return '';
	}

	/**
	 * Stellt eine Verbindung zu einer MySQL-Datenbank her
	 * @return bool True bei Erfolg, sonst False
	 */
	protected function dbConnect() {
		if ($this->dbConnection == null) {
			if (function_exists('mysql_connect')) {
				if ($this->dbConnection = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword)) {
					return $this->dbSelectDB();
				}
			}
			elseif (function_exists('mysqli_connect')) {
				if ($this->dbConnection = @mysqli_connect($this->dbhost, $this->dbuser, $this->dbpassword)) {
					return $this->dbSelectDB();
				}
			}
			return false;
		}
		return true;
	}

	/**
	 * Wählt die MySQL-Datenbank aus.
	 * Falls die Datenbank nicht exitiert, wird sie angelegt
	 * @return bool True bei Erfolg, sonst False
	 */
	private function dbSelectDB() {
		if (function_exists('mysql_select_db')) {
			if (mysql_select_db($this->db)) {
				return true;
			}
			else {
				if ($this->createDatabase()) {
					if (mysql_select_db($this->db)) {
						return true;
					}
				}
			}
		}
		elseif (function_exists('mysqli_select_db')) {
			if (mysqli_select_db($this->dbConnection, $this->db)) {
				return true;
			}
			else {
				if ($this->createDatabase()) {
					if (mysqli_select_db($this->dbConnection, $this->db)) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Führt eine SQL-Anweisung aus
	 * @param string $sql Die MySQL-Anweisung, die ausgeführt werden soll
	 * @return resource Ergebnis der MySQL-Anweisung
	 */
	protected function query($sql) {
		if ($this->dbConnect()) {
			if (function_exists('mysql_query')) {
				return mysql_query($sql);
			}
			elseif (function_exists('mysqli_query')) {
				return mysqli_query($this->dbConnection, $sql);
			}
		}
		return false;
	}

	/**
	 * Mappt das Ergebnis einer SQL-Anweisung auf ein Objekt und gibt dieses zurück
	 * @param resource $res Ergebnis der SQL-Anweisung
	 * @return mixed Das von der Datenbank erhaltene Objekt
	 */
	protected function fetch_object($res) {
		if (function_exists('mysql_fetch_object')) {
			return mysql_fetch_object($res);
		}
		elseif (function_exists('mysqli_fetch_object')) {
			return mysqli_fetch_object($res);
		}
		return null;
	}

	/**
	 * Rendert eine View.
	 * Wird keine View als Parameter angegeben, so wird die Standard-View aus dem Attribut "template" genutzt
	 * @param View $view Die View, die gerendert werden soll, Optional
	 * @return string Die gerenderte View
	 */
	protected function renderView(View $view = null) {
		$view = (isset($view)) ? $view : $this->view;

		$v = new View();
		$v->setTemplate($this->template);

		$v->assign('footer', '');
		$v->assign('menu', $this->getMenu());
		$v->assign('content', $view->loadTemplate());

		return $v->loadTemplate();
	}

	/**
	 * Gibt das Menü der Anwendung zurück
	 * @return string Das gerenderte Menü
	 */
	protected function getMenu() {
		$menu = new View();
		$menu->setTemplate('nav');
		return $menu->loadTemplate();
	}

	/**
	 * Legt das aktuelle Template fest
	 * @param string $template Das Template, das genutzt werden soll
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * Gibt das aktuelle Template zurück
	 * @return string Das aktuell ausgewählte Template
	 */
	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Legt die aktuelle Sprache der Anwendung fest
	 * @param string $lang Die Sprache, die genutzt werden soll
	 * @return string Weiterleitung auf den index
	 */
	public function setLanguage($lang) {
		if (substr($lang, 0, 5) == 'lang-') {
			$lang = substr($lang, 5);
		}

		/** @var  Configuration $conf */
		$conf = $_SESSION['config']; 
		$conf->setLanguage($lang);
		
		$sql = "UPDATE user SET language = '". $conf->getLanguage() . "' WHERE LOWER(name) = LOWER('".$_SESSION['username']."')";
		$this->query($sql);
		
		return $this->index();
	}
}
?>