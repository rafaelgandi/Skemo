<?php
session_start();

require 'util/location.config.php';
include(UTIL_DB_CONN); // ADDED 02-17-09

$msg = "";
$studentNo = $_POST['studentNo'];

if(isset($_POST['studentNo']))
{	
		$sql="SELECT * from student_table WHERE student_id='$studentNo'";
		$result = mysql_query($sql);

		$num_rows=mysql_num_rows($result);

		if($num_rows > 0)
    	{
        	$_SESSION['studentNo'] = $studentNo;
			$address = "mySkemo.php";
			header("Location: ".$address);
			exit();	
    	}
		else
    	{
        	$msg="Student ID not found! Please check...";
    	}
}

//////////// -- STUDENT KIOSK LOGIN INTEGRATION (03-09-09) -- ///////////

/**
 * 
 * KIOSK QUERY STRING NEEDED:
 * 
 * - fromKiosk = true
 * - studentNo = 2********* [must be 10 digits and a valid student number]
 * 
 */

/**
 * This function is used to check if the students id number is in the skemo database.
 * Returns true if found and false if not....
 * 
 * 
 * @param $_studNum
 * @return bool
 */
function checkStudentValidity($_studNum) {
	$query;
	if ((ctype_digit($_studNum)) && (strlen($_studNum) === 10)) {
		$query = sprintf("SELECT * from student_table WHERE student_id='%s'", $_studNum);
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
	
}

if ((isset ($_GET['fromKiosk'])) && (trim($_GET['studentNo']) !== "")) {
	if (checkStudentValidity($_GET['studentNo'])) {		
		$_SESSION['studentNo'] = trim($_GET['studentNo']);
		header("Location: mySkemo.php");
		exit();	
	}
	else {
		$msg = "Your student number was not found please try to log in again...";
	}
}

/////////////////////////////////////////////////////////////////////////

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="shortcut icon" href="images/favicon_skemo.ico" />
<link rel="stylesheet" href="css/main_style.css" />
<link rel="stylesheet" href="css/login.css" />

<script src="js/skemo.js"></script>
<script>
	var placeStudNumOnTxtField = function (_id) {
		var sn = '';
		if (_id === "ed") {
			sn = '2005013346';
		}
		else if (_id === "er") {
			sn = '2005005303';
		}
		else if (_id === "el") {
			sn = '2004039558';
		}
		document.getElementById('login_form').studentNo.value = sn;
	};
	
	window.onload = function () {
		document.body.className = 'has_js';
		SKEMO.browserSniffer(); // check if the browser is FF 3+
	}
</script>
<title>Log In</title>
</head>

<body>
<center>
<div id = "container">
 <div id = "top">&nbsp;
 </div>
 <div id = "nav" class = "filler">
  <div id = "logo" style="float:left"><img src="images/skemo_logo.png" /></div>
  <div id = "info"><span>&nbsp;<a href="about.static.htm">About</a></span></div>
 &nbsp;
  <div id = "nones" style="padding:10px 0 30px 0;">&nbsp;</div>
 </div><!--END OF NAV-->
 <div id = "content" class="filler">
   <!--BEGIN EDIT HERE-->
   
   <form method="post" id='login_form'>
    <div id = "window" align="center">
	
			<?php
		  		echo "<center><font color = \"red\" face = \"verdana\" size =\"2\">";
				echo $msg;
				echo "</center></font>"; 		
		   	?>
			<br/>
 
		<div id = "login_window" align="center" >
		<span id = "login_id" style="color:#960014;">&nbsp;Student ID</span>
		 <div style="margin-top:15px;"><span>
		   <input name="studentNo" type="text" maxlength="10" id='login_txtfield' />
		 </span></div>
		 <br />
		 <div id = "link">
		  <input type="submit" name = "skemo_login" value = "Sign In" />
		 </div>
		</div>
		
		<!-- login sample data (03-20-09) -->
		
		<div id="sample_label">Sample Data:</div>
		<div id="sample_data_container">
			<span id="sample_info">
				If your not an IT/CS student of the USJ-R and you want to test this application.
				Then try logging in using this student numbers:
			</span>
			<ul id="sample_data">
				<li id="ed" onmousedown="placeStudNumOnTxtField(this.id);">2005013346</li>
				<li id="er" onmousedown="placeStudNumOnTxtField(this.id);">2005005303</li>
				<li id="el" onmousedown="placeStudNumOnTxtField(this.id);">2004039558</li>
			</ul>
		</div>
	</div>
	</form>
  
   
    <!--END EDIT-->
   <div id = "nones" style=" padding-bottom:20px;">&nbsp;</div>
  </div><!--END OF CONTENT-->
  
 <div id = "bottom" class = "link">
  <span><a href="copyright.static.htm">Copyright</a> |</span>
  <span><a href="developer.static.htm">Developers</a> |</span>
  <span><a href="index.php">Skemo™</a></span>
 </div>
</div>
</center>
</body>
</html>
