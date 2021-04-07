<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Test form';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">


   <!---- FLASH from site/actionRbac -->
   <?php if( Yii::$app->session->hasFlash('success2') ): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('success2'); ?>
    </div>
    <?php endif;?>
	
	


    <h1><?= Html::encode($this->title) ?> <span class='glyphicon glyphicon-cloud-download' style='font-size:42px;color:green;margin-left:2%;'></span> </h1>
	
	
    <p>Please fill out the following fields to test:</p>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'name1')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'name2') ?>
            
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>