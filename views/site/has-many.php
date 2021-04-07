<?php
//HasMany DB relationship, see more info at /Readme_YII2_mine_Common_Comands.txt => 16. Has Many relation 

use yii\helpers\Html;

$name = "Has Many DB relationship";
$this->title = $name;
?>
<div class="has-many">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
       <p>Has Many relation:</p> 
    </div>

  
    <p>HasMany equivalent of Inner Join used in actionRbac(), uses 2 tables { user + rest_access_tokens (was->{auth_item}}</p>
	<p>Below gets the list of access_tokens of current user:</p>
    <div class="row">
        <div class="col-lg-5">
		    <p><b>works, but $b->username (from DB users won't work)</b></p><hr>
 
            <?php
			echo $currentUser->username . "<br><br>"; // . " " . $orders->item_name; //result from DB USER
			//var_dump($orders);
			foreach ($orders as $b){
				echo $currentUser->username . "______ ";
				echo $b->rest_tokens . "<br>"; //result from DB auth_assignment //works, but $b->username (from DB users won't work)
			}
			
			
			
 ?>
            
        </div>
    </div>

</div>












 

