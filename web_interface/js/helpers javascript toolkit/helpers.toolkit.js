
                                        // helpers.toolkit.js 2.0.1(beta) //



/*******************************************************************************************************************************************
                                               The XMLHttpRequest Object
 
 
 
                                                 Attributes
                                                ------------

* readyState 	         -the code successively changes value from 0 to 4 that means for "ready".
* status 	             -200 is OK 404 if the page is not found.
            
* responseText 	         -holds loaded data as a string of characters.
* responseXml 	         -holds an XML loaded file, DOM's method allows to extract data.
* onreadystatechange 	 -property that takes a function as value that is invoked when the readystatechange 
                          event is dispatched.

                                                   Methods 
                                                  ---------

* open(mode, url, boolean)  	   mode:    type of request, GET or POST
                                   url:     the location of the file, with a path.
                                   boolean: true (asynchronous) / false (synchronous).
                                            optionally, a login and a password may be added to arguments.
                               
* send("string")  	               null for a GET command.   

* abort()                          Cancels the current request.

* getAllResponseHeaders()          Returns the complete set of HTTP headers as a string.

* setRequestHeader(label, value)   Adds a label/value pair to the HTTP header to be sent. [i.e. xmlHttp.setRequestHeader("Content-Type","application/x-ww-form-urlencoded");]

                               
*********************************************************************************************************************************************/
/**
* CONSTRUCTOR OF THE helper OBJECT
*/
function HELP(){
	
  this.COMPLETE = 4; //ready state value...
  this.STAT_OK = 200; // status value...
  this.VERSION = "helpers.toolkit.js 2.0.0(beta)";
  this.AUTHOR = "el rafa gandi";
  this.XHR_IE1 = "Msxml2.XMLHTTP";
  this.XHR_IE2 = "Microsoft.XMLHTTP";
  this.postHeader = "application/x-www-form-urlencoded"; // for POST
  this.me = window.location;
  this.camefrom = document.referrer;
  this.emailRegExp = /^\w{2,}@[A-Za-z0-9]{2,}\.[a-z]{2,}(\.[a-z]{2,})?$/ig;
  this.usernameRegExp = /^[A-Za-z_0-9]{3,}$/ig;
	
}


/**
    getXHR()
    --------
  - THIS FUNCTION INITIALIZES THE XMLHttpRequest OBJECT IN A BROWSER SPECIFIC FASHION
  - FOR AJAX

*/
HELP.prototype.getXHR = function(){

  if(window.XMLHttpRequest){
   
    return new XMLHttpRequest(); 
	 
  }
  else if(window.ActiveXObject){
  	
	  try{
	  
	    return new ActiveXObject("Msxml2.XMLHTTP");
	  
	  }
	  catch(e){
	  
	    return new ActiveXObject("Microsoft.XMLHTTP");
	  
	  }	
  
  }
  else{
  
    window.alert("This browser does not support AJAX!");
    return null;
  
  }

}

////////////////////////////////////////////////////////////////////////////////

/*

    gatherData(id)
    --------------

  - THIS IS THE  gatherData(id) FUNCTION WHICH GATHERS ALL THE VALUES OF A FROM AND CONVERTS THEM INTO A query string NAME 
    VALUE PAIR THAT CAN BE PLACED WITHIN A CALL TO A SERVER SIDE PHP SCRIPT.
  - select-multiple IS NOT SUPPORTED YET IN THIS VERSION   
  - THE FUNCTION TAKES THE id OF THE FORM ELEMENT AS ITS PARAMETER.
  

*/
HELP.prototype.gatherData = function(id){

 var theform = document.getElementById(id);
 var namevaluepair = "";
 var iterateMe = true;
 
 if(theform){
 
     if(theform.length > 0){
     
         /**
         * CAUTION THE 'select-multiple' TYPE IS NOT YET SUPPORTED
         */
     
         for(cnt=0;cnt < theform.length;cnt++){
          
          iterateMe = true;
          
          if((theform.elements[cnt].type == 'checkbox') || (theform.elements[cnt].type == 'radio')){          
            
            if(theform.elements[cnt].checked == true){
                namevaluepair += theform.elements[cnt].name + "=" + theform.elements[cnt].value; 
            }
            else{
                iterateMe = false;
            }               
          
          }
          else{
          
             namevaluepair += theform.elements[cnt].name + "=" + theform.elements[cnt].value;
           
          } 
           if((cnt != (theform.length-1)) && (iterateMe != false)){
           
             namevaluepair += "&";
           
           }
         
         }
     
     }
     else{
     
       namevaluepair = "noprop=true";
     
     }
     
 }
 else{
 
       window.alert("The form has no properties...");
 
 }
 return namevaluepair;
 
}
////////////////////////////////////////////////////////////////////////////////
/**
     getChecked(str formId, str thename)
     -----------------------------------
     
     - CHECKS WHIC OF THE GIVEN RADIO/CHECKBOX IS SET.<b> 
     - PARAMETERS ARE THE FORM ID AND THE NAME OF THE CHECKBOXES/RADIO BUTTONS
     - RETURNS AN ARRAY OF THE VALUES CHECKED OF FALSE IF NOTHING IS CHECKED.
*/
HELP.prototype.getChecked = function(formId,thename){

   var cnt;
   var chkd = 0;
   var returnValArr = new Array();
   var fId = document.getElementById(formId);
   var baseObj = fId.elements[thename];
   
   if(baseObj.length){
      for(cnt=0;cnt<baseObj.length;cnt++){
        if(baseObj[cnt].checked){
           returnValArr[cnt] = baseObj[cnt].value;
           chkd++;
        }
      }
   }
   else{
      if(baseObj.checked){
        returnValArr[0] = baseObj.value;
        chkd++;
      }
      else{
        return false;
      }
   }    
   if(chkd == 0){
      return false;
   }
   else{
      return returnValArr;
   }  
}
////////////////////////////////////////////////////////////////////////////////
/**
     include(str path)
     -----------------
     
     - INCLUDES THE SCRIPT GIVEN TO IT AS PART OF THE CURRENT PAGE
     - PARAMETERS IS THE PATH OF THE JAVASCRIPT FILE. 
     - RETURNS VOID IN THIS VERSION.
*/
HELP.prototype.include = function(path){

   var scriptTag = document.createElement('script');
   scriptTag.setAttribute('language','javascript');
   scriptTag.setAttribute('type','text/javascript');
   scriptTag.setAttribute('src',path);
   //scriptTag.setAttribute('defer','defer');
   document.getElementsByTagName('head')[0].appendChild(scriptTag);
     
}
////////////////////////////////////////////////////////////////////////////////
/*

    nv(n,v) NOT PART OF THE helper OBJECT
    -------

    - THE nv(n,v) FUNCTION MAKES A NAME VALUE PAIR FROM THE PARAMETERS GIVEN TO IT
  
    * n : THE NAME
	* V : THE VALUE
  

*/
function nv(n,v){

 return n + "=" + v;

}
/////////////////////////////////////////////////////////////////////////////////

/*

    c_alert(msg) NOT PART OF THE helper OBJECT
    ------------

  - THIS IS THE c_alert(msg) FUNCTION WHICH IS A CUSTOM ALERT DIALOGUE BOX, THIS ALERT BOX CAN BE COSTOMIZED TO
    FIT THE DESIGN OF YOUR WEBSITE OR WEB APPLICATION. 
  

*/
function c_alert(msg){

  var w = 500;
  var h = 150;
  var x = (screen.width - w)/2;
  var y = (screen.height - h)/2;
  var specs = "width="+w+",height="+h+",left="+x+",top="+y+",location=0,scrollbars=0,modal=1,dependent=1,status=0";
    
     var c_alertWin = window.open("about:blank","c_alertWin",specs);
     
     // --PLACE CSS STYLES HERE :-)-- //
     var CSSspecs = "";
     CSSspecs += "<style>";
     CSSspecs += "body{font: 8pt verdana; background-color: #CCFFFF;}";
     CSSspecs += "table{font: 8pt verdana;}";
     CSSspecs += "button{width: 100px;}";
     CSSspecs += "h1{color: #000000; background-color: yellow; font: bold 1cm verdana; width: 50px;height: 50px; border: thick solid #000000; text-align: center;}";
     CSSspecs += "";
	 CSSspecs += "</style>";
     
     var alertHTML = "";
     alertHTML += "<html><head><title>ALERT!</title>";
     alertHTML += CSSspecs;
     alertHTML += "</head>";
     alertHTML += "<body onblur=\"self.close();\"><center>";
     alertHTML += "<table width=\""+(w-50)+"\" height=\""+(h-50)+"\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";
     alertHTML += "<tr><td><h1>!</h1></td>";
     alertHTML += "<td align=\"left\" valign=\"middle\">"+msg+"</td>";
     alertHTML += "</tr><tr><td colspan=\"2\" valign=\"bottom\" align=\"right\"><button onclick=\"self.close();\">OK</button></td>";
     alertHTML += "</tr></table>";
     alertHTML += "";
     alertHTML += "</center></body></html>";
     
     c_alertWin.document.open();
     c_alertWin.document.write(alertHTML);
     c_alertWin.document.close();     
     c_alertWin.focus();
       

}

////////////////////////////////////////////////////////////////////////////////

/*

    valOf(id)
    ---------

    - THIS FUNCTION RETURNS THE VALUE OF THE FORM ELEMENT WITH THE SPECIFIED id.... 
  

*/
HELP.prototype.valOf = function(id){

  var me = document.getElementById(id).value;
  return me;

}
////////////////////////////////////////////////////////////////////////////////

/*

    nameOf(id)
    ----------

    - THIS FUNCTION RETURNS THE NAME OF THE FORM ELEMENT WITH THE SPECIFIED id.... 
  

*/
HELP.prototype.nameOf = function(id){

  var me = document.getElementById(id).name;
  return me;

}
////////////////////////////////////////////////////////////////////////////////

/*

    isEmpty(str theval)
    -------------------

  - THIS FUNCTION CHECKS IF THE FORM ELEMENT WITH THE SPECIFIED id IS EMPTY OR NOT.
  - TAKES A STRING VALUE AS A PARAMETER
  - RETURNS TRUE IF EMPTY AND FALSE IF NOT.....  
  

*/
HELP.prototype.isEmpty = function(theval){

 //var field = document.getElementById(id).value;
 var field = theval;
 var regEx = /[^ ].{0,}/gi;
 var allow = regEx.test(field);
 
 if(allow){
  return false; 
 }
 else{
  return true; 
 }

}
////////////////////////////////////////////////////////////////////////////////

/*

    chkType(str chkme, str allowed)
    -------------------------------

  - THIS FUNCTION CHECKS IF TYPE EXTENSION IS AVAILABLE IN THE CHOICES THE USER PALCED IN 
    THE allowed PARAMETER.
    
  - RETURNS TRUE IF THE VALUE IS ALLOWED AND FALSE IF NOT.....  
  

*/
HELP.prototype.chkType = function(chkme,allowed){

  var returnval = false;
  var types = allowed.split(",");
  var compare = chkme.split(".");
  var compare_me = compare[compare.length - 1];
  if(compare.length > 0){
  
      if((types.length > 0)){
      
          for(cnt=0;cnt<types.length;cnt++){
          
            if(types[cnt] == compare_me){
    		
    		  returnval = true;
    		  break;
    		
    		}
          
          }
          
      }
      else{
      
       for(n=0;n<compare.length;n++){
       
            if(compare[n] == allowed){
    		
    		  returnval = true;
    		  break;
    		
    		}
       
       }
      
      }
      
  }
  else{
  
    return false;
  
  }

 return returnval;
 
}
////////////////////////////////////////////////////////////////////////////////

/**
    evilSniffer()
    -------------

  - THIS FUNCTION CHECKS THE TYPE OF BROWSER THE USER IS USING ETHER MSIE OR NOT...   
  - RETURNS "MSIE" IS IT IS Internet Explorer AND "NON-MSIE" IF NOT.....  
  
*/
HELP.prototype.evilSniffer = function(){

 var browsertype = navigator.appName;
 if(browsertype == "Netscape"){ 
  return "NON-MSIE"; 
 }
 else if(browsertype == "Microsoft Internet Explorer"){ 
  return "MSIE"; 
 }

}
////////////////////////////////////////////////////////////////////////////////

/**
    regexpMatch(str compareMe, regexp regExp)
    -----------------------------------------

  - THIS FUNCTION EVALUATES A GIVEN STRING VALUE AND REGULAR EXPRESSION IF THEY MATCH..........   
  - RETURNS TRUE IF ITS A MATCH AND FALSE IF IT ISN'T...  
  
*/
HELP.prototype.regexpMatch = function(compareMe,regExp){

  var good = regExp.test(compareMe);
  
  //alert(compareMe);
  //alert(regExp);
  
  if(good === true){
  
   return true;
  
  }
  else{
  
   return false;
  
  }

}
////////////////////////////////////////////////////////////////////////////////

/**
    smlWindow(str loc,int w,int h)
    ------------------------------
  - THIS FUNCTION OPENS A JAVASCRIPT WINDOW WITH CUSTOMIZABLE WIDTH AND HEIGHT.......   
  
*/

HELP.prototype.smlWindow = function(loc,winName,w,h){ 

  var l = (screen.width - w) / 2;
  var t = (screen.height - h) / 2;
  var specs = "width=" + w + ",height="+ h +",left=" + l +",top=" + t + ",scrollbars=1,location=no,resizable=no";
	   
  window.open(loc,winName,specs);

}
////////////////////////////////////////////////////////////////////////////////
/**
     onEnter(event e)
    -----------------

  -  THIS FUNCTION DETERMINES IF THE ENTER KEY WAS PRESSED......
     ^___^      
  
*/
HELP.prototype.onEnter = function(e){

  if((e.which == 13) || (e.which == 3)){   // if not IE... 
    return true; 
  }
  else if((e.keyCode == 13) || (e.keyCode == 3)){ 
    return true;  
  }
  else{ 
    return false; 
  } 

}
////////////////////////////////////////////////////////////////////////////////

/**
     keyAction(event e,int ky,str jsaction)
    -----------------------------------------

  -  THIS FUNCTION DETERMINES WHAT ACTION IS TAKEN IF A CERTAIN KEY IS PRESSED......
  -  RETURNS TRUE ON FAILURE AND FALSE IF SUCCESS.. A BIT WIERD ^___^      
  
*/
HELP.prototype.keyAction = function(e,ky,jsaction){   

  //enter key:(13 or 3)

  if((e.which == ky) || (e.which == ky)){   // if not IE...
  
    eval(jsaction);
    return false;
  
  }
  else if((e.keyCode == ky) || (e.keyCode == ky)){
  
    eval(jsaction);
    return false;
   
  }
  else{
  
    return true;
  
  }

}
////////////////////////////////////////////////////////////////////////////////

/**
     generateKeycode(e)
    -------------------
  -  GENERATES THE KEY CODE IN AN alert() FUNCTION.....      
  
*/

HELP.prototype.generateKeycode = function(e){

 if(e.which){   // if not IE...
  
    window.alert(e.which);
    return false;
  
  }
  else if(e.keyCode){
  
    window.alert(e.keyCode);
    return false;
   
  } 

}
////////////////////////////////////////////////////////////////////////////////

/**
    formPart(str id,str thenames)     [is still very sketchy in this version]
    -----------------------------
  -  RETURNS A QUERY STRING BASED ON THE NAMES GIVEN TO IT.      
  -  I DONT RECOMMEND USING THIS UNLESS UR REALLY SHORT ON TIME...

*/

HELP.prototype.formPart = function(id,thenames){
	
	var query_string = "";
  	var theform = document.getElementById(id).elements;
  	var thenamesArr = thenames.split(",");
  	
  	if(thenamesArr.length > 0){
  		
  		for(var cnt=0; cnt < thenamesArr.length; cnt++){
  			
  			query_string += thenamesArr[cnt] + "=" + theform[thenamesArr[cnt]].value; 
  			if(cnt != (thenamesArr.length - 1)){
  				
  			   query_string += "&";	
  				
  			}
  		}
  		
  	}
  	else{
  		
  		query_string += thenames + "=" + theform[thenames].value; 
  		
  	}
  	
  	return query_string;
	
}
////////////////////////////////////////////////////////////////////////////////

/**
    rollOver(str id, str imgpath)
    -----------------------------
  -  CREATES AN IMAGE ROLLOVER EFFECT.      
  
*/

HELP.prototype.rollOver = function(id,imgpath){
	
	var theimgid = document.getElementById(id);
	theimgid.src = imgpath;
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    noTags(str str)
    ---------------
  -  CHECKS IF THE PASSED STRING HAS TAGS IN IT.
  -  I DONT RECOMMEND BEING TO DEPENDENT ON THIS YOU MUST STILL CHECK IT ON THE SERVER SIDE SCRIPT (php)      
  
*/
HELP.prototype.noTags = function(str){
	
	var tag_re = /^.*<.{1,}>.*$/ig;
	var check = false;
	check = tag_re.test(str);
	
	if(check === true){
		
		return false;  // oh no a suspected tag has been found...
		
	}
	else{
		
	    return true; // all clean proceed....	
	
	}
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    getQSVal(str valname)
    ---------------------
  -  SHOWS THE VALUE OF THE NAME GIVEN TO IT IN THE QUERY STRING
  -  RETURNS THE VALUE IF FOUND AND FALSE IF NOT.       
*/
HELP.prototype.getQSVal = function(valname){
	
	var val = "";
	var if_nothing_found = true;
	var the_query_string = window.location.search.substring(1);
	
	var amperdiv = the_query_string.split("&");
	if(amperdiv.length > 0){
		
		var n;
		for(n=0;n < amperdiv.length;n++){
			
			var equaldiv = amperdiv[n].split("=");
			if(equaldiv[0] == valname){
				
				return equaldiv[1];
				if_nothing_found = false;
				break;
				
			}
			else{
				
			    if_nothing_found = true;	
				
			}
			
		}
		if(if_nothing_found){
			
			return false;
			
		}
		
		
	}
	else{
		
		return false;
		
	}
	
	
}

////////////////////////////////////////////////////////////////////////////////
/**
    xPos(int w)
    ---------------
  -  RETURNS THE CENTER X POSITON RELATIVE TO THE SCREEN
  -  TAKES THE WIDTH OF THE ELEMENT TO CENTER.       
*/
HELP.prototype.xPos = function(w){
	
	var thepos = (screen.width - parseInt(w)) / 2;
	return thepos; 
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    yPos(int h)
    ---------------
  -  RETURNS THE CENTER Y POSITON RELATIVE TO THE SCREEN
  -  TAKES THE HEIGHT OF THE ELEMENT TO CENTER.       
*/
HELP.prototype.yPos = function(h){
	
	var thepos = (screen.height - parseInt(h)) / 2;
	return thepos; 
	
}
////////////////////////////////////////////////////////////////////////////////

/**************************************************************************	
	getTime() - Number of milliseconds since 1/1/1970 @ 12:00 AM 
	getSeconds() - Number of seconds (0-59) 
	getMinutes() - Number of minutes (0-59) 
	getHours() - Number of hours (0-23) 
	getDay() - Day of the week(0-6). 0 = Sunday, ... , 6 = Saturday 
	getDate() - Day of the month (0-31) 
	getMonth() - Number of month (0-11) 
	getFullYear() - The four digit year (1970-9999) 	
****************************************************************************/

////////////////////////////////////////////////////////////////////////////////
/**
    time()
    ------
  -  RETURNS THE CURRENT TIME AS A STRING
  -  IF YOU WANT TO CREATE A MOVING REALTIME CLOCK THIS MUST BE INCORPORATED WITH THE window.setTimeout() METHOD.       
*/
HELP.prototype.time = function(){
	
	var now = new Date();
        
        var sec = now.getSeconds();
        var min = now.getMinutes();
        var hr = now.getHours();
        var timestr = "AM";
        
        if(min < 10){		
		  min = "0" + min;		
		}
		if(sec < 10){		
		  sec = "0" + sec;		
		}
		
		////////////////////////////////////////////////////////////////////////
                
		if(hr >= 12){		
		  timestr = "PM";                      // just a bunch of conditions.... ;-)		
		}
		
		/////////////////////////////////
		if(hr > 12){		
		  hr = hr-12;	
		}
		else if(hr < 10){		
		  hr = "0" + hr;		
		}		
		if(hr == 0){		
		 hr = 12;		
		} 
		         
 	return hr + ":" + min + ":" + sec + " " + timestr;
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    todayIs()
    ---------
  -  RETURNS THE CURRENT DATE AS A STRING<b>
  -  FORMAT [DAYOFWEEK MONTH DATE, YEAR].       
*/
HELP.prototype.todayIs = function(){
	
	 var now = new Date();
     	 		
 		var day = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
 		var month = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec");
        var dayWeek = now.getDay();
        var date = now.getDate();
        var curMonth = now.getMonth();
        var year = now.getFullYear();        
        var complete_day_info = day[dayWeek] + " " + month[curMonth] + " " + date + ", " + year;
        
     return complete_day_info;   
	
}
/////////////////////////////////////////////////////////////////////////////////
/**
    isDigit(str check)
    ---------
  -  CHECKS IF THE STRING VALUE PASSED TO IT IS A NUMERIC DIGIT
  -  RETURNS TRUE IF DIGIT AND FALSE IF NOT       
*/
HELP.prototype.isDigit = function(check){
	
	var digitRegExp = /^[0-9]{1,}$/ig;
	var isdigit = this.regexpMatch(check,digitRegExp);
	
	if(isdigit == true){
		
		return true;
		
	}
	else{
		
		return false;
		
	}
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    isAlpha(str check)
    ---------
  -  CHECKS IF THE STRING VALUE PASSED TO IT IS ALPHABETIC
  -  RETURNS TRUE IF ALPHABETIC AND FALSE IF NOT       
*/
HELP.prototype.isAlpha = function(check){
	
	var alphaRegExp = /^[A-Za-z]{1,}$/ig;
	var isalpha = this.regexpMatch(check,alphaRegExp);
	
	if(isalpha == true){
		
		return true;
		
	}
	else{
		
		return false;
		
	}
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    isAlnum(str check)
    ---------
  -  CHECKS IF THE STRING VALUE PASSED TO IT IS ALPHANUMERIC
  -  RETURNS TRUE IF ALPHANUMERIC AND FALSE IF NOT       
*/
HELP.prototype.isAlnum = function(check){
	
	var alnumRegExp = /^[A-Za-z0-9]{1,}$/ig;
	var isalnum = this.regexpMatch(check,alnumRegExp);
	
	if(isalnum == true){
		
		return true;
		
	}
	else{
		
		return false;
		
	}
	
}

////////////////////////////////////////////////////////////////////////////////
/**
    createDIV(array ids, array classes)
    ----------------------------------
  -  TAKES AN ARRAY OF DIV IDS AND CSS CLASS NAMES FOR THE DIVS       
*/
HELP.prototype.createDIV = function(ids,classes){

  var cnt;
  var div; 	
	
  if((ids.length != 0) && (classes.length != 0)){
  	
  	for(cnt=0;cnt < ids.length; cnt++){
  		
  	  div = document.createElement('div');
  	  div.setAttribute('id',ids[cnt]);
  	  div.className = classes[cnt];
  	  
  	  /*div.style.width = "200px";
  	  div.style.height = "300px";
  	  div.style.backgroundColor = "red";
  	  div.style.borderWidth = "thick";
  	  div.style.borderColor = "black";
  	  div.innerHTML = div.id;*/
  	  	
  	  document.body.appendChild(div); 	
  	  
  	}
  	
  }
  else{
  	
  	window.alert("the createDIV() method need to have 2 array parameters...which represents the id and the css class (BOTH MUST HAVE THE SAME LENGTH) of the divs");
  	
  }	
	
}
////////////////////////////////////////////////////////////////////////////////
/**
    tooltipPos(str id, event e)
    ---------------------------
  -  RETURNS THE X AND Y POSITION OF THE MOUSE POINTER RELATIVE TO THE CLIENT SCREEN      
*/
HELP.prototype.tooltipPos = function(id,e){
	
	var theid = document.getElementById(id);
	var pos = new Array();
	var posx = 0;
	var posy = 0;
	
	if((e.clientX) && (e.clientY)){
		
		 /*********** tooltip positions ************/
		 
		 if(this.evilSniffer() == 'MSIE'){	// IE is sometimes wierd with the positions...so be careful ;-) 
		    if(document.documentElement){
			    posx = (event.clientX + document.documentElement.scrollLeft);
			    posy = (event.clientY + document.documentElement.scrollTop);
		    }
		    else{
			    posx = (event.clientX + document.body.scrollLeft);
			    posy = (event.clientY + document.body.scrollTop);		    
		    } 
		 }
		 else if(this.evilSniffer() == 'NON-MSIE'){
		    posx = e.pageX;
		    posy = e.pageY; 
		 } 
	 
	}
	else{
		
		 posx = -1;
	     posy = -1;
		
	}
	
	pos['x'] = posx;
	pos['y'] = posy;
	
	return pos;
}
////////////////////////////////////////////////////////////////////////////////
/**
    setOpacity(str id, int opa)
    ---------------------------
  -  TAKES THE ID OF THE ELEMENT TO BE FADED AN THE INTENSITY OF THE OPACITY [0-10]
  -  FADES A CERTAIN ELEMENT      
*/
HELP.prototype.setOpacity = function(id,opa){
	
	var theid = document.getElementById(id);
	
	theid.style.opacity = parseInt(opa) / 10;
	theid.style.filter = 'alpha(opacity=' + parseInt(opa) * 10 + ')';
	
}

////////////////////////////////////////////////////////////////////////////////
/**
    addCover()
    ----------
  -  ADDS A BLACK SILOWET(forgive the spelling ;-)) COVER
   
*/
HELP.prototype.addCover = function(){
	
	if(document.getElementById('COVER') == null){
		var coverdiv = document.createElement('div');
		coverdiv.setAttribute('id','COVER');
		document.body.appendChild(coverdiv);
	}
    this.setOpacity('COVER',8);
    
	var coverId = document.getElementById('COVER');
	coverId.style.display = "block";
	coverId.style.width = screen.width + "px";
	coverId.style.height = (screen.height + 5000) + "px";
	//coverId.style.width = "100px";
	//coverId.style.height = "100px";
	coverId.style.position = "absolute";
	coverId.style.left = "0px";
	coverId.style.top = "0px";
	coverId.style.zIndex = "1";
	coverId.style.backgroundColor = "#000000";
	
}

////////////////////////////////////////////////////////////////////////////////
/**
    removeCover()
    ----------
  -  REMOVES THE BLACK SILOWET(forgive the spelling ;-)) COVER
   
*/
HELP.prototype.removeCover = function(){

  if(document.getElementById('COVER')!= null){
      var covId = document.getElementById('COVER').style;	  
	  covId.display = 'none';
	  covId.position = 'absolute';
	  covId.left = '-100px';
	  covId.top = '-100px';
	  covId.width = '5px';
	  covId.height = '5px';
  }

}

////////////////////////////////////////////////////////////////////////////////
/**
    changeBGColor(str theId, str thecolor)
    --------------------------------------
  -  CHANGES THE BACKGROUND OF A CERTAIN ELEMENT
  -  TAKES THE ID OF THE ELEMENT AND THE COLOR OF THE DESIRED BACKGROUND
   
*/
HELP.prototype.changeBGColor = function(theid,thecolor){

  var elementId_and_style = document.getElementById(theid).style;
  elementId_and_style.backgroundColor = thecolor;

}
/////////////////////////////////////////////////////////////////////////////////

/**
    fader(str theid,str shockColor,str origColor)
    ----------------------------------------------
  -  MAKES A FADING BACKGROUND EFFECT ON A CERTAIN ELELMENT.
  -  TAKES THE ID OF THE ELEMENT, THE FADING COLOR OF THE ELEMENT, AND THE ELEMENTS ORIGINAL BACKGROUND 

*/
var faderCounter = 10;
HELP.prototype.fader = function(theid,shockColor,origColor){

  var id = document.getElementById(theid);
  var caller = "helper.fader('"+theid+"','"+shockColor+"','"+origColor+"')";
  if(faderCounter == 1){
       window.clearTimeout(callme);
       this.setOpacity(theid,10);
       faderCounter = 10;
       id.style.backgroundColor = origColor;
  }
  else{
       id.style.backgroundColor = shockColor;
       this.setOpacity(theid,faderCounter);
       faderCounter--;
       var callme = window.setTimeout(caller,150);
  }
  
}
////////////////////////////////////////////////////////////////////////////////

/**
    helperCalendar(array _ids)
    ----------------
  -  CALLED WHEN YOU MAKE USE OF THE HELPER CALENDAR DATE PICKER MODULE.
  -  TAKES AN ARRAY OF IDS FROM A DIV TAG, WHICH IS THE CONTAINER OF THE TEXT FIELD/S....
  -  MUST BE CALLED ON THE DOCUMENTS ONLOAD EVENT. 

*/
HELP.prototype.datePickerConfig = function(_ids){
    //<div id="your id that will also be the basis for the name of the text filed created">--- helper date text field goes here ---</div>//
    
    generateCalendar(month,year);
    var dateTextField;
    if(_ids.length){
    	for(var c=0; c<_ids.length;c++){
    	  dateTextField = document.getElementById(_ids[c]);
	      dateTextField.innerHTML = "<input type=\"text\" name=\""+_ids[c]+"\" id=\""+_ids[c]+"_picker\" onkeyup=\"this.value = '';\"/><img src=\"helper_calendar_icon.png\" id=\"helper_calendar_button\" alt='date picker' onclick=\"showCalendar(event);getID('"+_ids[c]+"_picker');\" style='width: 30px; height: 20px;'/>";   	   
    	}
    }
    else{
	    dateTextField = document.getElementById(_ids[0]);
	    dateTextField.innerHTML = "<input type=\"text\" name=\""+_ids[0]+"\" id=\""+_ids[0]+"_picker\" onkeyup=\"this.value = '';\"/><img src=\"helper_calendar_icon.png\" id=\"helper_calendar_button\" alt='date picker' onclick=\"showCalendar(event);getID('"+_ids[0]+"_picker');\" style='width: 30px; height: 20px;'/>";
    }
    
}
////////////////////////////////////////////////////////////////////////////////

/**
   sure(str msg)
  ----------------
  -  A MORE ELEGANT CONFIRM DIALOG BOX ;-)..... 

*/
HELP.prototype.sure = function(msg){

     var returnMe = false;
     this.addCover();
     var ans = window.confirm(msg);
     if(ans === true){
       returnMe = true
     }
     this.removeCover();
     return returnMe;

}
////////////////////////////////////////////////////////////////////////////////
/**
    inArray(mixed _needle,array _heystack)
    --------------------------------------
  -  FINDS A SPECIFIED ELEMENT INSIDE AN ARRAY
  -  MUCH LIKE PHP'S in_array() FUNCTION.
  -  RETURN TRUE IF THE ELEMENT WAS FOUND AND FALSE IF NOT. 

*/
HELP.prototype.inArray = function(_needle,_heystack){   
   var returnVal = false;
   var cnt;
   for(cnt = 0;cnt<_heystack.length;cnt++){
      if(_needle === _heystack[cnt]){
        returnVal = true;
        break;
      }   
   }
   return returnVal;
}
////////////////////////////////////////////////////////////////////////////////
var helper = new HELP();
/*******************************************
 * Author: eL rafA gAndi                   *
 * Email:  rafaelgandi@yahoo.com           *
 * IDE:    Eclipse PDT 1.0.0               *
 *******************************************/
