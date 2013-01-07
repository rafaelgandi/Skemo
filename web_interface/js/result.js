/**
 * dependencies:
 *  -mootools1.2.js
 *  -helpers.toolkit.js
 *  -json2.js
 * 
 */
var Result = {
	
	init:function() {
		$('pref_container').slide('hide');
	},
	
	openPrintableSked:function() {
		
	},
	
	unplottedReason:function() {		
		var reason = [];
		reason.push("These are the possible reason why some subjects where not plotted:<br /><br />");
		reason.push("<ul>");		
		reason.push("<li> There was too much conflict with other schedules</li>");
		reason.push("<li> There was too much conflict with your preferences</li>");
		reason.push("<li> Plotting the subject causes a violation on the scheduling rules</li>");
		reason.push("<li> The subject was not found in our database</li>");		
		reason.push("</ul>");
		reason.push("<br />Sorry for the inconvinience.\n");
		reason.push("<br /><br />The <span style='color:red;font-weight:bold;'>SKEMO</span> team");
		
		SKEMO.DIALOG.open({
			label:"The Reason Why",
			body:reason.join('')
		});
		//window.alert(reason.join(''));		
	},
	
	callPrintablePage:function(_mode) {
		var w = 0;
		var h = 0;
		var l = 0;
		var t = 0;
		var specs = '';
		var url = SKEMO.GLINKS.printablePage;
		
		if (_mode === 'PRINT') {
			l = -1000;
			t = -1000;
			w = 100;
			h = 100;
			url = url + "?print=true";
		}
		else if (_mode === 'SAVE') {
			l = screen.width / 2;
			t = screen.height / 2;
			w = screen.width;
			h = screen.height;
			url = url + "?save=true";
		}
		specs = "width="+w+",height="+h+",scrollbars=1,menubar=1,resizable=0,left="+l+",top="+t;
		window.open(url,'printPage',specs);
	},
	
	preferencesSlider: function () {
		var prefSlider = new Fx.Slide('pref_container',{
			transition: 'bounce:out'
		});
		prefSlider.toggle();
	},
	
	initEvents:function() {
		var that = this;
		// -- event for the unplotted reason link -- //
		if (document.getElementById('unplottedReasons')) { //check if tere are unplotted subjects
			$('unplottedReasons').addEvent('mousedown',function () {
				that.unplottedReason();
			});
		}
		// -- events for the print ans save schedule functionality -- //
		$('printSched').addEvent('click',function () {
			that.callPrintablePage('PRINT');
		});
		$('saveSched').addEvent('click',function () {
			that.callPrintablePage('SAVE');
		});
		
		$('pref_toggler').addEvent('mousedown',function () {
			that.preferencesSlider();
		});
	}
};
window.addEvent('domready',function () {
	SKEMO.init();
	Result.init();
	Result.initEvents();
});