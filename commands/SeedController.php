<?php
// commands/SeedController.php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class SeedController extends Controller
{
    public function actionIndex()
    {
        
        
        $user                = new User();
        $user->username      = "test";
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash('testtest');
        $user->email         = "test@ukr.net";
        $user->save();
        
        
        /*
        $faker = \Faker\Factory::create();
        for ( $i = 1; $i <= 20; $i++ )
        {
            //$user->setIsNewRecord(true);
            $user = new User();
            $user->id = null;
            $user->username = $faker->username;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash('testtest');
            $user->email = $faker->email;
            $user->save();
           
        }
        */
        

    }
}