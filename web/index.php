<?php

//Enable error reporting for NOTICES => 	 
ini_set('display_errors', 1);  
error_reporting(E_NOTICE);

//Eneable development mode on Local host only
if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    // comment out the following two lines when deployed to production (only if u dont use above line if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') { })
    //defined('YII_DEBUG') or define('YII_DEBUG', true);
    //defined('YII_ENV') or define('YII_ENV', 'dev');
}

//define('YII_ENABLE_ERROR_HANDLER', true);//mine. To show my personal error handler

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
