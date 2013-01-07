<?php
session_start();
include('util/location.config.php');

require UTIL_DB_CONN;
require UTIL_HELPERS;
require CLASS_SKEMO_TIME_HANDLER;
require CLASS_SKEMO_SUBJECT_DESC;
require UTIL_TIME_CONVERTERS;
require MODEL_SKEMO_SCHEDULE_MASTER;

include('util/security.php');
if(!isset($_SESSION['subjectsChecked'])){
	$_SESSION['subjectsChecked'] = array();
}

$sNo = $_SESSION['studentNo'];

$pref = $_SESSION['preferences'];
if ($pref['mwf']['mwfNoClass'] == 'NOCLASS') {
	$pref['mwf']['sv'] = 1;
	$pref['mwf']['ev'] = 15;
}
if ($pref['tth']['tthNoClass'] == 'NOCLASS') {
	$pref['tth']['sv'] = 1;
	$pref['tth']['ev'] = 14.5;
}


$maxUnits = 30;

// -- checking of the units / student load below... -- //
if (!isset($_POST['overload_indicator'])) {
	if (isset($_POST['total_units'])) {
		if ((int)$_POST['total_units'] > $maxUnits) {
			header('Location: skemoNow.php?error=Your unit load exceeded the limit');
		}
	}
}
// -- checking if any subjects where passed -- //
if (isset($_POST['subjects'])) {
	if ((is_array($_POST['subjects'])) && (count($_POST['subjects']) > 0)) {
		$subject = $_POST['subjects'];
		$_SESSION['subjectsChecked'] = $_POST['subjects'];
	}
	else {
		header('Location: skemoNow.php?error=No subjects where checked');
	}	
}
else {
	if ((is_array($_SESSION['subjectsChecked'])) && (count($_SESSION['subjectsChecked']) > 0)) {
		$subject = $_SESSION['subjectsChecked'];
	}
	else {
		header('Location: skemoNow.php?error=No subjects where checked');
	}
}

//$subject = array('BA 3','CMPE 210','CHEM 1 (lec/lab)','CMPE 527','CS 111','CS 19D','CS 414','ECON 1','EE 322');

//$pref = array();
//$pref['mwf']['stime'] = '07:00am';
//$pref['mwf']['etime'] = '09:00pm';
//$pref['mwf']['location'] = 'BM';
//$pref['mwf']['sv'] = 0.0;
//$pref['mwf']['ev'] = 0.0;
//
//$pref['tth']['stime'] = '07:00am';
//$pref['tth']['etime'] = '08:30pm';
//$pref['tth']['location'] = 'BM';
//$pref['tth']['sv'] = 0.0;
//$pref['tth']['ev'] = 0.0;
//$sNo = '2004039558';

$scheduler = new SKEMO_Schedule_Master($sNo,$subject,$pref);

// distribute the data //
$yourSkemoSchedules = $scheduler->skemoEngine();
$_SESSION['yourSkemoSchedules'] = $yourSkemoSchedules; // for easier passing of the sked 2 other pages...

$plottedSubjects = $scheduler->getPlotted();
$unplottedSubjects = $scheduler->getUnplottedSubjects();

$description = new SKEMO_Subject_Desc(); // a class used to fetch the description of a subject



?>