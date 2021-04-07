<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Booking Cph V2 Hotels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-cph-v2-hotel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Booking Cph V2 Hotel'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'book_id',
            'booked_by_user',
            'booked_guest',
            'booked_guest_email:email',
            'book_from',
            'book_to',
            'book_from_unix',
            'book_to_unix',
            'book_room_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
