<?php
/**
 * dependencies: 
 *  - util/db_conn.inc.php
 *  - util/helpers.inc.php
 *
 */
class SKEMO_Preference_Handler{
	
	private $studentNo = '';
	
	public function __construct($_studentNo){
		$this->studentNo = $_studentNo;
	}
	public function initStudentPref(){
		if(empty($this->studentNo)){
			die("No student number set !!!");
		}
		/**
		 * if the preference session is not set try to set it by fetching the values from the db
		 */
		if(!isset($_SESSION['preferences'])){
			$_SESSION['preferences'] = array();
		}	
			$query = "SELECT * FROM `student_preferences` WHERE `student_no` = '$this->studentNo'";
			$result = mysql_query($query) or die(mysql_error_msg($query));
			if(mysql_num_rows($result) > 0){
				$studPref = mysql_fetch_object($result);
				
				$_SESSION['preferences']['mwf']['stime'] = $studPref->mwfSTime;
				$_SESSION['preferences']['mwf']['etime'] = $studPref->mwfETime;
				$_SESSION['preferences']['mwf']['location'] = strtoupper($studPref->mwfLocation);
				$_SESSION['preferences']['mwf']['sv'] = $studPref->mwfSVacant;
				$_SESSION['preferences']['mwf']['ev'] = $studPref->mwfEVacant;
				$_SESSION['preferences']['mwf']['mwfNoClass'] = $studPref->mwfNoClass;
				
				$_SESSION['preferences']['tth']['stime'] = $studPref->tthSTime;
				$_SESSION['preferences']['tth']['etime'] = $studPref->tthETime;
				$_SESSION['preferences']['tth']['location'] = strtoupper($studPref->tthLocation);
				$_SESSION['preferences']['tth']['sv'] = $studPref->tthSVacant;
				$_SESSION['preferences']['tth']['ev'] = $studPref->tthEVacant;
				$_SESSION['preferences']['tth']['tthNoClass'] = $studPref->tthNoClass;
			}
			else{
				/**
				 * this code will only execute for first time users.....their student number will be saved in the db along
				 * with the default values.....
				 */
				$query = "INSERT INTO `student_preferences`(`student_no`) VALUES('".$this->studentNo."')";
				mysql_query($query) or die(mysql_error_msg($query));
				$this->initStudentPref();
				
			}
	}
	public function updateStudentPref($_pref){
		$query = sprintf("UPDATE `student_preferences` SET `mwfSTime` = '%s',`mwfETime`='%s',`mwfLocation` = '%s',`tthSTime` = '%s',`tthETime`='%s',`tthLocation` = '%s',`mwfSVacant` = %f,`mwfEVacant` = %f,`tthSVacant` = %f,`tthEVacant` = %f, `mwfNoClass` = '%s' , `tthNoClass` = '%s' WHERE `student_no` = '%s'"
						
						,$_pref['mwfSTime'],
						$_pref['mwfETime'],
						$_pref['mwfLocation'],
						$_pref['tthSTime'],
						$_pref['tthETime'],
						$_pref['tthLocation'],
						$_pref['mwfSVacant'],
						$_pref['mwfEVacant'],
						$_pref['tthSVacant'],
						$_pref['tthEVacant'],
						
						$_pref['mwfClass'],
						$_pref['tthClass'],
						
						$this->studentNo);
						
		if(mysql_query($query)){
				$_SESSION['preferences']['mwf']['stime'] = $_pref['mwfSTime'];
				$_SESSION['preferences']['mwf']['etime'] = $_pref['mwfETime'];
				$_SESSION['preferences']['mwf']['location'] = $_pref['mwfLocation'];
				$_SESSION['preferences']['mwf']['sv'] = $_pref['mwfSVacant'];
				$_SESSION['preferences']['mwf']['ev'] = $_pref['mwfEVacant'];
				$_SESSION['preferences']['mwf']['mwfNoClass'] = $_pref['mwfClass'];
				
				$_SESSION['preferences']['tth']['stime'] = $_pref['tthSTime'];
				$_SESSION['preferences']['tth']['etime'] = $_pref['tthETime'];
				$_SESSION['preferences']['tth']['location'] = $_pref['tthLocation'];
				$_SESSION['preferences']['tth']['sv'] = $_pref['tthSVacant'];
				$_SESSION['preferences']['tth']['ev'] = $_pref['tthEVacant'];
				$_SESSION['preferences']['tth']['tthNoClass'] = $_pref['tthClass'];
				return true;
		}
		else{
				return false;
		}
	}
	public function getStudentPreferences() {
		$pref;
		$query = "SELECT * FROM `student_preferences` WHERE `student_no` = '$this->studentNo'";
		$result = mysql_query($query) or die(mysql_error_msg($query));
		if(mysql_num_rows($result) > 0){
			$studPref = mysql_fetch_object($result);
				
			$pref['mwf']['stime'] = $studPref->mwfSTime;
			$pref['mwf']['etime'] = $studPref->mwfETime;
			$pref['mwf']['location'] = strtoupper($studPref->mwfLocation);
			$pref['mwf']['sv'] = $studPref->mwfSVacant;
			$pref['mwf']['ev'] = $studPref->mwfEVacant;
			$pref['mwf']['mwfNoClass'] = $studPref->mwfNoClass;
				
			$pref['tth']['stime'] = $studPref->tthSTime;
			$pref['tth']['etime'] = $studPref->tthETime;
			$pref['tth']['location'] = strtoupper($studPref->tthLocation);
			$pref['tth']['sv'] = $studPref->tthSVacant;
			$pref['tth']['ev'] = $studPref->tthEVacant;
			$pref['tth']['tthNoClass'] = $studPref->tthNoClass;
			return $pref;
				
		}
		else {
			return false;
		}
		
	}
	public function getStudentNo(){
		return $this->studentNo;
	}
	
}
?>