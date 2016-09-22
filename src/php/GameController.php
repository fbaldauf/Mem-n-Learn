<?php
class GameController extends AppController {

	public function __construct($request) {
		parent::__construct($request);
	}

	public function startNew() {

		$this->view = new View();
		$this->view->setTemplate('game');
		$this->view->assign('cards', [
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card(),
			new Card()
		]);

		return $this->renderView();
	}
}