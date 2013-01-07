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



$result1 = mysql_query("select curriculum, course from student_table where student_id='$student_id'");
			
		while($row1 = mysql_fetch_array($result1))
		{
		if($row1['curriculum']==2008)
			$curr = 2008 ;
		else
			$curr = 2005;

		if($row1['course'] == '11BSIT')
			$dept = 'IT';
		else if ($row1['course'] == '11BSCS')
			$dept = 'CS';
		else if ($row1['course'] == '11CMPE')
			$dept = 'CMPE';
		else if ($row1['course'] == '11BSOA')
			$dept = 'OA';
		else if ($row1['course'] == '11BSOAMT')
			$dept = 'OAMT';
		}
		
		


switch($viewType)
{
	
	case 'taken':
	
	$store = array();

	$result = mysql_query("SELECT subject_no,offer_no,subject_id,grade from subjects_enrolled WHERE student_id = '$student_id'");

	while($row1 = mysql_fetch_row($result))
	{
		$result1 = mysql_query("SELECT subject_no from prospectus WHERE subject_no = '$row1[0]'");

   		if(mysql_num_rows($result1) == 0) //not found
    	{
		
			$result2 = mysql_query("SELECT subject_title from common_data WHERE offer_no = $row1[1] and 
				subject_id = $row1[2] limit 1;");   
	 
			$data = mysql_fetch_assoc($result2);
	  	
			$result3 = mysql_query("SELECT subject_no from prospectus WHERE subject_desc like '%$data[subject_title]%';");
	 
	 		$data = mysql_fetch_assoc($result3);

	  		$store[$data['subject_no']] = $row1[0];
		}
	
		else
		{
	 		$store[$row1['0']] = $row1[0];
		}

 	} 

			
	echo "<font face=\"verdana\" size=\"2\">";
	echo "<table width='562'>
		<tr align='center'>
		<td width='130' id = 'list_subj_td'>COURSE NO</td>
		<td width='300' id = 'list_subj_td'>COURSE DESCRIPTION</td>
		</tr><br/>";


	foreach($store as $key => $value)
	{

		$result = mysql_query("select subject_no, subject_desc from prospectus where subject_no = '$value'");
	
		if(mysql_num_rows($result)!=0)
		{
			while ($row = mysql_fetch_array($result)) 
			{
				
				echo "<tr>";
				echo "<td width='130'>".$row['subject_no']."</td>";
				echo "<td width='300'>".$row['subject_desc']."</td>";
				echo "</tr>";	
			}
		}
			
		else
		{
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
	
  		$courseType = substr($rowStud['course'],2,8);
		$store = array();// THIS WILL SERVED AS SUBJECTS_ENROLLED DATA ON THE LATER PART
		$dataStore = array();
		

		$sql = "SELECT subject_no,offer_no,subject_id,grade from subjects_enrolled WHERE student_id = '$student_id';";

		// 1) CHECK SUBJECT_ENROLLED
		$result0 = mysql_query($sql);
		
		while($row1 = mysql_fetch_row($result0))
		{
  			$result1 = mysql_query("SELECT subject_no from prospectus WHERE subject_no = '$row1[0]'");

   			if(mysql_num_rows($result1) == 0) //not found
    		{
				$result2 = mysql_query("SELECT subject_title from common_data WHERE offer_no = $row1[1] and subject_id = 
					$row1[2] limit 1;");   
	 
	  			
				$data = mysql_fetch_assoc($result2);
	  			$result3 = mysql_query("SELECT subject_no from prospectus WHERE subject_desc like '%$data[subject_title]%';");
	   
	   			$data = mysql_fetch_assoc($result3);

	  			$store[$data['subject_no']] = $row1[3];
			}
			else
			{
				$store[$row1['0']] = $row1[3];
			}

 		} 

		
		
		
		// 2) CHECK store[course_no] IN PROSPECTUS -->> GET COURSE_NO FROM PROSPECTUS NOT IN store[key];
 		$ctr = 0;
 
  		foreach($store as $key => $value)
		{
	 		$dataFilter .= "'".$key."',";   
    	}
	
		$sql = "select p.subject_no, p.subject_desc, pr.require
			from prospectus p, prospect_require pr
			WHERE curr_year = '$curr' and
			p.auth_code = pr.auth_code
			and department = 'IT' and p.subject_no not in(".substr($dataFilter,0,strlen($dataFilter) -1).")
			group by p.subject_no;";
	
		$result5 = mysql_query($sql);
	 
		echo "<font face=\"verdana\" size=\"2\">";
   		echo "<table width='562'><tr align='center'>
			 <td width='155' id = 'list_subj_td'>Course No</td>
			 <td width='450' id = 'list_subj_td'>Course Description</td>
			 <td width='147' id = 'list_subj_td'>Pre-Requisites</td>
			 </tr><br/>";
			
		while ($row = mysql_fetch_array($result5)) 
		{
			echo"<tr>";
			echo "<td width='155'>".$row['subject_no']."</td>";
			echo "<td width='450'>".$row['subject_desc']."</td>";
			echo "<td width='147'>".$row['require']."</td>";
			echo "</tr>";	
		}
			
		echo "</font></table><br /><hr width='330'>";
  	
   break;
 }


?>