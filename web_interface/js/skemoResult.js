/**
dependencies: 
-mootools1.2.js


THIS IS THE UNIQUE JAVASCRIPT FILE USED IN THE myskemo.php

*/
//////////////////////////////////////////////////////////////////////////// ;-)
var SKEMO_FX = {
 
    "fxHover": function(theid){
	
	   var el = $(theid);	   
	   el.fade('in');
	   	   	
	},
	"fxOut": function(theid){
	   var el = $(theid);	   
	   el.fade('0.5');
	}
	
 
}
 window.addEvent('domready',function(){
    SKEMO.init();
    //SKEMO_FX.fxHover('sked_window');
	$$('.sked_window').fade('0.5');
	
 });