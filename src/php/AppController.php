<?php
class AppController {
	protected $request = null;
	private $template = '';
	/**
	 *
	 * @var View
	 */
	protected $view = null;
	private $dbConnection = null;
	protected $db = 'julian1828';
	protected $dbuser = 'root';
	protected $dbpassword = '';
	protected $dbhost = 'localhost';
	
	/**
	 * Konstruktor, erstellet den Controller.
	 *
	 * @param Array $request
	 *        	Array aus $_GET & $_POST.
	 */
	public function __construct($request) {
		if ($_SERVER ['SERVER_NAME'] === 'julian1828.bplaced.net') {
			$this->dbuser = 'julian1828';
			$this->dbpassword = '14dwf1_mem';
		}
		
		$this->view = new View ();
		$this->request = $request;
		$this->template = ! empty ( $request ['view'] ) ? $request ['view'] : 'main';
		
		if (sizeof ( $errors = $this->checkApplication () ) > 0) {
			$this->view->setTemplate ( 'errors' );
			$this->view->assign ( 'errors', $errors );
			die ( $this->renderView ( $this->view ) );
		}
	}
	
	/**
	 * Methode zum anzeigen des Contents.
	 *
	 * @return String Content der Applikation.
	 */
	public function execute() {
		// var_dump($this->request);//die();
		// $view = new View();
		// switch ($this->template) {
		// case 'entry':
		// $view->setTemplate('entry');
		// $entryid = $this->request['id'];
		// $entry = Model::getEntry($entryid);
		// $view->assign('title', $entry['title']);
		// $view->assign('content', $entry['content']);
		// break;
		
		// case 'json':
		// return new JavaScriptResponse();
		// break;
		
		// case 'default':
		// default:
		// $entries = Model::getEntries();
		// $view->setTemplate('game');
		// $view->assign('entries', $entries);
		// }
		
		// $this->view->setTemplate('main');
		// $this->view->assign('footer', '');
		// $this->view->assign('menu', $this->getMenu());
		// $this->view->assign('content', $view->loadTemplate());
		// return $this->view->loadTemplate();
	}
	public function index() {
		$view = new View ();
		$view->setTemplate ( 'dashboard' );
		$view->assign ( 'username', $_SESSION ['username'] );
		
		return $this->renderView ( $view );
	}
	
	/**
	 * Prüft, ob die Anwendung gestartet werden kann
	 */
	protected function checkApplication() {
		$errors = [ ];
		$errors = array_merge ( $errors, $this->checkDatabaseConnection () );
		return $errors;
	}
	/**
	 * Prüft die Datenbankverbindung für die Anwendung
	 *
	 * @return string[]
	 */
	protected function checkDatabaseConnection() {
		$errors = [ ];
		if (! (function_exists ( 'mysql_connect' ) or function_exists ( 'mysqli_connect' ))) {
			$errors [] = 'MySQL ist nicht aktiviert!';
		} else {
			if (! $this->dbConnect ()) {
				$errors [] = 'Es konnte keine Verbindung zur Datenbank hergestellt werden!';
			} else {
				if (! $this->checkDatabaseTables ()) {
					$errors [] = 'Die Datenbank-Tabellen konnten nicht erstellt werden!';
					$errors [] = $this->dbError ();
				}
			}
		}
		return $errors;
	}
	
	/**
	 * Erstellt die benötigte Datenbank
	 */
	private function createDatabase() {
		return $this->query ( 'CREATE DATABASE julian1828;' );
	}
	
	/**
	 * Erstellt alle benätigten Datenbank-Tabellen
	 */
	private function checkDatabaseTables() {
		$tables = [ 
				'testtable' => 'CREATE TABLE testtable(id varchar(20))',
				'user' => 'CREATE TABLE `user` (
					`userID` INT NOT NULL AUTO_INCREMENT ,
					`name` VARCHAR(255) NOT NULL,
					`password` VARCHAR(255),
					PRIMARY KEY (`userID`)
				) ENGINE = InnoDB;',
				'result' => 'CREATE TABLE `result` (
					`F_userID` INT,
					`date` DATE,
					`totaltime` TIME,
					FOREIGN KEY `F_results_user`(F_userID) REFERENCES user(userID) ON DELETE RESTRICT
				) ENGINE = InnoDB;' 
		];
		foreach ( $tables as $table => $sql ) {
			if (! $this->query ( 'SELECT 1 FROM ' . $table . ' LIMIT 1' )) {
				if (! $this->query ( $sql )) {
					echo $sql;
					return false;
				}
			}
		}
		return true;
	}
	protected function dbError() {
		if (function_exists ( 'mysql_error' ))
			return mysql_error ();
		elseif (function_exists ( 'mysqli_error' ))
			return mysqli_error ( $this->dbConnection );
		return '';
	}
	
	/**
	 * Stellt eine Verbindung zu einer MySQL-Datenbank her
	 */
	protected function dbConnect() {
		if ($this->dbConnection == null) {
			if (function_exists ( 'mysql_connect' )) {
				if ($this->dbConnection = @mysql_connect ( $this->dbhost, $this->dbuser, $this->dbpassword )) {
					return $this->dbSelectDB ();
				}
			} elseif (function_exists ( 'mysqli_connect' )) {
				if ($this->dbConnection = @mysqli_connect ( $this->dbhost, $this->dbuser, $this->dbpassword )) {
					return $this->dbSelectDB ();
				}
			}
			return false;
		}
		return true;
	}
	/**
	 * Wählt die MySQL-Datenbank aus
	 *
	 * @return boolean
	 */
	private function dbSelectDB() {
		if (function_exists ( 'mysql_select_db' )) {
			if (mysql_select_db ( $this->db )) {
				return true;
			} else {
				if ($this->createDatabase ()) {
					if (mysql_select_db ( $this->db )) {
						return true;
					}
				}
			}
		} elseif (function_exists ( 'mysqli_select_db' )) {
			if (mysqli_select_db ( $this->dbConnection, $this->db )) {
				return true;
			} else {
				if ($this->createDatabase ()) {
					if (mysqli_select_db ( $this->dbConnection, $this->db )) {
						return true;
					}
				}
			}
		}
		return false;
	}
	protected function query($sql) {
		if ($res = $this->dbConnect ()) {
			if (function_exists ( 'mysql_query' )) {
				return mysql_query ( $sql );
			} elseif (function_exists ( 'mysqli_query' )) {
				return mysqli_query ( $this->dbConnection, $sql );
			}
		}
	}
	protected function fetch_object($res) {
		if (function_exists ( 'mysql_fetch_object' )) {
			return mysql_fetch_object ( $res );
		} elseif (function_exists ( 'mysqli_fetch_object' )) {
			return mysqli_fetch_object ( $res );
		}
	}
	protected function renderView(View $view = null) {
		$view = (isset ( $view )) ? $view : $this->view;
		
		$v = new View ();
		$v->setTemplate ( $this->template );
		
		$v->assign ( 'footer', '' );
		$v->assign ( 'menu', $this->getMenu () );
		$v->assign ( 'content', $view->loadTemplate () );
		
		return $v->loadTemplate ();
	}
	protected function getMenu() {
		$menu = new View ();
		$menu->setTemplate ( 'nav' );
		return $menu->loadTemplate ();
	}
	public function setTemplate($template) {
		$this->template = $template;
	}
	public function setLanguage($lang) {
		if (substr ( $lang, 0, 5 ) == 'lang-') {
			$lang = substr ( $lang, 5 );
		}
		$_SESSION ['config']->setLanguage ( $lang );
		return $this->index ();
	}
}
?>