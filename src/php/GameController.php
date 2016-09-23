<?php
class GameController extends AppController {
	private $defaultCard = 'templates/img/yugioh-card-back.png';
	public function __construct($request) {
		parent::__construct ( $request );
	}
	public function startNew() {
		$this->view = new View ();
		$this->view->setTemplate ( 'game' );
		$this->view->assign ( 'cards', $this->getCards () );

		$cardContainer = new View ( 'cardContainer' );

		$res = new JavaScriptResponse ();
		$res->setContent ( [
				'view' => $this->renderView (),
				'cards' => $this->getCards (),
				'cardContainer' => $cardContainer->loadTemplate (),
				'defaultCard' => $this->defaultCard,
				'language' => $_SESSION ['config']->getLanguage ()
		] );
		return $res;
	}

	/**
	 *
	 * @return Card[]
	 */
	public function getCards() {
		$cards = [ ];
		$cards = $this->loadCardsFromXML ( 15 );
		return $cards;
	}
	private function loadCardsFromXML($c) {
		$found = 0;
		$cards = [ ];
		$xml = new SimpleXMLElement ( file_get_contents ( 'data/cards.xml' ) );

		foreach ( $xml->children () as $card ) {
			$german = '' . $card->translations->german;
			/** @var Configuration $conf */
			$conf = $_SESSION ['config'];

			$translation = '' . $card->translations->{$conf->getLanguage ()};
			$image = 'templates/img/cards/' . $card->image;
			if ($german !== '' and $translation !== '' and $image !== '' and file_exists ( $image )) {
				$cards [] = new Card ( $german, $translation, $image );
				$found ++;
			}
		}
		shuffle ( $cards );
		return array_slice ( $cards, 0, $c );
	}
}