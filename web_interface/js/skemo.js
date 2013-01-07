/**
 * dependencies:
 *  -mootools1.2.js
 *  -helpers.toolkit.js
 *  -json2.js
 * 
 */
var SKEMO = {

    GLOBALS:{
    
        /* place the global variables here....[^_______^] */
        
        loading:"<img src='util/loader.gif' alt='' />Loading...",
        saving:"<img src='util/loader.gif' alt='' />Saving Preferences..."
    
    },
    
    GLINKS:{
       
       /* place the application links here  ;-)*/
       
       ajaxSessionRequest:"ajaxengines/ajaxSessionRequest.ajax.php",
       loader:'util/loader.gif',
       saveStudentPreferencesPage:"ajaxengines/studentPrefHandler.ajax.php",
       browsePage:'skemoBrowse.php',
       printablePage:'util/printableSched.php',
       skemoFavicon:'images/favicon_skemo.ico',
       logout: 'logout.php'
       
    },
    
    applicationName:"Skemo: The Subject Schedule Recommender",
    authors:["Erra Marjorie Pacaldo","Edmundo Balili Jr.","Rafael Gandionco"],
    clock:function(_id){
    	window.setInterval(function(){
    		$(_id).innerHTML = '&nbsp;' + helper.time();
    	},1000);
    },
    
    browserSniffer:function(){
    	var browserWarning = "WARNING:\n\n";
    	browserWarning += "SKEMO requires Firefox 3+, some components of this application might not work properly in this browser.\n";
    	browserWarning += "You can download the latest version of Firefox at http://www.mozilla.com/en-US/firefox. ";
    	browserWarning += "Sorry for the inconvinience.\n\n\n";
    	browserWarning += "The Skemo Team";
    	
    	var browserType = navigator.userAgent;
    	if(browserType.indexOf("Firefox/3") === -1){
    		window.alert(browserWarning);
    		window.location = 'http://www.mozilla.com/en-US/firefox/';
    	}
    },
    
    loadingScreen:function(_options){
    	/**
    	 * to be able to use this method, the
    	 * following files should be present in the document:
    	 *  - util/loadingScreen.inc.php
    	 *  - util/loadingScreeen.css
    	 */
    	 if(($('loading_cover') instanceof Object) && ($('loading_box') instanceof Object)) {
    	 	// the condition below is to check if a parameter exists and should be an object //
    	 	if(_options instanceof Object) {
    	 		if(typeof(_options.src) !== 'undefined') {
    	 			$('loading_img').src = _options.src;
    	 		}
    	 		if(typeof(_options.msg) !== 'undefined') {
    	 			$('loading_msg').innerHTML = _options.msg;
    	 		}
    	 	}
	    	 var cStyle = $('loading_cover').style;
	    	 var mStyle = $('loading_box').style;
	    	 var ml = (screen.width - 200) / 2;
	    	 var mt = (screen.height - 200) / 2;
	    	 
	    	 window.scroll(0,0); // scroll 2 the top to see the loading message and img
	    	 cStyle.width = screen.width + "px";
	    	 cStyle.height = (screen.height + 900) + "px";
	    	 cStyle.top = "0px";
	    	 cStyle.left = "0px";
	    	 cStyle.display = "block";
	    	 
	    	 mStyle.left = ml + "px";
	    	 mStyle.top = mt + "px";
	    	 mStyle.display = "block";
    	 }
    	 else {
    	 	throw "[ loading_cover div and loading_box div not found! The file 'util/loadingScreen.inc.php' was not included!!!!!! ]";
    	 }   	 
    },
    
    DIALOG:{
    	/**
    	 * This method opens a skemo dialog box...
    	 * @param object _options
    	 */
    	open:function (_options){
    		
    		/**
    		 * options properties:
    		 * 
    		 * label  : [string]
    		 * body   : [string]
    		 * width  : [integer]
    		 * height : [integer]
    		 * 
    		 */
    	
	    	if (_options instanceof Object) {
	    		if ($('dialog_container') instanceof Object) {
	    			var dContainer = $('dialog_container');
	    			var cStyle = dContainer.style;
	    			var w;
	    			var h;
	    			
	    			var dLabel = $('dialog_label');
	    			var lStyle = dLabel.style;
	    			
	    			var dBody = $('dialog_content');
	    			var bStyle = dBody.style;
	    			
	    			
	    			if (typeof(_options.label) !== 'undefined') {
	    				dLabel.innerHTML = _options.label;
	    			}
	    			if (typeof(_options.body) !== 'undefined') {
	    				dBody.innerHTML = _options.body;
	    			}
	    			
	    			// check the width and height, if not set give them their proper default value //
	    			if (typeof(_options.width) !== 'undefined') {
	    				if (_options.width >= 400) {
	    					cStyle.width = _options.width + "px";
	    					w = _options.width;
	    				}
	    			}
	    			else {
	    				w = 400;
	    			}
	    			if (typeof(_options.height) !== 'undefined') {
	    				cStyle.height = _options.height + "px";
	    				h = _options.height;
	    			}
	    			else {
	    				h = 300;
	    			}
	    			/////////////////////////////////////////////////
	    			
	    			window.scroll(0,0); // bring to top...
	    			
	    			// show the dialog box and makes it draggable //	    			
	    			cStyle.display = "block";
	    			cStyle.top = helper.yPos(h) + "px";
	    			cStyle.left = helper.xPos(w) + "px";
	    			dContainer.makeDraggable({
	    				container: window
	    			});
	    		}
	    		else {
	    			throw "( dialog box markup missing [util/dialog.inc.php] )";
	    		}
	    	}
	    	else {
	    		throw "( SKEMO.DIALOG.open() method missing object parameters )";
	    	}
    	},
    	/**
    	 * This method closes the skemo dialog box...
    	 */
    	close:function (){
    		var dContainer = $('dialog_container');
	    	var cStyle = dContainer.style;
	    	
    		cStyle.display = "none";
	    	cStyle.top = "-1000px";
	    	cStyle.left = "-1000px";
    	}
    },
    
    activityListener: function () {
    	var that = this;
    	window.setTimeout(function () {
    		window.location = that.GLINKS.logout;
    	},1800000); // 30 mins sleep
    },
    
    init: function() {
    	
    	document.body.className = 'has_js'; // used to check if js is inabled..
    	this.browserSniffer(); //check the browser...allow only FF3+
    	this.clock('time');
    	/**
    	 * preload loader image...
    	 */
    	var loaderImg = new Image();
    	loaderImg.src = this.GLINKS.loader;
    	this.activityListener();
    }
       
};
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var test = function(_mod,_divId) {
	var theid = document.getElementById(_divId);
	var dis = theid.style;
	if (_mod === 'a') {
		dis.display = 'block';
	}
	else if (_mod === 'b') {
		dis.display = 'none';
	}
};
var popBrowsePage = function() {
	var w = 900;
	var h = 575;
	
	var chrome = 'width='+w+',height='+h+',scrollbars=1,resizeable=0';
	window.open(SKEMO.GLINKS.browsePage,'browse',chrome);
};