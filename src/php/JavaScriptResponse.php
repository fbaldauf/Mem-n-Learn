<?php
class JavaScriptResponse implements Response {

	private $content = array();

	public function setContent($content) {
		$this->content = $content;
	}

	public function __toString() {
		return json_encode($this->content);
	}
}