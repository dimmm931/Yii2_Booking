<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['test-middle/new-account', 'token' => $code]);
?>
 
Hello <?= $user /*->username*/ ?>,
Follow the link below to reset your password:
 
<?= $resetLink ?>