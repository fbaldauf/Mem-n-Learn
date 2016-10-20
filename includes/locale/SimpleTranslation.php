<?php

// aus https://www.drweb.de/magazin/php-lokalisierung-auf-basis-von-xml/

class SimpleTranslation {
	var $xml;

	function loadTranslationFile($file) {
		if (is_file($file)) {
			if ($this->xml = simplexml_load_file($file)) {
				return true;
			}
		}
		else {
			echo "Die Datei " . $file . " konnte nicht geladen werden!<br />";
			// break;
		}
	}

	function printText($lang, $txt_id) {
		if ($this->xml != "") {
			$path = "/language[@id=\"" . strtoupper($lang) . "\"]/loctext[@id=\"$txt_id\"]";
			$res = $this->xml->xpath($path);

			if (isset($res[0]->text)) {
				$params = func_get_args();
				$params = array_slice($params, 2);
				$params = array_merge([$res[0]->text], $params);
				return call_user_func_array('sprintf', $params);
			}
			else {
				return ('<span style="color: red">loc: ' . $txt_id . ' not found!</span>');
			}
		}
		else {
			return "Bitte laden Sie zuerst eine Übersetzungsdatei!";
		}
	}

	function loadText($lang, $txt_id) {
		if ($this->xml != "") {
			$path = "/language[@id=\"$lang\"]/loctext[@id=\"$txt_id\"]";
			$res = $this->xml->xpath($path);
			$translation = $res[0]->text;
		}
		else {
			echo "Bitte laden Sie zuerst eine Übersetzungsdatei!";
		}
		return $translation;
	}

	function loadLangArray($lang) {
		if ($this->xml != "") {
			$translations = array();
			$path = "/language[@id=\"$lang\"]";
			$res = $this->xml->xpath($path);
		}
		else {
			echo "Bitte laden Sie zuerst eine Übersetzungsdatei!";
		}
		return $res;
	}
}