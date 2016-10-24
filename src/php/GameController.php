<?php
/**
 * Klasse, die für das Memory-Spiel genutzt wird.
 * Es lädt die benötigten Daten und speichert die Ergebnisse
 */
class GameController extends AppController {

	/**
	 * Das Bild, das standardmäßig für den Kartenrücken genutzt werden soll
	 * @var string
	 */
	private $defaultCard = 'templates/img/yugioh-card-back.png';

	/**
	 * XML-Datei der Karten
	 * @var string
	 */
	private $cardFiles = 'data/cards.xml';

	/**
	 * Konstruktor
	 * @param mixed $request HTTP-Request
	 */
	public function __construct($request) {
		// Konstruktor der Elternklasse aufrufen
		parent::__construct($request);
	}

	/**
	 * Ein neues Spiel wird gestartet.
	 * Die benötigten Daten und Einstellungen werden geladen und zurückgegeben
	 * @return array Alle Daten und Einstellungen für ein neues Spiel
	 */
	public function startNew() {
		// Die View für das Spiel festlegen
		$this->view = new View();
		$this->view->setTemplate('game');
		//TODO: nötig?
		$this->view->assign('cards', $this->getCards());

		// Die View für den Container einer Karte festlegen
		$cardContainer = new View('cardContainer');

		// Die Rückgabe ist eine JavaScriptResponse im JSON-Format, die von Javascript ausgelesen werden kann.
		$res = new JavaScriptResponse();
		$res->setContent(
			['view' => $this->renderView(), // View für das Spielfeld

			'cards' => $this->getCards(), // Alle Karten für das aktuelle Spielfeld

			'cardContainer' => $cardContainer->loadTemplate(), // View für den Container der Karten

			'defaultCard' => $this->defaultCard, // Name der Grafik, die für die Kartenrückseite genutzt werden soll

			'language' => 'german']); // Sprache, die für das Spiel genutzt werden soll

		return $res;
	}

	/**
	 * Speichert die Ergebnisse am Ende eines Spieles
	 * @return array
	 */
	public function save() {
		$response = new JavaScriptResponse();
		$sql = 'INSERT INTO result (`F_userID`, `date`, `totaltime`, `flips`) ';
		$sql .= 'VALUES (' . $_SESSION['ID'] . ', \'' . date('Y-m-d', time()) . '\', SEC_TO_TIME(' . $this->request['time'] . '), '. $this->request['flips'] .')';
		$response->setContent(($this->query($sql)) ? true : [false, $this->dbError()]);

		return $response;
	}

	/**
	 * Gibt eine zufällig gemischte Liste an Karten für ein neues Spiel zurück
	 * @return Card[] Liste der Karten
	 */
	public function getCards() {
		$cards = [];
		// 15 Karten aus einer XML-Datei laden
		$cards = $this->loadCardsFromXML(15);
		return $cards;
	}

	/**
	 * Lädt die angegebene Anzahl an Karten aus einer XML-Datei und gibt diese als Liste zurück
	 * @param int $c
	 * @return Card[]
	 */
	private function loadCardsFromXML($c) {
		$cards = [];
		
		if (!file_exists($this->cardFiles)) {
			// XML-Datei nicht gefunden -> Leere Liste zurückgeben
			return [];
		}
		$xml = new SimpleXMLElement(file_get_contents($this->cardFiles));

		foreach ($xml->children() as $card) {
			$german = '' . $card->translations->german;
			$translation = '' . $card->translations->german;
			$image = 'templates/img/cards/' . $card->image;
			
			if ($german !== '' and $translation !== '' and $image !== '' and file_exists($image)) {
				$cards[] = new Card($german, $translation, $image);
			}
		}
		shuffle($cards);
		return array_slice($cards, 0, $c);
	}
}