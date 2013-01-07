<?php
session_start();

include('util/location.config.php');
include('util/security.php');
include UTIL_DB_CONN;
require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/indexer.js');
$myUniqueCSS = array('css/indexer.css','css/dialog.css');
$template->setTitle('Skemo: The Subject Schedule Recommender'); // sets the title.......;-)
$template->pageConfig($myUniqueCSS,$myUniqueJS);

include($template->headerInclude); // include the header file

include(UTIL_DIALOG_MARKUP);
?>
<div id="response_container"></div>
<div id = "edit_paneHdr">Features</div>
		  <div id="instructions_countainer" style="text-align:left;">
		   <ul>
		    <li><span class="toggle_span"><u><b>my<font color="red">Skemo</font>&#0153;</b></u></span>:
			 <div class = "edit_paneText"> 
				<p>
					This is the SKEMO preferences page, this is where you can set the preferences for your schedule.
					Skemo sets your preferences by default if you don't edit it your self. You are given this options 
					on your preferences:<br /><br /><br />
					<ul>
						<li>
							<strong>The Starting Time and Ending Time Of Your Classes:</strong>
							<br />
							<dfn>
								This is where you can set the time when you want your classes to start and what
								time you want it to end.
							</dfn>
							<br />
							<img src="images/instructions/SET.jpg" alt="" id=""  class="screen_shot"/>
							<br /><br /><br />
						</li>
						<li>
							<strong>Your Prefered Campus Location:</strong>
							<br />
							<dfn>
								Set where you want to spend your classes on specific days (MWF / TTH).
							</dfn>
							<br />
							<img src="images/instructions/loc.jpg" alt="" id="" class="screen_shot"/>
							<br /><br /><br />
						</li>
						<li>
							<strong>Optional Vacant Time:</strong>
							<br />
							<dfn>
								You can optionally set a vacant time.This option is great for working students or students that have a part time job, but please keep in mind that SKEMO sets its own 
								vacancies that conform to the <a href="javascript:void(0);" id="skemo_rules" title="check out the rules that SKEMO follows in plotting your schedule.">rules</a> that SKEMO follows for your schedule.  
							</dfn>
							<br />
							<img src="images/instructions/vacant.jpg" alt="" id="" class="screen_shot"/>
							<br /><br /><br />
						</li>
					</ul>
				</p>		 
			 </div><br />

			</li>
			<li><span class="toggle_span"><u><b><font color="red">Skemo</font>&#0153; Now!</b></u></span> :
			 <div class = "edit_paneText"> 
			 <br />
				 This page contains all the available subjects a student can take. The students will just check the subjects they desire 
				 to enroll and submit after to have or view the plotted schedules. 
			 </div><br />
			</li>
			<li><span class="toggle_span"><u><b><font color="red">Skemo</font>&#0153; Browse</b></u></span> :
			 <div class = "edit_paneText"> 
			 <br />
				 In this page, you can search subject offerings either by department or by subject number. This is to verify that the 
				 given schedule result of My Skemo are available and existing. 
			 </div><br />

			</li>
			<li><span class="toggle_span"><u><b><font color="red">View</font> Profile </b></u></span>:
			 <div class = "edit_paneText"> 
			 <br />
				 This page lets you view your profile that include the subjects you have taken and the subjects you have not taken.
			 </div><br />
			</li>
			
		   </ul>
		  </div>
		  <div style="clear:left;margin-bottom:-90px;">&nbsp;</div>

<?php
include($template->footerInclude);
?>