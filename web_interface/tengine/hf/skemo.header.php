<?php
$template->setWebsiteName('skemo usj-r student schedule recommender');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $template->title; ?></title>
<head>
<link rel="shortcut icon" href="images/favicon_skemo.ico" />
<?php 
	
	// common css & js file paths AND YOUR UNIQUE ONES TOOO...... *__*
	$jsArray = array('js/mootools1.2.js','js/json2.js','js/helpers javascript toolkit/helpers.toolkit.js',
					 'js/skemo.js','js/fetchSessionForJs.js.php');
	$cssArray = array('css/main_style.css');	
	
	echo $template->uniqueCSS;
	echo Template::putCommonJSandCSS($jsArray,$cssArray);	
	echo $template->uniqueJS;
	echo Template::putOnloadActions();
?>
</head>

<body>
<center>
<div id = "container">
 <div id = "top">&nbsp;
 </div>
 <div id = "nav" class = "filler">
  <div id = "logo" style="float:left"><img src="images/skemo_logo.png"></div>
  <div id = "info"><span><a href="logout.php">Sign Out</a>&nbsp;|</span><span>&nbsp;<a href="about.php">About</a></span></div>
 &nbsp;
  <div id = "nones" style="padding:25px 0 30px 0;">&nbsp;</div>
 </div><!--END OF NAV-->
 <div id = "content" class="filler">
   <!--BEGIN EDIT HERE-->
   
   <div class = "link" align = "left" style="margin:0 0 7px 30px;"><a href="index.php"><b style="color:red">Home</b></a><span id="time"><img src="util/loader.gif" alt="" /></span></div>
    <div id = "window" align="center" >
		<!--<img src="images/ajax-loader.gif" width="200" height="200"/>-->
		<div id = "main_content">
		 <div id= "user_link" style=" height:20px;padding:2px;">
		  <div style="float:left">
		  
		  <?php
		  
		  	$student_id = $_SESSION['studentNo'];

			$query = "SELECT * from student_table where student_id='$student_id'";
			$result = mysql_query($query) or die("ERROR ON QUERY :".$query." | ".mysql_error());
			
			echo "Hi ";
		    echo "<font color=\"#009900\">";
		    while ($row = mysql_fetch_array($result)) 
			{	
		    	echo $row['last_name'].", ".$row['first_name']." ".$row['mid_name'];
		
	        }	
	
	
		    echo "</font>";
		  
		  ?>
		  
		  </div>
		  <div id = "test" style="float:right">
		    <span><a href="mySkemo.php"><img src="images/icons/mySkemo.png"></a></span>
		    <span><a href="skemoNow.php"><img src="images/icons/SkemoNow.png"></a></span>
		    <span><a href="javascript:popBrowsePage();"><img src="images/icons/SkemoBrowse.png"></a></span>
		    <span><a href="myProfile.php"><img src="images/icons/ViewProfile.png"></a></span>
		  </div>
		  <div style="clear:right" align="left">ID # : <span style="color:#009900">
		  <?php
		  
		  echo $_SESSION['studentNo'];
		  
		  ?>
		  
		  </span></div>
		 </div><!--END OF USER_LINK-->
		</div><!--END OF MAIN_CONTENT-->
		
		<div id = "edit_pane" style="width:785px;margin:-30px 0 0 0; font-size:12px;">
		<p>&nbsp;</p>
<!-- CONTENT AREA STARTS HERE (template engine)-->

