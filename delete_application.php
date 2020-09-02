<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'delete'){
        if(!empty($_REQUEST['applicantID'])){
            $userID=$db->getData("applicants","userID","applicantID",$_REQUEST['applicantID']);
            $condition_app = array('applicantID' => $_REQUEST['applicantID']);
            $condition_user = array('userID' => $userID);
            $applicantResultID=$db->getData("applicantresults","applicantResultID","applicantID",$_REQUEST['applicantID']);
            $cond_subject=array('applicantResultID'=>$applicantResultID);
            $delete_p=$db->delete("applicantapplication",$condition_app);
            $delete_p=$db->delete("applicantregistration",$condition_app);
            $delete_sub=$db->delete("applicantsubjects",$cond_subject);
            $delete_res=$db->delete("applicantresults",$condition_app);
            $delete_rmk=$db->delete("applicantremarks",$condition_app);
            $delete_app=$db->delete("applicants",$condition_app);
            $delete_user=$db->delete("users",$condition_user);
            $statusFlag=true;
            header("Location:index3.php?sp=viewbyremarks");

        }

    }
}
?>