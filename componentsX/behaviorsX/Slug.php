<?php
namespace app\componentsX\behaviorsX; //your namespace, i.e pathway;
use yii\base\Behavior;
//use yii\web\Controller;  //must-have  as you use controller
 
class Slug extends Behavior{  //must extend beahvior
 
 
 public $title = 'title';
 
    public function events(){
        return [
            //ActiveRecord::EVENT_BEFORE_VALIDATE => 'changeTitle',
			 \yii\web\Controller::EVENT_BEFORE_ACTION => 'getMyMetodX'   //your method to launch
        ];
    }
 
    //your method to launch
    public function getMyMetodX(){
	  //your code.......
      //throw new \yii\web\NotFoundHttpException("Launched in Behavior");
    }
 
}



?>