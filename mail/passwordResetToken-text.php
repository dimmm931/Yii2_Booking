<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['password-reset/reset-password', 'token' => $user->password_reset_token]);
?>
 
Hello <?= $user->username ?>,
Follow the link below to reset your password:
 
<?= $resetLink ?>