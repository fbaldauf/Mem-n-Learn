<?php
require_once 'src' . DS . 'php' . DS . 'Autoloader.php';
spl_autoload_register ( array (
		'Autoloader',
		'autoload'
) );

$uri = rtrim ( dirname ( $_SERVER ["SCRIPT_NAME"] ), '/' );
$uri = '/' . trim ( str_replace ( $uri, '', $_SERVER ['REQUEST_URI'] ), '/' );
$uri = urldecode ( $uri );

$rules = include 'src' . DS . 'php' . DS . 'routes.php';
foreach ( $rules as $action => $rule ) {
	if (preg_match ( '~^' . $rule ['route'] . '$~i', $uri, $params )) {
		$request = array_merge ( $_GET, $_POST );

		$fc = new $rule ['controller'] ( $request );
		if ($fc instanceof AppController) {
			$fc->setTemplate ( (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] )) ? 'json' : 'main' );
		}

		if (! isset ( $_SESSION )) {
			session_start ();
		}

		if (! isset ( $_SESSION ['config'] )) {
			$_SESSION ['config'] = new Configuration ();
		}

		// Prüfen, ob Benutzer schon eingeloggt ist
		$user = new UserController ( $request );
		if (! $user->isLoggedIn ()) {
			// Benutzer ist noch nicht eingeloggt
			// -> Zur Loginseite weiterleiten
			$user->setTemplate ( (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] )) ? 'json' : 'main' );
			if (strtolower ( $uri ) === '/register') {
				echo $user->register ();
			} else {
				echo $user->login ();
			}
			die ();
		}
		// Login Ende

		array_shift ( $params );
		$action = $rule ['action'];

		echo call_user_func_array ( array (
				$fc,
				$action
		), $params );
		die ();
	}
}

// URL entspricht keiner Route

if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] )) {
	header ( 'Content-Type: application/json' );
	die ( json_encode ( [ ] ) );
} else {
	header ( 'HTTP/1.1 404 Not Found' );
	die ( 'Die Seite wurde nicht gefunden!<br />Request: ' . $uri . '<br /><pre>' . var_export ( array_merge ( $_GET, $_POST ), true ) . '</pre>' );
}

