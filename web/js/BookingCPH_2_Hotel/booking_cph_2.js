//Booking CPH-2 Hotel (created based on BookingCPH but it is a version with multiple rooms)
(function(){ //START IIFE (Immediately Invoked Function Expression)
$(document).ready(function(){
	var clickedThis; //global var with $this ($this of clicked on a single month among 6 month )

	 //clicking on any room
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	
	$(document).on("click", '.subfolder', function(event) {      //for newly generated 
		event.preventDefault(); //not to react to <a href>
		$(".single-clicked-month").html(''); //hide calendar if was activated prev
		addRemoveClass($(this));
		var roomX = "Room " + this.id;
		$("#roomNumber").stop().fadeOut("slow",function(){ $(this).html(roomX)}).fadeIn(2000);
		if(screen.width <= 640){ //mobile only
		    scrollResults("#roomNumber", ".parent()."); //advanced scroll, scrolls to div with form //arg(DivID, levels to go up from DivID)
		}
		get_6_month(this.id);  
	 });
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	
	
	//clicking on any 1 one single  month (any of 6 month)
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	
	$(document).on("click", '.my-month', function() {      //for newly generated 
	    clickedThis = this; //to use in fucntion that deletes  a booking and needs to renew the view
		get_1_single_month(this); //pass this to get this attributes
		  
		//Scroll to results in Mobile only
		/*if(screen.width <= 640){ 
	        scrollResults(".single-clicked-month"); //scroll to div
		}*/
	});
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	

	
	
	//function that draw 6 month with badges //onLoad sends ajax request to BookingCphControler->function actionAjax_get_6_month()
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	function get_6_month(idX){ 
        $(".loader").show(200); //show the loader
		
	    //getting the path to current folder with JS to form url for ajax, i.e /yii2_REST_and_Rbac_2019/yii-basic-app-2.0.15/basic/web/booking-cph/ajax_get_6_month
        var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/'));
		var urlX = dir + '/ajax_get_6_month';
        
	    
		// send  data  to  PHP handler  ************ 
        $.ajax({
            url: ajaxUrl, //ajaxUrl is a var registered in view,
            type: 'POST',
			dataType: 'JSON', // without this it returned string(that can be alerted), now it returns object
			//passing the city
            data: { 
			    serverRoomId: idX
			},
            success: function(data) {
                // do something;
                //$(".all-6-month").stop().fadeOut("slow",function(){ $(this).html("OK")}).fadeIn(2000);
				console.log(data);
				getAjaxAnswer_andBuild_6_month(data, idX); //data => return from php script //idX => id of clicked room
				$(".loader").hide(3000); //hide the loader
				
            },  //end success
			error: function (error) {
				$(".all-6-month").stop().fadeOut("slow",function(){ $(this).html("Failed")}).fadeIn(2000);
				console.log(data);
            }	
        });
	}
	
    // **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	

	
	
	//Function to use in ajax SUCCESS section in function get_6_month(){. It builds 6 month view with badges
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	
	function getAjaxAnswer_andBuild_6_month(dataX, idXZ){ //dataX is an ajax result from a BookingCphControler/public function actionAjax_get_6_month() //idXZ => id of clicked room		
		//HTML This 1 current month
		var finalText = '';//'<div class="col-sm-3 col-xs-5 my-month badge badge1" id="0"><span class="v">Current</div>';
		
		//HTML other 
		for(var i = 0; i < dataX.array_All_CountDays.length; i++){
			finalText+= '<div class="col-sm-3 col-xs-5 my-month badge badge1 iphoneX"' + 
			            'data-badge="' + dataX.array_All_CountDays[i][0] + "/" + dataX.array_All_CountDays[i][1] + //data badge (amount of booked days in month (round red circle)), i.e{booked days/all days in month}
						'" data-myUnix=' + dataX.array_All_Unix[i][0] + '/' + dataX.array_All_Unix[i][1] + //additional "data-myUnix" to keep Unix time of the 1st and last days of the formed month, i.e = 43643483/3647634
						'" data-myRoom=' + idXZ +  //data with cliked selected room id
						' id=' + i + '> <span class="v">' + dataX.allMonths[i]  + '</span></div>';
		}
		
		$(".all-6-month").stop().fadeOut("slow",function(){ $(this).html(finalText)}).fadeIn(2000);
		$("#roomSelected").html(dataX.selectedRoom); //html() room selected number in form <p> in  index.php
		$("#roomNumberrr").val(dataX.selectedRoom); //val the form input "Room number" 
			
	}
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	
	//Function to draw one single month
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	
	function get_1_single_month(thisX){
		$(".loader").show(200); //show the loader
		 
		var roomZ = thisX.getAttribute("data-myRoom");; //gets selected room ID, take it from <div data-myRoom="">		 
		var Unix = thisX.getAttribute("data-myUnix");
		var firstDayUnix = Unix.split("/")[0];
		var lastDayUnix = Unix.split("/")[1];
		 
		//getting the path to current folder with JS to form url for ajax, i.e /yii2_REST_and_Rbac_2019/yii-basic-app-2.0.15/basic/web/booking-cph/ajax_get_6_month
		var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/'));
		var urlX = dir + '/ajax_get_1_month';
		
		// send  data  to  PHP handler  ************ 
        $.ajax({
            url: ajaxUrl2, //var is registered in view
            type: 'POST',
			dataType: 'text' ,//'JSON', // without this it returned string(that can be alerted), now it returns object
			//passing the city
            data: { 
			    serverFirstDayUnix:firstDayUnix,
				serverLastDayUnix: lastDayUnix,
				serverRoom: roomZ  //selected room
			},
            success: function(data) {
                // do something;
                $(".single-clicked-month").stop().fadeOut("slow",function(){ $(this).html(data)}).fadeIn(2000);
				//getAjaxAnswer_andBuild_1_month(data);
				$(".loader").hide(5000); //hide the loader
				scrollResults(".single-clicked-month"/*, ".parent()."*/); /*.single-clicked-month*///scroll to div. Put it here to ensure, that the div has been html-es, so it scroll directly to it, + make sure loader will visible
                //sets the same hight for Guest List Table in mobile only
				if(screen.width <= 640){ 
				    setSameHight_JS();
				}
			},  //end success
			error: function (error) {
				$(".single-clicked-month").stop().fadeOut("slow",function(){ $(this).html("Failed")}).fadeIn(2000);
            }	
        });
	 }
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	
	//Click on a free green date in 1 month calendar
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     **
	
	$(document).on("click", '.free', function() {//this click is used to react to newly generated cicles;
		var dayZ = this.getAttribute("data-dayZ"); //gets data-dayZ (it is set in {BookingCphControler/actionAjax_get_1_month()}, must be set there in format in format 2011-12-31
		//Sweet alert.
		swal({ title: dayZ.split("-")[2] + "." + dayZ.split("-")[1] + "." + dayZ.split("-")[0] + " is Free!", //Sweet alert   // 21.09.2011 is Free //dayZ is MM/DD/YY, change to DD/MM/DD/YY
		    text: "Want to start booking from this date?", 
		    type: "success", 
		    showCancelButton: true, 
			confirmButtonClass: "btn-danger",
			//closeOnConfirm: false
		},
		function(valueX){
			if(valueX){ //if click OK tobook
				$("#rbacAdd").show(999); //open the form, change it from hidden to open
				scrollResults("#rbacAdd", ".parent().parent()."); //advanced scroll, scrolls to div with form //arg(DivID, levels to go up from DivID)
				$("#uniqueIDFrom").val(dayZ); //set clicked in calendar day to form_input_1  
				$("#uniqueIDTo").val(dayZ); //set clicked in calendar day to form_input_2 (just to help navigation)
				$("#uniqueIDTo").focus(); //set focus to input_2
				//$("#uniqueIDFrom").datepicker("setDate", new Date(2018, 8, 1));
			} else {    //if click cancel
			    //alert('cancelled');
			}
        });
		//end Swall callback 

	});
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	//Click on a booked date in 1 month calendar
	$(document).on("click", '.taken', function() {//this click is used to react to newly generated cicles;
	    //alert("Sorry, this date is already booked");
		swal("Sorry!", "This date is already booked!", "error");
	});
	
	
	
	
	//Click to delete a single booking, available for click when u select a certain month
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	$(document).on("click", '.deleteBooking', function() {//this click is used to react to newly generated cicles;
	    if (confirm("Are you sure to delete this booking?") == true) {
		    run_ajax_to_delete_this_booking(this.id);
            return true;
        } else {
            return false;
        }
	});
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
			
	//Function to delete  a single booking, triggered in $(document).on("click", '.deleteBooking', function()
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	function run_ajax_to_delete_this_booking(passedID){
		$(".loader").show(200); //show the loader
		 
		//getting the path to current folder with JS to form url for ajax, i.e /yii2_REST_and_Rbac_2019/yii-basic-app-2.0.15/basic/web/booking-cph/ajax_get_6_month
		var loc = window.location.pathname;
        var dir = loc.substring(0, loc.lastIndexOf('/'));
		var urlX = dir + '/ajax_delete_1_booking';		
		
		// send  data  to  PHP handler  ************ 
        $.ajax({
            url: urlX,
            type: 'POST',
			dataType: 'json' ,//'JSON', // without this it returned string(that can be alerted), now it returns object
			//passing the city
            data: { 
			    serverBookingID:passedID,
	
			},
            success: function(data) {
                // do something;
				alert(data.delete_status);
				$(".loader").hide(5000); //hide the loader
				get_1_single_month(clickedThis); //renew the view of clicked month
				//scrollResults(".single-clicked-month"/*, ".parent()."*/); /*.single-clicked-month*///scroll to div. Put it here to ensure, that the div has been html-es, so it scroll directly to it, + make sure loader will visible
                get_6_month();//to renew Count badges
			},  //end success
			error: function (error) {
				alert("Error while deleting the booking");
            }	
        });
	  }
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	


   //function that sets the same hight for Guest List Table, uses function getTallestHeight. We use delay, as this div is loaded with animation for 2 sec and function can't see its classes ".colX"
   //Used in mobile only
   // **************************************************************************************
   // **************************************************************************************
   //                                                                                     ** 
   function setSameHight_JS(){
	    setTimeout(function(){
			var els = $('.colX');
	        var s = getTallestHeight(els); //alert(s);
            els.height(s);
		}, 3000);	
    }
   
    function getTallestHeight(elements) {
        var tallest = 0, height;

        for(i = 0; i < elements.length; i++) {
            height = $(elements[i]).height();

            if(height > tallest) 
               tallest = height;
        }

        return tallest;
    };
	
  // **                                                                                  **
  // **************************************************************************************
  // **************************************************************************************	
	
	
	

	
		
	//DISABLE BOOKED date in datepicker
    var disabledDays = [
       "21-8-2019", "21-08-2019", "26-12-2016",
       "4-4-2017", "5-4-2017", "6-4-2017", "6-4-2016", "7-4-2017", "8-4-2017", "9-4-2017"
    ];

	
   //========================================================================================================================




    
	
	
    // **************************************************************************************
    // **************************************************************************************
    //                                                                                     **
        
    function addRemoveClass(passObjFromTableSelectAction){  //passObjFromTableSelectAction is $(this) passed from tableSelectAction(thisObjZ)
        $(".subfolder").removeClass("selected");
        passObjFromTableSelectAction.addClass("selected");//assign class to clicked
    }

   // **                                                                                  **
   // **************************************************************************************
   // **************************************************************************************

	
	
	// Advanced Scroll the page to results  #resultFinal
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	function scrollResults(divName, parent)  //arg(DivID, levels to go up from DivID)  //scrollResults("#roomNumber", ".parent().");
	{   //if 2nd arg is not provided while calling the function with one arg
		if (typeof(parent)==='undefined') {
            $('html, body').animate({
                scrollTop: $(divName).offset().top
                //scrollTop: $('.your-class').offset().top
            }, 'slow'); 
		     // END Scroll the page to results
		} else {
			//if 2nd argument is provided
			var stringX = "$(divName)" + parent + "offset().top";  //i.e constructs -> $("#divID").parent().parent().offset().top
			$('html, body').animate({
                scrollTop: eval(stringX)         //eval is must-have, crashes without it
            }, 'slow'); 
		}
	}
	
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	
	function scroll_toTop() 
	{
	    $("html, body").animate({ scrollTop: 0 }, "slow");	
	}
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	
	
	
	
   //LOADER SECTION
   // **************************************************************************************
   // **************************************************************************************
   //                                                                                     ** 
	
	var showPage = function(){   
        document.getElementById("loaderX").style.display = "none"; //hides loader
        document.getElementById("all").style.display = "block";    //show div id="all"
    }
	
	
	
	function appendLoaderDiv(){
	    var elemDiv = document.createElement('div');
	    elemDiv.id = "loaderX";
        //elemDiv.style.cssText = 'position:absolute;width:100%;height:100%;opacity:0.3;z-index:100; top:20px;';
	    //$("#loaderX").append('<img id="theImg" src="images/load.gif" />');
	    //elemDiv.innerHTML = '<img id="theImg" src="images/load.gif" />'; 
	    //elemDiv.style.backgroundColor = "black";
	    //$("#loaderX").css("background", "url('images/load.gif')");
        document.body.appendChild(elemDiv);
	} 
	   
	if(document.getElementById("all") !== null){ //additional check to avoid errors in console in actions, other than actionShowAllBlogs(), when this id does not 
	    appendLoaderDiv(); //appends a div id="loaderX" with pure CSS loader to body, no code in index.php, just css to mycss.css
	    var myVar = setTimeout(showPage, 1000);
	}
	// **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************
	 //END LOADER SECTION 
	
});
// end ready	
	
}()); //END IIFE (Immediately Invoked Function Expression)