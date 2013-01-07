<?php
/**
 * this is the include file that has the time converter function and the location decrypter
 *  - valueEquivTime()
 *  - timeEquivValue()
 *  - locationDecrypter()
 */



/**
 * A copy of the method found on the SKEMO_Schedule_Master class
 * Converts integer to its equivalent string time....
 *
 * @param int $_intTimeValue 
 * @return string
 */
function valueEquivTime($_intTimeValue){	 	
		$equivStrTime = '';
		switch($_intTimeValue){
			case 0:
				$equivStrTime = 'NA';
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
		
		return $equivStrTime;
}
/**
 * return the equivalent integer value of the string time given to it...
 *
 * @param string $_strTime
 * @return string
 */
function timeEquivValue($_strTime){ 
		$equivIntValue = 0;
		switch($_strTime){
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
				$equivIntValue = 7;
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
 * Used to decrypt location codes [ie B to Basak Campus]
 *
 * @param string $_loc
 * @return string
 */
function locationDecrypter($_loc) {
	$returnMe;
	switch (strtoupper($_loc)) {
		case 'B':
			$returnMe = 'Basak Campus';
		break;
		case 'M':
			$returnMe = 'Main Campus';
		break;
		case 'BM':
			$returnMe = 'Basak / Main Campus';
		break;	
		default:
			$returnMe = 'NA';
	}
	return $returnMe;
}
	
?>