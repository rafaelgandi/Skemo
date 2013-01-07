<?php
/**
 * a class that gets the description of a subject
 * 
 * DATABASE TABLES:
 * - prospectus
 *
 * dependencies:
 *  - util/db_conn.inc.php
 *  - util/helpers.inc.php
 */
class SKEMO_Subject_Desc{
	
	private $table  = 'subjects_table';
	
	public function __construct(){
		// none //
	}
	public function getDescription($_subjectNum){
		$sn = strtoupper($_subjectNum);
		$returnDesc = '';
		//$query = sprintf("SELECT `desc` FROM `%s` WHERE `course_no` = '%s' LIMIT 0,1",$this->table,$sn);
		// TODO S2
		$query = sprintf("SELECT `title` AS `desc` FROM `%s` WHERE `subject_no` = '%s' LIMIT 0,1",$this->table,$sn);
		$result = mysql_query($query) or die(mysql_error_msg($query)); 
		if(mysql_num_rows($result) > 0){
			$desc = mysql_fetch_object($result);
			$returnDesc = $desc->desc;
		}
		else{
			$returnDesc = 'NO DESCRIPTION';
		}
		mysql_free_result($result);
		return $returnDesc;
	}
}
?>