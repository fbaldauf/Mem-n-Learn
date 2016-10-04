<?php
class AppController {

	protected $request = null;
	private $template = '';
	protected $view = null;

	/**
	 * Konstruktor, erstellet den Controller.
	 * @param Array $request Array aus $_GET & $_POST.
	 */
	public function __construct($request) {
		$this->view = new View();
		$this->request = $request;
		$this->template = !empty($request['view']) ? $request['view'] : 'main';
	}

	/**
	 * Methode zum anzeigen des Contents.
	 * @return String Content der Applikation.
	 */
	public function execute() {
		// var_dump($this->request);//die();
		// $view = new View();
		// switch ($this->template) {
		// case 'entry':
		// $view->setTemplate('entry');
		// $entryid = $this->request['id'];
		// $entry = Model::getEntry($entryid);
		// $view->assign('title', $entry['title']);
		// $view->assign('content', $entry['content']);
		// break;

		// case 'json':
		// return new JavaScriptResponse();
		// break;

		// case 'default':
		// default:
		// $entries = Model::getEntries();
		// $view->setTemplate('game');
		// $view->assign('entries', $entries);
		// }

		// $this->view->setTemplate('main');
		// $this->view->assign('footer', '');
		// $this->view->assign('menu', $this->getMenu());
		// $this->view->assign('content', $view->loadTemplate());
		// return $this->view->loadTemplate();
	}

	public function index() {
		$view = new View();
		$view->setTemplate('dashboard');
                $view->assign('username', $_SESSION['username']);
		return $this->renderView($view);
	}

	protected function renderView(View $view = null) {
		$view = (isset($view)) ? $view : $this->view;

		$v = new View();
		$v->setTemplate($this->template);

		$v->assign('footer', '');
		$v->assign('menu', $this->getMenu());
		$v->assign('content', $view->loadTemplate());

		return $v->loadTemplate();
	}

	protected function getMenu() {
		$menu = new View();
		$menu->setTemplate('nav');
		return $menu->loadTemplate();
	}

	public function setTemplate($template) {
		$this->template = $template;
	}
        
        public function setLanguage($lang) {
            if(substr($lang,0,5) == 'lang-') {
                $lang = substr($lang,5);
            }
            $_SESSION['config']->setLanguage($lang);
            return $this->index();
        }
}
?>