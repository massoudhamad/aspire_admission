<?php
session_start();
	if($_SESSION['role_session']==2)
	{
		include("home.php");
	}
	else if(($_SESSION['role_session']==1)||($_SESSION['role_session']==3)||($_SESSION['role_session']==4))
	{
		include("dashboard.php");
	}
        
?>