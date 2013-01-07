<?php
/**
 * this script outputs the SkemoNow js object and SkemoNow.units js object
 */
require '../util/location.config.php';
require '../'.UTIL_HELPERS;
require '../'.UTIL_DB_CONN;

$unitsArr = array();
$jsUnits = 'var SkemoNow = {};'."\n";
$jsUnits .= 'SkemoNow.units = {';

$query = "SELECT `subject_no`, `units` FROM `prospectus`";
$result = mysql_query($query) or die(mysql_error_msg($query)); 
if(mysql_num_rows($result) > 0){
	while ($unit = mysql_fetch_object($result)) {
		$unitsArr[] = '"'.$unit->subject_no.'":'.(int)$unit->units.''."\n";
	}
	mysql_free_result($result);
	$jsUnits .= implode(',',$unitsArr);
	$jsUnits .= '};';
}
else {
	$jsUnits .= '};';
}
header("Content-type: application/javascript");
echo $jsUnits;
?>