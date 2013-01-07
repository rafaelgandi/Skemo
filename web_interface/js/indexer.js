/**
dependencies: 
 * mootools1.2.js
 * skemo.js
used in:
 * index.php 
 */
var Indexer = {
	
	ACCORDION:{
		toggler:'.toggle_span',
		container:'.edit_paneText',
		initAccordion:function(){
			var tog = $$(this.toggler);
			var con = $$(this.container);
			// initialize a new Accordion object from mootoo00000ls here... //
			var accor = new Accordion(tog,con,{
				show:0
			});
		}
	},
	Events:{
		init:function () {
			$('skemo_rules').addEvent('mousedown',function () {
				var rules = [];
				rules.push("These are the rules that SKEMO follows in plotting your schedule:<br /><br />");
				rules.push("<ul>");
				rules.push("<li>The student is always given time to travel from basak campus to the main campus and vice versa. </li>");
				rules.push("<li>Students should not have more than three successive subjects without vacancy.</li>");
				rules.push("<li>SKEMO does not plot summer schedules only regular semesters.</li>");
				rules.push("</ul>");
				
				SKEMO.DIALOG.open({
					label:"SKEMO Rules",
					body: rules.join('')
				});
			});
		}
	}
	
};


window.addEvent('domready',function(){
	SKEMO.init();
	Indexer.Events.init();	
	Indexer.ACCORDION.initAccordion();
});