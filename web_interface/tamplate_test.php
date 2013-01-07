<?php
require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
//$myUniqueJS = array('js/myskemo.js');
$template->setTitle('SKEMO template test (myskemo)'); // sets the title.......;-)
//template->pageConfig(false,$myUniqueJS);


include($template->headerInclude); // include the header file
////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<div id = "edit_paneHdr">Features</div>
		  <div style="text-align:left;">
		   <ul>
		    <li><u><b>my<font color="red">Skemo</font>&#0153;</b></u> :
			 <div class = "edit_paneText"> <br />"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
			 </div><br />
			</li>
			<li><u><b><font color="red">Skemo</font>&#0153; Now!</b></u> :
			 <div class = "edit_paneText"> <br />"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
			 </div><br />
			</li>
			<li><u><b><font color="red">Skemo</font>&#0153; Choose</b></u> :
			 <div class = "edit_paneText"> <br />"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
			 </div><br />
			</li>
			<li><u><b><font color="red">View</font> Profile </b></u>:
			 <div class = "edit_paneText"> <br />"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
			 </div><br />
			</li>
			
		   </ul>
		  </div>
<?php  ///////////////////////////////////////////////////////////////////////////////////////////////////////
include($template->footerInclude); // include the f0000ter file
?>