<?php

namespace app\modules\admin\controllers;

//use yii\web\Controller;
use yii\rest\ActiveController;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends ActiveController
{
  public $modelClass = 'app\models\User';
}
