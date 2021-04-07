<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Become an admin with adminX role';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>



        <div class="alert alert-success">
            Currently, any logged user who visits this page, will obtain the access Rbac role {adminX}.
        </div>


			
			<?php echo $text; ?>
            
</div>
