<?php
 
use yii\helpers\Html;
 
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['test-middle/new-account', 'token' => $code]);
?>
 
<div class="password-reset">
    <p>Hello <?= Html::encode($user /*->username*/) ?>,</p>
    <p>Follow the link below to confirm registration by email:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>