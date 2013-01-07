<?php
session_start();
/**
 * this is the php script that creates a javascript session object.....
 */

$jsCode = '';
$jsCode .= 'var SESSION = {';
//$jsCode .= 'studentNo:"'.$_SESSION['studentNo'].'"';
//$jsCode .= '};';
if(isset($_SESSION)){
	$jsCode .= 'studentNo:"'.$_SESSION['studentNo'].'"';
	if(isset($_SESSION['preferences'])){
		$jsCode .= ',preferences:{';
			$jsCode .= 'mwf:{';
				$jsCode .= 'stime:"'.$_SESSION['preferences']['mwf']['stime'].'",';
				$jsCode .= 'etime:"'.$_SESSION['preferences']['mwf']['etime'].'",';
				$jsCode .= 'location:"'.$_SESSION['preferences']['mwf']['location'].'",';
				$jsCode .= 'sv:'.(float) $_SESSION['preferences']['mwf']['sv'].',';
				$jsCode .= 'ev:'.(float) $_SESSION['preferences']['mwf']['ev'].',';
				$jsCode .= 'mwfNoClass: "'.$_SESSION['preferences']['mwf']['mwfNoClass'].'"';
			$jsCode .= '},';
			$jsCode .= 'tth:{';
				$jsCode .= 'stime:"'.$_SESSION['preferences']['tth']['stime'].'",';
				$jsCode .= 'etime:"'.$_SESSION['preferences']['tth']['etime'].'",';
				$jsCode .= 'location:"'.$_SESSION['preferences']['tth']['location'].'",';
				$jsCode .= 'sv:'.(float) $_SESSION['preferences']['tth']['sv'].',';
				$jsCode .= 'ev:'.(float) $_SESSION['preferences']['tth']['ev'].',';
				$jsCode .= 'tthNoClass: "'.$_SESSION['preferences']['tth']['tthNoClass'].'"';
			$jsCode .= '}';
		$jsCode .= '}';
	}
	if(isset($_SESSION['subjectsChecked'])){
		$arrLength = count($_SESSION['subjectsChecked']);
		$cnt = 0;
		$jsCode .= ',subjectsChecked:[';
			foreach($_SESSION['subjectsChecked']as $subject){
				$jsCode .= '"'.$subject.'"';
				$cnt++;
				if($cnt != $arrLength){
					$jsCode .= ',';
				}
			}
		$jsCode .= ']';
	}
	
}
else{
	$jsCode = '// NO SESSION SET //';
}
$jsCode .= '};';

header("content-type: application/javascript");
echo $jsCode;
?>