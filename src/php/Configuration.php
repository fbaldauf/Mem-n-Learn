<?php
class Configuration {
	/**
	 * Aktuell ausgewählte Sprache
	 * @var string
	 */
	private $language = 'english';

	/**
	 * Gibt die aktuelle Sprache zurück
	 * @return string Die aktuelle Sprache
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Legt die aktuelle Sprache fest
	 * @param string $l Die Sprache, die genutzt werden soll
	 */
	public function setLanguage($l) {
		$this->language = (is_string($l) ? $l : 'english');
	}

	/**
	 * Da das Objekt in der Session serialisiert, muss diese Funktion implementiert werden.
	 * Sie legt fest, welche Attribute bei der Serialisierung erhalten werden sollen
	 * @return string[] Alle Attribute, die bei einer Serialisierung erhalten werden
	 */
	public function __sleep() {
		return ['language'];
	}
}