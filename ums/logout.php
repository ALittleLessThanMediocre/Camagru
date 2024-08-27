<?php 
	include_once ("session.php");
	include_once ("utils.php");
	
	session_unset();
	session_destroy();
	redirectTo("../index");
?>