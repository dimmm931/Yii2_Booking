<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$config = [
    'timeZone' => 'UTC',    
   
    //my Module
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Resttt',
        ],
    ],
	
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    
    'defaultRoute' => 'booking-cph-v2-hotel/index',
	
	//Components
    'components' => [
        'request' => [
              // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
              'cookieValidationKey' => 'fdgeggdfgb54654645t',
        ],
		
		//mine JSON------
		'response' => [
           //'format' => \yii\web\Response::FORMAT_JSON, //GIVES OUT JSON!!!!!!!!!!!!
        ],
		
        //Set my RBAC-------
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
	
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
			'identityCookie' => ['name' => '2f_dimmm931'], //Two Yii2 application Login Conflict mega Fix
        ],
		//END Set my RBAC----
		
		
		//setting error handler
        'errorHandler' => [
            'errorAction' => 'site/error', //'errorAction' => 'site/error-not-used',  //decoment last, if you you want to use your errof handler 
        ],
		
		//Set multilanguages---------------
		// set target language to be Russian
        'language' => 'ru-RU', //looks like not obligatory, I added new 'my-Lang', without adding it to config
        // set source language to be English
        'sourceLanguage' => 'en-US',
		
		
		'i18n' => [
        'translations' => [
            'app*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                //'basePath' => '@app/messages',
                //'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'app' => 'app.php',
                    'app/error' => 'error.php',
                ],
              ],
          ],
        ],
		//END Set multilanguages------------
		
		
		//E-MAIL settings
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true, //true means sending mail to /runtime/, false means sending real emails
			
		    'transport' => [
               'class' => 'Swift_SmtpTransport',
               'host' => 'smtp.ukr.net',   //imap.ukr.net
               'username' => 'acc**@ukr.net',
               'password' => '**',
               'port' => '465', /*993*/ //465
               'encryption' => 'ssl',
           ],
		 //END E-MAIL settings
			
			
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
		
		
        //PRETTY URL
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,  // Hide index.php
			//'class' => 'yii\web\UrlManager',
            'rules' => [
                 'booking'               => 'booking-cph-v2-hotel/index',
			     'rbac-management-table' => 'site/rbac', //pretty url for 1 action(if Yii sees 'site/rbac' it turn it to custom text)
			     'about-me'              => 'site/about',            //pretty url for 1 action(if Yii sees 'site/about' it turn it to custom text)
				 //Rules for REST API
			     ['class' => 'yii\rest\UrlRule', 'controller' => ['rest'],  ], //rule for rest api, means if Yii sees any action of RestController, it uses yii\rest\UrlRule 
				 //['extraPatterns' => ['GET /' => 'new'], /*'pluralize' => false*/ ], 
				 //Rules for pretty URL with regExp
				 '<controller:\w+>/<id:\d+>' => '<controller>/view',  //for others, turns {site/about?vies=14} to {}
				  '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                  '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				  'defaultRoute' => '/site/index',
            ],
        ], 
        //END PRETTY URL
		
    ],
	//END COMPONENTS
	
	
    'params' => $params,
	
	//create return Url to be redirected to prev page after login. RUNS after any Action in any Controller //NOT WORKING!!!!
    'on afterAction' => function (yii\base\ActionEvent $e) {  //afterRender  //afterAction
	    if( $e->action->id !== 'login' && $e->action->controller->id !== 'site' ){ //|| $e->action->id !== 'error'|| $e->action->id !== 'debug' 
		   if( $e->action->id !== 'default'){
            //Yii::$app->user->setReturnUrl(Yii::$app->request->url);
		    // \yii\helpers\Url::remember();    //Url::remember(['product/view', 'id' => 42], 'product');
		   }
	   }
    },
	
]; //end config







if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
