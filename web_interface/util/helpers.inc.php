<?php
/**
 * The prime porpose of this file is to provide helper functions to ease the development process
 * in shot  KAPOY CODE DAGHAN!!!!!!!!!!!!!!!!!!!!!!!!
 */

/**
 * a function that mimics a javascript alert method.....
 *
 * @param string $msg
 */
function alert($msg){ 

  //borrowed from javascript...:-)
  echo "<script language=\"javascript\">\n";
  echo "\t<!--\n";
  echo "\twindow.alert(\"".$msg."\");\n";
  echo "\t//-->\n";
  echo "</script>\n";

}// end of alert() function

/**
 * a function that diverts a page
 *
 * @param string $destination
 */
function location($destination){ 

  //borrowed from javascript...:-)
  echo "<script language=\"javascript\">\n";
  echo "\t<!--\n";
  echo "\twindow.location = \"".$destination."\";\n";
  echo "\t//-->\n";
  echo "</script>\n";

} // end of location() function...

/**
 * slices a string given the length...
 *
 * @param unknown_type $str
 * @param unknown_type $len
 * @return string
 */
function slicebread($str,$len = 3){   // a function that slices a string...

 $glue = array();
 $newStr = "";
   
   if($len > strlen($str)){  
        $len = strlen($str);   
   }
   
   for($r = 0; $r <= $len; $r++){
   
      $glue[$r] = $str{$r};
   
   }
   
   foreach($glue as $value){
   
     $newStr = $newStr.$value;
   
   }

 return trim($newStr) . "...";

}// end of slicebread() function.....


/**
 * this function checks if their are duplicate files in the move destination folder...
 *
 * @param unknown_type $uploadedFIle
 * @param unknown_type $dir
 * @return unknown
 */
function check_duplicate_uploaded_file($uploadedFIle,$dir){

    if(is_dir($dir)){
        	     
        $imagefile = basename($uploadedFIle);
    	$ok2upload = true;  // used to check if the uploaded file is ok to move... 
    	$dirhandle = opendir($dir) or die("unable to open $dir directory");
    			   
    		if($dirhandle){
    			   
    			while(false !== ($file = readdir($dirhandle))){
    				  
    				if($file == $imagefile){
    				    
    				   $ok2upload = false;  // check if the file already exists in the folder its being moved to.....					  
    				   break;
    					
    				} 
    				  
    			}
    			   
    		}
    			   
    	closedir($dirhandle); //or die("could no close $uploadDir directory");
    			  
    	return $ok2upload;
    			  			 
    }
    else{
    
        return false;  // if the dir does not exists ;-(
    
    }

}  // end of check_duplicate_uploaded_file() function....

/**
 * this function checks the type of file allowed to be uploaded is acceptable...
 *
 * @param string $chkfile
 * @param string $allowedtypes
 * @return bool
 */
function check_type($chkfile,$allowedtypes){ // this function checks the type of file allowed to be uploaded
   
	if($allowedtypes != "ALLOW_ALL"){
		
		$allowed = explode(",",$allowedtypes);
	    $ext = explode(".",$chkfile);
	    
	    if(count($ext) != 0){
	    	  
		    if(in_array($ext[count($ext) - 1],$allowed)){    	  
		         return true;    	  
		    }
		    else{
		    	 return false;		  
		    }
	    
	    }
	    else{
	    	
	    	return false;
	    	
	    }
	    
	}
	else{
		
		return true;
		
	} 
    	  
} //end of check_type() function....

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * returns an array of file names located inside a certain folder/directory and returns false on failure
 *
 * @param unknown_type $dirpath
 * @return array / bool
 */
function traverse_dir($dirpath){
	
	$filesarr = array();
	
	if(is_dir($dirpath)){
		
	  $dp = opendir($dirpath);
	  if($dp){
	  	
	  	while (($file = readdir($dp)) !== false){
	  		
	  		if(($file != ".") && ($file != "..")){
	  			
	  			$filesarr[] = $file;
	  			
	  		}
	  		
	  	}
	  	
	  	return $filesarr;
	  	
	  }
	  else{
	  	
	  	return false;
	  	
	  }	
		
	}
	else {
		
		return false;
		
	}
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * deletes a certain file...
 *
 * @param string $filepath
 * @return bool
 */
function delete_file($filepath){
	
	if(is_file($filepath)){
		
		$fhandler = fopen($filepath,"r") or die("UNABLE TO OPEN ".$filepath." FILE!");
	    fclose($fhandler) or die("UNABLE TO CLOSE ".$filepath." FILE!");           
	    unlink($filepath) or die("ERROR: unable to remove ".$filepath." from internal folder...");
	    
	    return true;
    
	}
	else{
		
		return false;
		
		
	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * a generic upload mechanism 8-)
 *
 * @param string $filename
 * @param stringe $dirpath
 * @param string $allowedfiles[optinal]
 * @return true on success ;-) and a string if failure ;-(
 */
function upload_machine($filename,$dirpath,$allowedfiles = "ALLOW_ALL"){
	
	$fn = $filename;
	$thefile = $dirpath.basename($_FILES[$fn]['name']);
	$cutdownname = basename($_FILES[$fn]['name']);
	
	if(isset($_FILES)){		
		if(isset($_FILES[$fn])){			
			if($_FILES['error'] == 0){				
				if(check_type($cutdownname,$allowedfiles)){					
					if(check_duplicate_uploaded_file($thefile,$dirpath) == true){						
						if(move_uploaded_file($_FILES[$fn]['tmp_name'],$thefile)){							
							return true;							
						}
						else{							
							return "AN UNKNOWN ERROR HAS OCCURED AND THE FILE WAS NOT UPLOADED, PLEASE TRY AGAIN...";							
						}						
					}
					else{				
				       return "SORRY THIS FILE ALREADY EXISTS AND WAS NOT UPLOADED, TRY RENAMING THE FILE";	
					}					
				}
				else{
				    return "FILE TYPE NOT SUPPORTED...[".$cutdownname."]";
				}				
			}
			else{				
				return "ERROR[".$_FILES[$fn]['error']."]: File uploading failure...";
			}			
		}
		else{					
			return "THE FILE ".$fn." WAS NOT SET...";
		}		
	}
	else{		
		return '$_FILES global variable was not set';
		
	}	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * returns a developer friendly mysql error message... ;-)
 *
 * @param unknown_type $thequery
 * @return unknown
 */
function mysql_error_msg($thequery){
	
	$str = "ERROR ON QUERY: [".$thequery."]<b>[".mysql_error()."]</b>";
	return $str;
	
}
/**
 * this function deletes all the files in the directory given to it....except other directories/folders with in that directory.
 *
 * @param string $dir
 * @return bool
 */
function shreader($dir){
	
	$error_cnt = 0;
	if(is_dir($dir)){
		
		$files = traverse_dir($dir);
		if((is_array($files)) && $files != false){
			
			foreach($files as $value){
				
				if(!is_dir($files)){
					
					$delfile = $dir."/".$value;
					if(!delete_file($delfile)){
						$error_cnt++;		
					}
					
				}
				
			}
			
			if($error_cnt !== 0){				
				return false;				
			}
			else{				
				return true;				
			}
			
		}
	
	}
	else{
		
		return false;
		
	}
	
}
/**
 * a funtion that gets the result of a SELECT query given to it as a parameter...
 * MySQL only!!!
 *
 * @param string $_query
 * @return bool | array
 */
function getDBResults($_query){	
	
	$resultArr = array();
	$_cnt = 0;
	$regExp = "/^SELECT [A-z0-9`\*\. ,()]{1,} FROM [A-z0-9`\. ]{1,}( .{1,})?$/i";
	if(preg_match($regExp,$_query) === 0){
		die("ERROR: QUERY PASSED TO THE getDBResult() HELPER FUNCTION IS NOT A VALID SELECT QUERY!");
	}
	$_result = '';
	$_result = mysql_query($_query) or die(mysql_error_msg($_query));
	if(mysql_num_rows($_result) <= 0){
		return false;
	}
	else{
		while($row  = mysql_fetch_assoc($_result)){
			foreach($row as $field=>$value){				
				$resultArr[$_cnt][$field] = $value;
			}
			$_cnt++;
		}
		mysql_free_result($_result);
		return $resultArr;
		unset($resultArr); //cleans the result array....
	}	
	
}
/**
 * this function prints an html redirection code when javascript is not enable--> ^___^
 *
 * @param string $_redirection_page_path
 */
function ifJSDisabled($_redirection_page_path){
	
	?>
	<noscript>	
	<meta http-equiv="refresh" content="2; URL="<?php echo $_redirection_page_path; ?>">
	<center>
	  <h2 style="font-family: verdana;">Sorry but this application need JavaScript to be enabled!</h2>
	</center>
	</noscript>
    <?php
    	
}// end of ifJSDisabled() function..

/*******************************************
 * Author: eL rafA gAndi                   *
 * Email:  rafaelgandi@yahoo.com           *
 * IDE:    Eclipe PDT 1.0.0                *
 *******************************************/
?>