<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BookingCPH_2_Hotel\BookingCphV2Hotel */

$this->title = 'Create Booking Cph V2 Hotel';
$this->params['breadcrumbs'][] = ['label' => 'Booking Cph V2 Hotels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-cph-v2-hotel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
