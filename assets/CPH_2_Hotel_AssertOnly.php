<?php
//Assets for CPH_2_Booking_Hotel (with multiple rooms)
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
class CPH_2_Hotel_AssertOnly extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
	
    public $css = [
        //'css/site.css',
		//'css/rbac.css', //rbac css
		'css/BookingCPH_2_Hotel/bookingcph_2.css', //booking_2 CPH css
		'css/BookingCPH_2_Hotel/loader_cph_2.css', //booking_2 CPH Loader css
		'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css', //Sweet Alert CSS
    ];
	
    public $js = [
	    'js/BookingCPH_2_Hotel/booking_cph_2.js', //booking CPH JS
		'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js', //Sweet Alert JS
		//'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js',
    ];
	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
