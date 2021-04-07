<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <!--<code><?= __FILE__ ?></code>-->
	
	 <div class="alert alert-danger">
        <?= nl2br("<h4><span class='glyphicon glyphicon-flag' style='font-size:38px;'></span> This page is available for users with <b>adminX</b> access roles only. Your role will be checked, see status below... </h4><br>"); ?>
    </div>
	
	
	
	
	<?php
	//check role, if current user doesn't have it, we assign it to current user
		if(Yii::$app->user->can('adminX')){
            echo '<h5>You have role <b>adminX</b> and can view current page</h5>';
			echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/unlocked.png' , $options = ["id"=>"unlck","margin-left"=>"3%","class"=>"cl-mine","width"=>"14%","title"=>"access granted"] );
            echo "<br><div class='col-sm-8 col-xs-12' style='border:1px solid black; padding:2em;font-size:1.6em; color:red;'>Some hidden content that is visible for users with adminX RBAC rights only</div>";
		} else {
			echo "<h5> You have no <b>adminX</b> role and <b>CAN NOT</b> view this page</h5>";
			echo '<p>Access to this page is Limited in views/site/about.php with RBAC <b>{ if(Yii::$app->user->can("adminX"))} </b></p>'; //must be single quote or 000webhost crashed!!!
			echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/locked.png' , $options = ["id"=>"unlck","margin-left"=>"3%","class"=>"cl-mine","width"=>"14%","title"=>"access granted"] );

		}
	?>
</div>
