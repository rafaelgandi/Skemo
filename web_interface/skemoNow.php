<?php
session_start();

include('util/security.php');
include('util/location.config.php');
include(UTIL_DB_CONN);

require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/skemoNowUnits.js.php','js/skemoNow.js');
$template->setTitle('Skemo™ Now'); // sets the title.......;-)
// -- updated 02-23-09 -- //
$template->pageConfig(array(
	'css/loadingScreen.css',
	'css/dialog.css'
),$myUniqueJS);

include(UTIL_LOADING_SCREEN_MARKUP);
include($template->headerInclude); // include the header file
// -- dialog markup 02-23-09-- //
echo "<div style='font-size: 10px;'>";
include(UTIL_DIALOG_MARKUP);
echo "</div>";
?>


<style type="text/css">
#a tr td
{
padding:14px 0 11px 0;
}
#a {
margin:-20px 0 0 1px;width:780px;border:3px dashed #FF6600; background-color:#EFEFEF;color: #0F0E0E;
}

.subject_label {
	cursor: pointer;
	cursor: hand;
}
#allow_unit_overload_text {
	cursor: pointer;
	cursor: hand;
}
</style>
<form action="result.php" method="post" id="plot_sched">
<div id = "edit_paneHdr">Allowable Subjects To Be Taken</div> <br />
<div id = 'edit_paneHdrSub'>Select subject(s) to be <b>scheduled</b></div><br /><br />
 <div id = 'a'>
 
  <br />
  
<?php 
 // db connection -- //
$link = mysql_connect("localhost","root","") or die("batig nawng!!!");
$selected_db = mysql_select_db("skemo",$link) or die(mysql_error());

$student_id = $_SESSION['studentNo'];

echo "<br/></br><b>LIST OF AVAILABLE SUBJECTS</b><br />";
echo "Please check the subjects you want to take.<br /><br />";
// -- updated 02-23-09 -- //
if (isset($_GET['error'])) {
	if (($_GET['error'] === trim('No subjects WHERE checked')) || ($_GET['error'] === trim('Your unit load exceeded the limit'))){
		echo "<span style='color: red; font-weight: bold;'>",$_GET['error'],"</span>";
	}
}

$resultStud = mysql_query("select yearLevel, curriculum,course from student_table WHERE student_id = '$student_id'");
			
	while($rowStud = mysql_fetch_array($resultStud))
	{
	$courseType = substr($rowStud['course'],4,8);
	
		if($row1['curriculum']==2008)
			$curr = 2008 ;
		else
			$curr = 2005;
	
		if($rowStud['yearLevel'] == 2)
		{
			$standing = '2ND YEAR';
		}
		else if($rowStud['yearLevel'] == 3)
		{
			$standing = '3RD YEAR';
			$standing2 = '2ND YEAR';
		}
		else if($rowStud['yearLevel'] == 4)
		{
			$standing = '4TH YEAR';
			$standing2 = '3RD YEAR';
			$standing3 = '2ND YEAR';
		}
 }

//echo $courseType;
$store = array();// THIS WILL SERVED AS SUBJECTS_ENROLLED DATA ON THE LATER PART
$dataStore = array();
$forDisplay = array();

$itELEC1 = array(0 => 'CS 19D',1 => 'CS 19H');
$itELEC2 = array(0 => 'CS 19F (PHP)',1 => 'CS 19J');

$sql = "SELECT subject_no,offer_no,subject_id,grade from subjects_enrolled WHERE student_id = '$student_id ';";

// 1) CHECK SUBJECT_ENROLLED --> CONVERSION STAGE
$result0 = mysql_query($sql);
while($row1 = mysql_fetch_row($result0))
 {
  $result1 = mysql_query("SELECT subject_no from prospectus WHERE subject_no = '$row1[0]' AND department = '$courseType'");
  //echo "SELECT subject_no from prospectus WHERE subject_no = '$row1[0]' AND department = '$courseType'";echo "<br/>";
  $cmp_result = strncmp($row1[0],'CS 19',5); // COMPARE FOR ANY IT ELECTS

   if($cmp_result == 0)
	{
	 if($row1['0'] == 'CS 19D')
	  { 
	   $row1['0'] = $courseType.' ELEC 1';
	   $store[$row1['0']] = $row1[3];
	  }
	 elseif($row1['0'] == 'CS 19F (PHP)') 
	  {
	   $row1['0'] = $courseType.' ELEC 2';
	   $store[$row1['0']] = $row1[3];
	  }
	 elseif($row1['0'] == 'CS 19H')
	  {
	   $row1['0'] = $courseType.' ELEC 1';
	   $store[$row1['0']] = $row1[3];
	  }
	 elseif($row1['0'] == 'CS 19J')
	  {
	   $row1['0'] = $courseType.' ELEC 2';
	   $store[$row1['0']] = $row1[3];
	  }  
	}
	
   elseif(mysql_num_rows($result1) == 0) //not found -> DATA CONTRASTING FROM COMMOM DATA
    {
	 $result2 = mysql_query("SELECT subject_title from common_data WHERE offer_no = $row1[1] and subject_id = $row1[2] limit 1;");     //echo "SELECT subject_title from common_data WHERE offer_no = $row1[1] and subject_id = $row1[2] limit 1;"."<br/>";
	  $data = mysql_fetch_assoc($result2);
	  
	  $result3 = mysql_query("SELECT subject_no from prospectus WHERE subject_desc like '%$data[subject_title]%' AND department = '$courseType';");
	 // echo "SELECT subject_no from prospectus WHERE subject_desc like '%$data[subject_title]%' AND department = '$courseType';"."<br/>";
	   //echo "SELECT subject_no from prospectus WHERE subject_desc like '%$data[subject_title]%';";
	 $data = mysql_fetch_assoc($result3);
      //echo $data['subject_no']."<br/>";
	  $store[$data['subject_no']] = $row1[3];
	}
	elseif(mysql_num_rows($result1) > 0)
	{
	 $store[$row1['0']] = $row1[3];
	}
	

 } // END OF WHILE
//print_r($store);
 // 2) CHECK store[course_no] IN PROSPECTUS -->> GET COURSE_NO FROM PROSPECTUS NOT IN store[key];
 $ctr = 0;
 
  foreach($store as $key => $value)
	{
	 $dataFilter .= "'".$key."',";   
    }
	
    $sql = "select subject_no from prospectus WHERE curr_year = '$curr' 
	and department = '$courseType' and subject_no not in(".substr($dataFilter,0,strlen($dataFilter) -1).");";
	//echo $sql."<br/>";
	$result5 = mysql_query($sql);
	 while($row = mysql_fetch_row($result5))
	 {
	  $dataStore[$ctr] = $row[0];
	  $ctr++;
	 }

  // 3) CHECK PREQUISITES

$ctr = 0;
//print_r($dataStore);
foreach($dataStore as $key => $value)
 {
   $sql = "select pr.require from prospectus p, prospect_require pr 
   where p.auth_code = pr.auth_code and p.subject_no = '$value';";
   //echo $sql."<br/>";
    $result = mysql_query($sql);
	$numOfReqORIG = mysql_num_rows($result);
	//echo $numOfReqORIG;
	$indicator = 0;
	
    while($row = mysql_fetch_row($result))
	 {
	     if(($row['0'] == 'NONE') ||($row['0'] == $standing)||($row['0'] == $standing2)||($row['0'] == $standing3))
			$indicator++;
		 else
		   {
			$grade = $store[$row[0]];
			//echo $grade."->".$row[0];
		    if(($grade !=0) && ($grade <= 3))
		     {
			  //echo "true".$grade."->".$row[0];
			  $indicator++;
			 }
		   }
	
	   if($indicator >= $numOfReqORIG)
	    {
		 //echo "true".$grade."->".$row[0];
		 $forDisplay[$ctr] = $value;
		 $indicator = 0;
		 $ctr++;
		}

	 } // END OF 1ST WHILE
	 
   } // END OF FOR LOOP
   
 // END OF MY UPDATE ^_^

$forDisplay2 = array(); 
$ctr2 = 0;

foreach($forDisplay as $value)
{
	$sql = "SELECT subject_no FROM subjects_table WHERE subject_no LIKE '%$value%'";
	
	$result = mysql_query($sql);
	
	if(mysql_num_rows($result) > 0 )
	{
		$forDisplay2[$ctr2] = $value;
		$ctr2++;
	}

} 

$ctrArray = 0;
$ctr = 1;

echo "<br/><br/>";
echo "<div style = 'margin-left:100px'>";
echo "<table height=\"29\" align=\"center\"'>";

foreach($forDisplay2 as $key => $value)
{
 if($ctr == 1)
 {
  echo "<tr>"; 
 }

 if($ctr > 0)
 // -- updated 02-23-09 -- //
  echo "<td width='140' style = 'padding:14px 0 11px 0;'><input type=\"checkbox\" name='subjects[]' value='".$value."' id='".$value."' onchange='SkemoNow.util.unitsIndicator();' /><span class='subject_label' onmousedown='SkemoNow.util.touchCheck(\"",$value,"\");'>$value</span></td>";
  
 if ($ctr == 4)
 {
  echo "</tr>";
  $ctr = 0;
 }
 $ctr++;
}

echo "</table>";
echo "</div>";


// -- updated 02-23-09 -- //
?>
<div style="vertical-align: bottom; text-align: left; padding-top: 25px; padding-left: 5px;">
	<span style="float:left;">
	<input type="hidden" id="total_units" name="total_units" value="undefined" />
	<input type="checkbox" id="overload_indicator" name="overload_indicator" value="allow" />&nbsp; <span id="allow_unit_overload_text">Allow unit overload.</span>
	</span>
	<span style="float: right;margin-right: 8px;" id="units_indicator"></span>
</div>
  
  <div style="clear:both;">&nbsp;</div>
  
</div>
<div style="margin:50px 0 -70px 0;">
 <input type="submit" value="Plot Schedule"  name="pref_submit" /> 
</div>
</form>
<?php
include($template->footerInclude);
?>