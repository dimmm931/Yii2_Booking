<?php
//access to this page have only users with RBAC role {adminX}
//access to this page is checked in site/rbac with => if(Yii::$app->user->can('adminX')){
/* @var $this yii\web\View */
//THIS page displays RBAC management table(based on 3 table INNERJOIN). Rendered in site/actionRbac .In table u can select and assign a specific RBAC role to a certain user. When u this, an ajax with userID & RBAC roleName are sent to site/AjaxRbacInsertUpdate

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'RBAC control';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-about">
   
   
   <!---- FLASH from site/actionRbac
   <?php if( Yii::$app->session->hasFlash('success') ): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('success'); ?>
    </div>
    <?php endif;?>
	
 
  <!-------------------------------------------------------- START HEADER ------------------------------------------------------------------------------------------>
  <div class="row my1">
  
   
   
  
    <!-- START of Left Block -->
    <div class="col-sm-8 col-xs-12 leftt">		
        <h1><?= Html::encode($this->title) ?></h1>
        <h4>RBAC access control page</h4>
	    <p>This page is designed to moderate user RBAC roles (InnerJoin) </p><br> 
	
	
	    <?php
	    //check if user  has RBAC role adminX
        echo '<h5>You have role <b>adminX</b> and can view current page</h5>';
	    echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/unlocked.png' , $options = ["id"=>"un", "margin-left"=>"3%","class"=>"cl-mine","width"=>"14%","title"=>"access granted"] );
        ?>
	</div><!-- END of class="leftt"--><!-- END of Left Block -->
	
	
	
	
	
	<!-- START of RIGHT Block, SECTION to add new RBAC role to auth_items-->
    <div class="col-sm-4 col-xs-12 rightt">
	   
	   <button data-toggle="collapse" data-target="#rbacAdd">Add a new RBAC role to auth_items</button><p></p>
	   <div id="rbacAdd" class="collapse"><br>
	   
          
		   <!----------------- FORM to add RBAC role to table {auth_items} ---------------------->
           <?php $form = ActiveForm::begin (/*[
           'id' => 'login-form',
           'layout' => 'horizontal',
           'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
            'enableAjaxValidation' => true, // my ajax
           ],
           ]*/); ?>


           <?= $form->field($authItem_Model, 'name')->textInput(['maxlength' => true]) ?>
           <?= $form->field($authItem_Model, 'description' /*,  ['enableAjaxValidation' => true]*/) ?>
  
           <div class="form-group">
            <?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
           </div>
           <?php ActiveForm::end(); ?>	
	       <!----------------- END FORM to add RBAC role to table {auth_items} ---------------------->
		   
			
       </div>
	</div><!-- END of class="right"--> <!-- END of RIGHT Block, SECTION to add new RBAC role to auth_items -->
	
  </div><!-- END class="row my1" -->
  <!---------------------------------------------------------- END HEADER ------------------------------------------------------------------------------------------>
	
	
	
	
	
	
	
	
	
	
	
	
  <!-------------------------------------------------------------- START RBAC TABLE ---------------------------------------------------------------------------->
  <div class="col-sm-12 col-xs-12 rbac">
	 <?php
	 //BUILDING A TABLE WITH RBAC management
	 //INNER JOIN RESULTS (3 tables) (user->rbac role)----------------------------

	 echo "<table id='rbResult'>"; //start table
	 echo "<tr><th>User Name</th><th>Rbac role</th><th>Descr</th><th>Action</th></tr>"; //create headers of table
	 
	 foreach($query as $innerj){
		 
		  //Building <select><option>---------------
		 //short val for select/option
		 $select1 = '<form id ="myForm" action="#"><p><select size="" name="rbacrole" id="">;<option selected>Select</option>';
		 
		 //loop through all Rbac roles() to build <select><option>
		 foreach($rbacRoleList as $rbList){
			 if($rbList->name == $innerj['item_name'] ){ //if foreach iteration roleName{$rbList->name} the same as a user has {$innerj['item_name']}, make that <option> SELECTED
				 $select1 = $select1 .'<option selected value="' . $rbList->name . '">' . $rbList->name .'</option>'; //$rbList->name is an DB->db field "name" //I.E, this line returns => <option selected value="adminX">adminX</option>
			 } else {
				 $select1 = $select1 .'<option value="' . $rbList->name . '">' . $rbList->name .'</option>'; //not selected //I.E, this line returns => <option value="adminX">adminX</option>
			 }        
		 }
		
         //final part of <select> + <button id="userID">		
		 $select1 = $select1 . '</select></p><p><input type="submit" value="Do" class="formzz" id="' .$innerj['id'].  '"></p></form>';
		 //END Building <select><option>------------
		 
		 //assign user rbac role var. Ternary operator is used
		 $userRole = $innerj['item_name']?$innerj['item_name']:"not set"; //if in foreach iteration {$innerj['item_name']} exists, take it. Otherwise use "not set"
		 
		 //echo finsl table row : username-> his current role-> select/option
		 echo "<tr><td>" . $innerj['username'] . "</td><td>" . $userRole . "</td><td class='rdescr'>" .   $innerj['description']   . "</td><td>" . $select1 ." </td></tr>";
	 }
	 echo "</table>";
	 //END INNER JOIN-----------------------------------------------------------------
     //END BUILDING A TABLE WITH RBAC management  
	?>
	
  </div><!-- END  class="col-sm-12 col-xs-12 rbac" -->
  <!-------------------------------------------------------------- END  RBAC TABLE ---------------------------------------------------------------------------->

  
</div>



















<?php 
//START AJAX. On Click sends ajax to change user RBAC ROLE. Sends to /site/ajax-rbac-insert-update
    $URL = Yii::$app->request->baseUrl . "/site/ajax-rbac-insert-update";   //WORKING,  gets the url to send ajax, defining it in  $ajax causes routing to 404, Not found, as the url address does not render correctly
    //url: 'http://localhost/iShop_yii/yii-basic-app-2.0.15/basic/web/index.php?r=products/getajaxorderdata',  // the correct address sample for ajax
    $Controller = Yii::$app->controller->id; // to pass in ajax
	
	//echo $URL;
	
	//My working JS Register
	//Checks in JS if the Validation runs fine 
	$this->registerJs( <<< EOT_JS_CODE
	// JS code here   //afterValidate
	

   //when u click OK in table
   $(document).on("click", '.formzz', function() {   // this  click  is  used  to   react  to  newly generated cicles; //#myForm
   //$("#myForm").on("beforeSubmit", function (event, messages) {
       // Now you can work with messages by accessing messages variable
       //var attributes = $(this).data().attributes; // to get the list of attributes that has been passed in attributes property
       //var settings = $(this).data().settings; // to get the settings
       //alert (attributes);
	   
	   //alert($(this).closest("form").find(":selected").val()); return false;
 
 
       //Checking if dropdown is "Select" (i.e a user didn't select a role), then stop anything further
	   if($(this).closest("form").find(":selected").val() == "Select"){
		   alert("Please, select a valid role");
		   return false;
	   }
	   
	   
       //Checking validation
       var form = $(this);
	   if (form.find('.has-error').length ) {  //if validation failed
	   
	       alert("Validate failed"); 
		   return false;  //prevent submitting and reload
	   
       //if validation is OK	   
	   } else { 
	   
	        alert("Validate OK");  
		    // runs ajax here
			//var userInput = $(this).serialize();  //user form input-FAILS
			//alert("form " + userInput);
			
			
			
			// Start AJAX
            $.ajax({
			    //url      : '<?php echo Yii::app()->createUrl("products/getajaxorderdata");?>',
		        url: '$URL',  //WORKING
				//url: form.attr('getajaxorderdata'),
                
                type: 'post',
				// dataType : "html",
				dataType:'json', // use JSON
               
			    //passing the data to ajax
				data: {
                    controller : '$Controller ',
				    
				    selectValue : $(this).closest("form").find(":selected").val(), // $(this).find(":selected").val(),  //value of nearest <select> to clicked button
					userID: $(this).attr('id'), //$(this).find("input[type=submit]").attr('id'),  //user ID

                },
				
				//if send was successful
                success: function(res){
                    console.log(res);
				    alert(res.result_status);
			        //modify the html of updated user in the rbac table.....
					//changes the description in rbac table!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					//$(this).prevAll(".rdescr").stop().fadeOut("slow",function(){ $(this).prevAll(".rdescr").html(res.descriptionNew)}).fadeIn(2000);
					//console.log($("#" + res.userIDX).closest('td').prev('td').prev('td').text());
					
					//CHANGE dynamically text in User Role table filed. 
					//$(this) or $(this).prevAll(".rdescr")} does not work, so we get userID {res.userIDX} from ajax, which is the same as button-> <input type="submit" value="Do" class="formzz", and from that button we find prev <td> for description and prev-prev for role name
					$("#" + res.userIDX).closest('td').prev('td').prev('td').stop().fadeOut("slow",function(){ $(this).html(res.roleNew)}).fadeIn(2000);
					//CHANGE dynamically text in User Role Description
					$("#" + res.userIDX).closest('td').prev('td').stop().fadeOut("slow",function(){ $(this).html(res.descriptionNew)}).fadeIn(2000);
                  
					
					
                },
                error: function(errX){
                    alert('Error from view/rbac-view.php View!' + errX);
                }
            });
			// END runs AJAX here
		    return false; //prevent reloading/submitting the form
		  
	   } // end else
  });
  
  
  
  // END JS code here		
  
EOT_JS_CODE
);
  ////any spaces before EOT_JS_CODE will cause the crash
	?>