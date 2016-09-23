<?php

/**
 * Klasse für die Entität einer Spielkarte.
 * Sie enthält alle Attribute, die die Daten der Karte hält.
 */
class Card implements JsonSerializable {
	/**
	 * Bild der Karte
	 * @var string
	 */
	private $image = 'blank.jpg';
	/**
	 * Wort der Karte
	 * @var string
	 */
	private $word = 'untitled';
	/**
	 * Configuration (evtl.
	 * hier an dieser Stelle nicht benötigt)
	 * @TODO: Prüfen, ob dies hier benötigt wird
	 * @var Configuration
	 */
	private $c;

	/**
	 * Erzeugt eine neue Instanz vom Typ Card
	 */
	public function __construct() {
		$this->c = $_SESSION['config'];

		// Falls Parameter übergeben wurden, wird ein entsprechender Konstruktor aufgerufen
		$a = func_get_args();
		$i = func_num_args();
		if (method_exists($this, $f = '__construct' . $i)) {
			call_user_func_array(array($this, $f), $a);
		}
	}

	/**
	 * Erzeugt eine neue Instanz vom Typ Card
	 * @param string $word
	 * @param string $image
	 */
	public function __construct2($word, $image) {
		$this->image = $image;
		$this->word = $word;
	}

	/**
	 * Gibt den Namen des Bilden zurück
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Gibt das Wort der Karte zurück
	 * @return string
	 */
	public function getWord() {
		return $this->word;
	}

	/**
	 * Für die Implementierung von jsonSerialize.
	 * Hier werden die entsprechenden Attribute zu einem JSON-Objekt serialisiert.
	 * {@inheritDoc}
	 *
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		return ['image' => $this->image, 'word' => $this->word];
	}
}