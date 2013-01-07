<?php
/**
 * This is the script that checks if the user is logged in...
 */
if (!isset($_SESSION['studentNo'])){
	header("Location: login.php");
}
?>