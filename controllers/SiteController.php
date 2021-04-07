<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\SignupForm;
use app\models\AuthItem; //table with Rbac roles
use app\models\AuthAssignment; //table with Rbac roles $ users' id assigned to that rbac role
use app\models\TestForm; //model for sql table for testing form CRUD
use app\models\RestAccessTokens; //model for table storing users tokens for Rest API 

use app\models\ChangePasswordForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
				
				//To show message to unlogged users. Without this unlogged users will be just redirected to login page
				'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\NotFoundHttpException("Only logged users are permitted(set in behaviors)!!!");
                 },
				 //END To show message to unlogged users. Without this unlogged users will be just redirected to login page
				 
				//following actions are available to logged users only 
                'only' => ['logout', 'add-admin', 'get-token', 'change-password'], //actionGetToken, actionChangePassword
                'rules' => [
                    [
                        'actions' => ['logout', 'add-admin', 'get-token', 'change-password'], //actionGetToken, actionChangePassword
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					
					// RBAC roles: actionAbout is avialable for users with role {adminX}-----
					[
                    'actions' => ['about'],
                    'allow' => true,
                    'roles' => ['adminX'],
                    ],
					//End RBAC roles: actionAbout is avialable for users with role {adminX}-----
					
                ],
            ],
			
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

	
	
	
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
		    //must be commented if want to use person actionError, otherwise errors will be handled with built vendor/yii\web\ErrorAction
            'error' => [
                'class' => 'yii\web\ErrorAction',  //pre-difined error handler, comment if want to use my personal
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	
	
	
	
/*
  public function beforeAction($action)
  {
	
    Yii::$app->session['beforelogin'] = Yii::app()->request->pathInfo;`
    return parent::beforeAction($action);
  }
	
*/
	
	
	
	
	
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	
	
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
            return $this->goBack();
			//return $this->redirect(Yii::$app->request->referrer);
			/*if(isset(Yii::app()->session['beforelogin'])) {
               $this->redirect(Yii::app()->session['beforelogin']);
            }*/
			
			/*
			$backUrl = \yii\helpers\Url::previous();
			echo $backUrl;
			$this->redirect($backUrl);
			*/
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

	
	
	
	
	
	
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	
	
	
//My Error handler NOT USED. To use , should comment {'error' => ['class' => 'yii\web\ErrorAction',}(not mandatory) and specify in config/web.php  'errorHandler' => [ 'errorAction' => 'site/error-not-used'], 
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
	public function actionErrorNotUsed()
    {
        $exception = Yii::$app->errorHandler->exception;
		
		//using diffrent templates for 404 and other errors
        if ($exception !== null) {
            if ($exception->statusCode == 404)
                return $this->render('error404', ['exception' => $exception->getMessage()]); //$exception->getMessage() to get short mess, i.e "Page not found"
            else
                 return $this->render('error', ['exception' => $exception]);
        }
    }
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************










//"Be the admin" section
//FALSE=> Add just 1 user Admin if it doesnot exist  
//TRUE=> Currently, any user, who visits this page wil get adminX rbac role. According to my logic each user can have only 1 role.
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
	 public function actionAddAdmin() {
		 
		 Yii::$app->user->can('adminX')? $t = 'You already have adminX Rbac role' : $t = 'You don"t have adminX ';
	
		 
		 //if(!Yii::$app->user->can('adminX')){
			throw new \yii\web\NotFoundHttpException("Sorry, this feature is now disabled. Please refer to SiteController/actionAddAdmin().\n Notice: {$t}");
		//}
		 
	     $text = "";
	     $text .= "<h3>Currently, any logged user who visits this page, will obtain the access Rbac role {adminX}</h3>";
		 echo "<h3>Currently, any logged user who visits this page, will obtain the access Rbac role {adminX}</h3>";
	     //$rbac = AuthItem::find()->where(['name' => 'admin'])->one();
	  
	 
	 
	     //$rbac_RolesList = Yii::$app->authManager->getRoles();//gets all existing roles from auth_item DB. Format is ARRAY, not object
         //echo '<pre>' , var_dump($rbac_RolesList) , '</pre>'; //pretty var-dump of array
         //echo "Role Item-> ". $rbac_RolesList['admin']->name; //gets the name of existing role
	 
     	

	//create a new role, it is created if this role does not exist in table {auth_item}
	if(AuthItem::checkIfRoleExist('adminX')){   //method from /models/AuthItem.php. Checks if Rbac role already exists. Name of rbac role is passes as arg $roleName
        $role = Yii::$app->authManager->createRole('adminX');
        $role->description = 'myAdminX';
        Yii::$app->authManager->add($role);
	} /*else {
		echo "<p> Role already exists</p>";
	}*/
	
	
	
	//create a new user 'admin' in table {users},checks if user Admin already exists	 
    $model = User::find()->where(['username' => 'admin'])->one();
	//if user Admin does not exist, create it
    if (empty($model)) {
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@кодер.укр';
        $user->setPassword('admin');
        $user->generateAuthKey();
        if ($user->save()) {
            echo 'good';
        }
	//if user Admin already exists
    } else {
		echo '<meta charset="'.  Yii::$app->charset .' ">'; //add charset as this page without render with view
		echo '<p>User {admin} already exists</p>';
		echo 'current user ' . Yii::$app->user->identity->username; //current user
		echo '<br>User with admin rights: ' . $model->username;   //admin name
		echo '<br>' . $idX = $model->id;   
		echo '<br>' . $model->email .'<br>';   //admin mail
		//echo Yii::$app->charset; //current charset
		
		//print user {admin} role rigths, $idX is a user {admin} id
		echo "<p>User <b>{admin}</b> roles-></p>";
		var_dump($rolesForUser = Yii::$app->authManager->getRolesByUser($idX));
		
		
		//Assigning 'adminX' role to the current user
		//check role, if current user doesn't have it, we assign it to current user
		if(Yii::$app->user->can('adminX')){
            echo '<br><br>Роль <b>adminX</b> уже присвоена to current user';
        } else {
			echo "<br> You have no <b>adminX</b> role";
			//.......check ig current has any role in auth_assignment table, if Yes, delete it before assigning a new one 
			//..................!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			
			$userRole = Yii::$app->authManager->getRole('adminX');
            Yii::$app->authManager->assign($userRole, Yii::$app->user->identity->id);
		}
		
		echo "<p> Current user <b>" . Yii::$app->user->identity->username . " </b>roles-></p>";
		$myRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id); //gets array with current user all roles rights (form table auth_item)
		foreach ($myRoles as $key => $value){
			echo $key . "<br>";
		}
	}
	
	/*
	return $this->render('become-admin', [
            'text' => $text,
        ]);
	   */
}
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************







//Registration, added by me
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
     public function actionSignup()
    {
        $model = new SignupForm();
 
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
 
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************









//RBAC management table, creats/displays RBAC management table(based on 3table INNERJOIN).
//In table u can select and assign a specific RBAC role to a certain user. When u this, an ajax with userID & RBAC roleName are sent to site/AjaxRbacInsertUpdate. Ajax logic is in views/site/rbac-view

// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
     public function actionRbac()
    {
			
		
		//check if user has Rbac role {adminX}. If user has, do next....
		if(Yii::$app->user->can('adminX')){
			
			
			
				
			
			//Instance/model of DB table {auth_item} to pass it to view (to render yii form "Add new Rbac role ") + detect form submission here
			$authItem_Model = new AuthItem();
		
			//**********************************************************************
			//If the Form to add a new RBAC role to {auth_item} DB is trigered-----------------
			
            // validate any AJAX requests fired off by the form
            /*if (Yii::$app->request->isAjax && $authItem_Model->load(Yii::$app->request->post())) {
               Yii::$app->response->format = Response::FORMAT_JSON;
               return ActiveForm::validate($authItem_Model);
             }	*/		
			
			
			
			
            if ($authItem_Model->load(Yii::$app->request->post()) /*&& $authItem_Model->save()*/) {  //&& $searchMine->validate()
				//var_dump(\Yii::$app->request->post());
				
			
                //	Section to create a new role (in Bootsrap dropdown collapse)		
		        //create a new role, it is created if this role does not exist in table {auth_item}
			    $is = AuthItem::find()->where(['name' => $authItem_Model->name])->one(); //find the role in table {auth_item} by suggested user input { $authItem_Model->name}
	            if (!$is) {   //Checks if Rbac role already exists. Name of rbac role is passes as $authItem_Model->name
                    $role = Yii::$app->authManager->createRole($authItem_Model->name);
                    $role->description = $authItem_Model->description;
                    Yii::$app->authManager->add($role);
				    Yii::$app->getSession()->setFlash('success', "New role is created-> <b>$authItem_Model->name </b>");
			    } else {
				    Yii::$app->getSession()->setFlash('success', "The role <b>$authItem_Model->name </b> already exists");
			}
			
              return $this->refresh(); //prevents from form resubmitting on reload & flash appearing  		
			}
			//END If the Form to add a new RBAC role to {auth_item} DB is trigered-----------------
            //*************************************************************************
		
		
		
		
		
		
		
		
		
			
			//Inner Join 3 tables---------------------
            $query = new \yii\db\Query;  //must be {$query = new \yii\db\Query;} not{$query = new Query;}, adding {use yii\db\Query} won't help
            $query  ->select(['item_name', 'user_id', /*users DB*/ 'id', 'username', /*auth_item DB*/'description'])  //columns list from all JOIN tables[/*auth_assignment DB*/,  /*users DB*/,/*auth_item DB*/ ]
                ->from('auth_assignment')  //table1
				
				
				//
				 ->join( 'INNER JOIN',  
                     'auth_item', //table2
                     'auth_item.name=auth_assignment.item_name' //table2.column = table1.column
                  )
				//
				
                 ->join( 'RIGHT JOIN',  //INNER JOIN //use RIGHT JOIN to get all users regardless in their ids in auth_assignment
                     'user', //table3
                     'auth_assignment.user_id=user.id ' //table2.column = table1.column
                  ); 
            $command = $query->createCommand();
            $query = $command->queryAll(); 
		    // END Inner Join 3 tables-----------------
			
			
			
			
			
			
			//Selects all RBAc roles from table auth_item(for <select><option>)
			$rbacRoleList = AuthItem::find()->/*where(['username' => 'admin'])->one()*/all();
			
		
			
			
			
			
		
			
			
			return $this->render('rbac-view', [
                         //'model' => $model,
						 'query' => $query, //Inner Join result (based on Buyres/Orders Sql)
						 'rbacRoleList' => $rbacRoleList, //all RBAc roles from table auth_item(for <select><option>)
						 'authItem_Model' => $authItem_Model,//Instance/model of DB table {auth_item} to pass to view (to render yii form "Add new Rbac role ")
						
                         ]);
						 
			
		//if user does not have Rbac role {adminX}	
		} else {
			return $this->render('no-access', [
            //'model' => $model,
            ]);
		}
        
 
        
    }
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************








//Ajax function/action that is triggered from view/site/rbac-view.php(renedered by actionRbac())
//This functions waits for request with userID & RbacRole from view/site/rbac-view.php, then UPDATES/INSERTS a specified user with specified Rbac role
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
     public function actionAjaxRbacInsertUpdate()
    {   
	    //just a test if ajax is recognized
        if (Yii::$app->request->isAjax) { 
	        $test = "Ajax Worked, recognized!";
	    } else {
            $test = "Ajax  not recognized!";
		}
		
		
		
		//assign new RBAC role to a specific user(the role to assign and userID came via ajax from view/site/rbac-view.php)=$_POST['selectValue'];$_POST['userID']
		
		//check if User has any role at all in {auth_assignment} DB, to decide to use DB INSERT or DB UPDATE
		$userExist = AuthAssignment::find()->where(['user_id' => $_POST['userID']])->one(); //finding a single user in DB
		
		
		
		if($userExist){//IF user is already in {auth_assignment} DB
		
			if($userExist->item_name == $_POST['selectValue'] ){ //if user selects a rbac role that is already currently assigned, DO NOTHING
				$status = "User is already assigned to this role. Do nothing";
			} else {
			    $status = "User has already a role in auth_assignment DB. Use UPDATE";
			    //UPDATE logic.....
			    
			    //$customer = Customer::findOne(123);
                /*$userExist->item_name = $_POST['selectValue'];
                $customer->save();
				$status = $status ." HAVE DONE";*/
				//Can't edit , just use REVOKE for Rbac role & assign a new role !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				$revokedRole = Yii::$app->authManager->getPermission($userExist->item_name);
			if (Yii::$app->authManager->revokeAll((int)$_POST['userID'])){ //revoke all user's rbac roles. to revoke one role-> revoke( $current_user_role, $this->id );
			    //after revoking all assign a new rbac role
				$userRole = Yii::$app->authManager->getRole($_POST['selectValue']); //gets the role that was selected in form, role is (from auth_assignment DB)
                Yii::$app->authManager->assign($userRole, $_POST['userID']);// assign a DB role to user ID
				$status = $status ." HAVE DONE. Rbac Revoked & assigned new";
			} else {$status = $status ." Revoke failed";}
			
			}
			
		} else { //IF user is not in {auth_assignment} DB - get role and assign it to him
			$status = "User is new to auth_assignment DB. Use INSERT";
			//INSERT logic
			$userRole = Yii::$app->authManager->getRole($_POST['selectValue']); //gets the role that was selected in form, role is (from auth_assignment DB)
            Yii::$app->authManager->assign($userRole, $_POST['userID']);// assign a DB role to user ID
			$status = $status ." HAVE DONE. Created New";
		}
		//END assign new RBAC role to a specific user(the role to assign and userID came via ajax from view/site/rbac-view.php)=$_POST['selectValue'];$_POST['userID']
		
		
		//getting  new role decription the user was assigned(to html with ajax)
		//$descriptionNewX = AuthItem::find()->where(['name' => $userRole])->one();
		//$desc = $descriptionNewX->description;
		
		
		  //RETURN JSON DATA
		  // Specify what data to echo with JSON, ajax usese this JSOn data to form the answer and html() it, it appears in JS consol.log(res)
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;  
          return [
             'result_status' => $test, // return ajx status
             'code' => 100,	 
             'selectedRBAC' => $_POST['selectValue'], //json echo rbac role, that came from /view/site/rbac-view.php
             'userIDX' => 	   $_POST['userID'], //json echo rbac role, that came from /view/site/rbac-view.php		
             'statusX' => $status,  //message wheather UserID exists in auth_assignment DB, if true use DB UPDATE, if false use DB INSERT	
             'roleNew' => $userRole->name, // new role the user was assigned(to html with ajax)	
             'descriptionNew' => $userRole->description, // new role decription the user was assigned(to html with ajax)				 
          ]; 
	}    
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************













//Action Just to Test form, saves the form data without assigning attributes
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
     public function actionTestForm()
    {
	   //throw new \yii\web\NotFoundHttpException("This exception is hand made."); //test exception
		
       $model = new TestForm();
	   
	   //saves the form data without assigning attributes
	   if ($model->load(Yii::$app->request->post()) && $model->save()) {  
		     Yii::$app->getSession()->setFlash('success2', "Has been saved");
		}
 
        return $this->render('test-form', [
            'model' => $model,
        ]);
    }
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************











//Action that generates acces token for making request in Yii2 Rest API and saves them to DB rest_access_tokens (saves userID and token)
//+ display the list of all token of current user
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
     public function actionGetToken()
    {
			
		$modelToken = new RestAccessTokens();//model for form
		
		//if the button "Get new token" is clicked
	    if ($modelToken->load(Yii::$app->request->post())  /*&& $modelToken->save()*/ ) {
		   //generates random token
		   $key = Yii::$app->getSecurity()->generateRandomString();
	       $modelToken->rest_tokens = $key; //assign token to DB field {rest_tokens}
		   $modelToken->r_user = Yii::$app->user->identity->id; //assign current user ID to DB field {r_user}
			
			
           //if ($modelToken->save()){	
           try {
               $modelToken->save();		   
		       Yii::$app->getSession()->setFlash('token', "New token " . $key );
		   } catch(\Exception $e) {
			   throw new \yii\web\NotFoundHttpException("Failed creating new token"); //test exception
		   }
	   } 
	   //END if the button "Get new token" is clicked
		 
	  //get all token of current user
		$allTokens = RestAccessTokens::find()->where(['r_user' => Yii::$app->user->identity->id])->all();
			
		
        return $this->render('get-token', [
            'modelToken' => $modelToken, //model for form to generate token
			'allTokens' => $allTokens //all token of current user
        ]);
    }
// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





    
	
	
	
//action to change a password. Contains extra specific logic in models/ChangePasswordForm.php -> function changePassword()
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
	
	public function actionChangePassword()
    {
        $id = \Yii::$app->user->id;
 
        try {
           $model = new ChangePasswordForm($id);
        } catch (InvalidParamException $e) {
            throw new \yii\web\BadRequestHttpException($e->getMessage());
        }
 
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
           \Yii::$app->session->setFlash('success', 'Password Changed!');
        }
 
       return $this->render('changePassword', [
           'model' => $model,
       ]);
   }





   
   
   
   
//hasMany relations, see more info at /Readme_YII2_mine_Common_Comands.txt => 16. Has Many relation 
// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **
	
	public function actionHasMany()
    {
       
       $model = new AuthItem(); //Not used???
	   
	   $currentUser = User::findOne(Yii::$app->user->identity->id); //just find current user
	   $orders = $currentUser->tokens; //call hasMany action //Token is a function getTokens
	   
       return $this->render('has-many', [
           'model' => $model,
		   'currentUser' => $currentUser, //current user
		   'orders'=> $orders //hasMany result
       ]);
   }



}
