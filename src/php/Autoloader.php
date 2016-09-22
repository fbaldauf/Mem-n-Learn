<?php
class Autoloader {
	private static $basisPfad = null;

	public static function autoload($klasse) {
		if (self::$basisPfad === null)
			self::$basisPfad = dirname(__FILE__);

		if (strpos($klasse, '.') !== false || strpos($klasse, '/') !== false || strpos($klasse, '\\') !== false || strpos($klasse, ':') !== false) {
			return;
		}
		// $teile = preg_split('/(?<=.)(?=\p{Lu}\P{Lu})|(?<=\P{Lu})(?=\p{Lu})/U', substr($klasse, 8));
		// $pfad = self::$basisPfad . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $teile) . '.php';

		$pfad = self::$basisPfad . DS . 'entities' . DS . $klasse . '.php';


		if (!file_exists($pfad)){
			$pfad = self::$basisPfad . DIRECTORY_SEPARATOR . $klasse . '.php';
			if (!file_exists($pfad)) {
			echo 'Nicht gefunden: '.$pfad ;
				return;
			}
		}

		include_once $pfad;
	}
}