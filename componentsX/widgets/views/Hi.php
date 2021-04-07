<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;
?>

<div class="my-widget">
    
	<div class="alert alert-info">
        <?= nl2br("<h4><span class='glyphicon glyphicon-flag' style='font-size:38px;'></span> This page is implementation of My Widget</h4>" .
		             "<p><b>See docs at .....</b></p>" .
		             "<hr>" .
		             "<p>Some more text.</p>" .
					 "<br>"); 
		?>
    </div>
	
	
	<!----- W3school Exmaple of Image Comparison (pure JS) ----->
	<div class="col-lg-12 col-md-12 col-sm-12">
        <h2>W3school Image Comparison Slider <span class='small font-italic'>(pure JS)</span></h2>
		
		<!----- Exmaple ----->
		<div class="img-comp-container">
		
            <div class="img-comp-img">
               <!--<img src="img_snow.jpg" width="300" height="200">-->
			   <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/city3.jpg' , $options = ["id"=>"","margin-left"=>"","class"=>"img-comp","width"=>"","title"=>"text"] ); ?>
            </div>
			
           <div class="img-comp-img img-comp-overlay">
              <!--<img src="img_forest.jpg" width="300" height="200">-->
			  <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/city2.jpg' , $options = ["id"=>"","margin-left"=>"","class"=>"img-comp","width"=>"","title"=>"text"] ); ?>
           </div>
		   
       </div>
	   <!---- Exmaple -->   
	</div>
	<!----- END W3school Exmaple of Image Comparison (pure JS) ----->
	
	
	
	
	
	
	<!----- Exmaple of Images Comparison JQ Plugin -> Twentytwenty plugin  ----->
	<!-- https://www.jqueryscript.net/other/Stylish-jQuery-Images-Comparison-Plugin-twentytwenty.html -->
	<div class="col-lg-12 col-md-12 col-sm-12">
	    </br></br><hr>
        <h2>Images Comparison Plugin -> Twentytwenty <span class='small font-italic'>(drag the image)</span></h2>
		
		<div class="demo">
             <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/city3.jpg' , $options = ["id"=>"","margin-left"=>"","class"=>"img-comp-twenty","width"=>"","title"=>"Copenhagen"] ); ?>
             <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/city2.jpg' , $options = ["id"=>"","margin-left"=>"","class"=>"img-comp-twenty","width"=>"","title"=>"Copenhagen"] ); ?>
        </div>

		
	 </div>
	 <!---- END Exmaple of Images Comparison JQ Plugin -> Twentytwenty plugin -->
	   
	   
	   
	   
	   
	   
	   
	   
	<!--- Just DB Juery example in widget -->
	<div class="col-lg-12 col-md-12 col-sm-12">
	    </br></br><hr>
	    <h2>Users List <span class='small font-italic'>(Query from DB)</span></h2>
		<?php
		$i = 0;
		foreach($model as $user){
			$i++;
			echo "<p  class='list-group-item'>" . $i . " " . $user->username . "</p>";
		}
		?>
	</div>
	<!--- END Just DB Juery example in widget -->
	
	
</div>
