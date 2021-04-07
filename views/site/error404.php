<?php
//NOT USED
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($exception)) ?> <!-- was $message-->
    </div>

    <p>
        404-> Mine!!!  Something went wrong.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

</div>
