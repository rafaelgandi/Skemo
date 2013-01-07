<?php
session_start();
include('util/security.php');

require 'util/location.config.php';
include(UTIL_DB_CONN); // NEWLY ADDED 02-17-09


require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/skemo.js','js/default.js');
$template->setTitle('Developer'); // sets the title.......;-)
$template->pageConfig(false,$myUniqueJS);

include($template->headerInclude); // include the header file

?>

<div id = "edit_paneHdr">Developers</div>
		  <div style="text-align:left">
		   <div style="">
		    <div style="float:left; border:1px solid black;width:258px;background-color:#00a8ec;">
			 <div style="border:1px solid silver; width:140px; height:130px;background-color:#fafafa;margin:15px 15px 15px 55px;" align="center">
			 <b>Rafael Gadiongco</b>
			 <br /><br /><img src="images/img_default.png" width="80" height="70">
			 </div>
			 
			</div>
			<div style="float:left; border:1px solid black;width:257px;background-color:#ffa5d6;">
			<div style="border:1px solid silver; width:140px; height:130px;background-color:#fafafa;margin:15px 15px 15px 55px;" align="center">
			 <b>Erra Marjorie Pacaldo </b>
			 <br />
			 <br /><img src="images/img_default.png" width="80" height="70">
			 </div>
			</div>
			<div style="float:left; border:1px solid black;width:258px;background-color:#cbc0ae;">
			<div style="border:1px solid silver; width:140px; height:130px;background-color:#fafafa;margin:15px 15px 15px 55px;" align="center">
			 <b>Edmund Balili Jr.</b>
			 <br /><br /><img src="images/img_default.png" width="80" height="70">
			 </div>
			 
			</div>
			<div id = "dev_profile" style="text-align:center; font-size:15px;padding:170px 0 0 0;">
			 <div style="float:left;width:257px">(Web/System) Developer</div>
			 <div style="float:left;width:257px">(Web/System) Developer</div>
			 <div style="float:left;width:257px">(Web/System) Developer,Designer</div>
			</div>
			<div style="clear:left;margin-bottom:-50px;">&nbsp;</div>
			
		   </div>
		   
		  </div>
<?php
include($template->footerInclude);
?>