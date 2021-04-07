<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Get token for Yii2 Rest API requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">


   <!---- FLASH from site/actionGetToken() -->
   <?php if( Yii::$app->session->hasFlash('token') ): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('token'); ?>
    </div>
    <?php endif;?>
	
	


    <h1><?= Html::encode($this->title) ?> <span class='glyphicon glyphicon glyphicon-lock' style='font-size:42px;margin-left:2%;'></span> </h1>
	<p>Tokens are stored in SQL DB</p>
	
	

	
	
	<!----------------- FORM ------------------------>
	<div class="row en1">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'token']); ?>
                <?= $form->field($modelToken, 'rest_tokens')->textInput(['autofocus' => true]) ?>
                
            
                <div class="form-group">
                    <?= Html::submitButton('Get new token', ['class' => 'btn btn-primary', 'name' => 'get-tok']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div><!-- end class="row en1">-->
	<!----------------- END FORM ------------------------>
	
	
	
	
	
		
	<!----------------- displays all token of current user ------------------------>
	<div class="row tg1">
        <div class="col-sm-8 col-xs-12">
		<h3>List of your tokens</h3>
		<?php
		    
			
		    foreach ($allTokens as $y){
				echo "<p class='alert alert-success alert'>" . $y->rest_tokens .   "<button type='button' style='float:right; color:red;' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-remove'></span> </button></p>";
			}
		?>
	
	   </div>
    </div><!-- end class="row tg1">-->
	<!----------------- END all token of current user ------------------------>

</div>