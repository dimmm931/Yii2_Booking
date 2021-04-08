<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
	
    public $css = [
        'css/site.css',
		'css/loader.css', //loader css
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', //fa fa lib 
		
		
		//'css/bookingcph.css', //booking CPH css-> moved to a separate assert CPH_AssertOnly.php
    ];
	
    public $js = [
	    //'js/booking_cph.js', //booking CPH JS -> moved to a separate assert CPH_AssertOnly.php
		  //'js/wpress.js',  //Wpress JS
		  //'js/jquery-easing_plugin/jquery.easing.min.js' //
		
    ];
	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
