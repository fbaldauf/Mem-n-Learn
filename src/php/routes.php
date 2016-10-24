<?php
return array (
		'index' => [ 
				'route' => '/',
				'controller' => 'AppController',
				'action' => 'index' 
		],
		'new-game' => [ 
				'route' => '/new-game',
				'controller' => 'GameController',
				'action' => 'startNew' 
		],
		'save-game' => [ 
				'route' => '/save-game',
				'controller' => 'GameController',
				'action' => 'save' 
		],
		'stats' => [ 
				'route' => '/statistic',
				'controller' => 'UserController',
				'action' => 'index' 
		],
		'logout' => [ 
				'route' => "/logout",
				'controller' => 'UserController',
				'action' => 'logout' 
		],
		'register' => [ 
				'route' => "/register",
				'controller' => 'UserController',
				'action' => 'register' 
		],
		'exportStatistics' => [ 
				'route' => "/exportStatistics",
				'controller' => 'UserController',
				'action' => 'exportStatistics' 
		],
		'language' => [ 
				'route' => "/(?'lang'[\w\-]+)",
				'controller' => 'AppController',
				'action' => 'setLanguage' 
		] 
);

// Examples
// 'picture' => "/picture/(?'text'[^/]+)/(?'id'\d+)", // '/picture/some-text/51'
// 'album' => "/album/(?'album'[\w\-]+)", // '/album/album-slug'
// 'category' => "/category/(?'category'[\w\-]+)", // '/category/category-slug'
// 'page' => "/page/(?'page'about|contact)", // '/page/about', '/page/contact'
// 'post' => "/(?'post'[\w\-]+)", // '/post-slug'
// 'home' => "/" // '/'
;