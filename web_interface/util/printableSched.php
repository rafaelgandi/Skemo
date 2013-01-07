<?php
session_start();

require '../classes/SKEMO_Subject_Desc.class.php';
$description = new SKEMO_Subject_Desc();
require 'db_conn.inc.php';
require 'helpers.inc.php';
require 'location.config.php';

// check what javascript file to run //
$scriptFile = '';
if (isset($_GET['print'])){
    $scriptFile = '../'.JS_PRINT;		
}
else if (isset($_GET['save'])){
    $scriptFile = '../'.JS_SAVE;		
}
else {
	header("Location: ../mySkemo.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title> My SKEMO Schedule - <?php echo $_SESSION['studentNo'];?></title>
	<meta http-equiv="Content-Language" content="en" />
    <meta name="GENERATOR" content="Eclipse PDT 1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="../<?php echo IMG_SKEMO_FAVICON;?>">   
    <link rel="stylesheet" href="../css/printableSched.css"/>
    <script src="../<?php echo JS_MOOTOOLS;?>"></script>
    <script src="../<?php echo JS_SKEMO;?>"></script>
    <script src="<?php echo $scriptFile; ?>"></script>
</head>
<body>
<noscript>THIS WEB APP REQUIRES THAT JAVASCRIPT SHOULD BE ENABLED</noscript>
<center>
<div id = "p_container" align="center">
 <div id = "p_head">
  <img src="../images/skemo_logo.png" alt="" />
 </div>
 <hr />
 <div id = "p_sched">
       <?php
		$code = 'NONE';
			
		echo "<table border='0' class='sked_table'>";
		echo "<tr><th>Offer No</th><th>Time</th><th>Subject No</th><th>Subject Descritpion</th><th>Days</th><th>Room</th></tr>";
		foreach($_SESSION['yourSkemoSchedules'] as $skemoSked){
			if(($cnt%2) == 0){
				$classStyle = 'odd_row';
			}
			else{
				$classStyle = 'even_row';
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
   </div>

  <div id = "p_foot">
   <div id = "end">&nbsp;</div>
  </div>

</div>
</center>
<!-- Author: eL rafA gAndi -->   
</body>
</html>