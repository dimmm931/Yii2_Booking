<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'No Rbac access';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

	<h4>This page is available for users with <b>adminX</b> access roles only. </h4>
    <p>This is a general RBAC No-Access template</p>

	<?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/no-access.png' , $options = ["id"=>"unlck","margin-left"=>"3%","class"=>"cl-mine","width"=>"14%","title"=>"access granted"] );?>
	
	
</div>
