<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BookingCPH_2_Hotel\BookingCphV2Hotel */

$this->title = $model->book_id;
$this->params['breadcrumbs'][] = ['label' => 'Booking Cph V2 Hotels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-cph-v2-hotel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->book_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->book_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'book_id',
            'booked_by_user',
            'booked_guest',
            'booked_guest_email:email',
            'book_from',
            'book_to',
            'book_from_unix',
            'book_to_unix',
            'book_room_id',
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>
