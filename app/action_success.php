<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'users';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            $userData = array(
                'login'=>1
            );

            $condition=array('userID'=>$_SESSION["user_session"]);
            $update=$db->update($tblName,$userData,$condition);
            $boolStatus=true;
            if($boolStatus)
            {
                header("Location:index.php");
            }
            else
            {
                header("Location:index.php");
            }
        }
    }
}catch (PDOException $ex)
{
    //echo "Data Error".$ex->getMessage();
    header("Location:index.php");
}