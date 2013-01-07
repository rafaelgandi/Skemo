/**


     THIS IS THE HELPER CALENDAR DATE GENERATOR SCRIPT
     THIS IS THE CALENDAR MODULE OF THE HELPERS JAVASCRIPT TOOLKIT
     THIS IS USED IN THE  DATE PICKER FUNCTIONALITY OF THE TOOLKIT


*/


var rightnow = new Date();
var month = rightnow.getMonth();
var year = rightnow.getFullYear();
var dateToday = rightnow.getDate();
var othermonth = rightnow.getMonth();
var refNum = 0;
var week = "<td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td>";
/////////////////////////////////////////////////////
function generateCalendar(m,y){

	var calindex = refNum;
	var calnum = 1;
	var start = getStartOfMonth(m,y);
	var end = getEndOfMonth(m,y);
	var dayHTML = "";
	var row = 6;        
	var checker = 1;     /// checks if the whole row is empty. 
	var watcher = false; /// ensures that the commenting happens only on the first row.
	var dayFinder = start;
	var calHTML = "<table cellspacing='2' cellpadding='2' border='0' class='calTable' width='180' height='200'>\n";
	calHTML += "<tr class='HEADERSTYLE'><td colspan='2'><button onclick='goBack();'  class='calButton'><<</button></td><th colspan='3'><center>" + year + "</center></th>";
	calHTML += "<td colspan='2' ><button onclick='goForward();' class='calButton' >>></button></td></tr>";
	calHTML += "<tr><th colspan='7' align='center' >" + getNameOfMonth(month) +"</th></tr>";
	calHTML += "<tr class='weekStyle'>" + week + "</tr>";
	
	////OUTER LOOP////
	
	for(var r=1; r<=row; r++){
	dayHTML +="<tr>";
	
	 ////INNER LOOP///
	 for(var c=1; c<=7; c++){
	
	 if ((calindex < start)||(calnum > end)) { //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
	     if((checker==7)&&(watcher==false)) {         ///this one checks if the whole row is empty. 
	           dayHTML += "<td >&nbsp;</td>";         
	           watcher = true;  
	     }
	     else {
	           dayHTML += "<td >&nbsp;</td>";         
	           checker++;
	     }
	 
	 }    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
	 else { //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
	
	  watcher=false;                                                 
	  if((calnum==dateToday) && (month == othermonth)){         ///Checks the day today
	    dayHTML += "<td><a href='javascript:putDayValue("+y+","+m+","+calnum+");' class='today'>" + calnum + "</a></td>";     ///
	    dayFinder++; 
	  }
	  else{
	   if(dayFinder > 6){ dayFinder=0; } //// this line checks if the dayFinder is greater than 6
	    if(dayFinder==0){ 
	       dayHTML += "<td class='sundayColor'><a href='javascript:putDayValue("+y+","+m+","+calnum+");' class='sundayColor'>" + calnum + "</a></td>";
	       dayFinder++;
	    }
	    else{
	       var countainer="<td ><a href='javascript:putDayValue("+y+","+m+","+calnum+");' class='normalDay'>" + calnum + "</a></td>";
	       dayHTML += countainer;
	       dayFinder++;
	    }
	  }  
	 calnum++;
	 }    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
	 calindex++;
	
	 }
	 ////END OF INNER LOOP////
	dayHTML += "</tr>";
	  if((checker==7)&&(watcher==true)) {   ///this one checks the start of the week
	  
	      dayHTML = "" ;
	      row = 7;
	  }
	}
	////END OF OUTER LOOP////
	
	//*
	if(document.getElementById('helper_calendar') == null){
		var calendar_div = document.createElement('div');
		calendar_div.setAttribute('id','helper_calendar');
		calendar_div.setAttribute('class','calTable');
		document.body.appendChild(calendar_div);
	}//*/
	
	var calObj= document.getElementById('helper_calendar');
	calObj.innerHTML = calHTML + dayHTML + "<tr><td colspan='4' align='center'><a href='javascript:today();' class='cancel'>Today</a></td><td colspan='3' align='center'><a href='javascript:hideCalendar();' class='cancel'>Cancel</a></td></tr></table>";

}


//////////////////////////////////////////////////
function getStartOfMonth(mth,yr){
   var getday = new Date();
   getday.setMonth(mth);
   getday.setFullYear(yr);
   getday.setDate(1);
   return getday.getDay();
}
////////////////////////////////////////////////
function getEndOfMonth(mth,yr){
   var getend = new Date();
   var endings = new Array(31,29,31,30,31,30,31,31,30,31,30,31);
   mth=parseInt(mth);
   yr=parseInt(yr);
 if(mth==1){
   getend.setMonth(1);
   getend.setFullYear(yr);
   getend.setDate(29);
   if(mth==2) endings[1]=28;
 }
   return endings[mth];
}
///////////////////////////////////////////////
function getNameOfMonth(mth){
 var thename=new Array();
 thename[0]="January";
 thename[1]="February";
 thename[2]="March";
 thename[3]="April";
 thename[4]="May";
 thename[5]="June";
 thename[6]="July";
 thename[7]="August";
 thename[8]="September";
 thename[9]="October";
 thename[10]="November";
 thename[11]="December";
 var monthHeader = thename[mth];
  return monthHeader.fontcolor("black") ;
}
////////////////////////////////////////////////
function goForward(){ 
  month++;
 if(month>11){
 month=0;
 year++;
  
 }
  generateCalendar(month,year);
}
/////////////////////////////////////////////
function goBack(){
  month--;
 if (month<0){
 month=11;
 year--;
 }
 
  generateCalendar(month,year);
}
/////////////////////////////////////////////
function changeStartOfWeek(num){

	 refNum = num;
	 var weekHTML = "";
	   
	 if(refNum == 0){
	 weekHTML += "<td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td>";
	 }
	 if(refNum == -6){
	 weekHTML += "<td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td><td onclick='changeStartOfWeek(0);'>s</td>";
	 }
	 if(refNum == -5){
	 weekHTML += "<td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td><td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td>";
	 }
	 if(refNum == -4){
	 weekHTML += "<td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td><td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td>";
	 }
	 if(refNum == -3){
	 weekHTML += "<td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td><td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td>";
	 }
	 if(refNum == -2){
	 weekHTML += "<td onclick='changeStartOfWeek(-2);'>f</td><td onclick='changeStartOfWeek(-1);'>s</td><td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td>";
	 }
	 if(refNum == -1){
	 weekHTML += "<td onclick='changeStartOfWeek(-1);'>s</td><td onclick='changeStartOfWeek(0);'>s</td><td onclick='changeStartOfWeek(-6);'>m</td><td onclick='changeStartOfWeek(-5);'>t</td><td onclick='changeStartOfWeek(-4);'>w</td><td onclick='changeStartOfWeek(-3);'>t</td><td onclick='changeStartOfWeek(-2);'>f</td>";
	 }
	week=weekHTML;
	generateCalendar(month,year);
	
}

function helperCalendarCSSLink(path){

  var linkTag = document.createElement('link');
   linkTag.setAttribute('rel','stylesheet');
   linkTag.setAttribute('type','text/css');
   linkTag.setAttribute('href',path);
   document.getElementsByTagName('head')[0].appendChild(linkTag);

}

var DATE_PICKER_ID; // the id of the text field....;-)

function getID(_id){
   DATE_PICKER_ID = _id; //gets the id of the text field....
}
function showCalendar(e){
	 	
	 var thepos = helper.tooltipPos('helper_calendar',e);
	 var thecalendar = document.getElementById('helper_calendar');	
	 thecalendar.style.display = "block";
	 thecalendar.style.left = thepos['x'] + "px";
	 thecalendar.style.top = thepos['y'] + "px";
	 document.getElementById('calpos').innerHTML = "x=" + thepos['x'] +" ,y=" +thepos['y'];
	 ///document.getElementById(DATE_PICKER_ID).disabled = true;	 
	 	
}

function hideCalendar(){
	 	
	var thecalendar = document.getElementById('helper_calendar').style;
	thecalendar.display = "none";
	//document.getElementById(DATE_PICKER_ID).disabled = false;
	
	var now = new Date();
	year = now.getFullYear();
    month = now.getMonth();
    generateCalendar(month,year);   
	 	
}

function putDayValue(_yr,_mth,_date){

   var field = document.getElementById(DATE_PICKER_ID);
   var MySQL_dateformat = _yr + "-" + (_mth + 1) + "-" + _date;
   field.value = MySQL_dateformat;
   hideCalendar();
   
}
function today(){

   var field = document.getElementById(DATE_PICKER_ID);
   var yr = rightnow.getFullYear();
   var mth = rightnow.getMonth() + 1;
   var MySQL_dateformat = yr + "-" + mth + "-" + dateToday;
   field.value = MySQL_dateformat;
   hideCalendar();
   
}
