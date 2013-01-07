<?php
/**
 * dependencies:
 * -pager.class.php
 * 
 * use:
 *  This is the extension of the Pager class for the skemo application.
 *  The class SKEMO_Pager is the customized version. Please be avar that this version of the
 *  Paging class needs a supporting ajax function [pager()].....
 */
require 'pager.class.php';

class SKEMO_Pager extends Pager{
	
	/**
	 * this method overrides the original gatAllPages() method of the Pager class....;-)
	 *
	 * @return string
	 */
	protected function getAllPages(){
		
		$returnVal = '';
		if($this->start == 0){
			$returnVal = "<a href='javascript:pager(0);'><b>1</b></a>&nbsp;";
		}
		else{
		    $returnVal = "<a href=javascript:pager(0);'>1</a>&nbsp;";
		}
		$divideTotal = ($this->getTotalFields() / $this->numPerPage);
		$numberOfPages = ceil($divideTotal);
		$startPerPage = $this->numPerPage;
		$showedNumber = 2;
		$cnt;
		for($cnt = 0; $cnt < ($numberOfPages - 1); $cnt++){
			
			if($startPerPage == $this->start){	
				$returnVal .= "<a href='javascript:pager(".$startPerPage.");'><b>".$showedNumber."</b></a>&nbsp;";				
			}
			else{		
                $returnVal .= "<a href='javascript:pager(".$startPerPage.");'>".$showedNumber."</a>&nbsp;";
			}
            $startPerPage += $this->numPerPage;
			$showedNumber++;
		}
		return $returnVal;
		
	}
	/**
	 * this method overrides the original setPagerNavigator() method of the Pager class
	 * ans customized for SKEMO....;-)
	 *
	 * @return string
	 */
	public function setPagerNavigator($_styleClass=''){
		$returnVal = '';
		if(($this->start >= $this->numPerPage) && ($this->start > 0)){
			
			$prev = $this->start - $this->numPerPage;
			$returnVal .= "<div class='".$_styleClass."'><a href='javascript:pager(".$prev.");'>Previous</a>";
			
		}
		else{
			
			$returnVal .= "<div class='".$_styleClass."'><span style='color:#CCCCCC;'>Previous</span>";
			
		}
		$returnVal .= "&nbsp;&nbsp;".$this->getAllPages()."&nbsp;&nbsp;";
		if((($this->start + $this->numPerPage) < $this->getTotalFields()) && ($this->start >= 0)){
			
			$next = $this->start + $this->numPerPage;
			$returnVal .= "<a href='javascript:pager(".$next.");'>Next</a></div>";
			
		}
		else{
			
			$returnVal .= "<span style='color:#CCCCCC;'>Next</span></div>"; 
			
		}
		
		return $returnVal;
	}
}
?>