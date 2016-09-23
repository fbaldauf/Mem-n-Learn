<?php
class GameController extends AppController {

	private $defaultCard = 'templates/img/yugioh-card-back.png';

	public function __construct($request) {
		parent::__construct($request);
	}

	public function startNew() {

		$this->view = new View();
		$this->view->setTemplate('game');
		$this->view->assign('cards', $this->getCards());

		$cardContainer = new View('cardContainer');

		$res = new JavaScriptResponse();
		$res->setContent(
			['view' => $this->renderView(), 'cards' => $this->getCards(), 'cardContainer' => $cardContainer->loadTemplate(),
				'defaultCard' => $this->defaultCard]);
		return $res;
	}

	/**
	 *
	 * @return Card[]
	 */
	public function getCards() {
		$cards = [];
		for ($i = 0; $i < 29; $i++) {
			$cards[] = new Card();
		}
		$cards[] = new Card('Vogel', 'templates/img/vogel.png');
		return $cards;
	}
}