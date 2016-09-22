<?php
class UserController extends AppController {

	public function index() {
		$this->view = new View();
		$this->view->setTemplate('userstats');
		return $this->renderView();
	}
}