<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
	<!--<meta charset="utf-8">-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<!-- Favicon -->
	<?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['images/favicon.ico'])]);?>
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
	

	
	NavBar::begin([
    'brandLabel' => 'Rest API',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
 
$menuItems = [
    ['label' => 'Home', 'url' => ['/site/index']],
    ['label' => 'About', 'url' => ['/site/about']],
    ['label' => 'Booking CPH_V.2 Hotel', 'url' => ['/booking-cph-v2-hotel/index']], //Booking CPH version 2 Hotel
	
	
	
	//Start submenu
    ['label' => 'Rest & other',  
        'url' => ['#'],
        'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
        'items' => [
            
            ['label' => 'Rest Controller(core) GET/', 'url' => ['/rest/index', "access-token" => "57Wpa-dlg-EonG6kB3myfsEjpo7v8R5b", ]],   //rest controller //we use here url with this user access-token(from DB), it is universal, if authenticator' => is disabled, the script just won't pay attaention to this $_GET['access-token']
            ['label' => 'Rest Controller (my custom action) GET/', 'url' => ['/rest/new', "access-token" => "57Wpa-dlg-EonG6kB3myfsEjpo7v8R5b", ]], //my custom rest action with WHERE statement
			//['label' => 'Rest(insert data via POST/)', 'url' => ['#', "access-token" => "57Wpa-dlg-EonG6kB3myfsEjpo7v8R5b", ]], //rest action to insert data via POST
            ['label' => 'Test Rest out of Yii(GET/, PUT/, DELETE/)', 'url' => ['../____my_rest_js_ajax_test/index.php', "access-token" => "57Wpa-dlg-EonG6kB3myfsEjpo7v8R5b", ]], //rest action to insert data via POST

						
			['label' => 'Rest Module (minor, no use)', 'url' => ['/admin/default/index']], //separate module with it's own controller
			['label' => 'MultiLanguage', 'url' => ['/multilanguage/index']], //page with different language translation
			['label' => 'Booking CPH', 'url' => ['/booking-cph/index']], //BookingCphController
			['label' => 'Booking CPH_V.2 Hotel', 'url' => ['/booking-cph-v2-hotel/index']], //Booking CPH version 2 Hotel
			['label' => 'Change password <br>(edit password, when logged)', 'url' => ['/site/change-password']], //edit password
			['label' => 'HasMany::relation', 'url' => ['/site/has-many']],
			['label' => 'Reset Password <br>(when u forget your password & cant login,<br> used at login page only)', 'url' => ['/password-reset/request-password-reset']],
			['label' => 'Codexception', 'url' => ['/site/codexcept']],
			['label' => 'Vote/comment Widgets(N/A, available in other Yii)', 'url' => ['#']],
			['label' => 'Dektrium/Yii2_User Module(N/A)', 'url' => ['#']],
			['label' => 'WPress alternative', 'url' => ['/wpress-blog/show-all-blogs']],
			['label' => 'Bot chat', 'url' => ['/bot/bot-chat']],
			['label' => 'Bus booking (N/A)', 'url' => ['#']],
			['label' => 'Send mail (N/A)', 'url' => ['#']],
			['label' => 'Test for middle(auth via email)', 'url' => ['/test-middle/index']],
			['label' => 'Shop Liq E-pay Cozastore (amado cart)', 'url' => ['/shop-liqpay/index']],
			['label' => 'Shop Liq E-pay_2 {:SIMPLE}', 'url' => ['/shop-liqpay-simple/index']],
			['label' => 'AdminPanel Shop2 {:SIMPLE} <i class="fa fa-floppy-o"></i>', 'url' => ['#']],
			['label' => 'Distance radius(N/A)', 'url' => ['#']],
			['label' => 'Sms Textbelt Api', 'url' => ['/sms-api/index']],
			['label' => 'My oAuth Api Server(N/A)', 'url' => ['#']],
			['label' => 'My Widget -> Image comparison', 'url' => ['/my-widget/index']],
			['label' => 'W3school How To <i class="fa fa-briefcase"></i>', 'url' => ['/w3school/index']],
			['label' => 'Manual Auto Quiz ', 'url' => ['/manual-auto-quiz/index']],
			

		
            //submenu with image,(won't  work  without  {encodeLabels' => false,}  ,  it  is  inserted  below)   /*/yii/basic_download/web*/
            ['label' => Html::img(Yii::$app->request->baseUrl.'/images/system_key.jpg' , $options = ["id"=>"sx","margin-left"=>"","class"=>"s","width"=>"16%","title"=>"my title"]) . ' My login(no use)',  'url' => ['/site/login'],     ],  
        ],         
    ],
   // END  Submenu 
   
	
];
 

//menu Items which change depending if (Yii::$app->user->isGuest)  
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}

//render the $menuItems to Nav widget 
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
    'encodeLabels' => false, 	// added  to  let  img  in menu
]);
 
NavBar::end();
	
	
	
	
	
	
	
	
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Dima Yii2 <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
