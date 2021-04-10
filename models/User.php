<?php
namespace app\models;
 
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

//use app\models\RestAccessTokens; //table with access_tokens (to retrieve in findIdentityByAccessToken )
 
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, \yii\filters\RateLimitInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
 
 
 
 
  
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
 
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
			[['username', 'email', 'auth_key', 'password_hash', 'password_reset_token'], 'safe'], //must be safe for REST Api POST/, i.e creating a new user
        ];
    }
 
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
 
 
 
 
 
    /**
     * @inheritdoc
	 //MINE //to use API Auth token, now it uses tokens stored in models/RestAccessTokens -> DB {rest_access_tokens}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		 return \app\models\RestAccessTokens::findOne(['rest_tokens' => $token]); 

		//return static::findOne(['access_token' => $token]); //MINE //FOR USE API Auth token
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
 
 
 
 
 
 
 
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
 
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
 
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
 
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
 
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
 
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
 
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
	
	
	
	
	
	//By default the model shows all fields, code below excludes some unsafe fields from response
	public function fields()
    {
        $fields = parent::fields();

        // remove unsafe fields
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        return $fields;
   }
   
   
   
   
   
   
   
   
   
   
   
    public $rateLimit = 1;
    public $allowance;
    public $allowance_updated_at;

   
   
   //START yii\filters\RateLimitInterface
   public function getRateLimit($request, $action)
   {
       return [$this->rateLimit/*1*/, 20]; // $rateLimit-> quantity of request per 20 sec
   }

   public function loadAllowance($request, $action)
   {
       return [$this->allowance, $this->allowance_updated_at];
   }

   public function saveAllowance($request, $action, $allowance, $timestamp)
  {
      $this->allowance = $allowance;
      $this->allowance_updated_at = $timestamp;
      $this->save();
  }
   //END yii\filters\RateLimitInterface
   
   
   
   
   
    
   
  
   
   
   
   
   
   
   
   
   
//RESET Password Section, used when u forget your password and claim to RESET IT at Login page----------------------
public static function findByPasswordResetToken($token)
{
 
    if (!static::isPasswordResetTokenValid($token)) {
        return null;
    }
 
    return static::findOne([
        'password_reset_token' => $token,
        'status' => self::STATUS_ACTIVE,
    ]);
}


public static function isPasswordResetTokenValid($token)
{
 
    if (empty($token)) {
        return false;
    }
 
    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire = Yii::$app->params['user.passwordResetTokenExpire'];
    return $timestamp + $expire >= time();
}

public function generatePasswordResetToken()
{
    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
}
 
public function removePasswordResetToken()
{
    $this->password_reset_token = null;
}


//END RESET Password Section, used when u forget your password and claim to RESET IT at Login page---------------------





}
