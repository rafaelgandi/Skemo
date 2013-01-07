<?php
session_start();

include('util/location.config.php');

include('util/security.php');
include(UTIL_DB_CONN);
include(UTIL_HELPERS);
include(UTIL_TIME_CONVERTERS);

require CLASS_SKEMO_PREFERENCE_HANDLER;
$studentPreferences = new SKEMO_Preference_Handler($_SESSION['studentNo']);
$studentPreferences->initStudentPref(); // /!\ VERY IMPORTANT TO SET FIRST... 

require 'tengine/template.class.php';
$template = new Template('tengine/hf/skemo.header.php','tengine/hf/skemo.footer.php');// make the template object
$myUniqueJS = array('js/myskemo.js');
$uniqueCSS = array('css/myskemo.css','css/loadingScreen.css','css/dialog.css');

$template->setTitle('mySkemo Settings'); // sets the title.......;-)
$template->pageConfig($uniqueCSS,$myUniqueJS);
//$template->pageConfig('css/myskemo.css');

function doSelectOptions($_day,$_timeMode,$_vacancy=false){
	$returnStr = '';
	$hour;
	$location = array('B'=>'Basak Campus','M'=>'Main Campus','BM'=>'Basak/Main Campus');
	$checkSession;
	$trueValue;
	
	/**
	 * MWF STARTS HERE....
	 */
	if(strtoupper($_day) == 'MWF'){
		
		if($_vacancy !== false){
				$checkSession_S = $_SESSION['preferences']['mwf']['sv'];
				$checkSession_E = $_SESSION['preferences']['mwf']['ev'];
				$returnStr = "<option value='0.0'>No Thanks</option>";
				$start = $checkSession_S;
				$end = $checkSession_E;
		}
		else{
				$checkSession_S = $_SESSION['preferences']['mwf']['stime'];
				$checkSession_E = $_SESSION['preferences']['mwf']['etime'];
				$start = timeEquivValue($checkSession_S);
				$end = timeEquivValue($checkSession_E);
		}
		
		// -- check if its a 07:00am or a No Thanks value(value of 0.0) -- //
		
		if(strtoupper($_timeMode) == 'START'){
						
			for($hour = 1;$hour <= 14;$hour++){
				if($_vacancy !== false){
					$trueValue = $hour;
				}
				else{
					$trueValue = valueEquivTime($hour);
				}
				///////////////////////////////////////
				if($trueValue == $checkSession_S){
					$returnStr .= "<option value='".$trueValue."' selected=''>".valueEquivTime($hour)."</option>";
				}
				else{
					$returnStr .= "<option value='".$trueValue."'>".valueEquivTime($hour)."</option>";
				}
			}
		}
		else if(strtoupper($_timeMode) == 'END'){
			for($hour = 1;$hour <= 15;$hour++){
				if($_vacancy !== false){
					$trueValue = $hour;
				}
				else{
					$trueValue = valueEquivTime($hour);
				}
				///////////////////////////////////////
				if($trueValue == $checkSession_E){
					$returnStr .= "<option value='".$trueValue."' selected=''>".valueEquivTime($hour)."</option>";
				}
				else{
					$returnStr .= "<option value='".$trueValue."'>".valueEquivTime($hour)."</option>";
				}
			}
		}
		else if(strtoupper($_timeMode) == 'LOC'){
			foreach($location as $key=>$value){
				if(strtoupper($key) == strtoupper($_SESSION['preferences']['mwf']['location'])){
					$returnStr .= "<option value='".strtoupper($key)."' selected=''>".$value."</option>";
				}
				else{
					$returnStr .= "<option value='".strtoupper($key)."'>".$value."</option>";
				}
			}
		}
		
	}
	/**
	 * TTH STARTS HERE....
	 */
	elseif(strtoupper($_day) == 'TTH'){
		
		if($_vacancy !== false){
				$checkSession_S = $_SESSION['preferences']['tth']['sv'];
				$checkSession_E = $_SESSION['preferences']['tth']['ev'];
				$returnStr = "<option value='0.0'>No Thanks</option>";
				$start = $checkSession_S;
				$end = $checkSession_E;
		}
		else{
				$checkSession_S = $_SESSION['preferences']['tth']['stime'];
				$checkSession_E = $_SESSION['preferences']['tth']['etime'];
				$start = timeEquivValue($checkSession_S);
				$end = timeEquivValue($checkSession_E);
		}
		
		// -- check if its a 07:00am or a No Thanks value(value of 0.0) -- //		
		if(strtoupper($_timeMode) == 'START'){
			for($hour = 1;$hour <= 13;$hour+=1.5){				
				if($_vacancy !== false){
					$trueValue = $hour;
				}
				else{
					$trueValue = valueEquivTime($hour);
				}
				///////////////////////////////////////////
				if($trueValue == $checkSession_S){
					$returnStr .= "<option value='".$trueValue."' selected=''>".valueEquivTime($hour)."</option>";
				}
				else{
					$returnStr .= "<option value='".$trueValue."'>".valueEquivTime($hour)."</option>";
				}
			}
		}
		else if(strtoupper($_timeMode) == 'END'){
			for($hour = 1;$hour <= 14.5;$hour+=1.5){				
				if($_vacancy !== false){
					$trueValue = $hour;
				}
				else{
					$trueValue = valueEquivTime($hour);
				}
				///////////////////////////////////////////
				if($trueValue == $checkSession_E){
					$returnStr .= "<option value='".$trueValue."' selected=''>".valueEquivTime($hour)."</option>";
				}
				else{
					$returnStr .= "<option value='".$trueValue."'>".valueEquivTime($hour)."</option>";
				}
			}
		}
		else if(strtoupper($_timeMode) == 'LOC'){
			foreach($location as $key=>$value){
				if(strtoupper($key) == strtoupper($_SESSION['preferences']['tth']['location'])){
					$returnStr .= "<option value='".strtoupper($key)."' selected=''>".$value."</option>";
				}
				else{
					$returnStr .= "<option value='".strtoupper($key)."'>".$value."</option>";
				}
			}
		}
		
	}
	
	return $returnStr;
}
include($template->headerInclude); // include the header file

include(UTIL_DIALOG_MARKUP);
include(UTIL_LOADING_SCREEN_MARKUP);

?>
<div id = "edit_paneHdr">Skemo™ Settings</div><br />
<div id = 'edit_paneHdrSub'>Set-up your <b>preferences</b> for later use</div><br />
<div id="myskemo_countainer">

<div id="pref_container">
  <div id = 'a'>
  <div id = "edit_paneHdr" style="color:#FF6633">
  <span><a href="<?php echo INDEX; ?>" class="help_info " title="What do I do next?"><img src="<?php echo IMG_SKEMO_FAVICON; ?>" style="border: 0px; margin-right: 3px;" alt="help"/>What do I do?</a></span>
	&nbsp;&nbsp;
	&nbsp;&nbsp;
	MWF
  </div>
  <div id = 'a'>
  <div id="error_msg">
  	Oh no... something is wrong with your preferences.
  	 Please check if all your preferences make sense.
  </div>
  <table width="520" border="0" cellpadding="7" cellspacing="2">
  <tr align="center">
   <td>&nbsp;</td>
   <td>Start Time</td>
   <td>End Time</td>
   <td>Location</td>
  </tr>
  <tr>
    <td width="111">Class Hours</td>
    <td width="41">
	<select name="mwfBEGINTIME" id="_mwfSTime" title="Please choose the time you would like your classes to start on MWF">
	   <?php
	   echo doSelectOptions('MWF','START');
	   ?>
	  </select>
	 </td>
    <td width="66">
	<select name="mwfENDTIME" id="_mwfETime" title="Please choose the time you would like your classes to end on MWF">
	   <?php
	   echo doSelectOptions('MWF','END');
	   ?>
	  </select>
	</td>
    <td width="173">
	<select name="mwfLOCATION" id="_mwfLocation" class="locations" title="Please choose your preferred campus location on MWF">
	   <?php
	   echo doSelectOptions('MWF','LOC');
	   ?>
	 </select>
	</td>
  </tr>
  <tr>
  	<td colspan="4" id="mwfSETMsg" class="msg">
 		
  	</td>
  </tr>
  <tr>
    <td>Vacant Hours</td>
    <td>
	<select name="mwfSVacant" id="_mwfSVacant" class="vacant_selects" title="Start my vacant time at...">
	   	<?php
	   	echo doSelectOptions('MWF','START',true);
	   	?>
	  </select>
	</td>
    <td>
	<select name="mwfEVacant" id="_mwfEVacant" class="vacant_selects" title="End my vacant time at...">
	   	<?php
	   	echo doSelectOptions('MWF','END',true);
	   	?>
	  </select>
	</td>
    <td>
	<!-- new 02-27-09 -->
	<input type="checkbox" id="no_class_mwf" name="no_class_mwf" value="MWF_NO_CLASS" />
	No class
	</td>
  </tr>
  <tr>
  	<td colspan="4" id="mwfSEVMsg" class="msg">
 		
  	</td>
  </tr>
</table>

  </div><!--END OF a-->
  <br/>
  <div id = "edit_paneHdr" style="color: #003399">
	&nbsp;&nbsp;
	&nbsp;&nbsp;
	TTH
  </div>
  <div id = 'a'>
   <table width="520" border="0" cellpadding="7" cellspacing="2">
  <tr align="center">
   <td>&nbsp;</td>
   <td>Start Time</td>
   <td>End Time</td>
   <td>Location</td>
  </tr>
  <tr>
    <td width="111">Class Hours</td>
    <td width="41">
	<select name="tthBEGINTIME" id="_tthSTime" title="Please choose the time you would like your classes to start on TTH">
	   <?php
	   echo doSelectOptions('TTH','START');
	   ?>
	 </select>
	 </td>
    <td width="66">
	<select name="tthENDTIME" id="_tthETime" title="Please choose the time you would like your classes to end on TTH">
	   <?php
	   echo doSelectOptions('TTH','END');
	   ?>
	 </select>
	</td>
    <td width="173">
	<select name="tthLOCATION" id="_tthLocation" class="locations"  title="Please choose your preferred campus location on TTH">
	   <?php
	   echo doSelectOptions('TTH','LOC');
	   ?>
	</select>
	</td>
  </tr>
  <tr>
  	<td colspan="4" id="tthSETMsg" class="msg">
 		
  	</td>
  </tr>
  <tr>
    <td>Vacant Hours</td>
    <td>
	<select name="tthSVacant" id="_tthSVacant" class="vacant_selects" title="Start my vacant time at...">
	   <?php
	   echo doSelectOptions('TTH','START',true);
	   ?>
	</select>
	</td>
    <td>
	<select name="tthEVacant" id="_tthEVacant" class="vacant_selects" title="End my vacant time at...">
	   <?php
	   echo doSelectOptions('TTH','END',true);
	   ?>
	</select>
	</td>
    <td>
	<!-- new 02-27-09 -->
	<input type="checkbox" id="no_class_tth" name="no_class_tth" value="TTH_NO_CLASS" />
	No class
	</td>
  </tr>
  <tr>
  	<td colspan="4" id="tthSEVMsg" class="msg">
 		
  	</td>
  </tr>
</table>
<p>&nbsp;</p>
</div>
<button name="pref_submit" id="savePref">Save Preferences</button>
<div id="error_message_below"></div>
  </div><!--END OF a--> 
</div>

</div><!--END OF myskemo_container -->
		
<?php
include($template->footerInclude);
?>