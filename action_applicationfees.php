<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/*ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
*/
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicationfees';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $studyLevelID = $_POST['studyLevelID'];
        $academicYearID = $_POST['admissionYearID'];
        $amount = $_POST['amount'];
        $userData = array(
            'studyLevelID' => $studyLevelID,
            'academicYearID'=>$academicYearID,
            'fees' => $amount
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
       header("Location:index3.php?sp=applicationfees&msg=succ");
        
    }elseif($_REQUEST['action_type'] == 'drop'){
            $condition = array('applicationFeesID' => $_REQUEST['id']);
            $update = $db->delete($tblName,$condition);
            $statusMsg = true;
           header("Location:index3.php?sp=applicationfees&msg=drop");
        }
}