<?php
session_start();
$_SESSION = array();
unset($_SESSION['subjectsChecked']);
session_destroy();

if(!isset($_SESSION['subjectsChecked'])){
	echo "session was destroyed.....";
}
?>