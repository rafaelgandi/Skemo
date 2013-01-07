<?php
/**
 * MySQL connection details.....
 */
$db_host = 'localhost';
$db_username = 'proj03';
$db_password = '!record';
$db = 'skemo';

$dh = mysql_connect($db_host,$db_username,$db_password) or die('Sorry unable to connect to MySQL');
mysql_select_db($db,$dh);
?>