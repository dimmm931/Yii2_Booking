<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Booking';
?>
<div class="site-index">

    <div class="jumbotron">
        <!--<h1>Congratulations!</h1>-->
        <p class="lead">A Yii2 application to book dates.</p>
        <?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/Screenshots/1.png' , $options = ["id"=>"","margin-left"=>"","class"=>"yii-logo","width"=>"","title"=>"text"] ); ?>

		<br><br><br>
		<p class="lead">A Yii2 application to book dates.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>
	
	

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Rest & RBAC <span class="glyphicon glyphicon-fire" style="font-size:1.2em"></span></h2>
				

                <p>
				   <b>This Yii2 application includes Restful API and RBAC management table.</b><br> RBAC management table displays RBAC management table(based on 3table InnerJoin).<br><br>
				    Table of content:<br>
					1.How to test REST API from non-Yii2 file.<br>
                    2.Rbac access management table + collapsed form to add a new Rbac to all rbac roles, i.e to auth_items DB.<br>
                    3.Automatically become an {adminX} (i.e gets adminX role) by going to link (actionAddAdmin).<br>
                    4.RestFul Api.<br>
				</p>

                <br><p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
			
            <!--<div class="col-lg-4">
                <h2>Info</h2>
                <p>
				     To get necessary DB table for this project, apply migrations:<br>
                     #for Users (see Readme_YII2_mine_Common_Comands.txt ->5.Yii2 basic. Registration, login via db.)<br>
                     #for Rbac (see Readme_YII2_mine_Common_Comands.txt -> 8.Yii RBAC)
				</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>-->
			
			
            <div class="col-lg-6">
                <h2>RBAC <span class="glyphicon glyphicon-fire" style="font-size:1.2em"></span></h2>

                <p>
				 <b>This YIi2 Basic uses stack:</b> <br>  # RESTful API, custom REST action (with WHERE statement),<br> # RBAC roles (if(Yii::$app->user->can('adminX')), <br> # SQL DB registration/authentication (check login credential), <br># Multilanguage, token generator, bookingCPH <br># Edit password, password reset by email.
				 <br><br>
				 1. Rbac works using 2 tables: {auth_items} DB (keeps all rbac roles & descriptions) and {auth_assignment} DB (keeps pairs: rbac role - userID, who has this role).<br>
                 Rbac managements table is created via php, updated via ajax.A collapsed form to add a new role uses php only.<br>
                 2. Rbac management table (created in siteController/actionRbac) displays all users from DB {users}, even those who are not in {auth_assignment } due to InnerRight. <br>
                 3. Access to {siteController/actionRbac} is granted for those who has {adminX} rbac role (in auth_asdignment DB.) Checked with if(Yii::$app->user->can('adminX')){}<br>
				 </p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
			
			<?php echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/images/city2.jpg' , $options = ["id"=>"","margin-left"=>"","class"=>"yii-logo","width"=>"","title"=>"text"] ); ?>
            <br><br>

        </div>

    </div>
</div>
