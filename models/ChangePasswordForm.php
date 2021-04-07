<?php
//changed old pass to new one
namespace app\models;
//namespace frontend\models;
 
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
//use models\User;
 
/**
 * Change password form for current user only
 */
class ChangePasswordForm extends Model
{
    public $id;
	public $old_password;
    public $password;
    public $confirm_password;
	//public $old_passwordX;
 
    /**
     * @var \common\models\User
     */
    private $_user;
 
    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($id, $config = [])
    {
        $this->_user = User::findIdentity($id);
        
        if (!$this->_user) {
            throw new InvalidParamException('Unable to find user!');
        }
        
        $this->id = $this->_user->id;
        parent::__construct($config);
    }
 
 
 
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','confirm_password', 'old_password'], 'required'],
            [['password','confirm_password'], 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }
 
    
	
	  public function attributeLabels(){
         return [
                'old_password'=>'Old Password',
                'password'=>'New Password',
                'confirm_password'=>'Repeat New Password',
            ];
        }
		
		
 
    /**
     * Changes password.
     *
     * @return boolean if password was changed.
     */
// **************************************************************************************
// **************************************************************************************
//                                                                                     **
    public function changePassword()
    {
        $user = $this->_user;
		
		//Mine----------------
		$password_hash_from_DB = $user->password_hash; //here we get current user's password from SQL DB
		//echo $password . "<br>";
		//$old_passwordX = \Yii::$app->security->generatePasswordHash($this->old_password);//sha1($this->old_password);
		
		//echo $this->old_password . "<br>"; //user input of old password 
		//echo \Yii::$app->security->generatePasswordHash($this->old_password); //hash of old password
		
		
		
		//below is a build-in method {Yii::$app->getSecurity()->validatePassword()} to compare a password entered by a user now and password_hash from DB SQL,
		//we don't need to encrypt a password entered by a user to hash to compare, Yii::$app->getSecurity()->validatePassword() does it by itself
		if (!Yii::$app->getSecurity()->validatePassword($this->old_password, $password_hash_from_DB)){ //check if old password in DB===password just enetered by user now
		//if($password!=\Yii::$app->security->generatePasswordHash($this->old_password)){
                //$this->addError($attribute,'Old password is incorrect');
				Yii::$app->getSession()->setFlash('failed', "Old Password is incorrect-> ");
				return false;
        }
		// END Mine ------------
		
        $user->setPassword($this->password);
 
        return $user->save(false);
    }
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************


}
