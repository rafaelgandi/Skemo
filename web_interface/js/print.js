var Print = {
	skemoPrint:function(){
		self.print();
		self.close();
	}
}
window.addEvent('domready',function(){
	Print.skemoPrint();
});