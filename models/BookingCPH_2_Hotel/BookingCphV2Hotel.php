<?php
//Model for CPH_2_Booking_Hotel (created based on BookingCPH but it is a version with multiple rooms)
namespace app\models\BookingCPH_2_Hotel;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "booking_cph_v2_hotel".
 *
 * @property int $book_id
 * @property string $booked_by_user
 * @property string $booked_guest
 * @property string $booked_guest_email
 * @property string $book_from
 * @property string $book_to
 * @property int $book_from_unix
 * @property int $book_to_unix
 * @property int $book_room_id
 */
class BookingCphV2Hotel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking_cph_v2_hotel';
    }


    
    public function behaviors()
    {
        return [
            [
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'entry_date',
            'updatedAtAttribute' => false,
            'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['booked_by_user', 'booked_guest', 'book_from', 'book_to',  'booked_guest_email',  'book_room_id', ], 'required'], // 'booked_by_user', 'booked_guest_email',  'book_room_id', 'book_from_unix', 'book_to_unix',
            [['booked_by_user', 'book_from_unix', 'book_to_unix', 'book_room_id'], 'integer'],
            [['booked_guest', 'booked_guest_email'], 'string', 'max' => 77],
            [['book_from', 'book_to'], 'string', 'max' => 33],
			
			['book_from','validateDatesX'], //my custom validation function. ['fromDate', 'compare', 'compareAttribute'=> 'toDate', 'operator' => '<', 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'book_id' => Yii::t('app', 'Book ID'),
            'booked_by_user' => Yii::t('app', 'Booked by'),
            'booked_guest' => Yii::t('app', 'Booked Guest'),
            'booked_guest_email' => Yii::t('app', 'Email'),
            'book_from' => Yii::t('app', 'Book From'),
            'book_to' => Yii::t('app', 'Book To'),
            'book_from_unix' => Yii::t('app', 'Book From Unix'),
            'book_to_unix' => Yii::t('app', 'Book To Unix'),
            'book_room_id' => Yii::t('app', 'Room number'),
        ];
    }
	
	
	
	
	
	
		
//your custom validation rule, checks if start/end time is not PAST, and if Start in Unix in smaller than End in Unix. Used in model in function rules()
// **************************************************************************************
// **************************************************************************************
//                                                                                     **	
	public function validateDatesX(){
        if(strtotime($this->book_from) <= date("U")){//if start date is Past //  date("U") is a today in UnixTime
            $this->addError('book_from','Can"t be past!!! Please give correct Start Day');
        }
		
		 if(strtotime($this->book_to) <= date("U")){//if End date is Past 
            $this->addError('book_to','Can"t be past!!! Please give correct End Day');
        }
		
		 if(strtotime($this->book_to) <= strtotime($this->book_from)){ //if Start Unix Time bigger then End
            $this->addError('book_from','Dates range is reversed!!!!! Check start/end dates.');
			$this->addError('book_to',  'Dates range is reversed!!!!! Check start/end dates.');
        }
    }
	
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************




	
	
//beforeSave(); //convert date to unixTime & assign to SQL db field
// **************************************************************************************
// **************************************************************************************
//                                                                                     **

  //WORKS!!!!!!!!!!!!!!!!!!!! (wasn't  working  because  used $_POST['Mydbstart']['mydb_v_am'] instead of  $this->mydb_v_am )
  public function beforeSave($insert)  //$insert
  {
    if (parent::beforeSave(false)) {
 
        // Place your custom code here
		
        // $model = new Mydbstart(); // Instead of creating a New Model - u have to use {$this};
        //NEW
        //$curr = self::findByPk($this->id); //::find()->orderBy ('mydb_id DESC')  ->all(); //WON't  work  we  don't  needd  getting  old  value  from SQL
        //END NEW
		
             if (!empty($this->book_from) && !empty($this->book_to )){ 
                 $this->book_from_unix = strtotime($this->book_from);  //convert date to unixTime & assign to SQL db field
				 $this->book_to_unix = strtotime($this->book_to);  //convert date to unix & assign to SQL db field
             }// END if(!empty($this->mydb_v_am)) 
                 
   
        // End  Place your custom code her
        return true;
    } else {
        return false;
    }
  } // END BEFORESAVE();
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************










 /**
  * Method description
  *
  * @return mixed The return value
  */
// **************************************************************************************
// **************************************************************************************
//                                                                                     **
 public function beforeValidate()
 {
     //$this->book_from
	 
     return parent::beforeValidate();
 }
 
 
 
 

 
 
   
 // **************************************************************************************
 // **************************************************************************************
 //                                                                                     **
     function get_All_Next_Months_List($i){ //pass {$i} as arg is a must
		    //$array_All_Month = array();//will store all 6 month data
		    //Start DATE for NEXT months  ONLY (+ this current month in first iteration)----------------------------
            $PrevMonth = date('M', strtotime(date('Y-m'). " + " .$i. " month")); //i.e Jul  //$PrevMonth=date('M', strtotime(date('Y-m')." -1 month"));         
            $PrevYear =  date('Y', strtotime(date('Y-m')." + " .$i. " month"));  //i.e 2019 // $PrevYear=date('Y', strtotime(date('Y-m')." -1 month"));// getting Next  month  and  year;
        
		    /*${'current'.$i}*/ $b = $PrevMonth . " " . $PrevYear; //i.e Jul 2019
			//array_push($array_All_Month, ${'current'.$i}); //adds next months to array in a loop
			return array($b, $PrevMonth) ; //$b=>"Nov 2019"   //$PrevMonth=> "Jun"
	 }
	 
	 
	 
	 
	 
	 
	 
 // **************************************************************************************
 // **************************************************************************************
 //                                                                                     **
    /**
     * Method corrects the year in case when building 6 months content (current month + 5 next) in a loop and if current month is Nov or Dec 202x, so in this case January must be next year (i.e 202x + 1).
     * @param string $PrevMonth, e.g $PrevMonth = 'Jan'; (date("M") returns short textual representation of a month (three letters))
     * @param integer $iterator, current for() loop iterator
     * @return array('may'=> $integer $may, 'yearX'=> string $yearX). Example array('may'=> 1, 'yearX'=> '2021')
     */
    function correctYear($PrevMonth, $iterator){
		 //var with year, used for creating Unix for next years, must be declared out of for loop, to save its value for further iteration, in case if($may == 1 )
		 $MonthList= array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); //General array for all click actions
		 
		 static $y = 0;  //Mega Fix, cast static type to ++ next year. Don't need this fix in old procedure version as $year there is global
		 
		 
		 $yearX = date("Y"); //gets the current year (string), i.e '2019'
	     $may =  array_search($PrevMonth , $MonthList); //search the index of $PrevMonth  in array, i.e index of Jul = 6
		 $may = $may + 1;
			
		 //if current month in loop is 1 (i.e January), for this & next months we use the next year. Example: current month is Nov or Dec 202x, so January must be next year (i.e 202x + 1).
		 //As it is loop, January here could NOT BE EVER here the current month, if($may == 1 ) it can only be the next or next+1, etc, so the current is always the past year & January is the next	
		 if ($may == 1 && $i > 0) { //mega fix 2021, line was => if ($may == 1 ) and when cureent month was 1, i.e January, this function generated the next year for cureent month and next 5
			 $y++;
		     //$yearX must be declared out of for loop, to save its value for further iteration, in case if($may == 1 )
		    // $yearX++ ; //was = (int)date("Y") + 1; Fix for unlimited future years// gets the current year & adds +1 to get the next year, ie. 2019 + 1 = 2020
			 //$yearX = (string)$yearX;
			
		 }
		 
		 $yearX = $yearX + $y; //encrease year
		 return array('may'=> $may, 'yearX'=> $yearX); //
	}
 
 
 
 
 
 // **************************************************************************************
 // **************************************************************************************
 //                                                                                     **
     function get_Next_Months_Unix($may, $yearX){
		 $first1 = date("Y-m-d", mktime(0, 0, 0, $may , 1 ,$yearX)); //gets the first day of the current month, returns  "2019-05-01"
		 $last1 = date("Y-m-d",  mktime(0, 0, 0, $may+1, 0, $yearX)); //gets the last day of the current month,returns "2019-05-31"
			
	     //gets Unix Start Time & Unix End Time of the current month (+ this current month in first iteration) (i.e Unix of the 1st & last day)
		 $array_tempo = array(strtotime($first1), strtotime($last1)); //push current month unix stamp start/stop Unix time to subarray // returns [1556654400,1559246400]
		 //array_push($array_All_Unix, $array_tempo); //push subarray to array in order to have structure [[35, 57], [35, 57], [35, 57],]
		 return array('array_tempo'=> $array_tempo, 'first1'=> $first1, 'last1'=> $last1); //$last1 => "2020-01-31"
	 }
 
 
 

 // **************************************************************************************
 // **************************************************************************************
 //                                                                                     **
 
    function findBooked_Dates_In_Month($i, $first1, $last1, $postD ){ 
		//Find SQL data for a specific NEXT month (+ this current month in first iteration) (from 6-months range) one by one in a loop
         //creating array {SmonthData1,SmonthData2,}
         /*${'monthData'.$i}*/ $m = /*BookingCphV2Hotel*/self::find() //->  orderBy ('book_id DESC')  /*->limit('5')*/ 
	            // ->where([ 'booked_by_user' => Yii::$app->user->identity->username , /*'mydb_id'=>1*/ ]) //if this line uncommented, each user has its own private booking(many users-> each user has own private booking appartment, other users cannot book it). Comment this if u want that booking is general, ie many users->one booking appartment(many users can book 1 general appartment)  
			     ->andWhere( 'book_room_id =:status', [':status' => $postD] ) //room ID $postD //   
				 ->andWhere([ 'or',
				             ['between', 'book_from_unix', strtotime($first1), strtotime($last1) ],
							 ['between', 'book_to_unix',   strtotime($first1), strtotime($last1) ] ])
				  //->andWhere(['between', 'book_from_unix', strtotime($first1), strtotime($last1) ])   /*->andFilterWhere(['like', 'supp_date', $PrevMonth])  ->andFilterWhere(['like', 'supp_date', $PrevYear])*/    
			      // ->orWhere (['between', 'book_to_unix',   strtotime($first1), strtotime($last1) ])  //(MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept) //strtotime("12-Aug-2019") returns unixstamp
				 ->all(); 
				 
				 
			
		return $m; //${'monthData'.$i};	  
          //array_push($array_All_sqlData, ${'monthData'.$i}); //adds current month booking data to array $array_All_sqlData
		  //END DATE for Previous month  ONLY (+ this current month in first iteration)-------------------------------
	}
	
	
	
	
 // **************************************************************************************
 // **************************************************************************************
 //                                                                                     **
	
	function getCount_OfBooked_Days_For_Badges_and_FixMargins($monthData, $first1, $last1){ //$last1 => "2020-01-31"
	        //Badges:count amount of booked days for for a specific NEXT month (from 6-months range) (+ this current month in first iteration) one by one in a loop. Unix book_to_unix & book_from_unix are from DB results
		    $countX = 0;
		    foreach ($monthData/*${'monthData'.$i}*/ as $a){
				
				//Start MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept)*********************   
				//fix for 1nd margin month, i.e for {28 Aug-31 Aug}  from (28 Aug - 3 Sept) (i.e we take only 28 Aug - 31 Aug) 
				if($a->book_to_unix > strtotime($last1)){ //if last booked day UnixStamp in this month is bigger than this month last day UnixStamp (i.e it means that this current loop booking is margin & last date of it ends in the next month )
				    $number = (strtotime($last1) - $a->book_from_unix )/60/60/24; //i.e This month last day minus this loop DB booked start day
				}
				
				//fix for 2nd margin month, i.e for {1 Sept-3 Sept}  from (28 Aug - 3 Sept) (i.e we take only 1 Sept - 3 Sept) 
				 else if($a->book_from_unix < strtotime($first1)){    //if 1st booked day UnixStamp in this month is smaller than this month 1st day UnixStamp (i.e it means that this current loop booking is margin & start date of it begun in past month )
			         $number = ($a->book_to_unix - strtotime($first1))/60/60/24; //i.e This loop DB booked end day minus This month first day 
					
                 //if booking is normal, without margin month, i.e 12 Aug - 25 aug					
				 } else {
		             $number = ($a->book_to_unix - $a->book_from_unix)/60/60/24;
				  } 
				//END MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept)********************* 
				
				
				
				 $number = $number + 1; //if u want to count from 5 aug to 6 aug as 2 days , not as one 
		         //$from = strtotime($last); $to = strtotime($first); $diff = $from - $to;   $countX = $diff;///60/60/24;
			     $countX = $countX + $number; //sum all booked days
		     }
			
			//getting the amount of days in this month, i.e the last day in this month
		     $lastDay = explode("-", $last1);// $last1 => "2020-01-31"
			 $daysInThisMonth = $lastDay[2] ; //gets the last day in this month, i.e 31 
	
			return array($countX, $daysInThisMonth); //(amount of booked days in this month, all amount of days in this month)
		    //array_push($array_All_CountDays, $countX); //adds this current month booked days (in numbers, i.e 22) to array 
		   //END count amount of booked days for for a specific next month one by one in a loop. Unix from & to are from DB results	
	}
	
	
	
	
	
	
 
 // END Methods used in BookingCphController/actionAjax_get_6_month()--------------------------------------------------------------------------------------



 
 
 
 
 
 
 
 

 // Methods used in BookingCphController/actionAjax_get_1_month(). ---------------------------------------------------------------------------------------
 //It gets data from SQL for 1 single clicked month and build a calendar 
 

 // Methods used in BookingCphController/aactionAjax_get_1_month() ---------------------------------------------------------------------------------------
 
 
 
 
 
	
	
	
}
