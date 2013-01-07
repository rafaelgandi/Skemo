/**
dependencies: 
 * mootools1.2.js
 * json2.js
 * helpers.toolkit.js
 * skemo.js
 * fetchSEssionForJs.js.php
used in:
 * mySkemo.php 

THIS IS THE UNIQUE JAVASCRIPT FILE USED IN THE myskemo.php

* BATTLE TESTED ON JSLINT!!!! http://jslint.com/
*/
//////////////////////////////////////////////////////////////////////////// ;-)
var MySkemo = {
	
	prefButtonId:'savePref',
	
	mwfSTime: '_mwfSTime',
	mwfETime: '_mwfETime',
	mwfLocation: '_mwfLocation',
	mwfSVacant: '_mwfSVacant',
	mwfEVacant: '_mwfEVacant',
	mwfNoClass: 'no_class_mwf',
		    
	tthSTime: '_tthSTime',
	tthETime: '_tthETime',
	tthLocation: '_tthLocation',
	tthSVacant: '_tthSVacant',
	tthEVacant: '_tthEVacant',
	tthNoClass: 'no_class_tth',
	
	errorChecker:{
		mwfSETime:true,
		tthSETime:true,
		mwfSEVacant:true,
		tthSEVacant:true
	},
	
	init:function(){
		
	},
	
	initFx:function(){
				
		$(this.prefButtonId).disabled = false;
		$('error_msg').slide('hide');
    	
	},
	
	timeEquivValue:function(_strTime){ 
		var equivIntValue = 0;
		switch(_strTime){
			case '00:01am':
				equivIntValue = 0;
			break;
			case '07:00am':
				equivIntValue = 1;
			break;
			case '07:30am':
				equivIntValue = 1.5;
			break;
			case '08:00am':
				equivIntValue = 2;
			break;
			case '08:30am':
				equivIntValue = 2.5;
			break;
			case '09:00am':
				equivIntValue = 3;
			break;
			case '09:30am':
				equivIntValue = 3.5;
			break;
			case '10:00am':
				equivIntValue = 4;
			break;
			case '10:30am':
				equivIntValue = 4.5;
			break;
			case '11:00am':
				equivIntValue = 5;
			break;
			case '11:30am':
				equivIntValue = 5.5;
			break;
			case '12:00am':
				equivIntValue = 6;
			break;
			case '12:00pm':
				equivIntValue = 6;
			break;
			case '12:30am':
				equivIntValue = 6.5;
			break;
			case '12:30pm':
				equivIntValue = 6.5;
			break;
			case '01:00am':
				equivIntValue = 7;
			break;
			case '01:00pm':
				equivIntValue = 7;
			break;
			case '13:00am':
				equivIntValue = 7;
			break;
			case '13:00pm':
				equivIntValue = 7;
			break;
			case '01:30pm':
				equivIntValue = 7.5;
			break;
			case '13:30am':
				equivIntValue = 7.5;
			break;
			case '13:30pm':
				equivIntValue = 7;
			break;
			case '02:00pm':
				equivIntValue = 8;
			break;
			case '14:00am':
				equivIntValue = 8;
			break;
			case '14:00pm':
				equivIntValue = 8;
			break;
			case '02:30pm':
				equivIntValue = 8.5;
			break;
			case '14:30am':
				equivIntValue = 8.5;
			break;
			case '14:30pm':
				equivIntValue = 8.5;
			break;
			case '03:00pm':
				equivIntValue = 9;
			break;
			case '15:00am':
				equivIntValue = 9;
			break;
			case '15:00pm':
				equivIntValue = 9;
			break;
			case '03:30pm':
				equivIntValue = 9.5;
			break;
			case '15:30am':
				equivIntValue = 9.5;
			break;
			case '15:30pm':
				equivIntValue = 9.5;
			break;
			case '04:00pm':
				equivIntValue = 10;
			break;
			case '04:30pm':
				equivIntValue = 10.5;
			break;
			case '05:00pm':
				equivIntValue = 11;
			break;
			case '05:30pm':
				equivIntValue = 11.5;
			break;
			case '06:00pm':
				equivIntValue = 12;
			break;
			case '06:30pm':
				equivIntValue = 12.5;
			break;
			case '07:00pm':
				equivIntValue = 13;
			break;
			case '07:30pm':
				equivIntValue = 13.5;
			break;
			case '08:00pm':
				equivIntValue = 14;
			break;
			case '08:30pm':
				equivIntValue = 14.5;
			break;
			case '09:00pm':
				equivIntValue = 15;
			break;
			case '00:06am':
				equivIntValue = 0;
			break;
			default:
				equivIntValue = 0;	
		}
		return equivIntValue;
	},
	
	valueEquivTime:function(_intTimeValue){
		equivStrTime = '';
		switch(_intTimeValue){
			case 0:
				equivStrTime = '00:01am';
			break;
			case 1:
				equivStrTime = '07:00am';
			break;
			case 1.5:
				equivStrTime = '07:30am';
			break;
			case 2:
				equivStrTime = '08:00am';
			break;
			case 2.5:
				equivStrTime = '08:30am';
			break;
			case 3:
				equivStrTime = '09:00am';
			break;
			case 3.5:
				equivStrTime = '09:30am';
			break;
			case 4:
				equivStrTime = '10:00am';
			break;
			case 4.5:
				equivStrTime = '10:30am';
			break;
			case 5:
				equivStrTime = '11:00am';
			break;
			case 5.5:
				equivStrTime = '11:30am';
			break;
			case 6:
				equivStrTime = '12:00pm';
			break;
			case 6.5:
				equivStrTime = '12:30pm';
			break;
			case 7:
				equivStrTime = '01:00pm';
			break;
			case 7.5:
				equivStrTime = '01:30pm';
			break;
			case 8:
				equivStrTime = '02:00pm';
			break;
			case 8.5:
				equivStrTime = '02:30pm';
			break;
			case 9:
				equivStrTime = '03:00pm';
			break;
			case 9.5:
				equivStrTime = '03:30pm';
			break;
			case 10:
				equivStrTime = '04:00pm';
			break;
			case 10.5:
				equivStrTime = '04:30pm';
			break;
			case 11:
				equivStrTime = '05:00pm';
			break;
			case 11.5:
				equivStrTime = '05:30pm';
			break;
			case 12:
				equivStrTime = '06:00pm';
			break;
			case 12.5:
				equivStrTime = '06:30pm';
			break;
			case 13:
				equivStrTime = '07:00pm';
			break;
			case 13.5:
				equivStrTime = '07:30pm';
			break;
			case 14:
				equivStrTime = '08:00pm';
			break;
			case 14.5:
				equivStrTime = '08:30pm';
			break;
			case 15:
				equivStrTime = '09:00pm';
			break;
			default:
				equivStrTime = '00:01am';	
		}
		if(_intTimeValue < 0){
			equivStrTime = '00:01am';
		}
		
		return equivStrTime;
	},
	
	// -- to be sent as JSON data -- //
	pref: {
		"mwfSTime": null,
		"mwfETime": null,
		"mwfLocation": null,
		"mwfSVacant": null,
		"mwfEVacant": null,
		"mwfClass": "CLASS",
		    
		"tthSTime": null,
		"tthETime": null,
		"tthLocation": null,
		"tthSVacant": null,
		"tthEVacant": null,
		"tthClass": "CLASS"
	},
	
	gatherPreferences:function(){
		
	     	this.pref.mwfSTime = $('_mwfSTime').value;
	     	this.pref.mwfETime = $('_mwfETime').value;
	     	this.pref.mwfLocation = $('_mwfLocation').value;
	     	this.pref.mwfSVacant = parseFloat($('_mwfSVacant').value);
	     	this.pref.mwfEVacant = parseFloat($('_mwfEVacant').value);
	     	
	     	this.pref.tthSTime = $('_tthSTime').value;
	     	this.pref.tthETime = $('_tthETime').value;
	     	this.pref.tthLocation = $('_tthLocation').value;
	     	this.pref.tthSVacant = parseFloat($('_tthSVacant').value);
	     	this.pref.tthEVacant = parseFloat($('_tthEVacant').value);

	    return JSON.stringify(this.pref);
	    
	},
	
	showError: function (_mode) {
		var e = $('error_message_below');
		if (_mode === 'show') {
			e.innerHTML = 'Oops! something is wrong with your preferences.';
		}
		else if (_mode === 'hide') {
			e.innerHTML = '';
		}
	},
	
	fadingInMsg:function(_id,_msg){
		
		$(_id).fade('out'); //mo error gamay...
    	$(_id).innerHTML = _msg;
    	$(_id).fade('in');
    	window.setTimeout(function(){
    		if (document.getElementById(_id)) {
    			$(_id).fade('out');
    		}
    	},10000); //do 10 sec delay bfore faDing the msg.....
    	
	},
	
	checkRange:function(_day,_mode,_vacant){
		var returnValue = true;
		var optionsHTML = [];
		
		var _st;
		var _et;
		
		var s;
		var e;
		
		var sTime_v;
		var eTime_v;
		
		var INVALID_COLOR = '#FFBFBF';
		var VALID_COLOR = '#FFFFFF';
		
		if(typeof(_vacant) === 'undefined'){
			_vacant = false;
		}
		if(_vacant === false){			
			if(_day === 'MWF'){
				_st = $(this.mwfSTime).value;
			    _et = $(this.mwfETime).value;			    
			    s = this.timeEquivValue(_st);
				e = this.timeEquivValue(_et);
			
			    if((_mode === 'START') || (_mode === 'END')){ // --- MWF START/END TIME CHANGE --- //					
					if ((s >= e)) {	
						//this.errorMsg('show');
						returnValue = false;
					}
				}
				
			}
			else if(_day === 'TTH'){
				_st = $(this.tthSTime).value;
				_et = $(this.tthETime).value;				
				s = this.timeEquivValue(_st);
				e = this.timeEquivValue(_et);
				
				if((_mode === 'START') || (_mode === 'END')){ // --- TTH START/END TIME CHANGE --- //
					if (s >= e) {
						//this.errorMsg('show');
						returnValue = false;
					}
				}
			}													
		}
		/**
		 * THIS IS FOR THE VACANT SELECTS IN THE PAGE........
		 */
		else{
			if(_day === 'MWF'){
				s = parseInt($(this.mwfSVacant).value,10);
				e = parseInt($(this.mwfEVacant).value,10);
				
				if((_mode === 'START') || (_mode === 'END')){ // --- MWF VACANT END TIME CHANGE --- //
					if (s >= e) {
						if (!((s === 0.0) && (e === 0.0))){ // for the 'No Thanks' option
							//this.errorMsg('show');
							returnValue = false;
						}
					}
				}				
			}
			else if(_day === 'TTH'){
				s = parseFloat($(this.tthSVacant).value);
				e = parseFloat($(this.tthEVacant).value);
				
				if((_mode === 'START') || (_mode === 'END')){ // --- TTH VACANT END TIME CHANGE --- //
					if (s >= e) {
						if (!((s === 0.0) && (e === 0.0))){ // for the 'No Thanks' option
							//this.errorMsg('show');
							returnValue = false;
						}
					}
				}
			}
			
		}
		return returnValue;
	},
	
	resolveVacantTimeRange: function (_day) {
		
		var st;
		var et;
		var sessionVacantStartTime;
		var sessionVacantEndTime;
		
		var optHTML = [];
		
		var sVacant;
		var eVacant;
		
		if (_day === 'mwf') {
			st = this.timeEquivValue($(this.mwfSTime).value);
			et = this.timeEquivValue($(this.mwfETime).value);
			
			sVacant = $(this.mwfSVacant);
			eVacant = $(this.mwfEVacant);
			
			sessionVacantStartTime = SESSION.preferences.mwf.sv;
			sessionVacantEndTime = SESSION.preferences.mwf.ev;
		}
		else if (_day === 'tth') {
			st = this.timeEquivValue($(this.tthSTime).value);
			et = this.timeEquivValue($(this.tthETime).value);
			
			sVacant = $(this.tthSVacant);
			eVacant = $(this.tthEVacant);
			
			sessionVacantStartTime = SESSION.preferences.tth.sv;
			sessionVacantEndTime = SESSION.preferences.tth.ev;
		}
		
		// inner utility function //
		function dayBasedIterator(_cnt) {
			if (_day === 'mwf') {
				return (_cnt + 1);
			}
			else if (_day === 'tth') {
				return (_cnt + 1.5);
			}
		}
		 		 
		// start tym vacant //
		cnt = st; 
		optHTML.push("<option value='0.0'>No Thanks</option>");
		while (cnt <= et) {
			if (cnt === sessionVacantStartTime) {
				optHTML.push("<option value='" + cnt + "' selected='selected'>" + this.valueEquivTime(cnt) + "</option>");
			}
			else {
				optHTML.push("<option value='" + cnt + "'>" + this.valueEquivTime(cnt) + "</option>");
			}
			cnt = dayBasedIterator(cnt);
		}		
		sVacant.innerHTML = optHTML.join('');
								
		// end tym vacant //
		cnt = st; 
		optHTML = [];
		optHTML.push("<option value='0.0'>No Thanks</option>");		
		while (cnt <= et) {
			if (cnt === sessionVacantEndTime) {
				optHTML.push("<option value='" + cnt + "' selected='selected'>" + this.valueEquivTime(cnt) + "</option>");
			}
			else {
				optHTML.push("<option value='" + cnt + "'>" + this.valueEquivTime(cnt) + "</option>");
			}
			cnt = dayBasedIterator(cnt);
		}
		eVacant.innerHTML = optHTML.join('');
				
	},
	
	errorMsg: function (_mode) {
		var s = new Fx.Slide('error_msg',{
			transition: 'bounce:out'
		});
		if (_mode === 'show') {
			s.slideIn();
		}
		else {
			s.slideOut();
		}
	},
	
	checkAllValid:function(){
		var o = this.errorChecker;
		$(this.prefButtonId).disabled = true;
		if((o.mwfSETime === true) && (o.tthSETime === true) && (o.mwfSEVacant === true) && (o.tthSEVacant === true)){
			$(this.prefButtonId).disabled = false;
			//this.errorMsg('hide');
			this.showError('hide');			
		}
		else{
			$(this.prefButtonId).disabled = true;
			//this.errorMsg('show');
			this.showError('show');
		}
	},
	
	// -- new 02-27-09 -- //
	/**
	 * does the enableing and disableling of the select boxes
	 */
	ableAll: function (_day,_mode) {
		if (_day === 'mwf') {
			if (_mode === 'disable') {
				_mode = true;
			}
			else if (_mode === 'enable') {
				_mode = false;
			}
			$(this.mwfSTime).disabled = _mode;
			$(this.mwfETime).disabled = _mode;
			$(this.mwfSVacant).disabled = _mode;
			$(this.mwfEVacant).disabled = _mode;
			$(this.mwfLocation).disabled = _mode;
		}
		else if (_day === 'tth') {
			if (_mode === 'disable') {
				_mode = true;
			}
			else if (_mode === 'enable') {
				_mode = false;
			}
			$(this.tthSTime).disabled = _mode;
			$(this.tthETime).disabled = _mode;
			$(this.tthSVacant).disabled = _mode;
			$(this.tthEVacant).disabled = _mode;
			$(this.tthLocation).disabled = _mode;
		} 
	},
	
	doNoClassLogic: function (_day, _mode) {
		if (_day === 'mwf' && _mode === 'disable') {			
			this.pref.mwfClass = 'NOCLASS';
		}
		else if (_day === 'tth' && _mode === 'disable') {			
			this.pref.tthClass = 'NOCLASS';
		}
	},
	
	checkSessionIfDisableClass: function () {
		if (SESSION.preferences.mwf.mwfNoClass === 'NOCLASS') {
			this.ableAll('mwf','disable');
			this.doNoClassLogic('mwf','disable');
			$(this.mwfNoClass).checked = true;
		}
		if (SESSION.preferences.tth.tthNoClass === 'NOCLASS') {
			this.ableAll('tth','disable');
			this.doNoClassLogic('tth','disable');
			$(this.tthNoClass).checked = true;
		}	
	},
	
	initEvents:function(){
		
		var vacancyMsg = "<img src='" + SKEMO.GLINKS.skemoFavicon + "' alt='' />Setting a mandatory vacant time is totally optional, SKEMO generates vacancies that coencide with your schedule either way ^_^.";
		var that = this; // reference to the parent object...MySkemo
		
		// -- NO CLASS MWF/TTH EVENT (checkbox) -- //
		
		this.checkSessionIfDisableClass(); // for initial checking if mwf or tth selectbox's would be enabled or disabled...
		
		$(this.mwfNoClass).addEvent('change', function () {
			if (!this.checked) {
				that.ableAll('mwf','enable');
				that.pref.mwfClass = 'CLASS';
			}
			else {
				that.ableAll('mwf','disable');
				that.doNoClassLogic('mwf','disable');
			}
		});
		
		$(this.tthNoClass).addEvent('change', function () {
			if (!this.checked) {
				that.ableAll('tth','enable');
				that.pref.tthClass = 'CLASS';
			}
			else {
				that.ableAll('tth','disable');
				that.doNoClassLogic('tth','disable');
			}
		});

		// START TIME AND END TIME SELECT EVENTS //
		$(this.mwfSTime).set({
			'events':{
				'change':function(){
					if(!that.checkRange('MWF','START')){
						that.errorChecker.mwfSETime = false;
					}
					else{
						that.errorChecker.mwfSETime = true;
					}
					$(that.mwfSTime).highlight();
					that.checkAllValid();
					
					that.resolveVacantTimeRange('mwf');
				},
				'focus': function () {
					
				},
				'blur':function(){
					
				}				
			}
		});
		$(this.mwfETime).set({
			'events':{
				'change':function(){
					if(!that.checkRange('MWF','END')){
						that.errorChecker.mwfSETime = false;
					}
					else{
						that.errorChecker.mwfSETime = true;
					}
					$(that.mwfETime).highlight();
					that.checkAllValid();
					
					that.resolveVacantTimeRange('mwf');
				},
				'focus': function () {
					
				},
				'blur':function(){
					
				}
			}
		});
		$(this.mwfLocation).set({
			'events':{
				'change':function(){
					$(that.mwfLocation).highlight();
				}
			}
		});
		$(this.tthSTime).set({
			'events':{
				'change':function(){
					if(!that.checkRange('TTH','START')){
						that.errorChecker.tthSETime = false;
					}
					else{
						that.errorChecker.tthSETime = true;
					}
					$(that.tthSTime).highlight();
					that.checkAllValid();
					
					that.resolveVacantTimeRange('tth');
				},
				'blur':function(){
					
				}
			}
		});
		$(this.tthETime).set({
			'events':{
				'change':function(){
					if(!that.checkRange('TTH','END')){
						that.errorChecker.tthSETime = false;
					}
					else{
						that.errorChecker.tthSETime = true;
					}
					$(that.tthETime).highlight();
					that.checkAllValid();
					
					that.resolveVacantTimeRange('tth');
				},
				'blur':function(){
					
				}
			}
		});
		$(this.tthLocation).set({
			'events':{
				'change':function(){
					$(that.tthLocation).highlight();
				}
			}
		});
		// VACANT SELECTS EVENTS //
		$(this.mwfSVacant).set({
			'events':{
				'change':function(){
					if(!that.checkRange('MWF','START',true)){
						that.errorChecker.mwfSEVacant = false;
					}
					else{
						that.errorChecker.mwfSEVacant = true;
					}
					$(that.mwfSVacant).highlight();
					that.checkAllValid();
				},
				'focus': function () {
					that.fadingInMsg('mwfSEVMsg',vacancyMsg);
				},
				'blur':function(){
					
				}
			}
		});
		$(this.mwfEVacant).set({
			'events':{
				'change':function(){
					if(!that.checkRange('MWF','END',true)){
						that.errorChecker.mwfSEVacant = false;
					}
					else{
						that.errorChecker.mwfSEVacant = true;
					}
					$(that.mwfEVacant).highlight();
					that.checkAllValid();
				},
				'focus': function () {
					that.fadingInMsg('mwfSEVMsg',vacancyMsg);
				},
				'blur':function(){
					
				}
			}
		});
		$(this.tthSVacant).set({
			'events':{
				'change':function(){
					if(!that.checkRange('TTH','START',true)){
						that.errorChecker.tthSEVacant = false;
					}
					else{
						that.errorChecker.tthSEVacant = true;
					}
					$(that.tthSVacant).highlight();
					that.checkAllValid();
				},
				'focus': function () {
					that.fadingInMsg('tthSEVMsg',vacancyMsg);
				},
				'blur':function(){
					
				}
			}
		});
		$(this.tthEVacant).set({
			'events':{
				'change':function(){
					if(!that.checkRange('TTH','END',true)){
						that.errorChecker.tthSEVacant = false;
					}
					else{
						that.errorChecker.tthSEVacant = true;
					}
					$(that.tthEVacant).highlight();
					that.checkAllValid();
				},
				'focus': function () {
					that.fadingInMsg('tthSEVMsg',vacancyMsg);
				},
				'blur':function(){
					
				}
			}
		});		
	},
	
	finalChecking: function(){ 
		var mwfST = this.timeEquivValue($(this.mwfSTime).value);
		var mwfET = this.timeEquivValue($(this.mwfETime).value);
		var tthST = this.timeEquivValue($(this.tthSTime).value);
		var tthET = this.timeEquivValue($(this.tthETime).value);
		var mwfSV = parseInt($(this.mwfSVacant).value,10);
		var mwfEV = parseInt($(this.mwfEVacant).value,10);
		var tthSV = parseFloat($(this.tthSVacant).value);
		var tthEV = parseFloat($(this.tthEVacant).value);
		
		if ((((mwfSV >= mwfST) || (mwfSV === 0))  && (mwfEV <= mwfET)) && (((tthSV >= tthST) || (tthSV === 0.0)) && (tthEV <= tthET))) {
			if ((mwfST < mwfET) && (tthST < tthET)){
				if (((mwfSV < mwfEV) || ((mwfSV === 0)&&(mwfEV === 0))) && ((tthSV < tthEV) || ((tthSV === 0.0)&&(tthEV === 0.0)))) {
					
					if ((((mwfSV === 0) && (mwfEV > 0)) || ((mwfSV > 0) && (mwfEV === 0))) || (((tthSV === 0.0) && (tthEV > 0)) || ((tthSV > 0) && (tthEV === 0.0)))){
						return false;
					}
					else {
						return true;
					}
				}
				else {
					return false;
				}
			}
			else{
				return false;
			}
		}
		else {
			return false;
		}
		
	},
	
	savePreferences: function(){
		 
		var req = new Request({		
		 	url:SKEMO.GLINKS.saveStudentPreferencesPage,
		 	method:"post",
		 	data:{
		 		"updatePref":"save",
		 		"pref":this.gatherPreferences()
		 	},
		 	onRequest:function(){
		 	   $('myskemo_countainer').innerHTML = SKEMO.GLOBALS.saving;
		 	},
		 	onSuccess:function(res){
		 	   $('myskemo_countainer').innerHTML = SKEMO.GLOBALS.saving;
		 	   window.setTimeout(function(){
		 	   		$('myskemo_countainer').innerHTML = res;
		 	   		$('myskemo_countainer').highlight();
		 	   },800); //delay gamay...para makita and loading..
		 	}	
		}).send();
		
	} 
};

window.addEvent('domready',function(){
	
    SKEMO.init();
    MySkemo.initEvents();
    MySkemo.initFx();
        
    $('savePref').addEvent('mousedown',function(){
    	if(MySkemo.finalChecking()){
    		MySkemo.errorMsg('hide');
	    	MySkemo.savePreferences();
    	}
    	else{
//    		SKEMO.DIALOG.open({
//    			label: "SKEMO Oops...",
//    			body: "<br /><br /<center>Their are some inconsistencies in your preferences, please review them to proceed.</center><br /<br /"
//    		});
			MySkemo.errorMsg('show');
    	}
	});
});