<?php

require 'pager.class.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Pager class Test</title>	
	<script language="javascript">
	/* <![CDATA[ */
	
	 function $(id){
	  return document.getElementById(id);
	 }
	 
	/* ]]> */
	</script>
	
	<style>
	 body{font-family: verdana;}
	 .nav{	 
	    color: red;
	    font: 12px verdana;
	    text-decoration: none;	 
	 }
	 .nav a{text-decoration: none;}
	</style>
	<noscript>THIS WEBSITE REQUIRES THAT JAVASCRIPT SHOULD BE ENABLED</noscript>
</head>
<body>
<?php
$dh = mysql_connect('localhost','root','') or die("UNABLE TO CONNECT OT MySQL!");
mysql_select_db('shop',$dh) or die("UNABLE TO FIND THE DATABASE!");

$start = 0;
if(isset($_REQUEST['pgStart'])){
	$start = (int)$_REQUEST['pgStart'];
}
$pager = new Pager('cap',10,$start);

if($pager->getTotalFields() > 0){
	
	$start = $pager->getStart();
	$numPerPage = $pager->getNumPerPage();
	
	$query = "SELECT * FROM `cap` LIMIT $start,$numPerPage";
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($result) > 0){
		
		while($data = mysql_fetch_object($result)){
			echo $data->description."<br />";
		}
		echo $pager->setPagerNavigator('nav');
		
	}
	else{
		
		 echo "EMPTY";
		
	}
	
}

?>
<!-- Author: eL rafA gAndi -->   
</body>
</html>