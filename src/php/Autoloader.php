<?php
/**
 * Klasse zum Laden der PHP-Klassen
 */
class Autoloader {

	/**
	 * Durchsucht ein Verzeichnis nach einer Datei mit dem Namen der gewünschten Klasse
	 * @param string $klasse Name der zu ladenen Klasse
	 */
	public static function autoload($klasse) {
		$basisPfad = dirname(__FILE__);

		if (strpos($klasse, '.') !== false || strpos($klasse, '/') !== false || strpos($klasse, '\\') !== false || strpos($klasse, ':') !== false) {
			// Klasse hat einen ungültigen Namen
			return;
		}

		// Erst im Verzeichnis "entities" suchen
		$pfad = $basisPfad . DS . 'entities' . DS . $klasse . '.php';

		if (!file_exists($pfad)) {
			// Nicht im Verzeichnis "entities" gefunden, also im Hauptverzeichnis suchen
			$pfad = $basisPfad . DIRECTORY_SEPARATOR . $klasse . '.php';
			if (!file_exists($pfad)) {
				// Datei der Klasse nicht gefunden
				echo 'Nicht gefunden: ' . $pfad;
				return;
			}
		}

		//Datei der Klasse einbindenF
		include_once $pfad;
	}
}