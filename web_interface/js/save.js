var Save = {
	skemoSave:function(){
		var instructions = [];
		instructions.push("To save this SKEMO schedule: \n\n");
		instructions.push("1.) Go to the menubar of your browser\n");
		instructions.push("2.) Click the file menu\n");
		instructions.push("3.) Choose Save Page As... or Ctrl + s\n");
		instructions.push("\n-The SKEMO Team");
		
		self.alert(instructions.join(''));
	}
}
window.addEvent('domready',function(){	
	Save.skemoSave();
});