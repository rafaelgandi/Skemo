/**
dependencies: 
 * mootools1.2.js
 * skemo.js
used in:
 * skemoNow.php 

THIS IS THE UNIQUE JAVASCRIPT FILE USED IN THE skemoNow.php

* Be advised thet the SkemoNow object was created in skemoNowUnits.js.php
* it was created by a PHP SCRIPT
*/

SkemoNow.util = {
	maxUnits: 30,
	
	unitCheckbox: 'overload_indicator',
	
	unitsIndicator: function () {
		var subjectChkbox = $('plot_sched').elements['subjects[]'];
		var len = subjectChkbox.length;
		var u = 0;
		var e = $('units_indicator');
		var c = 0;
		
		//debugger;
		for (c=0; c < len; c++) {
			if (subjectChkbox[c].checked) {
				u += SkemoNow.units[subjectChkbox[c].id];
			}
		}
		
		if (!$(this.unitCheckbox).checked) {
			
			e.innerHTML = 'Units: ' + u;
			
			// -- styling -- //
			if (u >= this.maxUnits) {
				e.style.color = 'red';
				e.style.fontWeight = 'bolder';
				e.style.textDecoration = 'underline';
			}
			else if (u > 24) {
				e.style.color = '#FF6600';
				e.style.fontWeight = 'bold';
				e.style.textDecoration = 'none';
			}
			else {
				e.style.color = '#000000';
				e.style.fontWeight = 'normal';
				e.style.textDecoration = 'none';
			}
		}
		else {
			e.innerHTML = 'Units: ' + u;
			e.style.color = '#000000';
			e.style.fontWeight = 'normal';
			e.style.textDecoration = 'none';
		} 
	},
	
	touchCheck: function (_id) {
		
		var e = $(_id);
		if (e.checked) {
			e.checked = false;
		}
		else {
			e.checked = true;
		}
		this.unitsIndicator();
		
	},
	
	checkMaxUnits: function () {
		var subjectChkbox = $('plot_sched').elements['subjects[]'];
		var len = subjectChkbox.length;
		var t = 0;
		
		if (!$(this.unitCheckbox).checked) {
			for (c=0; c < len; c++) {
				if (subjectChkbox[c].checked) {
					t += SkemoNow.units[subjectChkbox[c].id];
				}
			}
			if (t > this.maxUnits) {
				SKEMO.DIALOG.open({
					label: '<span style="font-size: 12px;">Overloading!</span>',
					body: '<span style="font-size: 12px;">Oh no, having this much load in one semester is bad for your health. Please reconsider.</span>'
				});
				return false;
			}
			else {
				$('total_units').value = t;
				return true;
			}
		}
		else {
			return true;
		}
	},
	
	initEvents: function () {
		var that = this;
		$('allow_unit_overload_text').addEvent('mousedown', function () {
			that.touchCheck('overload_indicator');
		});
	}
};


/**
 * DOM ready event starts here...
 */
window.addEvent('domready',function () {
	SKEMO.init();
	SkemoNow.util.initEvents();
	
	$('plot_sched').addEvent('submit',function () {
		if (helper.getChecked('plot_sched','subjects[]')) {
			if (SkemoNow.util.checkMaxUnits()){
				SKEMO.loadingScreen({
					msg: 'Plotting...'
				});
				return true;
			}
			else {
				return false;
			}
		}
		else {
			SKEMO.DIALOG.open({
				label: '<span style="font-size: 12px;">What\'s going on!?</span>',
				body: '<div style="font-size: 12px; text-align: center; padding: 20px;">You did not choose any subject...</div>'
			});
			return false;
		}
	});
	
});