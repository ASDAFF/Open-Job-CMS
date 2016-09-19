<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

require dirname(__FILE__) . '/../helpers/common.php';

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	'language'=>'ru',

	'name'=> OJC_VERSION_NAME,

	// preloading 'log' component
	'preload'=>array('bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',

		'application.modules.user.UserModule',
		'application.modules.user.models.*',
		'application.modules.user.components.*',

        'ext.reversePagination.*',

        'application.helpers.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'admin',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>array('127.0.0.1','::1'),
//		    'generatorPaths'=>array(
//				'bootstrap.gii',
//			),
//		),

		'user'=>array(
			'hash' => 'md5',
			'sendActivationMail' => true,
			'loginNotActiv' => false,
			'activeAfterRegister' => true,
			'autoLogin' => true,
			'registrationUrl' => array('/user/registration'),
			'recoveryUrl' => array('/user/recovery'),
			'loginUrl' => array('/user/login'),
			'returnUrl' => array('/user/profile'),
			'returnLogoutUrl' => array('/user/login'),
		),

        'message',
        'project',
        'skill',
        'install',
	),

	// application components
	'components'=>array(
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),

        'decoda' => array(
            'class' => 'ext.decoda.YiiDecoda',
            'defaults' => true,
        ),

		'user'=>array(
			// enable cookie-based authentication
			'class' => 'WebUser',
			'allowAutoLogin'=>true,
			'loginUrl' => array('/user/login'),
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'' => '/project/main/index',

				'/users/<un:\w+>' => '/user/user/view',

                '/users/feed' => '/user/user/feed',
                '/users' => '/user/default/index',

                '/projects' => '/project/main/index',
                '/project/skill/<skill:[\w-]+>' => '/project/main/index',

                '/project/<id:\d+>' => '/project/main/view',
                '/project/<action:\w+>' => '/project/main/<action>',

				'/version' => '/site/version',

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		'db' => require dirname(__FILE__).'/db.php',

        'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
//		'log'=>array(
//			'class'=>'CLogRouter',
//			'routes'=>array(
//				array(
//					'class'=>'CFileLogRoute',
//					'levels'=>'error, warning',
//				),
//				// uncomment the following to show log messages on web pages
//				/*
//				array(
//					'class'=>'CWebLogRoute',
//				),
//				*/
//			),
//		),

		'cache'=>array(
			'class'=>'system.caching.CFileCache',
		),

	),

	// using Yii::app()->params['paramName']
	'params' => require dirname(__FILE__).'/params.php',
);