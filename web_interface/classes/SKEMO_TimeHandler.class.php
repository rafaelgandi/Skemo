<?php
/**
 * SKEMO_TimeConverter
 * 
 * this class converts a string time in this format 01:00pm-02:00pm into
 * its equivalent timestamp and also does s0me other c00l stuff with it...;-)
 *
 */
class SKEMO_TimeHandler{
	
	private $strTime = ''; // ex. 13:30pm-02:30pm
	private $startTimestamp = 0;
	private $endTimestamp = 0;
	private $startMeridiem = '';
	private $endMeridiem = '';
	private $startTimeRaw = '';
	private $endTimeRaw = '';
	private $startTime = '';
	private $endTime = '';
	
	public function __construct($_time){
		$this->strTime = $_time;
		$this->handleStrTime(); //declared bel0w....
	}
	/**
	 * this method generates a timestamp depending on the string passed to it.
	 * this is used internally in the class. ;-)
	 *
	 * @param string $_unconvertedTime
	 * @return int
	 */
	protected function setTimestamps($_unconvertedTime){
		$cleanStrTime = substr($_unconvertedTime,0,strlen($_unconvertedTime)-2);
		$splittedStrTime = explode(':',$cleanStrTime);
		$hr = intval($splittedStrTime[0]);
		$min = intval($splittedStrTime[1]);
		return mktime($hr,$min,0);
	}
	/**
	 * handles the convertions of the time from string to timstamp and also gets 
	 * the meridiem.
	 *
	 */
	protected function handleStrTime(){
		$uncleanedTime = $this->strTime;
		$splittedTime = explode('-',$uncleanedTime); //BO0M!
		
		$startTimeWithAmPm = trim($splittedTime[0]);
		$endTimeWithAmPm = trim($splittedTime[1]);
		/**
		 * get the divided times...
		 */
		$this->startTimeRaw = $startTimeWithAmPm;
		$this->endTimeRaw = $endTimeWithAmPm;
		/**
		 * lengths
		 */
		$startLength = strlen($startTimeWithAmPm); // get lengths
		$endLength = strlen($endTimeWithAmPm); // get lengths
		/**
		 * get the start and end times.....string ni sya ha.... 
		 */
		$this->startTime = substr($startTimeWithAmPm,0,($startLength-2));
		$this->endTime = substr($endTimeWithAmPm,0,($endLength-2));		
		/**
		 * gets the meridiem....[AM/PM]
		 */		
		$this->startMeridiem = substr($startTimeWithAmPm,($startLength - 2),($startLength-1));
		$this->endMeridiem = substr($endTimeWithAmPm,($endLength - 2),($endLength-1));
		/**
		 * creataes the timestamps
		 */
		$this->startTimestamp = $this->setTimestamps($startTimeWithAmPm);
		$this->endTimestamp = $this->setTimestamps($endTimeWithAmPm);
	}
	public function make2Timestamp($_strTime){
		$splitTime = explode(':',$_strTime);
		$timestamp = mktime(intval($splitTime[0]),intval($splitTime[1]),0);
		return $timestamp;
	}
	public function addOneHour($_timestamp){
		$hour = $_timestamp + 3600;
		$formattedHour = date("h:i",$hour);
		return $formattedHour;	
	}
	public function addOneHour30Min($_timestamp){
		$hour = $_timestamp + 5400;
		$formattedHour = date("h:i",$hour);
		return $formattedHour;	
	}
	              // GETTERS //
	public function getStartMeridiem(){
		return strtolower($this->startMeridiem);
	}
	public function getEndMeridiem(){
		return strtolower($this->endMeridiem);
	}
	public function getStartTimeRaw(){
		return $this->startTimeRaw;
	}
	public function getEndTimeRaw(){
		return $this->endTimeRaw;
	}	
	public function getStartTime(){
		return $this->startTime;
	}
	public function getEndTime(){
		return $this->endTime;
	}
	public function getStartTimestamp(){
		return $this->startTimestamp;
	}
	public function getEndTimestamp(){
		return $this->endTimestamp;
	}
	public function getRawTime(){
		return $this->strTime; //get the raw string time...
	}
	
}

?>