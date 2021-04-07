<?php

namespace app\componentsX\widgets; //your namespace, i.e pathway;

use yii\base\Widget;
use app\models\User;

class HiWidget extends Widget
{
    public function run()
    {
		//finds all users
		$model = User::find()->all();
		
        //return '<h2>Hello!</h2>';
		return $this->render('Hi', [
            'model' => $model,
        ]);
    }
	
}

