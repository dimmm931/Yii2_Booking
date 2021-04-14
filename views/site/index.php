<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Booking';
?>

<div class="site-index">

    <div class="jumbotron">
        <!--<h1>Congratulations!</h1>-->
        <p class="lead">A Yii2 application to book dates.</p>
        <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/Screenshots/1.png' , $options = ["id"=>"","margin-left"=>"","class"=>"yii-logo","width"=>"6%","title"=>"screenshot"] ); ?>

		<br><br><br>
		<p class="lead">A Yii2 application to book dates.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>
	
	

    <div class="body-content">
      <div class="row">
      </div>
    </div>
</div>
