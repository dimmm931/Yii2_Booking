<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingCPH_2_Hotel\BookingCphV2Hotel */

$this->title = Yii::t('app', 'Update Booking Cph V2 Hotel: {nameAttribute}', [
    'nameAttribute' => $model->book_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Booking Cph V2 Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->book_id, 'url' => ['view', 'id' => $model->book_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="booking-cph-v2-hotel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
