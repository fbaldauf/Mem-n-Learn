<?php
class Configuration {
	private $language = '';

	public function getLanguage() {
		return $this->language;
	}

	public function setLanguage($l) {
		$this->language = (is_string($l) ? $l : 'english');
		//die('Sprache ist jetzt: ' . $this->language);
	}

	public function __sleep() {
		return ['language'];
	}
}