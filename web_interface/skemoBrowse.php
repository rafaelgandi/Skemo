<?php 

include('util/location.config.php');
include(UTIL_DB_CONN);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Skemo™ Choose</title>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
-->
</style>
<head>
<link rel="shortcut icon" href="images/favicon_skemo.ico">
<link rel='stylesheet' type='text/css' href='css/main_style.css' />

<script language='javascript' type='text/javascript' src='js/skemo.js'></script>
<script language='javascript' type='text/javascript' src='js/mootools1.2.js'></script>
<script>
	// -- check if js is enabled -- //
	window.onload = function () {
		document.body.className = 'has_js';
	}
</script>
</head>

<body>
<center>
<div id = "container">
 <div id = "top">&nbsp;
 </div>
 <div id = "nav" class = "filler">
  <div id = "logo" style="float:left"><img src="images/skemo_logo.png"></div>

  <div id = "info">
 

<form action="" method="post">
   	<table>
   		<tr>
			<td align="right"><b>Search by Department </b></td>
   			<td align="left">
   				<select name = "dept" style="text-align:center;margin-top:3px; color:red;border:1px solid silver;">
					<option>-Departments-</option>
					<option>CAS</option>
   					<option>CICCT</option>
   					<option>COE</option>
   					<option>COMMERCE</option>
   					<option>CRE</option>
					<option>ENGINEERING</option>
   					<option>GUIDANCE</option>
				</select>
			</td>
		</tr>
	 
   
		<tr>
   			<td align="right"><b>Search Course No</b></td>
   			<td align="left">
   				<input type="text" name = "txtCourseNo"
					style="text-align:center;margin-top:3px; color:red;border:1px solid silver;"/>
   			</td>
		</tr>
   
  		<tr>
			<td></td>
			<td>
   				<input type = "submit" name = "searchSubmit" value = "Submit" />
   			</td>
		</tr>
   		
   </table>
   
</form>
  
  
  </div>
 &nbsp;
  <div id = "nones" style="padding:25px 0 30px 0;">&nbsp;</div>
 </div><!--END OF NAV-->
 <div id = "content" class="filler">
   <!--BEGIN EDIT HERE-->
   
    <div id = "window" align="center" >
		<!--<img src="images/ajax-loader.gif" width="200" height="200"/>-->
		<!--END OF MAIN_CONTENT-->
<div id = "edit_pane" style="width:785px;margin:-30px 0 0 0; font-size:12px;">
<span class="style1">SUBJECT OFFERINGS</span> <br/>

<?php

if($_POST['searchSubmit'])
{

//	$link = mysql_connect("localhost","root","") or die("batig nawng!!!");
//	$selected_db = mysql_select_db("skemo",$link) or die(mysql_error());

	$subj_no = $_POST['txtCourseNo'];

	if(($_POST['dept'])=='CAS')
	{
		$courseNo = 6;
		$college = 'College of Arts and Sciences';
	}
	
	else if(($_POST['dept'])=='CICCT')
	{
		$courseNo = 11;
		$college = 'Collge of Information, Computer and Comunications Technology';
	}
	
	else if(($_POST['dept'])=='COE')
	{	
		$courseNo = 5;
		$college = 'College of Education';
	}

	else if(($_POST['dept'])=='COMMERCE')
	{
		$courseNo = 3;
		$college = 'College of Commerce';
	}

	else if(($_POST['dept'])=='CRE')
	{
		$courseNo = 16;
		$college = 'Center for Religious Education';
	}

	else if(($_POST['dept'])=='ENGINEERING')
	{
		$courseNo = 4;
		$college = 'College of Engineering';
	}

	else if(($_POST['dept'])=='GUIDANCE')
	{
		$courseNo = 'Guidance';
		$college = 'Student Development and Placement Center';
	}

	else 
	{
		$courseNo = null;
	}


	
	if(($courseNo != null) and ($subj_no == null))
	{
		if($courseNo == 'Guidance')
		{
			$result = mysql_query("SELECT offer_no, subject_no, time, day, room from subjects_table 
				where subject_no like '$courseNo%' order by subject_no, day");
		}
		
		else if($courseNo == 4)
			{
				$result = mysql_query("SELECT offer_no, subject_no, time, day, room from subjects_table 
				where offer_no like '$courseNo%' and subject_no not like 'Guid%' order by subject_no, day");
			}
		
		else
		{
			$result = mysql_query("SELECT offer_no, subject_no, time, day, room from subjects_table 
				where offer_no like '$courseNo%' order by subject_no, day");
			
		}
	}

	else if(($courseNo == null) and ($subj_no != null))
	{
		$result = mysql_query("SELECT offer_no, subject_no, time, day, room from subjects_table 
			where subject_no like '$subj_no%' order by subject_no, day");
	}

	else if(($courseNo != null) and ($subj_no != null))
	{
		$result = mysql_query("SELECT offer_no, subject_no, time, day, room from subjects_table 
			where subject_no like '$subj_no%' and offer_no like '$courseNo%' order by subject_no, day");
	}

	else if(($courseNo == null) and ($subj_no == null))
	{
		$result = null;
	}

	
	
	if(($result == null) or (mysql_num_rows($result)==0))
	{
		echo "<br/><br/><br/>Sorry. No Result(s) Found.<br/>";
	}
	else
	{
		echo "<b>$college</b><br/><br/><br/>";
	
		echo "<table width=\"732\" height=\"24\" align=\"center\">";
		echo "<tr>";
		echo "<td width=\"60\" align=\"center\"><b>Offer No</b></td>";
		echo "<td width=\"80\" align=\"center\"><b>Course No</b></td>";
		echo "<td width=\"95\" align=\"center\"><b>Time</b></td>";
		echo "<td width=\"60\" align=\"center\"><b>Day</b></td>";
		echo "<td width=\"60\" align=\"center\"><b>Room</b></td>";
		echo "</tr>";
 		echo "<tr>";
 		echo "<td>&nbsp;</td>";
 		echo "</tr>";

		while ($row = mysql_fetch_array($result)) 
		{		

			if($row['day'] == '135')
				$day_equi = 'MWF';
			else if ($row['day'] == '24')
				$day_equi = 'TTh';
			else if ($row['day'] == '1')
				$day_equi = 'M';
			else if ($row['day'] == '2')
				$day_equi = 'T';
			else if ($row['day'] == '3')
				$day_equi = 'W';
			else if ($row['day'] == '4')
				$day_equi = 'Th';
			else if ($row['day'] == '5')
				$day_equi = 'F';
			else if ($row['day'] == '6')
				$day_equi = 'S';

			echo "<tr>";
			echo "<td width=\"60\" align=\"center\">".$row['offer_no']."</td>";
			echo "<td width=\"80\" align=\"center\">".$row['subject_no']."</td>";
			echo "<td width=\"95\" align=\"center\">".$row['time']."</td>";
			echo "<td width=\"60\" align=\"center\">".$day_equi."</td>";
			echo "<td width=\"60\" align=\"center\">".$row['room']."</td>";
			echo "</tr>";
		   
	 
		}	
	
		echo "</table>";
	}

}

?> 

<!-- CONTENT AREA STARTS HERE (template engine)-->
 <br />
<br />
<br />

<!-- CONTENT AREA ENDS HERE (template engine)-->
</div><!--END OF EDIT PANE-->		
	</div><!--END OF TEST-->
   <!--END EDIT-->

   <!--END OF NAVS-->
   
   <div id = "nones" style=" padding-bottom:108px;">&nbsp;</div>
  </div><!--END OF CONTENT-->
  
 <div id = "bottom" class="link">
  
 </div>
</div>
</center>
</body>
</html>