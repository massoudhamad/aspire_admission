<?php 
require_once("DB.php");
$auth_user = new DBHelper();
if(!$auth_user->is_loggedin()) 
{
    $auth_user->redirect('home.php');
} 
  /*$userID = $_SESSION['user_session'];
  $user_privilege=$_SESSION['user_privilege']; 
switch((isset($_GET['sz'])?$_GET['sz'] : ''))
{
      case 'home':
      include('home.php');
      break;
  
      default:
      include('frontpage.php');
}*/
?>