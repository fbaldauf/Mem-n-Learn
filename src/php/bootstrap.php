<?php
// Autoloader laden, damit andere PHP-Klassen automatisch geladen werden können
require_once 'src' . DS . 'php' . DS . 'Autoloader.php';
spl_autoload_register(array('Autoloader', 'autoload'));

// URI des HTTP-Requests ermitteln
$uri = rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/');
$uri = '/' . trim(str_replace($uri, '', $_SERVER['REQUEST_URI']), '/');
$uri = urldecode($uri);

// Verfügbare Routen laden
$rules = include 'src' . DS . 'php' . DS . 'routes.php';

foreach ($rules as $action => $rule) {
	if (preg_match('~^' . $rule['route'] . '$~i', $uri, $params)) {
		// Angeforderte Route wurde gefunden
		$request = array_merge($_GET, $_POST);

		// Controller der Route instanziieren
		$fc = new $rule['controller']($request);
		if ($fc instanceof AppController) {
			// Wenn die Anforderung über AJAX erfolgt, dann als Standardtemplate "json" verwenden, ansonsten "main"
			$fc->setTemplate((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) ? 'json' : 'main');
		}

		if (!isset($_SESSION)) {
			// Session wurde noch nicht gestartet -> starten
			session_start();
		}

		if (!isset($_SESSION['config'])) {
			// Konfiguration wurde noch nicht initialisiert -> initialisieren
			$_SESSION['config'] = new Configuration();
		}

		// Prüfen, ob Benutzer schon eingeloggt ist
		$user = new UserController($request);
		if (!$user->isLoggedIn()) {
			// Benutzer ist noch nicht eingeloggt
			// Er ist also nur autorisiert für den Login oder die Registrierung
			$user->setTemplate((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) ? 'json' : 'main');
			if (strtolower($uri) === '/register') {
				// Registrierung wird aufgerufen
				echo $user->register();
				die();
			}
			else {
				// -> Zur Loginseite weiterleiten
				echo $user->login();
				die();
			}
		}
		// Login Ende

		array_shift($params);
		$action = $rule['action'];

		// Angeforderte Aktion ausführen
		echo call_user_func_array(array($fc, $action), $params);
		die();
	}
}

// URI entspricht keiner Route
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	header('Content-Type: application/json');
	die(json_encode([]));
}
else {
	header('HTTP/1.1 404 Not Found');
	die('Die Seite wurde nicht gefunden!<br />Request: ' . $uri . '<br /><pre>' . var_export(array_merge($_GET, $_POST), true) . '</pre>');
}
