<?php
class Card implements JsonSerializable {
	private $image = 'blank.jpg';
	private $word = 'untitled';
	private $c;

	public function __construct(){
		$this->c = $_SESSION['config'];
	}

	public function getImage(){
		return $this->image;
	}
	public function getWord() {
		return $this->word;
	}
	
	public function jsonSerialize() {
		return [$this->image, $this->word];
	}
}