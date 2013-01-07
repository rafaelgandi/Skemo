<?php
/**
 * @author el rafa gandi
 * @version 1.1 
 * This is a class that helps in the paging of data from a database
 * but it still relies on the query string to function properly...to be improved
 * d[^________^]b
 *
 */
class Pager{
	
	protected $start;
	protected $numPerPage;
	protected $table;
	/**
	 * Pager class c0nstructor
	 *
	 * @param string $_table
	 * @param int $_numPerPage
	 * @param int[optional] $_start
	 * @param string[optional] $_mode
	 */
	public function __construct($_table,$_numPerPage,$_start = 0,$_mode = 'QS'){
		
		$this->start = (int)$_start;
		$this->numPerPage = (int)$_numPerPage;
		$this->table = mysql_real_escape_string($_table);
		
	    if(($_start > $this->getTotalFields()) || ($_start < 0)){
			die("ERROR INVALID PAGE NUMBER PASSED [<a href='".$_SERVER['PHP_SELF']."'>OK</a>]");
		}
		
	}
	/**
	 * start property getter function
	 *
	 * @return int
	 */
	public function getStart(){		
		return $this->start;
	}
	/**
	 * numPerPage property getter function
	 *
	 * @return int
	 */
	public function getNumPerPage(){	
        return $this->numPerPage;		
	}
	/**
	 * Gets the total number of fields in the table given to it....
	 *
	 * @return int
	 */
	public function getTotalFields(){
		
		$TOTAL = 0;
		$_query = "SELECT COUNT(*) AS `db_totalNum` FROM ".$this->table;
		$_result = mysql_query($_query) or die("ERROR ON QUERY [".$_query."] - ".mysql_error());
		if(mysql_num_rows($_result) > 0){
			$totalNum = mysql_fetch_object($_result);
			$TOTAL = $totalNum->db_totalNum;
			mysql_free_result($_result);  // free
			return $TOTAL;
		}
		else{
			mysql_free_result($_result); // liberty....
			return 0;
		}
		
	}
	 /**
	 * Compiles all the pages and returns a sring of page numbers...
	 *
	 * @return string
	 */
	protected function getAllPages(){
		
		$returnVal = '';
		if($this->start == 0){
			$returnVal = "<a href='".$_SERVER['PHP_SELF']."?pgStart=0'><b>1</b></a>&nbsp;";
		}
		else{
		    $returnVal = "<a href='".$_SERVER['PHP_SELF']."?pgStart=0'>1</a>&nbsp;";
		}
		$divideTotal = ($this->getTotalFields() / $this->numPerPage);
		$numberOfPages = ceil($divideTotal);
		$startPerPage = $this->numPerPage;
		$showedNumber = 2;
		$cnt;
		for($cnt = 0; $cnt < ($numberOfPages - 1); $cnt++){
			
			if($startPerPage == $this->start){	
				$returnVal .= "<a href='".$_SERVER['PHP_SELF']."?pgStart=".$startPerPage."'><b>".$showedNumber."</b></a>&nbsp;";				
			}
			else{		
                $returnVal .= "<a href='".$_SERVER['PHP_SELF']."?pgStart=".$startPerPage."'>".$showedNumber."</a>&nbsp;";
			}
            $startPerPage += $this->numPerPage;
			$showedNumber++;
		}
		return $returnVal;
		
	}
	/**
	 * Sets the pager navigator (previous and next)
	 * @param string[optional] $_styleClass
	 * @return string
	 */
	public function setPagerNavigator($_styleClass=''){
		
		$returnVal = '';
		if(($this->start >= $this->numPerPage) && ($this->start > 0)){
			
			$prev = $this->start - $this->numPerPage;
			$returnVal .= "<div class='".$_styleClass."'><a href='".$_SERVER['PHP_SELF']."?pgStart=".$prev."'>Previous</a>";
			
		}
		else{
			
			$returnVal .= "<div class='".$_styleClass."'><span style='color:#CCCCCC;'>Previous</span>";
			
		}
		$returnVal .= "&nbsp;&nbsp;".$this->getAllPages()."&nbsp;&nbsp;";
		if((($this->start + $this->numPerPage) < $this->getTotalFields()) && ($this->start >= 0)){
			
			$next = $this->start + $this->numPerPage;
			$returnVal .= "<a href='".$_SERVER['PHP_SELF']."?pgStart=".$next."'>Next</a></div>";
			
		}
		else{
			
			$returnVal .= "<span style='color:#CCCCCC;'>Next</span></div>"; 
			
		}
		
		return $returnVal;
		
	}
			
}
?>