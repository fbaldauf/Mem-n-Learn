<?php
class View {
	
	// Pfad zum Template
	private $path = 'templates';
	// Name des Templates, in dem Fall das Standardtemplate.
	private $template = 'default';
	// Objekt für die Übersetzungen
	private $trans = null;
	
	/**
	 * Enthält die Variablen, die in das Template eingebetet
	 * werden sollen.
	 */
	private $_ = array ();
	
	/**
	 * Erzeugt eine neue Instanz vom Typ View
	 */
	public function __construct() {
		$this->loadTranslation ();
		// Falls Parameter übergeben wurden, wird ein entsprechender Konstruktor aufgerufen
		$a = func_get_args ();
		$i = func_num_args ();
		if (method_exists ( $this, $f = '__construct' . $i )) {
			call_user_func_array ( array (
					$this,
					$f 
			), $a );
		}
	}
	
	/**
	 * Erzeugt eine neue Instanz vom Typ View
	 *
	 * @param string $template
	 *        	Das Template, das genutzt werden soll
	 */
	public function __construct1($template) {
		$this->setTemplate ( $template );
	}
	
	/**
	 * Setzt die aktuell benutzte Sprache zurück
	 */
	public function resetTranslation() {
		$this->trans = null;
	}
	
	/**
	 * Lädt die Datei mit den Übersetzungen
	 */
	protected function loadTranslation() {
		$lang = 'english';
		if (isset ( $_SESSION )) {
			/** @var Configuration $conf */
			$conf = @$_SESSION ['config'];
			$lang = $conf->getLanguage ();
		}
		
		// Die Klasse einbinden
		include_once 'includes/locale/SimpleTranslation.php';
		// Neue Instanz der Klasse erstellen
		$langObj = new SimpleTranslation ();
		// Übersetzungsdatei laden
		$langObj->loadTranslationFile ( "locale/" . $lang . ".xml" );
		$this->trans = $langObj;
	}
	
	/**
	 * Funktion zum Übersetzen von Texten
	 * 
	 * @param string $txt
	 *        	ID des zu Übersetzenden Textes
	 * @return string Der Übersetzte Text
	 */
	public function _($txt) {
		$lang = 'english';
		if (isset ( $_SESSION ['config'] )) {
			/** @var Configuration $conf */
			$conf = $_SESSION ['config'];
			$lang = $conf->getLanguage ();
		}
		if ($this->trans == null) {
			$this->loadTranslation ();
		} elseif ($this->trans->getTrans () !== $lang) {
			$this->loadTranslation ();
		}
		if ($this->trans == null) {
			return '';
		}
		
		$params = [ 
				$lang 
		];
		$params = array_merge ( $params, func_get_args () );
		
		return call_user_func_array ( [ 
				$this->trans,
				'printText' 
		], $params );
	}
	
	/**
	 * Ordnet eine Variable einem bestimmten Schl&uuml;ssel zu.
	 *
	 * @param String $key
	 *        	Schlüssel
	 * @param String $value
	 *        	Variable
	 */
	public function assign($key, $value) {
		$this->_ [$key] = $value;
	}
	
	/**
	 * Setzt den Namen des Templates.
	 *
	 * @param String $template
	 *        	Name des Templates.
	 */
	public function setTemplate($template = 'default') {
		$this->template = $template;
	}
	
	/**
	 * Das Template-File laden und zurückgeben
	 *
	 * @param string $tpl
	 *        	Der Name des Template-Files (falls es nicht vorher
	 *        	über steTemplate() zugewiesen wurde).
	 * @return string Der Output des Templates.
	 */
	public function loadTemplate() {
		$tpl = $this->template;
		// Pfad zum Template erstellen & überprüfen ob das Template existiert.
		$file = $this->path . DIRECTORY_SEPARATOR . $tpl . '.php';
		$exists = file_exists ( $file );
		
		if ($exists) {
			// Der Output des Scripts wird n einen Buffer gespeichert, d.h.
			// nicht gleich ausgegeben.
			ob_start ();
			
			// Das Template-File wird eingebunden und dessen Ausgabe in
			// $output gespeichert.
			include $file;
			$output = ob_get_contents ();
			ob_end_clean ();
			
			// Output zurückgeben.
			return $output;
		} else {
			// Template-File existiert nicht-> Fehlermeldung.
			return 'could not find template: ' . $file;
		}
	}
}
?>