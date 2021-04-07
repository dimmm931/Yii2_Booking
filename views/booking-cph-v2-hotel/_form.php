<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingCPH_2_Hotel\BookingCphV2Hotel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booking-cph-v2-hotel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'booked_by_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'booked_guest')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'booked_guest_email')->textInput() ?>

    <?= $form->field($model, 'book_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'book_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'book_from_unix')->textInput() ?>

    <?= $form->field($model, 'book_to_unix')->textInput() ?>

    <?= $form->field($model, 'book_room_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
