<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicationfees';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
     $studyLevelID=$_POST['studyLevelID'];
     $academicYearID=$_POST['admissionYearID'];
     $amount=$_POST['amount'];
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'studyLevelID' => $studyLevelID,
            'academicYearID'=>$academicYearID,
            'fees' => $amount
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=applicationfees&msg=succ");
        
    }elseif($_REQUEST['action_type'] == 'drop'){
            $condition = array('applicationFeesID' => $_POST['id']);
            $update = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=applicationfees&msg=drop");
        }
}