<?php
/**
 * dependencies:
 *  - classes/SKEMO_Preference_Handler.class.php
 *  - util/db_conn.inc.php
 *  - util/helpers.inc.php
 * 
 * THIS AJAX REQUEST IS USED BY mySkemo.php PAGE.....
 * 
 * the codes below are used by the myskemo.js ajax request.....
 * 
 * this page stores the students preferences into the database, with the
 * use of the SKEMO_Preference_Handler class.
 */
if(isset($_POST['updatePref'])){
	session_start();
	require '../util/location.config.php';
	
	require '../'.CLASS_SKEMO_PREFERENCE_HANDLER;
	include('../'.UTIL_DB_CONN);
	include('../'.UTIL_HELPERS);
	include('../'.UTIL_TIME_CONVERTERS);
	
	// --- decode the JSON strings into usable php arrays --- //
	$prefJSON = json_decode(stripslashes($_POST['pref']),true);
	
		$mwfST = timeEquivValue($prefJSON['mwfSTime']);
		$mwfET = timeEquivValue($prefJSON['mwfETime']);
		$tthST = timeEquivValue($prefJSON['tthSTime']);
		$tthET = timeEquivValue($prefJSON['tthETime']);
		$mwfSV = (int)$prefJSON['mwfSVacant'];
		$mwfEV = (int)$prefJSON['mwfEVacant'];
		$tthSV = (float)$prefJSON['tthSVacant'];
		$tthEV = (float)$prefJSON['tthEVacant'];

	if(($mwfST < $mwfET) && ($tthST < $tthET)){ // 2nd line of checking.
		if((($mwfSV < $mwfEV) || (($mwfSV === 0)&&($mwfEV === 0))) && (($tthSV < $tthEV) || (($tthSV === 0.0)&&($tthEV === 0.0)))){
			$studentPreferences = new SKEMO_Preference_Handler($_SESSION['studentNo']);
			if($studentPreferences->updateStudentPref($prefJSON)){
				echo "<center>Your preferences has successfully been set and saved. Time to <a href='skemoNow.php' class='time_to_plot' title='Choose subjects'>plot</a></center>";
				//print_r($prefJSON);
				//echo $_REQUEST['pref'];
				//var_dump($prefJSON);
			}
			else{
				echo "Sorry Problem in saving your preferences please try again.";
			}
		}
		else{
			echo "Sorry Problem in saving your preferences please review it and try again. ";
		}
	}
	else{
		echo "Sorry Problem in saving your preferences please review it and try again. ";
	}	
		
	mysql_close($dh); //GMRC....
}
?>