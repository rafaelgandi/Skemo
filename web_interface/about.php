<?php
session_start();
include('util/security.php');

require 'util/location.config.php';
include(UTIL_DB_CONN); // NEWLY ADDED 02-17-09

require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/skemo.js','js/default.js');
$template->setTitle('About'); // sets the title.......;-)
$template->pageConfig(array('css/about.css'),$myUniqueJS);

include($template->headerInclude); // include the header file
////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
 <div id = "edit_paneHdr">About</div>
 <div style="text-align:left">
		   <div class = "edit_paneText">
			   <p>
			   	One of the most important and memorable part of a college students life is the enrollment process. 
			   	Some students love it some hate it, but most students hate it.  We think the most hard and pain staking part 
			   	of the whole enrollment process is the dreaded plotting of ones schedule. We’ve all been there, being able
			   	to plot the perfect schedule only to be told when you are all ready to print your study
			   	  load that one or several of the subjects in your schedule are already closed or the
			   	   schedule does not exists then you have to re do all your scheduling. These kinds of
			   	    situations are really annoying and sometimes heart breaking.
			   </p>
			   <p>
			   	We, the SKEMO team thought these kinds of situations are really troublesome to the college student’s already 
			   	stressful life and frankly unnecessary!  So we decided to develop a system that is able to do this vital part of 
			   	the enrollment process which is mainly:
			   </p>
			   <br />
			   <ul id="about_ul">
			   	<li>
			   		Let the student know what subjects are allowed for him or her to take on that semester.
			   	</li>
			   	<li>
			   		Let the student choose from a list of subjects for him or her to enroll without being 
			   		concern of the schedule.
			   	</li>
			   	<li>
			   		Give students the freshest information about the schedules of the subjects being 
			   		offered on that semester.
			   	</li>
			   	<li>
			   		And finally why not try to let the system plot there schedules for them.
			   	</li>
			   </ul>
			   <br />
			   <p>
			   	We think that these four functionality is very important in coming up with a system that can help ease the 
			   	enrollment nightmare. Because of the need for this functionality <span class="skemo_logo">SKEMO</span><b>, The Subject Schedule Recommender</b> was 
			   	born. SKEMO or “skedul mo” is an application with one thing in mind and that is to lessen the pain of plotting 
			   	your schedule upon enrollment. <span class="skemo_logo">SKEMO</span> is design to do all the four functionalities stated above and even a bit more.  
			   	Never before has a system like this been implemented in USJ-R. We truly hope that this system would help our fellow josenians 
			   	in their college life.
			   </p>
			   <br />
			   <p>The <span class="skemo_logo">SKEMO</span> Team</p>
		   </div>
 </div>
 <div style="clear:left;margin-bottom:-50px;">&nbsp;</div>
<?php  
include($template->footerInclude);
?>