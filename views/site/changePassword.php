<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
 
/* @var $this yii\web\View */
/* @var $model frontend\models\ChangePasswordForm */
/* @var $form ActiveForm */
 
$this->title = 'Change Password';
?>

 <div class="">
    <h1><?= Html::encode($this->title) ?>  <span class="glyphicon glyphicon-ok-sign"></span> </h1>
 </div><hr><br>
  
  
  <!---- FLASH from site/actionGetToken() -->
   <?php if( Yii::$app->session->hasFlash('failed') ): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('failed'); ?>
    </div>
    <?php endif;?>
	
  
<div class="col-sm-6 col-xs-12 user-changePassword" >
 
    <?php $form = ActiveForm::begin(); ?>
	
        <?= $form->field($model, 'old_password')->passwordInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'confirm_password')->passwordInput() ?>
 
        <div class="form-group">
            <?= Html::submitButton('Change', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
 
</div>