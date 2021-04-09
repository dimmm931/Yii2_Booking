<?php
//Controller for CPH_2_Booking_Hotel (created based on BookingCPH but it is a version with multiple rooms)
namespace app\controllers;

use Yii;
use app\models\BookingCPH_2_Hotel\BookingCphV2Hotel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BookingCphV2HotelV2HotelController implements the CRUD actions for BookingCphV2HotelV2Hotel model.
 */
class BookingCphV2HotelController extends Controller
{
	public $roomX;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
		
		    'access' => [
                'class' => AccessControl::className(),
				
				//To show message to unlogged users. Without this unlogged users will be just redirected to login page
				'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\NotFoundHttpException("Only logged users are permitted!!!");
                 },
				 //END To show message to unlogged users. Without this unlogged users will be just redirected to login page
				 
				//following actions are available to logged users only 
                'only' => ['index', 'index-grid', 'ajax_get_6_month'], 
                'rules' => [
                    [
                        'actions' => ['index', 'index-grid', 'ajax_get_6_month'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],		
                ],
            ],
			 
			 
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
                'class' => 'yii\web\ErrorAction',  //predifined error handler, comment if want to use my personal
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	
		
	
    /**
     * Lists all BookingCphV2HotelV2Hotel models.
     * @return mixed
     */
    public function actionIndexGrid()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BookingCphV2Hotel::find(),
        ]);

        return $this->render('index-grid', [
            'dataProvider' => $dataProvider,
        ]);
    }

	

	
    /**
     * MAIN-CORE action (sends ajaxes). Uses web/js/booking_cph.js
     * @return string
     */
    public function actionIndex() 
    {
	    date_default_timezone_set('UTC');  
	    $model = new BookingCphV2Hotel(); 
	    $model->booked_by_user = Yii::$app->user->identity->id; //fill the field "Who is booking" default value
	  
	    //SAVING THE FORM TO DB 
	    //if u filled in the form to book a new date range for a new guest
	    if ($model->load(\Yii::$app->request->post())) {
		 
		    //check if selected in form dates are not booked yet(not yet in DB BookingCphV2Hotel) 
		    $checkIf_free = BookingCphV2Hotel::find() 
		        ->where( 'book_room_id =:status', [':status' => $model->book_room_id] ) //room ID from Form, form is in views/idex.php. Value to this form field is set in booking_cph-2.js by ID // 
			    ->andWhere([ 'or',
				            ['or', 
							    ['between', 'book_from_unix', strtotime($model->book_from), strtotime($model->book_to) ], ['between', 'book_to_unix',   strtotime($model->book_from), strtotime($model->book_to) ] ],
							['and', 
							    ['<','book_from_unix',strtotime($model->book_from)], ['>', 'book_to_unix', strtotime($model->book_to) ] ],
					        ])->all();
      
            
		    if(empty($checkIf_free)){ //i.e if these form dates are not in DB, meaning free
		        if($model->save()){
                     \Yii::$app->session->setFlash("successX", "Successfully booked with guest <i class='fa fa-address-book-o' style='font-size:1.2em;'></i> <b> {$model->booked_guest}</b> . 
				        <b>Room <i class='fa fa-hotel' style='font-size:1.3em;'></i>:{$model->book_room_id}, from {$model->book_from} to {$model->book_to} </b>. 
					     Details are sent to your mail <i class='fa fa-envelope-ol' style='font-size:1.3em;'></i>");		  			  	
			        return $this->refresh(); //prevent  F5  resending	
                } else {
		             \Yii::$app->session->setFlash('failedX', 'Booking Failed, Please click  <button data-target="#rbacAdd" data-toggle="collapse">&darr;&darr;</button> button to see details. &nbsp;&nbsp;<i class="fa fa-asterisk fa-spin" style="font-size:24px"></i>');

			    } 
		    } else {
			    \Yii::$app->session->setFlash('failedX', 'Booking Failed, These dates are already taken. Please click  <button data-target="#rbacAdd" data-toggle="collapse">NEW BOOKING</button> button to change dates &nbsp;&nbsp; <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
		    }   	 
	    }
	 
        return $this->render('index' , [
            'model' => $model,  //form model of models/BookingCphV2Hotel
        ]);
    }	
	
	
    /**
     * function that handles ajax request sent from js/booking_cph.js -> function get_6_month()
     * @return json
     * 
     */
    public function actionAjax_get_6_month() 
    {	
	    global $roomX;
	    $roomX = $_POST['serverRoomId'];
	   
        $model = new BookingCphV2Hotel(); 
	    date_default_timezone_set('UTC');  //use ('UTC') to avoid -1 day result    //('europe/kiev')	('UTC')
	    error_reporting(E_ALL & ~E_NOTICE); //JUST TO FIX 000wen HOSTING, Hosting wants this only for Ajax Actions!!!!!!!!!!!!!!!
	    $array_All_Month = array();//will store all 6 month data
	    $array_All_Unix = array();//will store all Unix start & stop time for all 6 month, will be in format [[CurrentStart, Currentend], [start-1, end-1], [start-2, end-2]]
        $array_All_sqlData = array();//will store all sql results
	    $array_All_CountDays = array();//will store counts of booked days for every month (for badges)
	   	   
	    $nextMonthQuantity = 9;
	    for ($i = 0; $i < $nextMonthQuantity; $i++) {
		    //gets next 9 months list & adds it to array $array_All_Month, i.e $array_All_Month = ["Nov 2019", "Dec 2019", etc] 
		    $thisMonth = $model->get_All_Next_Months_List($i);  //this methods return=> array(Jul 2019, Jul) //pass {$i} as arg is a must
		    array_push($array_All_Month, $thisMonth[0]); // $thisMonth[0] => $PrevMonth . " " . $PrevYear; //i.e Jul 2019
		    $y = $model->correctYear($thisMonth[1], $i);  //@param string $thisMonth[1], e.g $PrevMonth = 'Jan'// $thisMonth[1] => $PrevMonth, i.e 'Jul' //this methods returns=> array('may'=> 6, 'yearX'=> 2019)
		   
		   //gets next 9 months UnixTime & adds it to array $array_All_Unix, i.e $array_All_Unix =[[CurrentStart, Currentend], [start-1, end-1], [start-2, end-2]]
		   $unix = $model->get_Next_Months_Unix($y['may'], $y['yearX']); //$y['may']=> index of month, i.e if Jul returns => 6 //this methods returns=> array('array_tempo'=> [UnixStart, UnixEnd], 'first1'=> '2019-05-01', 'last1'=> '2019-05-31')
		   array_push($array_All_Unix, $unix['array_tempo']); //will store all Unix start & stop time for all 6 month, will be in format [[CurrentUnixStart, CurrentUnixEnd], [start-1, end-1], [start-2, end-2]]
		   
		   //gets next 9 months booked dates info & adds them to array $array_All_sqlData, i.e $array_All_sqlData = [[{book_id: 55, booked_by_user: "Dima", book_from: "2019-12-27", book_to: "2019-12-28"], [], []];
		   $sqlResult = $model->findBooked_Dates_In_Month($i, $unix['first1'], $unix['last1'], $_POST['serverRoomId']); //Find SQL data for a specific NEXT month//$unix['first1']=> gets the first day of the current month, i.e "2019-05-01" //this methods returns=> ${'monthData'.$i}
		   array_push($array_All_sqlData, $sqlResult);//Find SQL data for a specific NEXT month (+ this current month in first iteration) (from 6-months range) one by one in a loop
		   
		   //count the quantity of booked days for every next 9 months & adds them to array $array_All_CountDays, i.e $array_All_CountDays = [0,2,4,0]
		   $badgesCount = $model->getCount_OfBooked_Days_For_Badges_and_FixMargins($sqlResult, $unix['first1'], $unix['last1']);
		   array_push($array_All_CountDays, $badgesCount);//will store counts of booked days for every month (for badges)
	    }
	 
		//RETURN JSON DATA
		// Specify what data to echo with JSON, ajax usese this JSOn data to form the answer and html() it, it appears in JS consol.log(res)
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;  
        return [
            'result_status'       => "OK", 
            'code'                => 100,	 
			'allMonths'           => $array_All_Month, //$r[0]['array_All_Month'], //.$array_All_Month, //["Nov 2019", "Dec 2019", etc] 
			'array_All_sqlData'   => $array_All_sqlData, //$r[4]['array_All_sqlData'], // $array_All_sqlData,    //will all sql DB booking RESULTS,
			'array_All_Unix'      => $array_All_Unix, // $r[5]['array_All_Unix'], //$array_All_Unix,     //will store all Unix start & stop time for all 6 month, will be in format [[CurrentStart, Currentend], [start-1, end-1], [start-2, end-2]]
			'array_All_CountDays' => $array_All_CountDays, //$r[6] //$array_All_CountDays //will store counts of booked days for every month (for badges)
            'selectedRoom'        => $_POST['serverRoomId'] //number of selected room, passing to html(0 it in views/index.php to id='roomSelected' 
        ]; 
	}
    
	
	   
	/**
     * function that handles ajax request sent from js/booking_cph.js -> function get_1_single_month(thisX)
	 * gets data from SQL for 1 single clicked month and build a calendar
     * @return string
     * 
     */
    public function actionAjax_get_1_month()
    {
		error_reporting(E_ALL & ~E_NOTICE); //JUST TO FIX 000wen HOSTING, Hosting wants this only for Ajax Actions!!!!!!!!!!!!!!!
		$model = new BookingCphV2Hotel(); 
        
		$array_1_Month_days = array();//will store 1 month data days, ie [5,6,7,8]
		$array_allGuests = array();//will store all guests in relevant order according to values in $array_1_Month_days , ie [name, name]
		$MonthList= array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); //General array for all click actions
		//$overallBookedDays; //all amount of days booked in this month
		$text; //will contain the whole result
		 
		$start = (int)$_POST['serverFirstDayUnix']; //1561939200 - 1st July;  //var is from ajax, 1st day of the month in Unix 
		$end = (int)$_POST['serverLastDayUnix']; //1564531200 - 31 July; //var is from ajax, the last day in this month, i.e amount of days in this month, i.e 31
	
		$text = "<br><br><br><br><div><h2>" .date("F-Y", $start) . "</h2> <p><span class='example-taken'></span> - means booked dates</p></div><br>"; //header: month-year //returns July 2019 + color sample explain
		$text.="<table class='table table-bordered'>";
		$text.= "<tr><th> Mon </th><th> Tue </th><th> Wed </th><th> Thu </th><th> Fri </th><th> Sat </th><th> Sun </th></tr>";
		
	    //Finds SQL and construct Data For Guest List (finds only for this User's data, i.e WITH =>  {where([ 'booked_by_user' => Yii::$app->user->identity->username,})
		//SQL ActiveRecord:find all this 1 single month data for THIS SPECIFIC USER. This Data is for Guest List (display all booked dates of this ONE USER ONLY)
		$thisMonthDataList = BookingCphV2Hotel::find() ->orderBy ('book_id DESC')  /*->limit('5')*/ 
		    ->where([ 'booked_by_user' => Yii::$app->user->identity->id, /* 'mydb_id'=>1*/])   //if this line uncommented, each user has its own private booking(many users-> each user has own private booking appartment, other users cannot book it). Comment this if u want that booking is general, ie many users->one booking appartment(many users can book 1 general appartment) 
			->andWhere( 'book_room_id =:status', [':status' => $_POST['serverRoom']] ) //room ID $postD //   
			->andWhere([ 'or',
				['between', 'book_from_unix', $start, $end  ], //$start, $end => is Unix
			    ['between', 'book_to_unix',   $start, $end ] ])
			->all(); 
        
        //function that builds Guests List for this one month, 
        $generalBookingInfo = $model->buildThisMonthBookedDaysList($thisMonthDataList); 
		
        
		/*				
		//guest list for $generalBookingInf //Forming here column names(like <TH>) for $guestList table, i.e(guest/start/end/duration/delete)
		$guestList = "<div class='col-sm-12 col-xs-12 border guestList'>" . 
		             "<div class='col-sm-3 col-xs-2 bg-primary colX'>Guest </div>" . 
		             "<div class='col-sm-3 col-xs-3 bg-primary colX'>From  </div>" . 
					 "<div class='col-sm-3 col-xs-3 bg-primary colX'>To    </div>" . 
					 "<div class='col-sm-2 col-xs-2 bg-primary colX'>Duration</div>" .
					 "<div class='col-sm-1 col-xs-2 bg-primary colX'>Delete  </div>" .
					 "</div>";
	
              
	    //complete $array_1_Month_days with booked days in this month, i,e [7,8,9,12,13]
       
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
	    */
        //END Find For Guest List (only this User data)------------------------------------------------------
		 

		 
	    //Find data For Calendar (finds ALL  User's data, i.e WITHOUT => { where([ 'booked_by_user' => Yii::$app->user->identity->username,})
		//SQL ActiveRecord:find all this 1 current single month data. This Data is for calendar (gets all booked dates of all users for this month and room). The aim to compile CORE array {$array_1_Month_days} with booked days  i,e [7,8,9,12,13]
		$thisMonthData = BookingCphV2Hotel::find() ->orderBy ('book_id DESC')   
			->andWhere( 'book_room_id =:status', [':status' => $_POST['serverRoom']] ) //room ID $postD //   
			->andWhere([ 'or',
				['between', 'book_from_unix', $start, $end  ], //$start, $end => is Unix
				['between', 'book_to_unix',   $start, $end ] ])
			->all(); 
	     
		//complete $array_1_Month_days with booked days in this month, i,e [7,8,9,12,13]
		if ($thisMonthData) {
		    foreach ($thisMonthData as $a) {
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
				//END MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept)**************************

			    //complete $array_1_Month_days with booked days in this month, i,e [7,8,9,12,13]
			    for ($i = 0; $i < $diff+1; $i++) {
			        $d = (int)$startDate[2]++; //(int) is used to remove 0 if any, then do ++
			        array_push($array_1_Month_days, $d ); //adds to array booked days, i.e [7,8,9,12,13]
				    array_push/*array_unshift*/($array_allGuests, $a->booked_guest); //adds to array a guest name to display as titile in calnedar on hover //use array_unshift() to add to begging(not end) of array
		        } 
		    }
		}
		
        $calendarText = $model->buildCalendar($start, $end, $array_1_Month_days, $array_allGuests); 
        $text = $text . $calendarText;
		//START BUILDING A CALENDAR-----------------
        /*
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
		for($j = 1; $j < (int)$lastDay[2]+1 ; $j++){  //$lastDay[2]+1 is a quantity of days in this month //$array_1_Month_days-> is an array with booked days in this month, i,e [7,8,9,12,13]
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
				$text.= "<td class='free iphoneX' onClick='' title='start booking' data-dayZ='".$day."'> " . $j . "</td>";
			} 
		}
        */
		//END BUILDING A CALENDAR---------------------
		 
		$text.= "</table><hr><hr>" . $generalBookingInfo; // . "<h4>Booked days array=>" . implode("-", $array_1_Month_days) ."</h4>"; // just to display array with booked days, i.e [4,5,6,18,19]
		 
		return $text;
	}
		



    //STOPPED HERE!!!!!!!!!!!!!!!!!!!!!!!!!!!

	
	
	
	
	
	
	//action to delete  a single booking. Comes as ajax from js/booking_cph.js-> run_ajax_to_delete_this_booking(passedID). Triggered in $(document).on("click", '.deleteBooking', function()
	// **************************************************************************************
    // **************************************************************************************
    // **                                                                                  **
    // **                                                                                  **
	 public function actionAjax_delete_1_booking() //ajax
     {
		$status = "Pending"; 
	    $thisMonthData = BookingCphV2Hotel::find() 
		     -> where([ 'book_id' => $_POST['serverBookingID']])  
			 ->where([ 'booked_by_user' => Yii::$app->user->identity->id])
			 -> one() 
			 -> delete();  
			 
        if($thisMonthData){
			$status = "Deleted Successfully " . $_POST['serverBookingID'];
		} else {
			$status = "Failed deleting";
		}	
        //RETURN JSON DATA
		 // Specify what data to echo with JSON, ajax usese this JSOn data to form the answer and html() it, it appears in JS consol.log(res)
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;  
          return [
             'result_status' => "OK", // return ajx status
             'delete_status' => $status,		 
          ]; 		
	 }
	
    		
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************	
		
	
	
	
	
	
	
	
	
    /**
     * Displays a single BookingCphV2HotelV2Hotel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BookingCphV2HotelV2Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BookingCphV2HotelV2Hotel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->book_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BookingCphV2HotelV2Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->book_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BookingCphV2HotelV2Hotel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BookingCphV2HotelV2Hotel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookingCphV2HotelV2Hotel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookingCphV2HotelV2Hotel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
	
	
	
	
	
}
