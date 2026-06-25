<?php
	if (session_status() === PHP_SESSION_NONE) session_start();
	require_once 'DB.php';
	$session = new DBHelper();
	
	if(!$session->is_loggedin())
	{
		// session no set redirects to login page
		//$session->redirect('index.php');
            header("Location:index.php");
	}
?>