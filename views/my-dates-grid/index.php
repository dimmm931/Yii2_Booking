<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Booking Cph V2 Hotels';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-cph-v2-hotel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // echo Html::a('Create Booking Cph V2 Hotel', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <h3> Your booked dates </h3>
    <?= GridView::widget([
        'dataProvider' => $myBookedDatesProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'book_id',
            //'booked_by_user',
            [
                'label' => 'Booked by',
                'value' => function ($model) {
                    return $model->userName->username; //hasOne relation
                }
            ],
            'booked_guest',
            'booked_guest_email',
            'book_from',
            'book_to',
            'book_room_id',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
