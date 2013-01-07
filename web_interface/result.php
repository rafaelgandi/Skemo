<?php
include('controller/result.ctr.php');

require SKEMO_TEMPLATE_ENGINE;
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$template->setTitle('Your Schedule!'); // sets the title.......;-)
$template->pageConfig(array('css/result.css','css/dialog.css'),'js/result.js');
include($template->headerInclude); // include the header file
/////////////////////////////////////////////////////////////////////////////
include(UTIL_DIALOG_MARKUP);
?>
<div class="result_container">
<p class="result_note">
	<strong>Note:</strong> This schedule is only a recommendation, it is not final. 
	You may choose to use it or disregard it completely. If you find a better schedule than this
	you are free to use that instead.
	<br /><br /><br />
	Thank you for using <strong>SKEMO</strong>, please tell your friends about me tnx ^__^
</p><br /><br />

<!-- preferences slider begins here -->
<div style="text-align: left; padding: 5px;"><a href="javascript:void(0);" id="pref_toggler" class="result_link">Check my preferences</a></div>
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
<br /><br />
<!-- preferences slider ends here -->
<?php
echo "<table border='0' class='sked_table'>";
$cnt = 0;
$classStyle = 'odd_row';
$code = 'NONE';
echo "<tr><th>Offer No</th><th>Time</th><th>Subject No</th><th>Subject Descritpion</th><th>Days</th><th>Room</th></tr>";
foreach($yourSkemoSchedules as $skemoSked){
	if(($cnt%2) == 0){
		$classStyle = 'even_row';
	}
	else{
		$classStyle = 'odd_row';
	}
	echo "<tr class='".$classStyle."'>";
	if (trim($skemoSked['offer_no']) !== '') {
		$code = $skemoSked['offer_no'];
	}
	echo "<td>".$code."</td>";
	echo "<td>".$skemoSked['time']."</td>";
	echo "<td>".$skemoSked['subject_no']."</td>";
	echo "<td width='300'>".$description->getDescription($skemoSked['subject_no'])."</td>";
	echo "<td>".$skemoSked['day']."</td>";
	//echo "<td>".$skemoSked['dayInt']."</td>";
	//echo "<td>".$skemoSked['meridiem']."</td>";
	echo "<td>".$skemoSked['room']."</td>";
	echo "</tr>";
	$cnt++;
}
echo "</table>";
?>
<div class="print_save_div">
<br />
<a href="javascript:void(0);" id="printSched" class="result_link" title="Print this schedule">Print Schedule</a>
<a href="javascript:void(0);" id="saveSched" class="result_link" title="Save this schedule">Save Schedule</a>
</div>
<br /><br /><br /><br /><br /><br />

<div id = "plot_result">
<?php
$howmanyPlotted = count($plottedSubjects);
$howmanyUnplotted = count($unplottedSubjects);

//echo "<div class='plot_details'>";
// PLOTTED SUBJECTS // blu box sa result.php
if($howmanyPlotted > 0){
	echo "<div class='result_plotted' style = 'float:left'>";
	echo "<strong>SKEMO was able to plot the following ".$howmanyPlotted." subjects:</strong><br /><br />";
	foreach($plottedSubjects as $subjectNo){
		echo $subjectNo."<br />";
	}
	echo "</div>";
}

// UNPLOTTED SUBJECTS //
if($howmanyUnplotted > 0){ // red box sa result.php
	echo "<div class='result_unplotted' style = 'float:right'>";
	echo "<strong>SKEMO was not able to plot the following ".$howmanyUnplotted." subjects:</strong><br /><br />";
	foreach($unplottedSubjects as $subjectNo){
		echo $subjectNo."<br />";
	}
	echo "<br /><a href='javascript: void(0);' id='unplottedReasons' title='Tell me the possible reasons'>Why were these subject(s) not plotted?</a>";
	echo "</div>";
}
//echo "</div>";

/*
echo '<hr />subjects<br /><pre>';
print_r($_SESSION['subjectsChecked']);
echo '</pre>';
echo '<hr />unplotted subjects:<br /><pre>';
print_r($scheduler->getUnplottedSubjects());
echo '</pre>';
echo '<hr />plotted subjects:<br /><pre>';
print_r($scheduler->getPlotted());
echo '</pre>';
echo '<hr />something:<br /><pre>';
print_r($scheduler->getSomething());
echo '</pre><br />';
//*/

?>

</div>
<div style="clear:both;"></div>
</div>
<?php

/////////////////////////////////////////////////////////////////////////////
include($template->footerInclude);
mysql_close($dh);
?>