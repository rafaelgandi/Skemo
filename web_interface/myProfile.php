<?php
session_start();

require 'util/location.config.php';

include('util/security.php');
include(UTIL_DB_CONN); // ADDED 02-17-09
include(UTIL_TIME_CONVERTERS); // NEWLY ADDED 02-21-09

require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/list_subjects.js','js/default.js');
$template->setTitle('My Profile'); // sets the title.......;-)
$template->pageConfig('css/myProfile.css',$myUniqueJS);

include($template->headerInclude); // include the header file
?>
<form action="" method="get">
<div id = "edit_paneHdr">Your Profile</div>
<div class = "edit_paneText">

<?php
if (isset($_SESSION['preferences'])) {
	$pref = $_SESSION['preferences'];
?>
<!-- preferences start here -->

<center>
	<div id="pref_container">
	<?php

$mwfVacantStart = valueEquivTime($pref['mwf']['sv']); 
$mwfVacantEnd = valueEquivTime($pref['mwf']['ev']);
$tthVacantStart = valueEquivTime($pref['tth']['sv']); 
$tthVacantEnd = valueEquivTime($pref['tth']['ev']);

if ($pref['mwf']['mwfNoClass'] == 'NOCLASS') {
	$pref['mwf']['stime'] = 'No Class';
	$pref['mwf']['etime'] = 'No Class';
	$pref['mwf']['location'] = 'NA';
	$mwfVacantStart = 'No Class';
	$mwfVacantEnd = 'No Class';	
}

if ($pref['tth']['tthNoClass'] == 'NOCLASS') {
	$pref['tth']['stime'] = 'No Class';
	$pref['tth']['etime'] = 'No Class';
	$pref['tth']['location'] = 'NA';
	$tthVacantStart = 'No Class';
	$tthVacantEnd = 'No Class';		
}
?>
	<table id="" border="0" cellspacing="0" class="pref_container_table"> 
	  <tr>   	
	    <td><span class="pref_label">MWF</span></td>
	    <td><span class="pref_label">TTH</span></td>
	  </tr>
	  <tr>   
	    <td>
	    <span class="label">Start Time:&nbsp;</span> 
	    <?php
			echo $pref['mwf']['stime'];
	    ?>
	    </td>
	    <td>
	    <span class="label">Start Time:&nbsp;</span> 
	    <?php
			echo $pref['tth']['stime'];
	    ?>
	    </td>
	  </tr>
	  <tr>   
	    <td>
	    <span class="label">End Time:&nbsp;</span> 
	    <?php
			echo $pref['mwf']['etime'];
	    ?>
	    </td>
	    <td>
	    <span class="label">End Time:&nbsp;</span> 
	    <?php
			echo $pref['tth']['etime'];
	    ?>
	    </td>
	  </tr>
	  
	  <!-- vacant TYMS-->
	  
	  <tr>   
	    <td>
	    <span class="label">Vacant Start Time:&nbsp;</span> 
	    <?php
			echo $mwfVacantStart;
	    ?>
	    </td>
	    <td>
	    <span class="label">Vacant Start Time:&nbsp;</span> 
	    <?php
			echo $tthVacantStart;
	    ?>
	    </td>
	  </tr>
	  <tr>   
	    <td>
	    <span class="label">Vacant End Time:&nbsp;</span> 
	    <?php
			echo $mwfVacantEnd;
	    ?>
	    </td>
	    <td>
	    <span class="label">Vacant End Time:&nbsp;</span> 
	    <?php
			echo $tthVacantEnd;
	    ?>
	    </td>
	  </tr>
	  <tr>   
	    <td>
	    <span class="label">Campus Location:&nbsp;</span> 
	    <?php
			echo locationDecrypter($pref['mwf']['location']);
	    ?>
	    </td>
	    <td>
	    <span class="label">Campus Location:&nbsp;</span> 
	    <?php
			echo locationDecrypter($pref['tth']['location']);
	    ?>
	    </td>
	  </tr>
	</table>
	<br />
	<a href="mySkemo.php" class="result_link" title="Change my preferences">Edit my preferences</a>

	</div>
</center>
<br />
<!-- preferences end here -->
<hr />
<?php
}

// end of preferences block
?>


<?php	
	$student_id = $_SESSION['studentNo'];

	$query = "SELECT * from student_table where student_id='$student_id'";
	$result = mysql_query($query) or die("ERROR ON QUERY :".$query." | ".mysql_error());
	
	while ($row = mysql_fetch_array($result)) 
	{	
		echo "<ul>";
		echo "<table style=\"list-style-type:none;\">";
		echo "<tr><td width=\"100\"> NAME</td><td>"."<b>".$row['last_name'].", ".$row['first_name']." "
			.$row['mid_name']."</td></tr>";
		echo "<tr><td width=\"100\"> COURSE</td><td>"."<b>".str_replace('11','',$row['course'])."</td></tr>";
		echo "<tr><td width=\"100\"> YEAR</td><td>"."<b>".$row['yearLevel']."</td></tr>";
	}	
	
	echo"</table>";
	?>	

	<br/>
			  <div style="border:3px dashed #FF6600; padding:8px;background-color:#EFEFEF;width:325px;"> View Subject(s): 
			  
			  <input type="radio" name="subj_type" value="taken" onclick="ajax_list_subj('taken');" />
			  <b>Taken</b>&nbsp;
			  
			  <input type="radio" name="subj_type" value="nottaken" onclick="ajax_list_subj('nottaken');"  />
			  <b>Not Taken</b>
			    
             </div>
		   
		 
		  </div>
		  
		  <div id = 'list_subj'> 
		  </div>
		  

</form>
<?php
include($template->footerInclude);
?>