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
            'error' => [
                'class' => 'yii\web\ErrorAction',  //pre-difined error handler, comment if want to use your personal
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
	    //date_default_timezone_set('UTC');  
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
	    //date_default_timezone_set('UTC');  //use ('UTC') to avoid -1 day result    //('europe/kiev')	('UTC')
	    error_reporting(E_ALL & ~E_NOTICE); //JUST TO FIX 000wen HOSTING, Hosting wants this only for Ajax Actions!!!!!!!!!!!!!!!
	    $array_All_Month = array();    //will store all 6 month data
	    $array_All_Unix = array();     //will store all Unix start & stop time for all 6 month, will be in format [[CurrentStart, Currentend], [start-1, end-1], [start-2, end-2]]
        $array_All_sqlData = array();  //will store all sql results
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
		$text; //will contain the whole result
		$start = (int)$_POST['serverFirstDayUnix']; //1561939200 - 1st July;  //var is from ajax, 1st day of the month in Unix 
		$end   = (int)$_POST['serverLastDayUnix']; //1564531200 - 31 July; //var is from ajax, the last day in this month, i.e amount of days in this month, i.e 31
	
		$text = "<div><h2>" .date("F-Y", $start) . "</h2> <p><span class='example-taken'></span> - means booked dates</p></div><br>"; //header: month-year //returns July 2019 + color sample explain
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
        $generalBookingInfo = $model->buildThisMonthBookedDaysList($thisMonthDataList, $start); 

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
                
                //Start MARGIN MONTHS fix, when booking include margin months, i.e 28 Aug - 3 Sept)  
                $fixMargins = $model->marginMonthFix($a, $start, $end);
                $diff      = $fixMargins['difference']; 
                $startDate = $fixMargins['startDateX'];
                
			    //complete $array_1_Month_days with booked days in this month, i,e [7,8,9,12,13]
			    for ($i = 0; $i < $diff+1; $i++) {
			        $d = (int)$startDate[2]++; //(int) is used to remove 0 if any, then do ++
			        array_push($array_1_Month_days, $d ); //adds to array booked days, i.e [7,8,9,12,13]
				    array_push($array_allGuests, $a->booked_guest); //adds to array a guest name to display as titile in calnedar on hover //use array_unshift() to add to begging(not end) of array
		        } 
		    }
            
		}
		
        //START BUILDING A CALENDAR
        $calendarText = $model->buildCalendar($start, $end, $array_1_Month_days, $array_allGuests); 
        $text = $text . $calendarText;
		$text.= "</table><hr><hr>" . $generalBookingInfo; // . "<h4>Booked days array=>" . implode("-", $array_1_Month_days) ."</h4>"; // just to display array with booked days, i.e [4,5,6,18,19]
		return $text;
	}
	
	
	/**
     * Action to delete  a single booking. Comes as ajax from js/booking_cph.js-> run_ajax_to_delete_this_booking(passedID). Triggered in $(document).on("click", '.deleteBooking', function()
     * @return 
     * 
     */
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
