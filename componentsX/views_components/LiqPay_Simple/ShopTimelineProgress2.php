<?php
//used in LiqPay Shop {Simple}
namespace app\componentsX\views_components\LiqPay_Simple;
use Yii;
use yii\helpers\Html;
//use yii\bootstrap\Collapse;  //  Collapse (hide/show)

class ShopTimelineProgress2
{
	
   //Show Progress Status Icons, currently active icon is assigned with argument $activeClass
   //Variant 1 (Main/Core)	     
   // **************************************************************************************
   // **************************************************************************************
   // **                                                                                  **
   // **                                                                                  **
     public static function showProgress2($activeClass) {
	 ?>	 
	 
	<div class="row">
	
		  <center>
            <h3 class="shadowX widthX"><i class="fa fa-info-circle" style="font-size:0.8em"></i> Status </h3>
			<br>
			
			<div class="row items-list">
			
			     <!-- Shop icon -->
			    <div class="icon-item <?php echo ($activeClass == 'Shop' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->
			        <i class="fa fa-archive" style="font-size:24px;"></i>
					<p><?=Html::a( "Shop", ["/shop-liqpay-simple/index",] , $options = ["title" => "Shop",] ); ?></p>
		        </div>
				
				<!-- A line  -->
				<div class="icon-item">
				    <span class="line2">  </span>
			    </div>
				
				
			    <!-- Cart icon -->
			    <div class="icon-item <?php echo ($activeClass == 'Cart' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->
			        <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
				    <p><?=Html::a( "Cart", ["/shop-liqpay-simple/cart",] , $options = ["title" => "Cart",] ); ?></p>
		        </div>
				
				<!-- A line  -->
				<div class="icon-item">
				    <span class="line2">  </span>
			    </div>
				
                <!-- Order icon -->				
				<div class="icon-item <?php echo ($activeClass == 'Order' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->	
				    <i class="fa fa-tablet" style="font-size:24px;"></i>
					<p>Order</p>
				</div>
				
				<!-- A line  -->
				<div class="icon-item">	
				    <span class="line2">  </span>
				</div>
				
                <!-- Payment icon -->				
				<div class="icon-item <?php echo ($activeClass == 'Payment' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->	
				    <i class="fa fa-cc-mastercard" style="font-size:24px;"></i>
					<p>Payment</p>
				</div>
				
				<!-- A line  -->
				<div class="icon-item">	
				    <span class="line2">  </span>
				</div>
				
                <div class="icon-item <?php echo ($activeClass == 'Complete' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argument {$activeClass} -->				
				    <i class="fa fa-check" style="font-size:24px;"></i>
					<p>Complete</p>
			    </div>
			
		
		    </div>
			
			
	  <!--<hr>-->
	  </center>
	</div>	
	
	<?php
	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 //variant 2, not used
	 //Show Progress Status Icons, currently active icon is assigned with argument $activeClass 	     
    // **************************************************************************************
    // **************************************************************************************
    // **                                                                                  **
    // **                                                                                  **
     public static function showProgress_2($activeClass) {
	 ?>	 
	 
	    <div class="row">
	
		  <center>
            <h3 class="shadowX widthX">Status</h3>
			<br>
			
			<div class="row">
			
			  <div class="col-sm-2 col-xs-2 <? echo ($activeClass == 'Cart' ? 'myactive' : 'myinactive'); ?> "> <!-- assign css class by function argumnet -->
		          <!--<span class="badge badge-pill badge-secondary myactive-icon">-->
			         <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
			     <!-- </span> -->
				  <p>Cart</p>
			  </div>
			  
			  <div class="col-sm-1 col-xs-1">
			     <p class="line"></p>
			  </div>
			  
			
			  <div class="col-sm-2 col-xs-2 <? echo ($activeClass == 'Place order' ? 'myactive' : 'myinactive'); ?> ">
			    <i class="fa fa-tablet" style="font-size:24px"></i>
				<p>Place order</p>
			  </div>
			  
			  <div class="col-sm-1 col-xs-1">
			     <p class="line"></p>
			  </div>
			
			  <div class="col-sm-2 col-xs-2 <? echo ($activeClass == 'Payment' ? 'myactive' : 'myinactive'); ?> ">
			    <i class="fa fa-money" style="font-size:24px"></i>
				<p>Payment</p>
			  </div>
			
			  <div class="col-sm-1 col-xs-1">
			     <p class="line"></p>
			  </div>
			  
			  <div class="col-sm-2 col-xs-2 <? echo ($activeClass == 'Complete' ? 'myactive' : 'myinactive'); ?> ">
			    <i class="fa fa-check" style="font-size:24px"></i>
				<p>Complete</p>
			  </div>
			
		
		</div>
	  <!--<hr>-->
	  </center>
	</div>	
	
	
	<?php
	 }
	 
	
}