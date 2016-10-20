<?php
class UserController extends AppController {

	public function __construct() {
		// Falls Parameter übergeben wurden, wird ein entsprechender Konstruktor aufgerufen
		if (func_num_args() == 1) {
			parent::__construct(func_get_arg(0));
		}
	}

	public function index() {
		$this->view = new View();
		$this->view->setTemplate('userstats');

		$data = $this->getStatistics();
		$this->view->assign('data', $data);
		$this->view->assign('username', $_SESSION['username']);

		return $this->renderView();
		// return json_encode(['view' => $this->renderView(), 'data' => $data, 'user' => $_SESSION['username']]);
	}

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
		$sql = "SELECT date, totaltime, TIME_TO_SEC(totaltime) / 60 as timeMinutes FROM `result` WHERE F_userID = '$sessionuserID' ORDER By date asc";
		$ergebnistabellerow = $this->query($sql);
		$data['games'] = [];

		while ($row = $this->fetch_object($ergebnistabellerow)) {
			$tmpDate = new DateTime($row->date);
			$data['games'][] = ['date' => $tmpDate->format('d.m.Y'), 'time' => $row->totaltime, 'timeMinutes' => $row->timeMinutes,
				'dateObj' => $tmpDate];
		}

		return $data;
	}

	public function login() {
		$check = false;
		$this->view = new View();

		// Prüfe ob Login erfolgreich
		if (isset($this->request['user']) and isset($this->request['password'])) {
			$check = $this->checkLogin($this->request['user'], $this->request['password']);
			if (!$check) {
				$this->view->assign('errmessage', 'Fehlerhafter Login');
			}
		}

		if (!$check) {
			// Wenn nicht erfolgreich, zeige Formular
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

		// Prüfe ob Login erfolgreich
		if (isset($this->request['user']) and isset($this->request['password'])) {
			$check = $this->checkRegister($this->request['user'], $this->request['password']);
			if (!$check) {
				$this->view->assign('errmessage', 'Fehlerhafte Registrierung');
			}
		}

		if (!$check) {
			// Wenn nicht erfolgreich, zeige Formular
			$this->view->setTemplate('register');
			return $this->renderView();
		}
		return $this->login();
	}

	public function isLoggedIn() {
		if (isset($_SESSION['eingeloggt']) and $_SESSION['eingeloggt'] === true) {
			return true;
		}
		return false;
	}

	protected function checkLogin($user, $password) {
		$_SESSION['eingeloggt'] = false;
		$this->query(
			'UPDATE user SET password=\'' . md5($password) . '\' WHERE password=\'' . $password . '\' AND LOWER(name)=LOWER(\'' . $user . '\')');

		// $verbindung = new pdo ( 'mysql:dbname=' . $this->db . ';host=' . $this->dbhost . ';port=3306', $this->dbuser, $this->dbpassword );
		// $verbindung->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		// $abfrage = 'SELECT name, password, userID FROM user WHERE name = ? AND password = ?';
		$abfrage = "SELECT name, password, userID FROM user WHERE LOWER(name) = LOWER('$user') AND password = '" . md5($password) . "'";

		// $stmt = $verbindung->prepare ( $abfrage );

		// $stmt->bindParam ( 1, $user, PDO::PARAM_STR );
		// $stmt->bindParam ( 2, $password, PDO::PARAM_STR );

		// $status = $stmt->execute ();

		if (!$res = $this->query($abfrage)) {
			echo "Abfrage fehlgeschlagen.";
		}

		if (!$row = $this->fetch_object($res))
			return false;
		$id = $row->name;

		// echo "name:".$id;

		if (!empty($id)) {
			$_SESSION['eingeloggt'] = true;
			$_SESSION['username'] = $user;
			$_SESSION['ID'] = $row->userID;
		}

		return $this->isLoggedIn();
	}

	protected function checkRegister($user, $password) {
		$insert = "Insert into User(name,password) values ('$user','" . md5($password) . "')";
		return $this->query($insert);
	}

	public function exportStatistics() {
		$stats = $this->getStatistics();
		$xml = new SimpleXMLElement('<root />');
		$xml->addChild('username', $_SESSION['username']);

		$xml->addChild('generated', date('Y-m-d\TH:i:s', time()));
		$results = $xml->addChild('results');
		foreach ($stats['games'] as $game) {
			$result = $results->addChild('result');
			$result->addChild('date', $game['dateObj']->format('Y-m-d'));
			$result->addChild('time', $game['time']);
			$result->addChild('flips', 5);
		}

		$dom = new DOMDocument();
		$dom->loadXML($xml->asXML());
		if (!$dom->schemaValidate('data/results.xsd')) {
			echo 'error';
		}

		// echo htmlspecialchars($xml->asXML());

		$xsl = new DOMDocument();
		$xsl->load('data/results.xsl');

		// Prozessor instanziieren und konfigurieren
		$proc = new XSLTProcessor();
		$proc->importStyleSheet($xsl); // XSL Document importieren

		// echo $proc->transformToXML($dom);

		// make request to FOP server
		require 'includes/xsl-fo/HTTPPost.php';
		$httppost = new HTTPPost();
		$pdfdata = @$httppost->post_request("localhost", "8087", getcwd() . "/data/export.fo", $xml->asXML());

		if ($pdfdata['status'] == 'err') {
			echo '<strong>Fehler</strong><br />';
			echo $pdfdata['error'] . '<br />';
			echo '<a href="statistic">Zurück</a>';
			return;
		}

		$err = substr($pdfdata, 0, 6);
		if ($err == '<html>') {
			// Fehler beim Generieren des PDF-Dokumentes
			echo ($pdfdata);
		}

		// save PDF output to a PDF file
		$myFile = "testFile.pdf";
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $pdfdata);
		fclose($fh);

		header("Content-Type: application/pdf");
		header("Content-Disposition: attachment; filename=\"Ergebnisse.pdf\"");
		readfile($myFile);
	}
}