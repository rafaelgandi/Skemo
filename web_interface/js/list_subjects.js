function ajax_list_subj(viewType)
{
	// add loading image 02-17-09//
	document.getElementById("list_subj").innerHTML = "<img src='" + SKEMO.GLINKS.loader + "' alt='' /><b>Loading...</b>";
 xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("CANT LOAD AJAX... BROWSER ERROR RETURNED");
  return;
  } 
 var url="list_subjects.php";
 url=url+"?query="+viewType;
 
 xmlHttp.open("GET",url,true);
 xmlHttp.onreadystatechange=stateChanged;
 xmlHttp.send(null);
}
// END OF popWindow
function stateChanged() 
{ 
	if (xmlHttp.readyState == 4)
	{ 
	 document.getElementById("list_subj").highlight(); // added highlight effect 02-17-09
	 document.getElementById("list_subj").innerHTML=xmlHttp.responseText;
	}
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;
}