<?php
//MAIN ERRPR PAGE, it handles all exception, displays core error with $message
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $message; //$name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br("<span class='glyphicon glyphicon-level-up' style='font-size:38px;'></span><br>We are deeply sorry. " .Html::encode($message)) ?>
    </div>


	<br><br>
	
	<div class="row x">
	
	    <!----- Left column ----->
	    <div class="col-sm-3 col-xs-12" id="">
		    <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/sorry.jpg' , $options = ["id"=>"","margin-left"=>"","class"=>"","width"=>"","title"=>"sorry"] ); ?>
		</div>
		
		<!----- Right column ----->
	    <div class="col-sm-6 col-xs-12" id="">
            <p>Error happened as <b><?=$message;?></b>. <br></p>
			<p>This is common error page, it is triggered with <br><b>{ throw new \yii\web\NotFoundHttpException("your text"); }</b></p>
			
            <p>The above error occurred while the Web server was processing your request.Please contact us if you think this is a server error. Thank you <span class='glyphicon glyphicon-phone-alt' style='font-size:12px;margin-left:1%;'></span></p>
	    </div>
	</div><!-- end class="row x" -->

	
</div>
