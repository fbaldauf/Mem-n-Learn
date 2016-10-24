<?php

/**
 * Antwort für JavaScript.
 * Konvertiert automatisch ins JSON Format
 *
 */
class JavaScriptResponse {
	
	/**
	 * Inhalt der Antwort
	 * 
	 * @var array
	 */
	private $content = array ();
	
	/**
	 * Legt den Inhalt der Antwort fest
	 * 
	 * @param
	 *        	mixed Inhalt der Antwort
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 * Beim Konertieren in einen String ins JSON-Format übertragen
	 * 
	 * @return string Antwort im JSON Format
	 */
	public function __toString() {
		return json_encode ( $this->content );
	}
}