<?php
//used in LiqPay Shop {Simple}
namespace app\componentsX\getters_setters\shopSimple;

use Yii;
//use yii\helpers\Html;
//use yii\bootstrap\Collapse;  //  Collapse (hide/show)
use yii\base\BaseObject;

class ShopSimple extends BaseObject
{
    private $_label;

    public static function getLabel()
    {
        return "Check: " . $this->_label;
    }

    public function setLabel($value)
    {
        $this->_label = trim($value);
    }

	 public static function test(){
		 echo "<p>222</p>";
	 }
   
	
}