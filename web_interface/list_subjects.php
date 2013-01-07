<?php
session_start();

$host = "localhost";
$dbname="root";
$password="";
$database = "skemo";

$link = mysql_connect($host, $dbname, $password) or die('Could not connect: ' . mysql_error());
$selected_db = mysql_select_db($database, $link); 

$viewType = $_REQUEST['query'];
$student_id = $_SESSION['studentNo'];



switch($viewType)
{
	
	case 'taken':
	
	$store = array();
	$count = 0;

	$result = mysql_query("SELECT subject_no,offer_no,subject_id,grade from subjects_enrolled WHERE student_id = '$student_id'");

	
	while($row1 = mysql_fetch_row($result))
	{
		$result1 = mysql_query("SELECT subject_no from prospectus WHERE subject_no = '$row1[0]'");

   		if(mysql_num_rows($result1) == 0) //--not found
    	{
		
			$result2 = mysql_query("SELECT subject_title from common_data WHERE offer_no = $row1[1] and 
				subject_id = $row1[2] limit 1;");   
	 
			$data = mysql_fetch_assoc($result2);
	  	
			$result3 = mysql_query("SELECT subject_no from prospectus WHERE subject_desc like '%$data[subject_title]%';");
	 
	 		$data = mysql_fetch_assoc($result3);

	  		$store[$count] = $row1[0];
		}
	
		else
		{
	 		$store[$count] = $row1[0];
		}
		
		$count ++;

 	} 

			
	echo "<font face=\"verdana\" size=\"2\">";
	echo "<b>SUBJECTS ENROLLED</b><br/><br/>";
	echo "<table bgcolor = '#FFFF99' width='562' cellspacing='0'>
		<tr align='center' bgcolor='#FFCC66'>
		<td width='130' height='50' id = 'list_subj_td'>COURSE NUMBER</td>
		<td width='320' id = 'list_subj_td'>COURSE DESCRIPTION</td>
		</tr>";

//for display
	
	foreach($store as $key => $value)
	{

		//--get description in prospectus

		$result = mysql_query("select subject_no, subject_desc from prospectus where subject_no = '$value' group by subject_no");
	
		if(mysql_num_rows($result)!=0)
		{
			while ($row = mysql_fetch_array($result)) 
			{
				
				echo "<tr height='21'>";
				echo "<td width='130'>".$row['subject_no']."</td>";
				echo "<td width='300'>".$row['subject_desc']."</td>";
				echo "</tr>";	
			}
		}
			
		else
		{

			//--get description in common data			

			$result=mysql_query("select subject_no, subject_title from common_data where subject_no = '$value'
				group by subject_no");
			
			while ($row = mysql_fetch_array($result)) 
			{
				
				echo "<tr>";
				echo "<td width='130'>".$row['subject_no']."</td>";
				echo "<td width='300'>".$row['subject_title']."</td>";
				echo "</tr>";	
			}
		}
	}
 
   	break;

 	
	
	case 'nottaken':


	$resultStud = mysql_query("select curriculum,course from student_table WHERE student_id = '$student_id'");
			
	while($rowStud = mysql_fetch_array($resultStud))
	{
		$courseType = substr($rowStud['course'],4,8);
	
		if($row1['curriculum']==2008)
			$curr = 2008 ;
		else
			$curr = 2005;
	
	}


	$store = array();// THIS WILL SERVED AS SUBJECTS_ENROLLED DATA ON THE LATER PART
	$dataStore = array(); //not yet enrolled
	

	//**** 1) CHECK SUBJECT_ENROLLED --> CONVERSION STAGE
	
	$result0 = mysql_query("SELECT subject_no,offer_no,subject_id,grade from subjects_enrolled WHERE student_id = '$student_id ';");
	
	
	while($row1 = mysql_fetch_row($result0))
{
	
//checking subjects enrolled in prospectus

	$result1 = mysql_query("SELECT subject_no from prospectus WHERE subject_no = '$row1[0]' AND department = '$courseType'");
  
  	$cmp_result = strncmp($row1[0],'CS 19',5); // COMPARE FOR ANY IT ELECTS --returns 0 if string1 and 2 are equal

   	if($cmp_result == 0)
	{
		if($row1['0'] == 'CS 19D')
		{ 
			$row1['0'] = $courseType.' ELEC 1';
			$store[$row1['0']] = $row1[3];	//get grade of a particular subject
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
	
	else if(mysql_num_rows($result1) > 0)
	{
 		$store[$row1['0']] = $row1[3];
	}
	
   	elseif(mysql_num_rows($result1) == 0) 	//--not found -> DATA CONTRASTING FROM COMMOM DATA
    	{
		$result2 = mysql_query("SELECT subject_title from common_data WHERE offer_no = $row1[1] and 
			subject_id = $row1[2] limit 1;");    //get description
		$data1 = mysql_fetch_assoc($result2);
  
		//--compare common data description to prospectus description

  		$result3 = mysql_query("SELECT subject_no from prospectus WHERE subject_desc like '%$data1[subject_title]%' AND
			department = '$courseType';");
		$data2 = mysql_fetch_assoc($result3);
     
  		$store[$data2['subject_no']] = $row1[3];
	}
	
	
	
 } // END OF WHILE


//**** 2) CHECK store[course_no] IN PROSPECTUS -->> GET COURSE_NO FROM PROSPECTUS NOT IN store[key];

$ctr = 0;
 
foreach($store as $key => $value)
{
	$dataFilter .= "'".$key."',";   
}

//---getting subjects in prospectus that is not yet enrolled---
	

	$sql = "select p.subject_no, p.subject_desc
			from prospectus p, prospect_require pr
			WHERE curr_year = '$curr' and
			p.auth_code = pr.auth_code
			and p.department = '$courseType' and p.subject_no not in(".substr($dataFilter,0,strlen($dataFilter) -1).")
			group by p.subject_no;";
	
	$result5 = mysql_query($sql);
	

	
	echo "<font face=\"verdana\" size=\"2\">";
	echo "<b>SUBJECTS NOT YET ENROLLED</b><br/><br/>";
	echo "<table bgcolor = '#FFFF99' width='562' cellspacing = '0'>
		<tr align='center' bgcolor='#FFCC66'>
		<td width='130' height='50' id = 'list_subj_td'>COURSE NUMBER</td>
		<td width='320' id = 'list_subj_td'>COURSE DESCRIPTION</td>
		</tr>";
			
		while ($row = mysql_fetch_array($result5)) 
		{
			
			echo "<tr height='21'>";
			echo "<td width='130' >".$row['subject_no']."</td>";
			echo "<td width='300'>".$row['subject_desc']."</td>";
			echo "</tr>";
			
		}
							
		echo "</font></table><br /><hr width='330'>";
  	
   break;
 }


?>