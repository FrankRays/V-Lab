<?php
	session_start();
	if(isset($_SESSION['username'])){	$name = $_SESSION['username']; }
	require_once('db.php');
	$lab_name = $_GET['lab_name'];
	$lab_id = $_GET['lab_id'];
	echo "<u><b>Upload files to the ".$lab_name."</b></u><br/>";
	
	$sql=mysql_query("SELECT DISTINT ");
?>