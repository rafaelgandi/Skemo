<?php
/**
 * This is the model class that plots the schedule for the student ^_____^
 * 
 * dependencies:
 * -------------
 * - util/helpers.inc.php
 * - util/db_conn.inc.php
 * - classes/SKEMO_TimeHandler.class.php 
 * 
 * session variables used INTERNALLY:
 * ---------------------------------
 * - subjectsChecked 
 * 
 * DATABASE TABLES:
 * ----------------
 * - subjects_table
 * - my_skemo_schedule
 * - student_preferences
 *
 * last updated:
 * -------------
 * - 03-06-09
 */
class SKEMO_Schedule_Master {
	
	private $subjects; // used to gather the subject numbers
	private $countPerSubNo; // count of subjects per day(mwf,tth,s)
	private $studentNum = ''; // the students number...duh
	private $howmany = array(); //used on priority....
	private $plottedSubjects = array(); //holds the subject numbers(i.e.IT 120) dat was already placed on the `my_skemo_schedule` db table...
	private $plottedSchedules = array(); //holds the schedules that has already presented and plotted by the class[the ids inside `my_skemo_schedule` db table] 
	private $timeBeforeMe = false; //the ending tym 0f the schedule before...
	private $preferences; //this is the property that stores the students preferences....	
	/**
	 * The skedule b4 reference array..,
	 * 
	 * keys: 
	 * -----
	 * 	- time
	 *  - loc
	 *  - status
	 *
	 * @var array
	 */
	private $skedBefore = array('status'=>'NO_VALUE',
								'loc'=>'NO_VALUE',
								'time'=>'NO_VALUE'); 
	
	// location (B,M,BM) recorder arrays // 
	private $mwfLocRecord = array(); //mwf
	private $tthLocRecord = array(); //tth
	private $satLocRecord = array(); //sat
	private $allLocRecord = array(); //all
	
	/**
	 * arrays containing the different combinations of days
	 * categorized by MWF, TTH, ALL(combination of mwf and tth).
	 */
	private $mwfDayCombinations = array('135','1','3','5','13','35','15');
	private $tthDayCombinations = array('24','2','4');
	private $allDayCombinations = array('1234','12345','1235','124','12','14','23','25','34','45');
	
	private $plottedTimesM = array();// holds the plotted times on m[e.i. 7:00am,8:00am]
	private $plottedTimesT = array();// holds the plotted times on t[e.i. 8:30am,10:00am]
	private $plottedTimesW = array();// holds the plotted times on w[e.i. 7:00am,8:00am]
	private $plottedTimesTH = array();// holds the plotted times on th[e.i. 8:30am,10:00am]
	private $plottedTimesF = array();// holds the plotted times on f[e.i. 7:00am,8:00am]
	private $plottedTimesSAT = array();// holds the plotted times on saturday
	
	/**
	 * needed 4 days that fall on a tuesday or thursday that has a time of ex 01:00pm-02:00pm
	 * or mwf that has times like 05:30pm-06:30pm...
	 *
	 * These arrays contain the list of the beginning tym minus 0.5
	 * The data inside these arrays are string tym formats [i.e. 01:30pm]
	 */
	private $mAlsoExcludeTimes = array(); //m
	private $tAlsoExcludeTimes = array(); //t
	private $wAlsoExcludeTimes = array(); //w
	private $thAlsoExcludeTimes = array(); //th
	private $fAlsoExcludeTimes = array(); //f
	private $satAlsoExcludeTimes = array(); //sat
	
	private $COLLECTOR = array(); //collector of the crawled schedules
	private $outputSchedule = array(); // this is where the final subjects that where plotted is going to be placed...		
	/**
	 * This is the SKEMO_Schedule_Master constructor. This is the method that handles the 
	 * initial assignment of data and calling of certain methods
	 * 
	 * process:
	 * --------
	 * -check the $subjectNo and $studentNo
	 * -set the $studentNum and $preferences private members
	 * -check for duplicate subject number by calling checkIfInListOfCheckedSubjects() method
	 * -set the $subjects private memeber array
	 * -then finally gather the schedule datas needed for schduling by
	 *  calling gatherAndInsertSubjectsToScheduleTable() method.
	 * 
	 * @param string $_studentNo
	 * @param string $_subjectNo
	 * @param array $_preferences
	 */
	public function __construct($_studentNo,$_subjectNo,$_preferences) {
		
		if((!is_array($_subjectNo) || (trim($_studentNo) == ''))){
			die('sorry class SKEMO_Schedule_Master needs a student number and an array of subject numbers as parameters');
		}
		if(!is_array($_preferences)){
			die('student preferences not set');
		}
		// /!\CAUTION: the methods bel0w should be in 0rder.... //
		$this->studentNum = $_studentNo;
		$this->preferences = $_preferences;
		$safeSubjectNo = $this->checkIfInListOfCheckedSubjects($_subjectNo);
		$this->subjects = array_unique($_subjectNo); // giv the $subjects private member a list of all the subject no passed to it (array)
		$this->gatherAndInsertSubjectsToScheduleTable($safeSubjectNo);
		
	}// end of constructor.....
	
	/**
	 * This method generates the equivalent number value of a string time
	 * passed to it.
	 * 
	 * used in:
	 * --------
	 *  - doPreferencesValidation()
	 *  - handlePlottingOfTimeRecords()
	 *  - doSecondPreferencesValidation()
	 *  - getSubjectSched() 
	 *
	 * @param string $_strTime
	 * @return int
	 */
	private function timeEquivValue($_strTime) { 
		$equivIntValue = 0;
		switch ($_strTime) {
			case '00:01am':
				$equivIntValue = 0;
			break;
			case '07:00am':
				$equivIntValue = 1;
			break;
			case '07:30am':
				$equivIntValue = 1.5;
			break;
			case '08:00am':
				$equivIntValue = 2;
			break;
			case '08:30am':
				$equivIntValue = 2.5;
			break;
			case '09:00am':
				$equivIntValue = 3;
			break;
			case '09:30am':
				$equivIntValue = 3.5;
			break;
			case '10:00am':
				$equivIntValue = 4;
			break;
			case '10:30am':
				$equivIntValue = 4.5;
			break;
			case '11:00am':
				$equivIntValue = 5;
			break;
			case '11:30am':
				$equivIntValue = 5.5;
			break;
			case '12:00am':
				$equivIntValue = 6;
			break;
			case '12:00pm':
				$equivIntValue = 6;
			break;
			case '12:30am':
				$equivIntValue = 6.5;
			break;
			case '12:30pm':
				$equivIntValue = 6.5;
			break;
			case '01:00am':
				$equivIntValue = 7;
			break;
			case '01:00pm':
				$equivIntValue = 7;
			break;
			case '13:00am':
				$equivIntValue = 7;
			break;
			case '13:00pm':
				$equivIntValue = 7;
			break;
			case '01:30pm':
				$equivIntValue = 7.5;
			break;
			case '13:30am':
				$equivIntValue = 7.5;
			break;
			case '13:30pm':
				$equivIntValue = 7.5;
			break;
			case '02:00pm':
				$equivIntValue = 8;
			break;
			case '14:00am':
				$equivIntValue = 8;
			break;
			case '14:00pm':
				$equivIntValue = 8;
			break;
			case '02:30pm':
				$equivIntValue = 8.5;
			break;
			case '14:30am':
				$equivIntValue = 8.5;
			break;
			case '14:30pm':
				$equivIntValue = 8.5;
			break;
			case '03:00pm':
				$equivIntValue = 9;
			break;
			case '15:00am':
				$equivIntValue = 9;
			break;
			case '15:00pm':
				$equivIntValue = 9;
			break;
			case '03:30pm':
				$equivIntValue = 9.5;
			break;
			case '15:30am':
				$equivIntValue = 9.5;
			break;
			case '15:30pm':
				$equivIntValue = 9.5;
			break;
			case '04:00pm':
				$equivIntValue = 10;
			break;
			case '04:30pm':
				$equivIntValue = 10.5;
			break;
			case '05:00pm':
				$equivIntValue = 11;
			break;
			case '05:30pm':
				$equivIntValue = 11.5;
			break;
			case '06:00pm':
				$equivIntValue = 12;
			break;
			case '06:30pm':
				$equivIntValue = 12.5;
			break;
			case '07:00pm':
				$equivIntValue = 13;
			break;
			case '07:30pm':
				$equivIntValue = 13.5;
			break;
			case '08:00pm':
				$equivIntValue = 14;
			break;
			case '08:30pm':
				$equivIntValue = 14.5;
			break;
			case '09:00pm':
				$equivIntValue = 15;
			break;
			case '00:06am':
				$equivIntValue = 0;
			break;
			default:
				$equivIntValue = 0;	
		}
		return $equivIntValue;
	}
	/**
	 * This method generates the equivalent string value of a integer time
	 * passed to it.
	 *
	 * used in:
	 * --------
	 *  - handlePlottingOfTimeRecords()
	 *  - check3SuccessiveSubjectsRule()
	 * 
	 * @param int $_intTimeValue
	 * @return string
	 */
	private function valueEquivTime($_intTimeValue) {	 	
		$equivStrTime = '';
		switch ($_intTimeValue) {
			case 0:
				$equivStrTime = '00:01am';
			break;
			case 1:
				$equivStrTime = '07:00am';
			break;
			case 1.5:
				$equivStrTime = '07:30am';
			break;
			case 2:
				$equivStrTime = '08:00am';
			break;
			case 2.5:
				$equivStrTime = '08:30am';
			break;
			case 3:
				$equivStrTime = '09:00am';
			break;
			case 3.5:
				$equivStrTime = '09:30am';
			break;
			case 4:
				$equivStrTime = '10:00am';
			break;
			case 4.5:
				$equivStrTime = '10:30am';
			break;
			case 5:
				$equivStrTime = '11:00am';
			break;
			case 5.5:
				$equivStrTime = '11:30am';
			break;
			case 6:
				$equivStrTime = '12:00pm';
			break;
			case 6.5:
				$equivStrTime = '12:30pm';
			break;
			case 7:
				$equivStrTime = '01:00pm';
			break;
			case 7.5:
				$equivStrTime = '01:30pm';
			break;
			case 8:
				$equivStrTime = '02:00pm';
			break;
			case 8.5:
				$equivStrTime = '02:30pm';
			break;
			case 9:
				$equivStrTime = '03:00pm';
			break;
			case 9.5:
				$equivStrTime = '03:30pm';
			break;
			case 10:
				$equivStrTime = '04:00pm';
			break;
			case 10.5:
				$equivStrTime = '04:30pm';
			break;
			case 11:
				$equivStrTime = '05:00pm';
			break;
			case 11.5:
				$equivStrTime = '05:30pm';
			break;
			case 12:
				$equivStrTime = '06:00pm';
			break;
			case 12.5:
				$equivStrTime = '06:30pm';
			break;
			case 13:
				$equivStrTime = '07:00pm';
			break;
			case 13.5:
				$equivStrTime = '07:30pm';
			break;
			case 14:
				$equivStrTime = '08:00pm';
			break;
			case 14.5:
				$equivStrTime = '08:30pm';
			break;
			case 15:
				$equivStrTime = '09:00pm';
			break;
			default:
				$equivStrTime = '00:01am';	
		}
		if($_intTimeValue < 0){
			$equivStrTime = '00:01am';
		}
		
		return $equivStrTime;
	}
	/**
	 * This method resolvs on what subjects to allow based on the students preferences
	 * on the campus location. Minly used in the checking of the prefrences. Returns
	 * true or false
	 * 
	 * process:
	 * --------
	 * -first it check if thelocation passed to it is inthe $basakRooms array
	 * -if it is inside the array than check the preferred location passed to
	 *  it as a parameter.
	 * -then return the appropriate boolian value depending on the preferences (location)
	 *  that was passed.
	 * -if not in array then check the regular expression if the room begins with a B
	 *  meaning it is located at the basak campus.
	 * -then again return the appropriate boolian value depending on the preferences (location)
	 *  that was passed.
	 * 
	 * used in:
	 * --------
	 *  - doPreferencesValidation()
	 *  - doSecondPreferencesValidation()
	 *
	 * @param string $_loc
	 * @param string $_prefLocation
	 * @return bool
	 */
	private function locationDecider($_loc,$_prefLocation) {
		$basakRooms = array('CAF','GM1','GM2','GM3','GM4','ORH','OG','OG1','OG2','OG3','FLD');
		$basakRoomsRegExp = "/^B[A-z0-9 ]{1,}$/i"; // basak rooms regular expression....
		if (in_array($_loc,$basakRooms)) {
			if ($_prefLocation == 'B') {
				return true;
			}
			else if ($_prefLocation == 'M') {
				return false;
			}
			else if($_prefLocation == 'BM') {
				return true;
			}
		}
		else {
			if (preg_match($basakRoomsRegExp,$_loc) != 0) {
				if ($_prefLocation == 'B') {
					return true;
				}
				else if ($_prefLocation == 'M') {
					return false;
				}
				else if ($_prefLocation == 'BM') {
					return true;
				}
			}
			else{
				if ($_prefLocation == 'B') {
					return false;
				}
				else if ($_prefLocation == 'M') {
					return true;
				}
				else if ($_prefLocation == 'BM') {
					return true;
				}
			}
		}
	}
	/**
	 * This is one of the two methods that checks the students prefernces
	 *
	 * process:
	 * --------
	 * -1st it checks if the parameter is an array and acts accordingly if its not (it dies!)
	 * -then it gathers the proper data from the private preferences array member that hold the 
	 *  students preferences data (stime,etime,location).
	 * -vacancy is not checked here.
	 * -checks for open times meaning when start and end tym equates to zero, it then returns
	 *  true, meaning it can pass.
	 * -note: that saturday and wierd days scheduled subjects confrom to mwf preferences.
	 * -it checks for the appropriate day code to check from [ie 135, 24 12, 12345]
	 * -checks if the current start tym is greaterthan or equal to the prefered start tym
	 *  and if the curret end tym is less than or equal to the prefered end tym else return
	 *  false.
	 * -it the checks the location preferences if false then return false.
	 * 
	 * used in:
	 * -------
	 *  - insertInto_my_skemo_schedule()
	 *  - 
	 * 
	 * 
	 * @param array $_schedule
	 * @return bool
	 */
	private function doPreferencesValidation($_schedule) {
		if (!is_array($_schedule)) {
			die('ERROR: parameter passed to doPreferencesValidation() method is not an array!!!');
		}
		// mwf
		$mwfPrefStartTime = $this->timeEquivValue($this->preferences['mwf']['stime']);
		$mwfPrefEndTime = $this->timeEquivValue($this->preferences['mwf']['etime']);
		$mwfLocation = strtoupper($this->preferences['mwf']['location']);
		
		//tth
		$tthPrefStartTime = $this->timeEquivValue($this->preferences['tth']['stime']);
		$tthPrefEndTime = $this->timeEquivValue($this->preferences['tth']['etime']);
		$tthLocation = strtoupper($this->preferences['tth']['location']);
		
		$day = $_schedule['day'];
		$time = new SKEMO_TimeHandler($_schedule['time']);
		$thisScheduleStartTime = $this->timeEquivValue($time->getStartTimeRaw()); //start time
		$thisScheduleEndTime = $this->timeEquivValue($time->getEndTimeRaw()); //end time
		
		if (($thisScheduleStartTime !== 0) && ($thisScheduleEndTime !== 0)) { // for OPEN time schedules.....
			/**
			 * saturday schedules conform to the mwf preferences...
			 */
			if (in_array($day,$this->mwfDayCombinations)) {
				if (($thisScheduleStartTime >= $mwfPrefStartTime) && ($thisScheduleEndTime <= $mwfPrefEndTime)) {
					if ($this->locationDecider($_schedule['room'],$mwfLocation)) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			}
			if (in_array($day,$this->tthDayCombinations)) {
				
				if (($thisScheduleStartTime >= $tthPrefStartTime) && ($thisScheduleEndTime <= $tthPrefEndTime)) {
					if ($this->locationDecider($_schedule['room'],$tthLocation)) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			}
			// kani nga condition pareha ra sa mwfs nga condition sa babaw.... ^//
			if (in_array($day,$this->allDayCombinations)) {
				if (($thisScheduleStartTime >= $mwfPrefStartTime) && ($thisScheduleEndTime <= $mwfPrefEndTime)) {
					if ($this->locationDecider($_schedule['room'],$mwfLocation)) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			}
		}
		else {
			// -- return true nalang deretso ky OPEN tyn man cya --//
			return true;
		}
	}
	/**
	 * this is the method that gathers the data needed from the `subjects_table` db table
	 * then calls a method to insert the data to the `my_skemo_schedule` db table
	 * 
	 * process:
	 * --------
	 * -first it retrives all the data needed by the scheduler from the `subjects_table`
	 *  [`offer_no`,`subject_no`,`time`,`day`,`room`]
	 * -then places it in a multi-dimensional array
	 * -then passes the arrar to the insertInto_my_skemo_schedule() method, to be filterd and
	 *  inserted in the `my_skemo_schedule` table.
	 * -then unsets the multidimensional array ---> GIGO konohay...
	 * 
	 * used in:
	 * ---------
	 * - the constructor method
	 *
	 * @param array $_subjectNum
	 */
	private function gatherAndInsertSubjectsToScheduleTable($_subjectNum) {
		$query = '';
		$subjectResults = array();
		$pref = $this->preferences;
		
		// gather the selected data from d db and place them in an array 4 storage
		// after dat insert the data stored in d array in d `my_skemo_schedule` db table....
		foreach ($_subjectNum as $value) {
			// TODO S1
			$query = sprintf("SELECT `offer_no`,`subject_no`,`time`,`day`,`room` FROM `subjects_table` WHERE `subject_no` = '%s' ORDER BY `time`",$value);
			$result = mysql_query($query) or die(mysql_error_msg($query)); 
			if (mysql_num_rows($result) > 0) {
				while ($subDetails = mysql_fetch_object($result)) {
					$subjectTime = new SKEMO_TimeHandler($subDetails->time);
										
						$subjectKey = $this->subjectKeyMaker($subDetails->offer_no,$subDetails->time,$subDetails->day);
						$subjectNo = strtoupper($subDetails->subject_no);
						
						$subjectResults[$subjectNo][$subjectKey]['offer_no'] = $subDetails->offer_no;
						$subjectResults[$subjectNo][$subjectKey]['subject_no'] = strtoupper($subDetails->subject_no);
						$subjectResults[$subjectNo][$subjectKey]['time'] = $subDetails->time;
						$subjectResults[$subjectNo][$subjectKey]['day'] = $subDetails->day;
						$subjectResults[$subjectNo][$subjectKey]['meridiem'] = $subjectTime->getStartMeridiem();
					    $subjectResults[$subjectNo][$subjectKey]['room'] = $subDetails->room;
					    
					    /**
					     * Sample:
					     * ------
					     * ...
					     * $subjectResults[IT 413][3023807:00am-08:30am24]['meridiem']
					     * $subjectResults[IT 413][3023807:00am-08:30am24]['room']
					     * 
					     */
				}
			}
			mysql_free_result($result);						
		}		
		//--------------------------------------------------------//
		
		$this->insertInto_my_skemo_schedule($subjectResults);
		unset($subjectResults); // clean the subjectResults memory.....tidy it up
	}
	/**
	 * This method check if the subject number passed to it is already in the subjectsChecked
	 * session variable, to prevent duplicate copies. This is used in the constructor to ready
	 * the paremeters to be passed to the gatherAndInsertSubjectsToScheduleTable() method.
	 *
	 * process:
	 * --------
	 * -lo00ps through the $_subjectNumber array and if its not found inside $_SESSION['subjectsChecked']
	 * -then place it in $_SESSION['subjectsChecked'] array to be returned; 
	 * 
	 * used in:
	 * --------
	 * - the constructor method
	 * 
	 * @param array $_subjectNumber
	 * @return array
	 */
	private function checkIfInListOfCheckedSubjects($_subjectNumber) {
		$safeSubjects; // newley ch0sen subjects....safe no duplicates
		if (count($_SESSION['subjectsChecked']) > 0) { 
			foreach($_subjectNumber as $subject){
				if(!in_array($subject,$_SESSION['subjectsChecked'])){
					$_SESSION['subjectsChecked'][] = $subject; // add the new one in the array....					
				}
			}
			$safeSubjects = $_SESSION['subjectsChecked'];
		}
		else {
			$safeSubjects = $_subjectNumber;
		}
		return $safeSubjects; 
	}
	/**
	 * This method is used to count how many schedule offering per day[ie 135,24,1235,13]
	 * in every subject then returns an array with keys [mwf, tth, s, howmany]. Dependent on the `my_skemo_schedule` db table.
	 *
	 * process:
	 * --------
	 * -selects the `my_skemo_schedule` table with diff queries depending on the format(mwf,tth,s,howmany)
	 * -places theresult in an array with a key with the corresponding format.
	 * -does 4 select statements
	 * 
	 * used in:
	 * --------
	 *  - insertInto_my_skemo_schedule()
	 * 
	 * @param string $_subjectNumber
	 * @return array
	 */
	private function countPerSubjectNo($_subjectNumber) {
		$countPerSubNumber = array();
		$subjectNumber = $_subjectNumber;
		
			/**
			 *  count the MWF schedules
			 * 
			 *  NOTE: /!\
			 *  ---------
			 *   the wierd days are selected here in the mwf count select query.
			 */
			$query = sprintf("SELECT COUNT(*) AS `count` FROM `my_skemo_schedule` WHERE `day` IN('135','1','3','5','13','15','35','12','14','23','25','34','45','12345','1234','1235','124') AND `subject_no` = '%s'",$subjectNumber);
			$result = mysql_query($query) or die(mysql_error_msg($query));
			$num = mysql_fetch_object($result);
			$countPerSubNumber['mwf'] = $num->count;
			mysql_free_result($result);
			/**
			 *  count the TTH schedules
			 */
			$query = sprintf("SELECT COUNT(*) AS `count` FROM `my_skemo_schedule` WHERE `day` IN('24','2','4','12','14','23','25','34','45','12345','1234','1235','124') AND `subject_no` = '%s'",$subjectNumber);
			$result = mysql_query($query) or die(mysql_error_msg($query));
			$num = mysql_fetch_object($result);
			$countPerSubNumber['tth'] = $num->count;
			mysql_free_result($result);
			/**
			 *  count the SATURDAY schedules
			 */
			$query = sprintf("SELECT COUNT(*) AS `count` FROM `my_skemo_schedule` WHERE `day` = '6' AND `subject_no` = '%s'",$subjectNumber);
			$result = mysql_query($query) or die(mysql_error_msg($query));
			$num = mysql_fetch_object($result);
			$countPerSubNumber['s'] = $num->count;
			mysql_free_result($result);
			/**
			 *  count ALL the schedules
			 */
			$query = sprintf("SELECT COUNT(*) AS `count` FROM `my_skemo_schedule` WHERE `subject_no` = '%s'",$subjectNumber);
			$result = mysql_query($query) or die(mysql_error_msg($query));
			$num = mysql_fetch_object($result);
			$countPerSubNumber['howmany'] = $num->count;
			mysql_free_result($result);
			
			// -- the mwf, tth and s are not really used in the shcheduling as of 02-25-09 -- //
			
		return $countPerSubNumber;
	}
	/**
	 * Used to make a unique key for the array used in the gatherAndInsertSubjectsToScheduleTable
	 * method.
	 * 
	 * process:
	 * --------
	 * -combines the subject id, a random number, the subject tym and the subject days code
	 * -and returns it as a unique string, which will be used as a key.
	 * 
	 * used in:
	 * --------
	 *  - gatherAndInsertSubjectsToScheduleTable()
	 * 
	 * @param int $_subjectId
	 * @param string $_subjectTime
	 * @param string $_subjectDays
	 * @return string (the unique key)
	 */
	private function subjectKeyMaker($_subjectId,$_subjectTime,$_subjectDays) { 
		$subKey = '';
		$subKey = $_subjectId . rand(1,5) . '-' . $_subjectTime . '-' . $_subjectDays;
		return $subKey;
	}
	/**
	 * Used to generate the proper letter acronym[ie MWF,TTH,S] for the days[ie 135, 24,6] passed to it
	 * 
	 * process:
	 * -------
	 * -a simple switch case conditioning.
	 * 
	 * used in:
	 * --------
	 * -insertInto_my_skemo_schedule()
     * - getSubjectSched()
	 * - check4OpenTimes()
	 * 
	 * @param string $_dayInStringNumbers
	 * @return string
	 */
	private function dayAnalyzer($_dayInStringNumbers) { 
		$convertedDay = '';
		switch($_dayInStringNumbers){
			case '135':
				$convertedDay = 'MWF';
			break;
			case '24':
				$convertedDay = 'TTH';
			break;
			case '6':
				$convertedDay = 'SAT';
			break;
			case '1':
				$convertedDay = 'M';
			break;
			case '2':
				$convertedDay = 'T';
			break;
			case '3':
				$convertedDay = 'W';
			break;
			case '4':
				$convertedDay = 'TH';
			break;
			case '5':
				$convertedDay = 'F';
			break;
			case '12':
				$convertedDay = 'MT';
			break;
			case '124':
				$convertedDay = 'MTTH';
			break;
			case '1234':
				$convertedDay = 'MTWTH';
			break;
			case '12345':
				$convertedDay = 'MTWTHF';
			break;
			case '1235':
				$convertedDay = 'MTWF';
			break;
			case '15':
				$convertedDay = 'MF';
			break;
			case '13':
				$convertedDay = 'MW';
			break;
			case '25':
				$convertedDay = 'TF';
			break;
			case '35':
				$convertedDay = 'WF';
			break;
			case '45':
				$convertedDay = 'THF';
			break;
			case '23':
				$convertedDay = 'TW';
			break;
			case '34':
				$convertedDay = 'WTH';
			break;
			case '7':
				$convertedDay = 'SUN';
			break;
			default:
				// do nothing...
		}
		return $convertedDay;
	}
	/**
	 * Used to filter and insert the proper schedule data into `my_skemo_schedule` db table.
	 * 
	 * process:
	 * --------
	 * -loop to the multidimensional array of schedules passed to it as a parameter
	 * -filter the individual schdule array according to the preferences (doPreferencesValidation()).
	 * -break out of the loop
	 * 
	 * -place the `howmany`, `hm_mwf`, `hm_tth`, `hm_s` values
	 * -then loop through the $subjects private member array
	 * -call the countPerSubjectNo() method on every subject number
	 * -update the `my_skemo_schedule` table with the results from countPerSubjectNo().
	 *
	 * used in:
	 * --------
	 *  - gatherAndInsertSubjectsToScheduleTable()
	 * 
	 * @param multidimensional array $_subjectResults
	 */
	private function insertInto_my_skemo_schedule($_subjectResults) {
		foreach ($_subjectResults as $subjectNo) {
			foreach ($subjectNo as $subjectKey) {	// ex. $subjectKey = 3023807:00am-08:30am24					
				if ($this->doPreferencesValidation($subjectKey)) {// filter the data according to the students preferences here....
					 $dayConvert = '';
					 $dayConvert = $this->dayAnalyzer($subjectKey['day']);				 
					 $query = sprintf("INSERT INTO `my_skemo_schedule`(`student_no`,`subject_id`,`subject_no`,`time`,`meridiem`,`day`,`room`) 
					 				   VALUES('%s','%s','%s','%s','%s','%s','%s')",
					 				   $this->studentNum,
					 				   $subjectKey['offer_no'],
					 				   $subjectKey['subject_no'],
					 				   $subjectKey['time'],
					 				   $subjectKey['meridiem'],
					 				   $subjectKey['day'],
					 				   $subjectKey['room']);
					 mysql_query($query) or die(mysql_error_msg($query));
					 
				}//	 
			}
		} 
		// -- adds in the value of the howmany, hm_mwf, ht_tth, hw_s fields in the `my_skemo_schedule` table -- //
		foreach($this->subjects as $subjectNum){
			$theCount = $this->countPerSubjectNo($subjectNum);
			$query = sprintf("UPDATE `my_skemo_schedule` SET `howmany`=%d, `hm_mwf`=%d, `hm_tth`=%d, `hm_s`=%d WHERE `student_no`='%s' AND `subject_no`='%s'",$theCount['howmany'],$theCount['mwf'],$theCount['tth'],$theCount['s'],$this->studentNum,$subjectNum);
			mysql_query($query) or die(mysql_error_msg($query));
		}
		
	}
	/**
	 * Glues the subject numbers into comma separated string,
	 * this is for use in the IN() mysql function in the decider query
	 *
	 * process:
	 * --------
	 * -lo00ps through the $plottedSubjects private member array
	 * -places a comma on every value
	 * -then returns the comma separated string
	 * 
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @return string
	 */
	private function plottedSubjectsGlue() { 
		$plotted = '';
		$plottedCnt = 0;
		foreach  ($this->plottedSubjects as $subj){
			$plotted .= "'".$subj."'";
			$plottedCnt++;
			if ($plottedCnt != count($this->plottedSubjects)) {
				$plotted .= ",";
			}
		}
		return $plotted;	
	}
	/** 
	 * Used to handle the data inconsistencies that the EDB database has on the following times:
	 * 12:00pm, 01:00pm, 02:00pm, 03:00pm and places the other values with the same meaning in their
	 * respected plotted subjects arrays.
	 * 
	 * process:
	 * --------
	 * -first checks if the $_dayNumber passed is one of the numbers to whatch for
	 * -if not do nothing.
	 * -if the $_dayNumber passed is one of the numbers watched for then place their other
	 *  equivalent values.
	 * -check if the $tym variable is not equal to an empty string
	 * -if not then proceed to placing of the equivalent data to the plotted times arrays
	 *  depending on the $_day variable passed to it.
	 *
	 * used in:
	 * --------
	 *  - handlePlottingOfTimeRecords()
	 * 
	 * @param string $_day
	 * @param int $_dayNumber
	 */		
	private function dataInconsistencyHandler($_day='135',$_dayNumber=6) {//due to how f**kd up the data are...i had 2 make dis meth0d....
		$tym = '';
		if (($_dayNumber == 6) || ($_dayNumber == 7) || ($_dayNumber == 8) || ($_dayNumber == 9)) { 
			if($_dayNumber == 6){
				$tym = '12:00am';
			}
			else if($_dayNumber == 7){
				$tym = '13:00pm';
			}
			else if($_dayNumber == 8){
				$tym = '14:00pm';
			}
			else if($_dayNumber == 9){
				$tym = '15:00pm';
			}
			
			/////////////////////////////////////////////////////
			if ($tym !== ''){	
				if ($_day == '135') {			
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesW[] = $tym;
					$this->plottedTimesF[] = $tym;				
				}
				else if ($_day == '1') {
					$this->plottedTimesM[] = $tym;
				}
				else if ($_day == '3') {
					$this->plottedTimesW[] = $tym;
				}
				else if ($_day == '5') {
					$this->plottedTimesF[] = $tym;
				}
				else if ($_day == '13') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesW[] = $tym;				
				}
				else if ($_day == '35') {
					$this->plottedTimesW[] = $tym;
					$this->plottedTimesF[] = $tym;				
				}
				else if ($_day == '15') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesF[] = $tym;			
				}
				else if ($_day == '24') {
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesTH[] = $tym;			
				}
				else if ($_day == '2') {
					$this->plottedTimesT[] = $tym;
				}
				else if ($_day == '4') {
					$this->plottedTimesTH[] = $tym;	
				}
				else if ($_day == '1234') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesW[] = $tym;
					$this->plottedTimesTH[] = $tym;			
				}
				else if ($_day == '12345') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesW[] = $tym;
					$this->plottedTimesTH[] = $tym;
					$this->plottedTimesF[] = $tym;
				}
				else if ($_day == '1235') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesW[] = $tym;
					$this->plottedTimesF[] = $tym;				
				}
				else if ($_day == '12') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesT[] = $tym;				
				}
				else if ($_day == '14') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesTH[] = $tym;			
				}
				else if ($_day == '23') {
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesW[] = $tym;				
				}
				else if ($_day == '25') {
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesF[] = $tym;				
				}
				else if ($_day == '34') {
					$this->plottedTimesW[] = $tym;
					$this->plottedTimesTH[] = $tym;				
				}
				else if ($_day == '45') {
					$this->plottedTimesTH[] = $tym;
					$this->plottedTimesF[] = $tym;				
				}
				else if ($_day == '124') {
					$this->plottedTimesM[] = $tym;
					$this->plottedTimesT[] = $tym;
					$this->plottedTimesTH[] = $tym;				
				}			
				else if ($_day == '6') {
					$this->plottedTimesSAT[] = $tym;	
				}
			}				
		}
	}
	/**
	 * This method handles the recording of the plotted times and places it in memory or in
	 * other words places in the plotted times arrays.
	 * 
	 * process:
	 * ---------
	 * -gets the int value of the current staring time and ending time.
	 * -first checks the $_day
	 * -loops through the times encompasing the current time {from the start tym to the tym less than 30mins of the ending time}
	 * -the loop runs by adding 30mins on the starting time and ending less than the ending tym.
	 * -while looping place the values(string times) inside the plotted times array accrding to the days passed
	 * -also call the  dataInconsistencyHandler() method to handle data inconsistencies.
	 * -place the beginig time minus 0.5/30mins in their respected alos exclude times array.
	 * -then eliminate the duplicate values in the plotted times arrays withe array_unique() function.
	 * 
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param string $_strTime
	 * @param string $_day
	 */
	private function handlePlottingOfTimeRecords($_strTime,$_day) {
		$time = new SKEMO_TimeHandler($_strTime);
		$beginingTime = $this->timeEquivValue($time->getStartTimeRaw());
		$endingTime = $this->timeEquivValue($time->getEndTimeRaw());
			
		$containerForTimesArray = array();
		// place this time to the list of plotted times arrays //
						
		if ((in_array($_day,$this->mwfDayCombinations)) || ($_day == '6')) { // for mwf plotting of times //			
			
			if ($_day === '6') { // for saturday plotting of times //
				for ($cnt=$beginingTime; $cnt<$endingTime; $cnt+=0.5) {
					if ($this->valueEquivTime($cnt) !== '00:01am') {
						$this->plottedTimesSAT[] = $this->valueEquivTime($cnt);
						$this->dataInconsistencyHandler($_day,$cnt);
						
					}
				}
				/**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [sat]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->satAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else {
				for ($cnt=$beginingTime; $cnt<$endingTime; $cnt+=0.5) {
					if ($this->valueEquivTime($cnt) !== '00:01am') {
						$containerForTimesArray[] = $this->valueEquivTime($cnt);
						$this->dataInconsistencyHandler($_day,$cnt); //
					}
				}
				if ($_day == '1') {
					$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. [m]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) { // incasog 07:00am ang $beginingTime...			
						$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
				}
				else if ($_day == '3') {
					$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. [w]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) {			
						$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
				}
				else if ($_day == '5') {
					$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. [f]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) {			
						$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
				}
				else if ($_day == '135') {
					$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
					$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
					$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. mwf]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) {			
						$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
						$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
						$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
					$this->mEndingTimes[] = $this->valueEquivTime($endingTime); //store ending tym...
					$this->wEndingTimes[] = $this->valueEquivTime($endingTime); //store ending tym...
					$this->fEndingTimes[] = $this->valueEquivTime($endingTime); //store ending tym...
				}
				else if ($_day == '13') {
					$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
					$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. [mw]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) {			
						$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
						$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
				}
				else if ($_day == '35') {
					$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
					$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. [wf]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) {		
						$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
						$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
				}
				else if ($_day == '15') {
					$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
					$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
					/**
					 * do an exclution of times equal to the beginning time in the parameter
					 * minus one. [mf]
					 */
				    $bTimeMinusOne = $beginingTime - 0.5;
					if ($bTimeMinusOne > 0.5) {			
						$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
						$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					}
				}				
			}	
		}		
		
		if (in_array($_day,$this->tthDayCombinations)) { // for tth plotting of times //			
			for ($cnt=$beginingTime; $cnt<$endingTime; $cnt+=0.5) {
				if ($this->valueEquivTime($cnt) !== '00:01am') {
					$containerForTimesArray[] = $this->valueEquivTime($cnt);
					$this->dataInconsistencyHandler($_day,$cnt);
				}
			}
			
			if ($_day == '2') {
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [t]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '4') {
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
				/**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [th]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '24') {
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
				/**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [tth]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {	
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);		
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
		}
		//this condition is for full week and wierd days plotted times array//
		//if(($_day == '1234') || ($_day == '12345') || ($_day == '1235') || ($_day == '12') || ($_day == '14') || ($_day == '23') || ($_day == '25') || ($_day == '34') || ($_day == '45') || ($_day == '124')){
		
		if (in_array($_day,$this->allDayCombinations)) { // ----- //
			for ($cnt=$beginingTime; $cnt<$endingTime; $cnt+=0.5) {
				if ($this->valueEquivTime($cnt) !== '00:01am') {
					$containerForTimesArray = $this->valueEquivTime($cnt);					
					$this->dataInconsistencyHandler($_day,$cnt);
				}
			}			
			if ($_day == '1234') {
				$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
				$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [mtwth]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '12345') {
				$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
				$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
				$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [mtwthf]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '12') {
				$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [mt]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '14') {
				$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [mth]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->tthAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '23') {
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
				$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [tw]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '25') {
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
				$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [tf]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '34') {
				$this->plottedTimesW = array_merge($this->plottedTimesW,$containerForTimesArray);
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [wth]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->wAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '45') {
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
				$this->plottedTimesF = array_merge($this->plottedTimesF,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [thf]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->fAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}
			else if ($_day == '124') {
				$this->plottedTimesM = array_merge($this->plottedTimesM,$containerForTimesArray);
				$this->plottedTimesT = array_merge($this->plottedTimesT,$containerForTimesArray);
				$this->plottedTimesTH = array_merge($this->plottedTimesTH,$containerForTimesArray);
			    /**
				 * do an exclution of times equal to the beginning time in the parameter
				 * minus one. [mtth]
				 */
				$bTimeMinusOne = $beginingTime - 0.5;
				if ($bTimeMinusOne > 0.5) {			
					$this->mAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->tAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
					$this->thAlsoExcludeTimes[] = $this->valueEquivTime($bTimeMinusOne);
				}
			}			
		}// ----- //
		$this->plottedTimesM = array_unique($this->plottedTimesM);
		$this->plottedTimesT = array_unique($this->plottedTimesT);
		$this->plottedTimesW = array_unique($this->plottedTimesW);
		$this->plottedTimesTH = array_unique($this->plottedTimesTH);
		$this->plottedTimesF = array_unique($this->plottedTimesF);
		$this->plottedTimesSAT = array_unique($this->plottedTimesSAT);		
	}
	/**
	 * This method does a second prefernce validation or filtering.
	 * 
	 * process:
	 * --------
	 * -basically it does the same thing as the first doPreferencesValidation() method
	 * -but with different parameters.
	 * -saturdays and wierd days scheds still conform to the mwf preferences.
	 *
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param string $_day
	 * @param string $_room
	 * @param int $_sTime
	 * @param int $_eTime
	 * @return bool
	 */
	private function doSecondPreferencesValidation($_day,$_room,$_sTime,$_eTime) {
		
		$mwfPrefStartTime = $this->timeEquivValue($this->preferences['mwf']['stime']);
		$mwfPrefEndTime = $this->timeEquivValue($this->preferences['mwf']['etime']);
		$mwfLocation = strtoupper($this->preferences['mwf']['location']);
		
		$tthPrefStartTime = $this->timeEquivValue($this->preferences['tth']['stime']);
		$tthPrefEndTime = $this->timeEquivValue($this->preferences['tth']['etime']);
		$tthLocation = strtoupper($this->preferences['tth']['location']);
		
		if (in_array($_day,$this->tthDayCombinations)) {
			
			if (($_sTime >= $tthPrefStartTime) && ($_eTime <= $tthPrefEndTime)) {
				if ($this->locationDecider($_room,$tthLocation)) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			if (($_sTime >= $mwfPrefStartTime) && ($_eTime <= $mwfPrefEndTime)) {
				if ($this->locationDecider($_room,$mwfLocation)) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
				
	}
	/**
	 * This method determines if the room passed to it as a parameter is
	 * in basak or in the main campus. Retrurns [B,M]
	 * 
	 * process:
	 * --------
	 * -first checks the array for gyms in basak
	 * -then checks if the room number starts with a B
	 * -then returns the location
	 *
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param unknown_type $_room
	 * @return unknown
	 */
	private function basakOrMain($_room) {
		$location;
		$basakRooms = array('CAF','GM1','GM2','GM3','GM4','ORH','OG','OG1','OG2','OG3','FLD');
		$basakRoomsRegExp = "/^B[A-z0-9 ]{1,}$/i";
		if (in_array($_room,$basakRooms)) {
			$location = 'B';
		}
		else {
			if (preg_match($basakRoomsRegExp,$_room) != 0) {
				$location = 'B';
			}
			else {
				$location = 'M';
			}
		}
		return $location;
	}
	/**
	 * This method checks if the current time does not violate the vacancy times set by
	 * the student.
	 * 
	 * process:
	 * --------
	 * -first checks the days to determine the proper preferences data to use [MWF / TTH]
	 * -if start tym is less than pref start vacancy or start tym is greater than or equal
	 *  to pref end tym.
	 * -and ending tym is less than or equal to pref starting vacant tym or
	 *  end tym is greater than pref end vacant tym. return true
	 *
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param int $_sTime
	 * @param int $_eTime
	 * @param string $_day
	 * @return bool
	 */
	private function checkVacancy($_sTime,$_eTime,$_day) {	
		$returnValue = false;
		$sv;
		$ev;
				
		if (($_day == '24')  || ($_day == '2') || ($_day == '4')) {	
			
			$sv = $this->preferences['tth']['sv'];
			$ev = $this->preferences['tth']['ev'];
		}
		else {
			$sv = $this->preferences['mwf']['sv'];
			$ev = $this->preferences['mwf']['ev'];
		}	
		// editted on 02-06-09
		// check the time passed if it is not in conflict with the students prefered vacant tym //
		if ((($_sTime < $sv) || ($_sTime >= $ev)) && (($_eTime <= $sv) || ($_eTime > $ev))) {
			$returnValue = true;
		}
		
		return $returnValue;
	}
	/**
	 * This method makes a location record code[ ie B|2|3 ], then places it in its
	 * respected location record arrays.
	 * 
	 * process:
	 * --------
	 * -first makes the code to be placed
	 * -then check the days for the right location recorder array.
	 * -place the code inside the array for memory....
	 * 
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param string $_day
	 * @param string $_loc
	 * @param int $_stime
	 * @param int $_etime
	 */
	private function locationRecorder($_day,$_loc,$_stime='NONE',$_etime='NONE') {

		$assembledValue = strtoupper($_loc)."|".$_stime."|".$_etime;
		
		if (in_array($_day,$this->mwfDayCombinations)) {
			$this->mwfLocRecord[] = $assembledValue;		
		}
		if (in_array($_day,$this->tthDayCombinations)) {
			$this->tthLocRecord[] = $assembledValue;
		}
		if ($_day == '6') {
			$this->satLocRecord[] = $assembledValue;
		}
		// -- record no matter what! -- //	
		$this->allLocRecord[] = $assembledValue;
		
	}
	/**
	 * Parses the location record code.
	 * 
	 * process:
	 * -------
	 * -explodes the code
	 * -then places the exploded values inside an array.
	 * -then returns that array.
	 * 
	 * used in:
	 * --------
	 * - checkTheLocation()
	 * 
	 * @param string $_locationAndTime
	 * @return array
	 */
	private function locationParser($_locationAndTime) {
		$returnArr = array();
		$Loc_Time = explode('|',$_locationAndTime);
		
		$returnArr['loc'] = strval(strtoupper($Loc_Time[0]));
		$returnArr['stime'] =  $Loc_Time[1];
		$returnArr['etime'] =  $Loc_Time[2];
		
		return $returnArr;
	}
	/**
	 * Checks if the current schedule has a succeeding subject before or after it that
	 * has a different location. This used to give studnets travel tym from basak to main 
	 * and vice versa.
	 * 
	 * process:
	 * --------
	 * - check the days for the proper location record to use.
	 * - loop through the valid location record array
	 *   then places the value on the locationParser() method
	 *	 check if the endtime found in the location record is equal to the 
	 * 	 starting time passed to it as a parameter and if the starttime found in the location record is equal to the 
	 * 	 end time passed to it as a parameter, also checks if the 
	 * 	 location found in the location record array is not equal to false,
	 * 
	 *	 if their is an element that satisfies the condition above then return a 
	 * 	 false value, bec the time before and the current time is to close
	 * 	 and the current time has a different location with the before time
	 * 	 (to be able to give a time allowance for the student to travel from M->B,M<-B)
	 * 
	 * used in:
	 * -------- 
	 *  - getSubjectSched()
	 *
	 * @param unknown_type $_day
	 * @param unknown_type $_loc
	 * @param unknown_type $_sTime
	 * @param unknown_type $_eTime
	 * @return unknown
	 */
	private function checkTheLocation($_day,$_loc,$_sTime,$_eTime) {
		
		$returnValue = true;
		$theArr;
		if (in_array($_day,$this->mwfDayCombinations)) {
			$theArr = $this->mwfLocRecord;			
		}
		if (in_array($_day,$this->tthDayCombinations)) {
			$theArr = $this->tthLocRecord;	
		}
		if ($_day == '6'){
			$theArr = $this->satLocRecord;	
		}
		if (in_array($_day,$this->allDayCombinations)) {
			$theArr = $this->allLocRecord;	
		}
		
		
		if (count($theArr) > 0) {
			foreach ($theArr as $strCode) {
				$loctime = $this->locationParser($strCode);
				if (($loctime['etime'] == $_sTime) && ($loctime['loc'] != $_loc)) {
					$returnValue = false;
					break;
				}
				if (($loctime['stime'] == $_eTime) && ($loctime['loc'] != $_loc)) {
					$returnValue = false;
					break;
				}
			}
		}
		else { // should happen only once..when the scheduler first starts recording //
				$returnValue = true;
		}
						
		return $returnValue;
		
	}
	/**
	 * This method determines h0w many hrs a subject takes
	 * through passing it the start tym and end tym as parameters.
	 * 
	 * process:
	 * --------
	 * -it takes the difference bet the end tym minus the start tym
	 * 
	 * used in:
	 * --------
	 *  - check3SuccessiveSubjectsRule()
	 * @param int $_sTime
	 * @param int $_eTime
	 * @return int
	 */
	private function howManyHrsASubjectTakesCounter($_sTime,$_eTime) {
		$hours = 0;
		$hours = ($_eTime - $_sTime);
		return $hours;
	}
	/**
	 * This is the method that enforces the 3 successiv subjects rulem, meanign a studnet
	 * can only have 3 successive subjects and must have a vacant tym after that.
	 * 
	 * process:
	 * --------
	 * -it first determines how many hrs a subject takes by calling the howManyHrsASubjectTakesCounter()
	 *  method.
	 * -checks if its more than zero
	 * -checks the day combination to determine the right plotted times array
	 *  and also exclude times array to use.
	 * -then crawls backwards starting from the current start tym, decrementing it by 0.5/30mins 
	 *  checking for 3 subject successiveness.
	 * -if the value from the looping is found in the plotted times array used then incerement
	 *  the COUNTER by 0.5
	 * -then crawls forward starting from the current end tym, incrementing it by 0.5/30mins 
	 *  checking for 3 subject successiveness.
	 * -if the value from the looping is found in the plotted times array used then incerement
	 *  the COUNTER by 0.5
	 * -then check if the current end tym is in the also exclude times
	 *  if its in the array then give the return value a value of true.
	 * -also check if their isn't any tym in the plotted tyms array thats in the scope
	 *  of the current start and end tym.
	 *  [ie their should not be a 09:00am inside the plotted tyms array with current tyms of 08:00am-10:00am] 
	 * -while constantly checking if the COUNTER is > 3
	 * -if it does go beyon 3 then return false meaning 3 successive violation rule was violated
	 * -else return false
	 *
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param string $_day
	 * @param int $_cStart
	 * @param int $_cEnd
	 * @return bool
	 */
	private function check3SuccessiveSubjectsRule($_day,$_cStart,$_cEnd) {
		$returnValue = true; //booo000l
		$COUNTER = 0;
		$looper = true;
		$also = array(); // alsoExcludeTimes array container...
		define('MAX_HRS',3);
		
		$howManyHrs = $this->howManyHrsASubjectTakesCounter($_cStart,$_cEnd);
		
		if ($howManyHrs > 0) {			
			$COUNTER = $howManyHrs; // initialize the counter of succesive subjects.....initial value would depend on the number of hours the schedule passed takes
						
			 // --- check the days here --- //
			if ($_day == '135') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesW,$this->plottedTimesF);				
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes));
			}
			else if ($_day == '1') {
				$timesArrayUsed = $this->plottedTimesM;
				$also = $this->mAlsoExcludeTimes;
			}
			else if ($_day == '3') {
				$timesArrayUsed = $this->plottedTimesW;
				$also = $this->wAlsoExcludeTimes;
				
			}
			else if ($_day == '5') {
				$timesArrayUsed = $this->plottedTimesF;
				$also = $this->fAlsoExcludeTimes;
				
			}
			else if ($_day == '13') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesW);
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->wAlsoExcludeTimes));
								
			}
			else if ($_day == '35') {
				$timesArrayUsed = array_merge($this->plottedTimesW,$this->plottedTimesF);
				$also = array_unique(array_merge($this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes));
								
			}
			else if ($_day == '15') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesF);
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->fAlsoExcludeTimes));
								
			}
			else if ($_day == '24') {
				$timesArrayUsed = array_merge($this->plottedTimesT,$this->plottedTimesTH);
				$also = array_unique(array_merge($this->tAlsoExcludeTimes,$this->thAlsoExcludeTimes));
								
			}
			else if ($_day == '2') {
				$timesArrayUsed = $this->plottedTimesT;
				$also = $this->tAlsoExcludeTimes;
					
			}
			else if ($_day == '4') {
				$timesArrayUsed = $this->plottedTimesTH;
				$also = $this->thAlsoExcludeTimes;
					
			}
			else if ($_day == '1234') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesW,$this->plottedTimesTH);				
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->thAlsoExcludeTimes));
				
			}
			else if ($_day == '12345') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesW,$this->plottedTimesTH,$this->plottedTimesF);
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->thAlsoExcludeTimes,$this->fAlsoExcludeTimes));
				
			}
			else if ($_day == '1235') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesW,$this->plottedTimesF);
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes));
				
			}
			else if ($_day == '12') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesT);
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes));
				
			}
			else if ($_day == '14') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesTH);
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->thAlsoExcludeTimes));
							
			}
			else if ($_day == '23') {
				$timesArrayUsed = array_merge($this->plottedTimesT,$this->plottedTimesW);
				$also = array_unique(array_merge($this->tAlsoExcludeTimes,$this->wAlsoExcludeTimess));
								
			}
			else if ($_day == '25') {
				$timesArrayUsed = array_merge($this->plottedTimesT,$this->plottedTimesF);
				$also = array_unique(array_merge($this->tAlsoExcludeTimes,$this->fAlsoExcludeTimes));
								
			}
			else if ($_day == '34') {
				$timesArrayUsed = array_merge($this->plottedTimesW,$this->plottedTimesTH);
				$also = array_unique(array_merge($this->wAlsoExcludeTimes,$this->thAlsoExcludeTimes));
								
			}
			else if ($_day == '45') {
				$timesArrayUsed = array_merge($this->plottedTimesTH,$this->plottedTimesF);
				$also = array_unique(array_merge($this->thAlsoExcludeTimes,$this->fAlsoExcludeTimes));
				
			}
			else if ($_day == '124') {
				$timesArrayUsed = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesTH);	
				$also = array_unique(array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->thAlsoExcludeTimes));	
						
			}			
			else if ($_day == '6') {
				$timesArrayUsed = $this->plottedTimesSAT;
				$also = $this->satAlsoExcludeTimes;
				
			}
			
			$hrsBefore = $_cStart; //starting tym
			$hrsForward = $_cEnd;  //ending tym 
			
			/**
			 * THE CRAWLERS
			 */
			// crawls the back hours of the current time checking for 3 hours siccessivness //
			while ($looper == true) { // hours before the current //
				$hrsBefore -= 0.5;
				if (in_array($this->valueEquivTime($hrsBefore),$timesArrayUsed)) {					
					$COUNTER+=0.5;
				}
				else {
					$looper = false;
					break;
				}
					
				if ($COUNTER > MAX_HRS) {
					$returnValue = false;
					$looper = false;
					break;
				}
			}
			$looper = true; // reset $looper
			
			if ($COUNTER <= MAX_HRS) { // check to see if we still need to crawl the front hours
				
				// crawls the front hours of the current time checking for 3 hours siccessivness //
				while ($looper == true) { // hours infront of the current //
					$hrsForward += 0.5;
					if (in_array($this->valueEquivTime($hrsForward),$timesArrayUsed)) {
						$COUNTER+=0.5;
					}
					else {
						$looper = false;
						break;
					}
						
					if ($COUNTER > MAX_HRS) {
						$returnValue = false;
						$looper = false;
						break;
					}
				}
				
				// -- ALSO CHECKING -- //
				/**
				 * 
				 * situations why this cindition is needed:
				 * ----------------------------------------
				 * 08:00am
				 * 08:30am
				 * 09:00am
				 * 09:30am
				 * 
				 * [10:30am - 11:30am] <-current time...
				 * 
				 * 12:00pm
				 * 12:30pm
				 * 
				 * 
				 * skemo only checks for the front hours or end time
				 * 
				 */
				if (in_array($this->valueEquivTime($_cEnd),$also)) { //????
					$returnValue = true;
				}
				
			}
			
			// -- FINAL CHECKING -- //
			for ($c = $_cStart; $c < $_cEnd; $c+=0.5) {
				if (in_array($this->valueEquivTime($c),$timesArrayUsed)) {
					$returnValue = false;
					break;
				}
			}
		}		
		return $returnValue;						
	}
	/**
	 * This is the method incharge of selecting the schedule. It prioritizes basak held subjects.
	 *
	 * process:
	 * --------
	 * -first it iterates through the sked array passed to it.
	 * -if the $_b4Location is not RAND then
	 * -check if the loc in the $_skedArrsked is equal to the $_b4Location
	 * -then assign the array to the return value and break from the l000p...
	 * -if the $_b4Location is RAND then check if the subject_no from the $_skedArr
	 *  is not in the $plottedSubjects private member array.
	 * -if not then assign the array(schedule) to the return value and break
	 * -else continue searching.....
	 * -if nothing or no match is found return false.
	 * 
	 * used in:
	 * --------
	 *  - getSubjectSched()
	 * 
	 * @param array $_skedArr
	 * @param string $_b4Location
	 * @return bool | array
	 */
	private function selector($_skedArr,$_b4Location = 'RAND') {
		$returnValue = false;
		foreach ($_skedArr as $key=>$theChosenSked) {
			if ($_b4Location !== 'RAND') {
				if ($theChosenSked['loc'] == strtoupper($_b4Location)) {
					$returnValue = $_skedArr[$key];
					break;
				}
			}
			else {
				if (!in_array($theChosenSked['subject_no'],$this->plottedSubjects)) {											
					$returnValue = $_skedArr[$key];
					break;																	
				}
			}
		}
		return $returnValue;
	}
	/**
	 * used to convert some 24 hr format times into 12 hr format...
	 * 
	 * used in:
	 * --------
	 * - getSubjectSched()
	 * 
	 * @param string $_strTime
	 * @return string (time)
	 */
	private function do12HrConversion($_strTime) {
		$bad = array('13:00pm','12:00am','12:30am','14:00am','14:00pm','14:30pm','14:30am');
		$good = array('01:00pm','12:00pm','12:30pm','02:00pm','02:00pm','02:30pm','02:30pm');
			
		return str_replace($bad,$good,$_strTime);
	}
	/**
	 * This is the method that implements all the constraints and all the checking by calling
	 * the method declared above to add a schdule to the over all schdule of the student.
	 * 
	 * This is the mother method.
	 * 
	 * process:
	 * --------
	 * -first construct a mysql IN() function using the plottedSubjectsGlue() method
	 * -run the decider query to fetch the data from the `my_skemo_schedule` table
	 * -gather the data and place it inside a multi-dimensional array
	 * -check if the array count is greater than zero, if it is zero set the skedBefore['status'] value
	 *  to NO_VALUE.
	 * -if more thatn zero then check if skedBefore['status'] value === OK then call the selector() method
	 *  with the schdule array and the skedBefore['loc'] as the parameters, this means that this
	 *  is no the first tym the getSubjectSched() method has run for this combination of days($days).
	 * -else then call the selector() method with the schdeule array  and a 2nd parameter with a value of B{prioritize basak}
	 * -if the result is still false or an array count of 0 then
	 * -call the selector() method again with the schedule array only as the prameter, meaning
	 *  we do not care what will result as l0ng as it is a schedule.
	 * - then after all that we check if the skedule array is an array
	 * -we then chek if the sked['subject_no'] is not in the plottedSubjects array.
	 * -gather the proper int value of the current time.
	 * -check if the startime is not equal to 0..
	 * -check the location travel tym rule, call checkTheLocation() method.
	 * -check if the befor end tym is less than or equal to the current start tym
	 * -do the 2nd preferences filtering, call doSecondPreferencesValidation() method.
	 * -check the users prefered vacant tym if any, call checkVacancy() method.
	 * -place the sked array details in the return value array.
	 * -record the plotted tym by calling handlePlottingOfTimeRecords() method.
	 * -add the $sked['subject_no'] to the $plottedSubjects member array.
	 * -record the location by calling locationRecorder() method.
	 * -update the skedBefore['satus','time','location'].
	 * -return the sked array....
	 * 
	 * used in:
	 * --------
	 *  - traverseTheDays()
	 * 
	 * @param string $_subjectTime
	 * @param string $days
	 * @return array
	 */
	private function getSubjectSched($_subjectTime,$days) {
		$returnArrSked = array(); // the array to be returned by this method
		$plottedAlready = $this->plottedSubjectsGlue();
		$in = ''; //used to check if the subject hasen't alredy been pl0tted...
		
		$scheduleArr = array(); // the array used as a container for the data caugh up by the db
		$scheduleArrCnt = 0;

		// -- check if there are pl0tted subjects -- //
		if (trim($plottedAlready) != '') {
			$in = "AND `subject_no` NOT IN(".$plottedAlready.")";
		}
				
		// ---- DECIDER QUERY ---- //
		//$query = "SELECT MIN(".$priority.") as `priority`,`time`,`subject_no`,`day`,`room`,`meridiem`,`subject_id` FROM `my_skemo_schedule` where `time` LIKE '".$_subjectTime."%' AND `student_no` = '".$this->studentNum."' AND `day`='".$days."' ".$in." GROUP BY `subject_no` ORDER BY ".$priority;
		$query = "SELECT MIN(`howmany`) as `priority`,`time`,`subject_no`,`day`,`room`,`meridiem`,`subject_id` FROM `my_skemo_schedule` where `time` LIKE '".$_subjectTime."%' AND `student_no` = '".$this->studentNum."' AND `day`='".$days."' ".$in." GROUP BY `subject_no` ORDER BY `howmany`";				
		
		$result = mysql_query($query) or die(mysql_error_msg($query));
		if (mysql_num_rows($result) > 0) {
			while ($schedule = mysql_fetch_object($result)) {
				$scheduleArr[$scheduleArrCnt]['time'] = $this->do12HrConversion($schedule->time);
				$scheduleArr[$scheduleArrCnt]['subject_no'] = $schedule->subject_no;
				$scheduleArr[$scheduleArrCnt]['offer_no'] = $schedule->subject_id; // newly added
				$scheduleArr[$scheduleArrCnt]['day'] = $schedule->day;
				$scheduleArr[$scheduleArrCnt]['dayInt'] = $days;
				$scheduleArr[$scheduleArrCnt]['meridiem'] = $schedule->meridiem;
				$scheduleArr[$scheduleArrCnt]['room'] = $schedule->room;
				$scheduleArr[$scheduleArrCnt]['loc'] = $this->basakOrMain($schedule->room);
				$scheduleArrCnt++;
			}						
		}
		mysql_free_result($result); //free the result...
		// ---- SCOPE OF DECIDER QUERY ENDS HERE ---- //
		
		if (count($scheduleArr) > 0) { // check if theRE are skedules to play with...
			
				$this->COLLECTOR[$_subjectTime.$days] = $scheduleArr; // THE COLLECTOR ARRAY // 
				$sked; //chosen sked value container...it SHOULD be an array..
				
				// CHECK IF THE PREVIOUS SKED WAS VACANT //
				if ($this->skedBefore['status'] === 'OK') {							   
					$sked = $this->selector($scheduleArr,$this->skedBefore['loc']);		
				}
				else {
					$sked = $this->selector($scheduleArr,'B'); // prioritize basak...
					if (($sked === false) || (count($sked) === 0)) { //if no basak is found...
						$sked = $this->selector($scheduleArr);
					}
				}
				//$sked = $scheduleArr[0];

				if (is_array($sked)) { // check if the selector outputed any thing...
					if (!in_array($sked['subject_no'],$this->plottedSubjects)) { // checking 4 no duplication of subject numbers in the schedule....
					
						//CHECK the B4 TYM N C0MPARE IT WID THE CURRENT....	2nd line of defense 					
						if ($this->timeBeforeMe === false) { // another checker 4 the before tym...this condition should never equuate to true...or it should never get this deep...
							$properInitTime  = '00:01am-00:06am';
							$before = new SKEMO_TimeHandler($properInitTime);				    
						}
						else {					
							$before = new SKEMO_TimeHandler($this->timeBeforeMe);
						}
						$current = new SKEMO_TimeHandler($sked['time']);
					
						// --- USED TO CALCULATE THE ORDER OF TIMES (time number values) --- //
						$beforeEndTime = $this->timeEquivValue($before->getEndTimeRaw());
						$currentStartTime = $this->timeEquivValue($current->getStartTimeRaw());
						// -------------------------------------------------------------------//
						
						// --- CURRENT TIME PROPERTIES (time number values)--- //
						$startTime = $this->timeEquivValue($current->getStartTimeRaw());
						$endTime = $this->timeEquivValue($current->getEndTimeRaw());
						// -------------------------------------------------------------//
						
//					if($sked['subject_no'] == 'ECON 1' && $days == '24'){
//									//echo $before->getEndTimeRaw()."<br />";
//									//echo $this->timeBeforeMe['status'];
//									echo $startTime,":",$endTime;
//									//die($sked['subject_no']);
//									die($sked['time']);
//					}		
						
						if ($this->check3SuccessiveSubjectsRule($days,$startTime,$endTime)) {	// check the 3 successive subject rule.... here																	
						
							if ($startTime != 0) { // no time starting ar 00:01am											  								
							
								if ($this->checkTheLocation($days,$sked['loc'],$startTime,$endTime)) {
								
									if ($beforeEndTime <= $currentStartTime) {
												
										if ($this->doSecondPreferencesValidation($days,$sked['room'],$startTime,$endTime)) {
											
											if ($this->checkVacancy($startTime,$endTime,$days)) {
												
												// -- assign the values to the array to be returned by this method -- //
												
												$returnArrSked['time'] = $sked['time'];
												$returnArrSked['subject_no'] = $sked['subject_no'];
												$returnArrSked['offer_no'] = $sked['offer_no'];
												$returnArrSked['day'] = $this->dayAnalyzer($sked['day']);
												$returnArrSked['dayInt'] = $days; // SHOULD BE REMOVE ON DEPLOYMENT...
												$returnArrSked['meridiem'] = $sked['meridiem']; // SHOULD BE REMOVE ON DEPLOYMENT...
												$returnArrSked['room'] = $sked['room'];

												/**
												 * PLACE IN MEMORY
												 */
												
												// do recording of times and subjects plotted here //
												$this->handlePlottingOfTimeRecords($sked['time'],$days);

												// record the subject number as one of the plotted subjects //
												$this->plottedSubjects[] = strtoupper($sked['subject_no']);
														
												// record the location.... //
												$this->locationRecorder($days,$sked['loc'],$startTime,$endTime);
														
												// skedBefore remembers this plotted schedule.../!\ //
												$this->skedBefore['status'] = 'OK';
												$this->skedBefore['time'] = $sked['time'];
												$this->skedBefore['loc'] = $this->basakOrMain($sked['room']);
														
												$this->timeBeforeMe = $sked['time'];	// remember this schedules time...
												
											} //checkVacancy closing
											else {
												// -- the current tym violated your prefered vacant tym -- //
												$this->skedBefore['status'] = 'NO_VALUE';	
											}											
										} //2nd pref validation closing
										else {
											// -- for some reason while checking your preferences the 2nd tym...it did not qualify to your preferences (this should not or should rearly happen) -- //
											$this->skedBefore['status'] = 'NO_VALUE';
							
										}					
									}
									else {
										// -- the before time is much bigger/or takes more tym than the current time, the curretn should not be plotted -- //
										$this->skedBefore['status'] = 'NO_VALUE';
									}
								} //checkLOcation closing
								else {
									// -- the campus location is different must give travel time allowance -- //
									$this->skedBefore['status'] = 'NO_VALUE';
								}	
							}
						} // 3 successive subjects closing...						
						else {
							// -- the 3 successive subjects rule was violated -- //
						  	$this->skedBefore['status'] = 'NO_VALUE';				  	
						}
					}// in_array closing
					else {
						// -- the subject was already plotted -- //
						$this->skedBefore['status'] = 'NO_VALUE';	
					}
				}// is_array($sked) closing
				else {
					// -- no schdules where found -- //
					$this->skedBefore['status'] = 'NO_VALUE';
				}				
		} // count() closing....
		else {
				// if their are no proper schedules for the $_subjectTime parameter above set the status property of skedBefore array to 'NO_VALUE'
				$this->skedBefore['status'] = 'NO_VALUE';
		}
		
		// -- return the schdule or an array with a count of 0 -- //
		return $returnArrSked;
	}
	/**
	 * This method handles the distribution of the schedule to the outputSchedule[] array.
	 * Assigns the outputed schdule array to the $outputSchedule member if the count of the 
	 * array returned by getSubjectSched() method is greater than zero....
	 *
	 * used in:
	 * --------
	 *  - traverseTheDays()
	 * 
	 * @param array $_skedArr
	 */
	private function allocatorChecker($_skedArr) {
		if (count($_skedArr) > 0) {
				
			$this->outputSchedule[] = $_skedArr;
		}
	}
	/**
	 * Checks for OPEN time subjects and places it in the outputSchedule[]
	 * array, basically it just searches the `my_skemo_schedule` for times 
	 * having a value of 00:01am-00:06am.
	 * 
	 * used in:
	 * --------
	 * - traverseTheDays()
	 * 
	 */
	private function check4OpenTimes() {
		$schedContainerArray = array();
		$query = "SELECT * FROM `my_skemo_schedule` WHERE `time` = '00:01am-00:06am' GROUP BY `subject_no`";
		$result = mysql_query($query) or die(mysql_error_msg($query));
		if (mysql_num_rows($result) > 0) {
			while ($sched = mysql_fetch_object($result)) {
				$schedContainerArray['time'] = 'OPEN';
				$schedContainerArray['subject_no'] = $sched->subject_no;
				$schedContainerArray['offer_no'] = $sched->subject_if;
				$schedContainerArray['day'] = $this->dayAnalyzer($sched->day);
				$schedContainerArray['dayInt'] = $sched->day;
				$schedContainerArray['meridiem'] = $sched->meridiem;
				$schedContainerArray['room'] = $sched->room;
				
				$this->plottedSubjects[] = strtoupper($schedContainerArray['subject_no']);
				
				$this->outputSchedule[] = $schedContainerArray; // pass it to outputSchedule array
			}
		}
	}
	/**
	 * This is the method that traverses all the times and possible day combinations
	 * to find a schdule that is fit.
	 * 
	 * process:
	 * --------
	 * -check for OPEN time subject by calling check4OpenTimes() method.
	 * -iterates over the $individualDays array iterates on the $allTimes array
	 * -it checks the appropriate day combination to be evaluated
	 * -then check if the time is not already in the plotted times array
	 *  and not in the also exclude times array.
	 * -if not then call the allocatorChecker() method with the 
	 *  getSubjectSched() method as the parameter in order to plot the schedule.
	 * -then reset the $timeBeforeMe and skedBefore['status'].
	 * 
	 * used in:
	 * --------
	 *  - skemoEngine()
	 * 
	 */
	private function traverseTheDays() {
		
		$returnedSubjects = '';
		$individualDays = array('1','2','3','4','5');
		// MWF: 13 15 35
		// WIERD: 12 14 23 25 34 45
		$wierdDays = array('12','14','23','25','34','45');
		
		$mwfsTimes = array('00:01am','07:00am','08:00am','09:00am','10:00am','11:00am',
		                  '12:00pm','01:00pm','02:00pm','03:00pm','04:00pm',
						  '05:00pm','06:00pm','07:00pm');
				
		$tthTimes = array('00:01am','07:00am','08:30am','10:00am','11:30am',
						  '01:00pm','02:30pm','04:00pm','05:30pm',
						  '07:00pm','08:30pm');  
		
		$allTimes = array('07:00am','07:30am','08:00am','08:30am','09:00am','09:30am',
						  '10:00am','10:30am','11:00am','11:30am','12:00pm','12:30pm',
						  '01:00pm','01:30pm','02:00pm','02:30pm','03:00pm','03:30pm',
						  '04:00pm','04:30pm','05:00pm','05:30pm','06:00pm','06:30pm',
						  '07:00pm','07:30pm','08:00pm','08:30pm','09:00pm');	
		$plottedTyms;
		$alsoExcludeTyms;
		
		/**
		 * First of all check for OPEN time subjects
		 */
		$this->check4OpenTimes(); // for OPEN tyms
		
		/**
		 * // --- DO THE LOOOOOPS HERE --- //
		 * - their is a reason for the order of the looops /!\
		 */
		/************************************************************************/
				foreach ($individualDays as $day) {// individual days...
					foreach ($allTimes as $time) {
						if ($day == '1') {
							if ((!in_array($time,$this->plottedTimesM)) && (!in_array($time,$this->mAlsoExcludeTimes))) {
								$this->allocatorChecker($this->getSubjectSched($time,$day));
							}
						}
						else if ($day == '3') {
							if ((!in_array($time,$this->plottedTimesW)) && (!in_array($time,$this->wAlsoExcludeTimes))) {
								$this->allocatorChecker($this->getSubjectSched($time,$day));
							}
						}
						else if ($day == '5') {
							if ((!in_array($time,$this->plottedTimesF)) && (!in_array($time,$this->fAlsoExcludeTimes))) {
								$this->allocatorChecker($this->getSubjectSched($time,$day));
							}
						}
						else if ($day == '2') {
							if ((!in_array($time,$this->plottedTimesT)) && (!in_array($time,$this->tAlsoExcludeTimes))) {
								$this->allocatorChecker($this->getSubjectSched($time,$day));
							}
						}
						else if ($day == '4') {
							if ((!in_array($time,$this->plottedTimesTH)) && (!in_array($time,$this->thAlsoExcludeTimes))) {
								$this->allocatorChecker($this->getSubjectSched($time,$day));
							}
						}
					}
					$this->timeBeforeMe = false;
					$this->skedBefore['status'] = 'NO_VALUE';
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE'; // ????? 
				////////////////////////////////////////////////////////////////////////////		
				foreach ($allTimes as $time) {// for mwf
					if ((!in_array($time,array_merge($this->plottedTimesM,$this->plottedTimesW,$this->plottedTimesF))) && (!in_array($time,array_merge($this->mAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes)))) {				
						$this->allocatorChecker($this->getSubjectSched($time,'135'));
					}				
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				foreach ($allTimes as $time) {// for tth
					if ((!in_array($time,array_merge($this->plottedTimesT,$this->plottedTimesTH))) && (!in_array($time,array_merge($this->tAlsoExcludeTimes,$this->thAlsoExcludeTimes)))) {				
						$this->allocatorChecker($this->getSubjectSched($time,'24'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				foreach ($allTimes as $time) {// for sat
					if ((!in_array($time,$this->plottedTimesSAT)) && (!in_array($time,$this->satAlsoExcludeTimes))) {	
						$this->allocatorChecker($this->getSubjectSched($time,'6'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				////////////////////////////////////////////////////////////////////////////				
				foreach ($wierdDays as $day) {
					if ($day == '12') {
						$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesT);
						$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes);
					}
					else if ($day == '14') {
						$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesTH);
						$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->thAlsoExcludeTimes);
					}
					else if ($day == '23') {
						$plottedTyms = array_merge($this->plottedTimesT,$this->plottedTimesW);
						$alsoExcludeTyms = 	array_merge($this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes);
					}
					else if ($day == '25') {
						$plottedTyms = array_merge($this->plottedTimesT,$this->plottedTimesF);
						$alsoExcludeTyms = 	array_merge($this->tAlsoExcludeTimes,$this->fAlsoExcludeTimes);
					}
					else if ($day == '34') {
						$plottedTyms = array_merge($this->plottedTimesW,$this->plottedTimesTH);
						$alsoExcludeTyms = 	array_merge($this->wAlsoExcludeTimes,$this->thAlsoExcludeTimes);
					}
					else if ($day == '45') {
						$plottedTyms = array_merge($this->plottedTimesTH,$this->plottedTimesF);
						$alsoExcludeTyms = 	array_merge($this->thAlsoExcludeTimes,$this->fAlsoExcludeTimes);
					}
					foreach ($allTimes as $time) {// for wierd days						
						if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {				
							$this->allocatorChecker($this->getSubjectSched($time,$day));
						}
					}
					$this->timeBeforeMe = false;
					$this->skedBefore['status'] = 'NO_VALUE';
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				////////////////////////////////////////////////////////////////////////////
				$plottedTyms = array_merge($this->plottedTimesW,$this->plottedTimesF);// for wf
				$alsoExcludeTyms = 	array_merge($this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes);					
				foreach ($allTimes as $time) {
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {				
						$this->allocatorChecker($this->getSubjectSched($time,'35'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				
				$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesF);// for mf
				$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->fAlsoExcludeTimes);					
				foreach ($allTimes as $time) {
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {				
						$this->allocatorChecker($this->getSubjectSched($time,'15'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				
				$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesW);// for mw	
				$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->wAlsoExcludeTimes);
				foreach ($allTimes as $time) {				
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {				
						$this->allocatorChecker($this->getSubjectSched($time,'13'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				////////////////////////////////////////////////////////////////////////////
				// for mtwthf
				$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesW,$this->plottedTimesTH,$this->plottedTimesF);
				$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->thAlsoExcludeTimes,$this->fAlsoExcludeTimes);										
				foreach ($allTimes as $time) {
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {							
						$this->allocatorChecker($this->getSubjectSched($time,'12345'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				// for mtwth
				$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesW,$this->plottedTimesTH);
				$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->thAlsoExcludeTimes);										
				foreach ($allTimes as $time) {
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {
						$this->allocatorChecker($this->getSubjectSched($time,'1234'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				// for mtwf
				$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesW,$this->plottedTimesF);
				$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes);									
				foreach ($allTimes as $time) {
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {
						$this->allocatorChecker($this->getSubjectSched($time,'1235'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';
				// for mtth
				$plottedTyms = array_merge($this->plottedTimesM,$this->plottedTimesT,$this->plottedTimesTH);
				$alsoExcludeTyms = 	array_merge($this->mAlsoExcludeTimes,$this->tAlsoExcludeTimes,$this->thAlsoExcludeTimes);										
				foreach ($allTimes as $time) {
					if ((!in_array($time,$plottedTyms)) && (!in_array($time,$alsoExcludeTyms))) {
						$this->allocatorChecker($this->getSubjectSched($time,'124'));
					}
				}
				$this->timeBeforeMe = false;
				$this->skedBefore['status'] = 'NO_VALUE';				
	
		/************************************************************************/		
	}
	/**
	 * do cleaning up....
	 *
	 */
	private function doCleanUp() {		
		$count;
		$checker = false;
		$query = sprintf("DELETE FROM `my_skemo_schedule` WHERE `student_no` = '%s'",$this->studentNum);
		$success = mysql_query($query) or die(mysql_error_msg($query));
		if (!$success) {
			die("Unable to delete the unused schedule data in the `my_skemo_schedule` table");
		}
		$query = "SELECT COUNT(*) AS `count` FROM `my_skemo_schedule`";
		$result = mysql_query($query) or die(mysql_error_msg($query));
		if (mysql_num_rows($result) > 0) {
			$count = mysql_fetch_object($result);
			if ($count->count === 0) {
				$checker = true; // this means do truncating
			}
		}
		mysql_free_result($result);
		if ($checker) { //if true truncation of the table begins
			$query = "TRUNCATE TABLE `my_skemo_schedule`";
			$success = mysql_query($query) or die(mysql_error_msg($query));
			if (!$success) {
				die("Truncation of `my_skemo_schedule` table failed");
			}
		}
		//-- unsetting of the memorty arrats should be done here -- //
		
	}
	/**
	 * This is the public method wrapper of the privet method traverseTheDays()
	 * 
	 * process:
	 * --------
	 * -calls the traverseTheDays() method
	 * -and then returns the outputSchedule private member array.
	 *
	 * used in:
	 * --------
	 *  - used publicly
	 * 
	 * @return multidimensional array
	 */
	public function skemoEngine() {		
		// --start traversing/crawling through the days-- //
		$this->traverseTheDays();
		$this->doCleanUp(); 
		return $this->outputSchedule;					
	}
	/**
	 * Collects all the subjects that where not plotted by the scheduler
	 * 
	 * process:
	 * --------
	 * -iterates over the $subjects private member.
	 * -and if one of its values is not found in the $plottedSubjects array
	 * -then this subject_no was not plotted
	 * -place it in a new array to be returned
	 * -return the array containig the subject_no of subjects that wher not
	 *  plotted, if their are any.....
	 * 
	 * used in:
	 * --------
	 * - used publicly
	 *
	 * @return array
	 */
	public function getUnplottedSubjects() {
		$unplotted = array();
		foreach ($this->subjects as $subject) {
			if (!in_array(strtoupper($subject),$this->plottedSubjects)) {
				$unplotted[] = $subject;
			}
		}
		return $unplotted;
	}
	/**
	 * Get the subjects that where plotted
	 * 
	 * process:
	 * --------
	 * -returns the $plottedSubjects private member array.
	 *
	 * used in:
	 * --------
	 * - used publicly
	 * 
	 * @return array
	 */
	public function getPlotted() {
		return $this->plottedSubjects;
	}
	/**
	 * This method is mainly used for debugging.....
	 *
	 * used in:
	 * --------
	 * - used publicly
	 * 
	 * @return unknown
	 */
	public function getSomething() {
		//return $this->howmany;
		//return $this->plottedTimesM;
		//return $this->plottedTimesT;
		//return $this->plottedTimesTH;
		//return $this->countPerSubNo;
		//return $this->mwfLocRecord;
		//return $this->tthLocRecord;
		//return $this->selectorCache;
		//return $this->tthAlsoExcludeTimes;
		//return $this->preferences;
		return array_merge($this->plottedTimesT,$this->plottedTimesTH);
		//return $this->plottedTimesTH;
		//return array_unique(array_merge($this->plottedTimesM,$this->plottedTimesW,$this->plottedTimesF));
		//return array_unique(array_merge($this->mAlsoExcludeTimes,$this->wAlsoExcludeTimes,$this->fAlsoExcludeTimes));
		//return $this->subjects;
		//return $this->COLLECTOR;
	}
} //end of class

?>