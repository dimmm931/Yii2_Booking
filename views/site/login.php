<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;  //Collapse (hide/show)

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
	
	
	
	
	
	
	
	
	
<?php
//  Collapse (Hide/  show  options)
// ******************************************************
// ******************************************************
//                                                     **
echo Collapse::widget([
    'items' => [
        [
            'label' => '-',
            'content' => '   
                         <div class="col-lg-offset-1" style="color:#999;">
                         You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
						 Or <strong>dima/****x2</strong>.<br>
                         To modify the username/password, please check out the code <code>app\models\User::$users</code>.
                         </div>   ',
            // to  be  this  block open  by  default   de  comment  the  following 
            /*'contentOptions' => [
                'class' => 'in'
            ]*/
        ], ]
]);
// **                                                          **
// **************************************************************
// **************************************************************
// END  Collapse (Hide/  show  options
?>	
	
	
	
	

  
	
	<div class="col-lg-offset-1">
	<br>
    If you forgot your password you can 
	   <?= Html::a('Reset It', ['password-reset/request-password-reset'], ['class'=>'btn btn-xs btn-danger'], ['options'=>['style' => '']]);
	   echo "&nbsp;";
	   echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/reset.png' , $options = ["id"=>"","margin-left"=>"5%","class"=>"","width"=>"3%","title"=>"reset"] ); ?>
    </div>
</div>
