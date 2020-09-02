<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'delete_user'){
        if(!empty($_REQUEST['id'])){
            $condition_user = array('userID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $delete_user=$db->delete("users",$condition_user);
            $statusFlag=true;
            header("Location:index3.php?sp=user");
        }

    }
}
?>