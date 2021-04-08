<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p> This is the About page. You may modify the following file to customize its content: </p>

    <!--<code><?= __FILE__ ?></code>-->
	
	 <div class="alert alert-info">
        <?= nl2br("<h4><span class='glyphicon glyphicon-flag' style='font-size:38px;'></span> A Yii2 application to book dates.</h4><br>"); ?>
    </div>
	
    
	
	
	
</div>
