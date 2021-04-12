<?php
//Model for CPH_2_Booking_Hotel (created based on BookingCPH but it is a version with multiple rooms)
namespace app\models\BookingCPH_2_Hotel;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\User;

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
	
    /**
     * hasOne relation
     * @return 
     */
    public function getUserName()
    {
        return $this->hasOne(User::className(), ['id' => 'booked_by_user']);
    }   
 	
	
	 /**
     * your custom validation rule, checks if start/end time is not PAST, and if Start in Unix in smaller than End in Unix. Used in model in function rules()
     *
     * @return 
     */	

	public function validateDatesX(){
        if(strtotime($this->book_from) <= date("U")){//if start date is Past //  date("U") is a today in UnixTime
            $this->addError('book_from', 'Can"t be past!!! Please give correct Start Day');
        }
		
		if(strtotime($this->book_to) <= date("U")){//if End date is Past 
            $this->addError('book_to','Can"t be past!!! Please give correct End Day');
        }
		
		if(strtotime($this->book_to) <= strtotime($this->book_from)){ //if Start Unix Time bigger then End
            $this->addError('book_from','Dates range is reversed!!!!! Check start/end dates.');
			$this->addError('book_to',  'Dates range is reversed!!!!! Check start/end dates.');
        }
    }

  
    /**
     * beforeSave() convert date to unixTime & assign to SQL db field
     *
     * @return boolean
     */
    public function beforeSave($insert)  //$insert
    {
        if (parent::beforeSave(false)) {
            if (!empty($this->book_from) && !empty($this->book_to )){ 
                $this->book_from_unix = strtotime($this->book_from);  //convert date to unixTime & assign to SQL db field
				$this->book_to_unix   = strtotime($this->book_to);  //convert date to unix & assign to SQL db field
            } 
            return true;
        } else {
            return false;
        }
    } 





   /**
    * Method description
    *
    * @return mixed 
    */
    public function beforeValidate()
    {
        //$this->book_from
        return parent::beforeValidate();
    }
 
 
   /**
    * Method gets next 9 months list & adds it to array $array_All_Month, i.e $array_All_Month = ["Nov 2019", "Dec 2019", etc] 
    * @param int $i, foreach iterator
    * @return array of strings
    */
    function get_All_Next_Months_List($i)
    { 
		//$array_All_Month = array();//will store all 6 month data
		//Start DATE for NEXT months  ONLY (+ this current month in first iteration)
        $PrevMonth = date('M', strtotime(date('Y-m'). " + " .$i. " month")); //i.e Jul  //$PrevMonth=date('M', strtotime(date('Y-m')." -1 month"));         
        $PrevYear  =  date('Y', strtotime(date('Y-m')." + " .$i. " month"));  //i.e 2019 // $PrevYear=date('Y', strtotime(date('Y-m')." -1 month"));// getting Next  month  and  year;
		$b         = $PrevMonth . " " . $PrevYear; //i.e Jul 2019
		//array_push($array_All_Month, ${'current'.$i}); //adds next months to array in a loop
		return array($b, $PrevMonth) ; //$b=>"Nov 2019"   //$PrevMonth=> "Jun"
	}
	 
	 
	 
	 
	 
	 

    /**
     * Method corrects the year in case when building 6 months content (current month + 5 next) in a loop and if current month is Nov or Dec 202x, so in this case January must be next year (i.e 202x + 1).
     * @param string $PrevMonth, e.g $PrevMonth = 'Jan'; (date("M") returns short textual representation of a month (three letters))
     * @param integer $iterator, current for() loop iterator
     * @return array('may'=> $integer $may, 'yearX'=> string $yearX). Example array('may'=> 1, 'yearX'=> '2021')
     */
    function correctYear($PrevMonth, $iterator)
    {
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
		}
		 
		$yearX = $yearX + $y; //encrease year
		return array('may'=> $may, 'yearX'=> $yearX); //
	}
 
 
 
 
    /**
     * Method gets next 9 months UnixTime & adds it to array $array_All_Unix, i.e $array_All_Unix =[[CurrentStart, Currentend], [start-1, end-1], [start-2, end-2]]
     * @param int $may, current month
     * @param string $yearX, current year
     * @return array('array_tempo'=> [1556654400,1559246400], 'first1'=> "2019-05-01", 'last1'=> "2019-05-31")
     */
    function get_Next_Months_Unix($may, $yearX){
		 $first1 = date("Y-m-d", mktime(0, 0, 0, $may , 1 ,$yearX)); //gets the first day of the current month, returns  "2019-05-01"
		 $last1 = date("Y-m-d",  mktime(0, 0, 0, $may+1, 0, $yearX)); //gets the last day of the current month,returns "2019-05-31"
			
	     //gets Unix Start Time & Unix End Time of the current month (+ this current month in first iteration) (i.e Unix of the 1st & last day)
		 $array_tempo = array(strtotime($first1), strtotime($last1)); //push current month unix stamp start/stop Unix time to subarray // returns [1556654400,1559246400]
		 //array_push($array_All_Unix, $array_tempo); //push subarray to array in order to have structure [[35, 57], [35, 57], [35, 57],]
		 return array('array_tempo'=> $array_tempo, 'first1'=> $first1, 'last1'=> $last1); //$last1 => "2020-01-31"
	 }
 
 

    /**
     * Method gets next 9 months booked dates info & adds them to array $array_All_sqlData, i.e $array_All_sqlData = [[{book_id: 55, booked_by_user: "Dima", book_from: "2019-12-27", book_to: "2019-12-28"], [], []];
     * @param int $i (iterator)
     * @param string first1 
     * @param string $last1
     * @param string $postD ($_POST['serverRoomId'])
     * @return collection of objects
     */
    function findBooked_Dates_In_Month($i, $first1, $last1, $postD){ 
		//Find SQL data for a specific NEXT month (+ this current month in first iteration) (from 6-months range) one by one in a loop
        $m = self::find() 
			    ->andWhere( 'book_room_id =:status', [':status' => $postD] )   
				->andWhere([ 'or',
				             ['between', 'book_from_unix', strtotime($first1), strtotime($last1) ],
							 ['between', 'book_to_unix',   strtotime($first1), strtotime($last1) ] ])
				  //->andWhere(['between', 'book_from_unix', strtotime($first1), strtotime($last1) ])   /*->andFilterWhere(['like', 'supp_date', $PrevMonth])  ->andFilterWhere(['like', 'supp_date', $PrevYear])*/    
			      // ->orWhere (['between', 'book_to_unix',   strtotime($first1), strtotime($last1) ])  //(MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept) //strtotime("12-Aug-2019") returns unixstamp
				->all(); 
				 
		return $m; //${'monthData'.$i};	  
	}
	
	

     /**
     * Method count the quantity of booked days for every next 9 months & adds them to array $array_All_CountDays, i.e $array_All_CountDays = [0,2,4,0]
     * @param collection of objects $monthData (sqlResult)
     * @param string $first1 (the first day of the current month, i.e"2019-05-01")
     * @param string $last1  (the last day of the current month)
     * @return array (1, '30')
     */
	
	function getCount_OfBooked_Days_For_Badges_and_FixMargins($monthData, $first1, $last1){ 
	    //Badges:count amount of booked days for for a specific NEXT month (from 6-months range) (+ this current month in first iteration) one by one in a loop. Unix book_to_unix & book_from_unix are from DB results
		$countX = 0;
		foreach ($monthData as $a){
				
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
			$countX = $countX + $number; //sum all booked days
		}
			
		//getting the amount of days in this month, i.e the last day in this month
		$lastDay = explode("-", $last1);// $last1 => "2020-01-31"
		$daysInThisMonth = $lastDay[2] ; //gets the last day in this month, i.e 31 
	
		return array($countX, $daysInThisMonth); //(amount of booked days in this month, all amount of days in this month)
	}
		
 
    // END Methods used in BookingCphController/actionAjax_get_6_month()--------------------------------------------------------------------------------------



 
 

    // Methods used in BookingCphController/actionAjax_get_1_month(). ---------------------------------------------------------------------------------------
    //It gets data from SQL for 1 single clicked month and build a calendar 
 
   	/**
     * function that builds Guests List for this one month, completes $array_1_Month_days with booked days in this month, i,e [7,8,9,12,13]
	 * @param collection of obejcts $thisMonthDataList
     * @param int $start, (int)$_POST['serverFirstDayUnix']; //1561939200
     * @return string $generalBookingInfo
     * 
     */
   function buildThisMonthBookedDaysList($thisMonthDataList, $start)
   {
        $overallBookedDays; //all amount of days booked in this month

        //guest list for $generalBookingInf //Forming here column names(like <TH>) for $guestList table, i.e(guest/start/end/duration/delete)
		$guestList = "<div class='col-sm-12 col-xs-12 border guestList'>" . 
		             "<div class='col-sm-3 col-xs-2 bg-primary colX'>Guest </div>" . 
		             "<div class='col-sm-3 col-xs-3 bg-primary colX'>From  </div>" . 
					 "<div class='col-sm-3 col-xs-3 bg-primary colX'>To    </div>" . 
					 "<div class='col-sm-2 col-xs-2 bg-primary colX'>Duration</div>" .
					 "<div class='col-sm-1 col-xs-2 bg-primary colX'>Delete  </div>" .
					 "</div>";
                     
        if ($thisMonthDataList) {
            
		    foreach ($thisMonthDataList as $a) {
				
			    //generating guest list var $guestList  for $generalBookingInfo, i.e(guest/start/end/duration/delete)
			    $singleGuestDuration = (( $a->book_to_unix - $a->book_from_unix)/60/60/24) + 1; //amount of booked days for every guest
			    $guestList.= "<div class='col-sm-12 col-xs-12 border guestList'>" . 
				                 "<div class='col-sm-3 col-xs-2 colX'><i class='fa fa-calendar-check-o'></i>" . $a->booked_guest . "</div>" . //guest
        			             "<div class='col-sm-3 col-xs-3 colX'>" . $a->book_from  .  "</div>" . //from
						         "<div class='col-sm-3 col-xs-3 colX'>" . $a->book_to    . "</div>"  . //to
						         "<div class='col-sm-2 col-xs-2 colX'>" . $singleGuestDuration . "</div>" . //duration
						         "<div class='col-sm-1 col-xs-2 colX deleteBooking iphoneX' id='" . $a->book_id . "'> <i class='fa fa-cut' style='color:red;'></i></div>" .  //Delete icon
							  "</div>";
			    $overallBookedDays+= (( $a->book_to_unix - $a->book_from_unix)/60/60/24) + 1; //all amount of days booked in this month
		     }
		 
		} else {
			$overallBookedDays = " zero days";
			$guestList = "<p style='color:red'>" .
			             "You have NO Bookings in <b>" .  date("F-Y", $start) . "</b>" .  //month/year
              			 " for Room <b>" . $_POST['serverRoom'] . "</b>" .             //room
						 "&nbsp;<i class='fa fa-exclamation-triangle'></i></p><hr>";
		}
        
          
		//Var with general info, ie "In June u have 2 guests. Overal amount of days are 22."
		$generalBookingInfo = "<br><h3>In <b>" . date("F", $start).  //i.e June*
		                       "</b> the amount of booking ranges you have: <i class='fa fa-calendar-check-o'></i><b>&nbsp;" . count($thisMonthDataList) . "</b>. <br><br>" .
		                       "Overall amount of booked days are: <i class='fa fa-area-chart'></i>" . $overallBookedDays;
							   
		$generalBookingInfo.= "<hr><p><b>Guest list :</b></p>" . $guestList; 
        return $generalBookingInfo;        
        
    }
    
    
    /**
     * function that builds calendar
	 * @param int $start, $_POST['serverFirstDayUnix']
     * @param int $end,   $_POST['serverLastDayUnix']
     * @param array of ints $array_1_Month_days, array with booked days in this month, i,e [7,8,9,12,13]
     * @param array of strings $array_allGuests, will store all guests in relevant order according to values in $array_1_Month_days , ie [name, name]
     * @return string $text
     * 
     */
    function buildCalendar($start, $end, $array_1_Month_days, $array_allGuests)
    {
        $dayofweek = (int)date('w', $start); //returns the numeric equivalent of weekday of the 1st day of the month, i.e 1. 1 means Monday (first days of Loop month is Monday)
		$dayofweek = (($dayofweek + 6) % 7) + 1; //Mega Fix of Sundays, as Sundays in php are represented as {0}, and with this fix Sundays will be {7}
		$breakCount = 0; //var to detect when to use a new line in table, i.e add <td>
		$lastDayNormal = date("F-Y-d", $end);// gets the last day in normal format fromUnix, ie Jule-2019-31
		$lastDay = explode("-", $lastDayNormal);//gets the last day in this month, i.e 31
		$guestX = 0; //iterator to use in $array_allGuests
		 
		//building blanks days, it is engaged only in case the 1st day of month(1) is not the first day of the week, i.e not Monday
		for($i = 1; $i < $dayofweek; $i++) {  //$dayofweek is the 1st week day of the month, i.e 1. 1 means Monday
			$text.= "<td class='blank'>  </td>"; //Y
			$breakCount++;
		}
		 
		//building the calendar with free/taken days
		for($j = 1 /*$dayofweek*/; $j < (int)$lastDay[2]+1 /*count($array_1_Month_days)*/; $j++){  //$lastDay[2]+1 is a quantity of days in this month //$array_1_Month_days-> is an array with booked days in this month, i,e [7,8,9,12,13]
			//var to detect when to use a new line in table, i.e add <td>
			if($breakCount%7 == 0) {
                $text.= "<tr>";
            }
			$breakCount++;
			 
			if (in_array($j, $array_1_Month_days)) { //if iterator in array $array_1_Month_days, i.e this day is booked
			    $text.= "<td class='taken iphoneX' onClick='' title='already booked for " . $array_allGuests[$guestX]  ."'>" . $j . "</td>";  //title "booked for Guest name"
                $guestX++;			   
			} else {
				if ($j < 10) {
                    $v = "0" . $j; //if less than 10, add a zero, ie 09
                } else {
                    $v = $j;
                } 
				$day = date("Y-m", $start) . "-" . $v; //construct this date in format 2011-12-31, to use in data-dayZ[] in js/booking_cph.js
				$text.= "<td class='free iphoneX' onClick='' title='start booking' data-dayZ='" . $day . "'> " . $j . "</td>";
			} 
		}
        return $text;
    }
 
	
    
    
   
     /**
     * function that fix month margin, when booking include margin months, i.e 28 Aug - 3 Sept
	 * @param object $a ($a is a foreach iterator)
     * @param string $start, ($_POST['serverFirstDayUnix'], var is from ajax, 1st day of the selected month in Unix)
     * @param string $end,   ($_POST['serverLastDayUnix'], var is from ajax, last day of the selected month in Unix)
     * @return array
     * 
     */
    function marginMonthFix($a, $start, $end)
    {
      
        //Start MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept)*********************   
	    //fix for 1nd margin month, i.e for {28 Aug-31 Aug}  from (28 Aug - 3 Sept) (i.e we take only 28 Aug - 31 Aug) 
		if ($a->book_to_unix > (int)$_POST['serverLastDayUnix']) { //if last booked day UnixStamp in this month is bigger than this month last day UnixStamp (i.e it means that this current loop booking is margin & last date of it ends in the next month )
			$startDate = explode("-", $a->book_from); //i.e 2019-07-04 (y-m-d) split to [2019, 07, 04] //$startDate is a first booked day in DB for this month
			$diff = ((int)$_POST['serverLastDayUnix'] - $a->book_from_unix )/60/60/24; //i.e This month last day minus this loop DB booked start day
				
        } else if ($a->book_from_unix < (int)$_POST['serverFirstDayUnix']) {    //if 1st booked day UnixStamp in this month is smaller than this month 1st day UnixStamp (i.e it means that this current loop booking is margin & start date of it begun in past month )
			//fix for 2nd margin month, i.e for {1 Sept-3 Sept}  from (28 Aug - 3 Sept) (i.e we take only 1 Sept - 3 Sept) 
            $temp = date("Y-m-d", $_POST['serverFirstDayUnix']); //gets i.e 2019-07-01 (y-m-d), gets from the fist day in this month, i.e 2019-07-01
			$startDate = explode("-", $temp); //i.e 2019-07-01 (y-m-d) split to [2019, 07, 01] //$startDate is a first day for this month ;
		    $diff = ($a->book_to_unix - (int)$_POST['serverFirstDayUnix'])/60/60/24; ////i.e This loop DB booked end day minus This month fisrt day 
				
        //if booking is normal, withou margin month, i.e 12 Aug - 25 aug					
		} else {
			$startDate = explode("-", $a->book_from); //i.e 2019-07-04 (y-m-d) split to [2019, 07, 04] //$startDate is a first booked day in DB for this month
			$diff = ( $a->book_to_unix - $a->book_from_unix)/60/60/24; // . "<br>";  //$diff = number of booked days in this month, i.e 17 (end - start)
		}
                
        return array('difference'=> $diff, 'startDateX'=> $startDate );
    }

}