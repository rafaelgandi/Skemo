<?php
/**
 * This is a simple template engine class.... 
 * @version 1.1
 */
class Template{
	
	private $headerInclude = '';
	private $footerInclude = '';
	private $title = 'Untitled';
	private $uniqueJS;
	private $uniqueCSS;
	private static $onloadAction = array();
	private static $website_name; // holds the name of the website...	
	public static $copyRight = '';
	
	/**
	 * This is the Template Class, this class takes the path of the header and footer php files
	 *
	 * @param string $_header
	 * @param string $_footer
	 */
	public function __construct($_header,$_footer){
		$this->headerInclude = $_header;
		$this->footerInclude = $_footer;
		
		self::$website_name = 'somewebsite.com';//set default...
		self::$copyRight = "<center>Copyright&copy;&nbsp;".date('Y')."&nbsp;".self::$website_name."</center>";//set default..
	}
	/**
	 * Sets the title of the page
	 *
	 * @param string $_title
	 */
	public function setTitle($_title){		
		$this->title = htmlentities(strval($_title));		
	}
	/**
	 * sets the common javascript includes that are set through out the website. Pass is an array of .js paths. Only used internally.
	 *
	 * @param array $_jsArray
	 */
	protected function setCommonJS($_jsArray){
		
		$jsIncludes = '';
		if(is_array($_jsArray)){
		    foreach($_jsArray as $path){
		    	$jsIncludes .= "<script src='".strip_tags($path)."'></script>\n";
		    }
		    return $jsIncludes;
		}
		else{
			die("ERROR: THE VALUE PASSED TO setCommonJS() METHOD IS NOT AN ARRAY!");
		}	
	}
	/**
	 * sets the common CSS links that are used through out the website. The same with the setCommonJS() method it accepts an array of .css paths. Only used internally.
	 *
	 * @param array $_cssArray
	 */
	protected function setCommonCSS($_cssArray){
		
		$cssLinks = '';
		if(is_array($_cssArray)){
		   
		   foreach($_cssArray as $path){
		   	   $cssLinks .= "<link rel='stylesheet' type='text/css' href='".strip_tags($path)."' />\n"; 
		   }
		   return $cssLinks;
		}
		else{
			die("ERROR: THE VALUE PASSED TO setCommonCSS() METHOD IS NOT AN ARRAY!");
		}
	}
	/**
	 * The method that sets the common javascript and stylesheet(css) paths....
	 *
	 * @param array $_js
	 * @param array $_css
	 * @return string
	 */
	public static function putCommonJSandCSS($_js=false,$_css=false){
	    $returnValue = '';
		if(($_js !== false) && ($_css !== false)){
	       $returnValue = self::setCommonCSS($_css)."\n".self::setCommonJS($_js)."\n";
	    }
		if(($_js === false) && ($_css !== false) && (is_array($_css))){
	    	$returnValue = self::setCommonCSS($_css)."\n";
	    }
	    if(($_css === false) && ($_js !== false) && (is_array($_js))){
	    	$returnValue = self::setCommonJS($_js)."\n";
	    }
	    return $returnValue;	
	}
	/**
	 * Sets a javascript include path to a .js file specific to this page.
	 *
	 * @param array | string path $_uniqueJS
	 */
	public function setUniqueJS($_uniqueJS){
		$cleanJS = '';		
		if(is_string($_uniqueJS)){
			$cleanJS = "<script src='".strval($_uniqueJS)."'></script>\n";
			$this->uniqueJS = $cleanJS;
		}
		if(is_array($_uniqueJS)){
			foreach($_uniqueJS as $jsPath){
			  $cleanJS .= "<script src='".strval($jsPath)."'></script>\n";	
			}
			$this->uniqueJS = $cleanJS;
		}
	}
	/**
	 * Sets a unique style for this page.
	 *
	 * @param  array | string path $_uniqueCSS
	 */
	public function setUniqueCSS($_uniqueCSS){
		$cleanCSS = '';
		if(is_string($_uniqueCSS)){
			$cleanCSS = "<link rel='stylesheet' type='text/css' href='".strval($_uniqueCSS)."' />\n";
			$this->uniqueCSS = $cleanCSS;
		}
		if(is_array($_uniqueCSS)){
			foreach($_uniqueCSS as $cssPath){
			  $cleanCSS .= "<link rel='stylesheet' type='text/css' href='".strval($cssPath)."' />\n";
			  $this->uniqueCSS = $cleanCSS;
			}
		}
	}
	/**
	 * Seta a unique javascript action the moment this page loads
	 *
	 * @param array | string JS code $_jsAction
	 */
	public function setOnloadAction($_jsAction){
		if(is_string($_jsAction)){
			self::$onloadAction[] = $_jsAction."\n";
		}
		if(is_array($_jsAction)){			
			foreach($_jsAction as $jsActionCode){
				self::$onloadAction[] = $jsActionCode."\n";
			}			
		}
	}
	/**
	 * the method that prints out all the onload javascript codes...
	 *
	 * @param array | string $_commonJSonloadCodes
	 * @return string
	 */
	public static function putOnloadActions($_commonJSonloadCodes = ''){
		$commonJS = $_commonJSonloadCodes;
		$jsOnloadCode = '';
		$jsOnloadCode .= ("
			<script type='text/javascript'>
		    /* <![CDATA[ */
		    /*THIS JAVASCRIPT WAS PRODUCED BY THE SKEMO TEMPLATE ENGINE*/
		       window.onload = function(){
		");
		if(is_array($commonJS)){
			foreach($commonJS as $jsActionCode){
				$jsOnloadCode .= $jsActionCode."\n";
			}
		}
		if(is_string($commonJS)){
			$jsOnloadCode .= $commonJS."\n";
		}
		if(count(self::$onloadAction > 0)){		
			foreach(self::$onloadAction as $jsActionCode){
				$jsOnloadCode .= $jsActionCode."\n";
			}
		}		
		$jsOnloadCode .= ("
			}
			/* ]]> */   
		    </script>
		");
		return $jsOnloadCode;
	}
	/**
	 * This method is more of a shortcut, rather tahn calling the 3 methods to configure your page you can call this singe method instead...convenient insn't it...
	 *
	 * @param array | string $_uniqueJS
	 * @param array | string $_uniqueCSS
	 * @param array | string $_jsOnloadAction
	 */
	public function pageConfig($_uniqueCSS = false,$_uniqueJS = false,$_jsOnloadAction = false){
		 if($_uniqueJS !== false){
		   $this->setUniqueJS($_uniqueJS);
		 }	
		 else{
		   $this->uniqueJS = '';
		 }
		 if($_uniqueCSS !== false){
		   $this->setUniqueCSS($_uniqueCSS);
		 }
		 else{
		   $this->uniqueCSS = '';
		 }
		 if($_jsOnloadAction !== false){
		   $this->setOnloadAction($_jsOnloadAction);
		 }
	}
	/**
	 * the method that sets the website's name and forms its copyright...
	 *
	 * @param string $_websiteName
	 */
	public function setWebsiteName($_websiteName){
		self::$website_name = strval($_websiteName);		
		self::$copyRight = "<center>Copyright&nbsp;&copy;&nbsp;".date('Y')."&nbsp;".self::$website_name."</center>";		
	}
	
	/*******************************************************************************************************/
	                                  //THIS IS WHERE THE MAGIC METHODS ARE PLACED//
	/*******************************************************************************************************/
	
    /**
	 * getter magic method...d0nt allow any alien properties to be retrive...
	 *
	 * @param unknown_type $prop
	 * @return property
	 */ 
	public function __get($prop){		
		$selfProperties = array('headerInclude','footerInclude','title','uniqueJS','uniqueCSS');
	    if(!in_array($prop,$selfProperties)){
	    	die("ERROR: UNKNOWN PROPERTY FOUND [".$prop."][GETTER]");
	    }
	    return $this->$prop;
	}
	/**
	 * the setter magic method...d0nt allow any alien properties to be seT.. ;-)
	 *
	 * @param unknown_type $_prop
	 * @param unknown_type $_value
	 */
	public function __set($_prop,$_value){
		$selfProperties = array('headerInclude','footerInclude','title','uniqueJS','uniqueCSS');		 
		if(!in_array($_prop,$selfProperties)){
			die("ERROR: UNKNOWN PROPERTY FOUND [".$_prop."][SETTER]");
		}
	}
	/**
	 * returns the string equivalent of the object???????
	 *
	 * @return the website name
	 */
	public function __toString(){
		return self::$website_name;
	}

}
?>