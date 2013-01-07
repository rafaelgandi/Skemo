<?php
session_start();
include('util/security.php');

require 'util/location.config.php';
include(UTIL_DB_CONN); // NEWLY ADDED 02-17-09

require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/skemo.js','js/default.js');
$template->setTitle('Copyright'); // sets the title.......;-)
$template->pageConfig(array('css/about.css'),$myUniqueJS);

include($template->headerInclude); // include the header file
////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
 <div id = "edit_paneHdr">Copyright</div>
 <div style="text-align:left">
		   <div class = "edit_paneText"> 
			   <p>
			   		&copy;<?php echo date('Y'); ?>,Skemo™. All Rights Reserved.</p>
					<p style="text-align:justify">					
					All title and copyrights in and to the SOFTWARE PRODUCT
(including but not limited to any images, animation and text incorporated into the (SOFTWARE PRODUCT), the accompanying printed materials, and any copies of the SOFTWARE PRODUCT are owned by Skemo™ Team. The SOFTWARE PRODUCT is protected by copyright laws and international treaty provisions. Therefore, you must treat the SOFTWARE PRODUCT like any other copyrighted material except that you may install the SOFTWARE PRODUCT on a single computer provided you keep the original solely for backup or archival purposes.

				</p>
		   </div>
 </div>
 <div style="clear:left;margin-bottom:-50px;">&nbsp;</div>
<?php  
include($template->footerInclude);
?>