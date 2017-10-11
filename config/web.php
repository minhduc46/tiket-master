<?php
$params  = require(__DIR__ . '/params.php');
$baseUrl = str_replace('/web', '', (new \yii\web\Request)->getBaseUrl());
$config  = [
	'id'         => 'basic',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => [
		'log',
	],
	'timeZone'   => 'Asia/Ho_Chi_Minh',
	'language'   => 'vi',
	'components' => [

		'request'      => [
			'cookieValidationKey' => 'ioOQN-Wa1IdN6iOKWWmb1JxqsfawWQ-h',
			'baseUrl'             => $baseUrl,
		],
		'cache'        => [
			'class' => 'yii\caching\FileCache',
		],
		'user'         => [
			'identityClass'   => 'app\models\User',
			'enableAutoLogin' => true,
		],

		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mailer'       => [
			'class'            => 'yii\swiftmailer\Mailer',
			'useFileTransport' => true,
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => [
						'error',
						'warning',
					],
				],
			],
		],
		'view'         => [
			'class' => 'app\components\View',
			'theme' => [
				'pathMap'  => [
					'@dektrium/user/views' => '@app/views/user',
				],
				'basePath' => '@app',
				'baseUrl'  => '@web',
			],
		],
		'db'           => require(__DIR__ . '/db.php'),
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [],
		],
	],
	'modules'    => [
		'roxymce'     => [
			'class'       => 'navatech\roxymce\Module',
			'defaultView' => 'list',
		],
		'gridview'    => [
			'class' => '\kartik\grid\Module',
		],
		'user'        => [
			'class'              => 'dektrium\user\Module',
			'enableRegistration' => false,
			'enableConfirmation' => false,
			'admins'             => ['admin'],
			'modelMap'           => [
				'User'      => 'navatech\role\models\User',
				'LoginForm' => 'navatech\role\models\LoginForm',
			],
			'controllerMap'      => [
				'security' => 'app\controllers\UserController',
				'admin' => 'app\controllers\user\AdminController'
			],
		],
		'role'        => [
			'class'       => 'navatech\role\Module',
			'controllers' => [
				'app\controllers',
				'navatech\role\controllers',
			],
		],
		'datecontrol' => [
			'class'           => 'kartik\datecontrol\Module',
			'displaySettings' => [
				'date'     => 'php:d-m-Y',
				'time'     => 'H:i:s A',
				'datetime' => 'd-m-Y H:i:s A',
			],
			'saveSettings'    => [
				'date'     => 'php:Y-m-d',
				'time'     => 'H:i:s',
				'datetime' => 'Y-m-d H:i:s',
			],
			'autoWidget'      => true,
		],
	],
	'params'     => $params,
];
if (YII_ENV_DEV) {
	//	 configuration adjustments for 'dev' environment
	$config['bootstrap'][]      = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
	];
	$config['bootstrap'][]      = 'gii';
	$config['modules']['gii']   = [
		'class' => 'yii\gii\Module',
	];
}
return $config;
