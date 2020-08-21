<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
try {
include '../DB.php';
$db = new DBHelper();
$tblName = 'applicantapplication';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        $firstChoice=$_POST['programmeID'];
        $secondChoice=$_POST['progID'];
        $firstID=$_REQUEST['firstID'];
        $secondID=$_REQUEST['secondID'];
        
        $userData1= array(
            'applicantID'=>$_SESSION['applicantID'],
            'programmeMajorID'=>$firstChoice,
            'choice'=>1,
            'admissionStatus'=>0
        );
         $userData2= array(
            'applicantID'=>$_SESSION['applicantID'],
            'programmeMajorID'=>$secondChoice,
            'choice'=>2,
            'admissionStatus'=>0
        );

        if($_REQUEST['action_type'] == 'add'){
        $insert = $db->insert($tblName,$userData1);
        $insert = $db->insert($tblName,$userData2);
        $boolStatus=true;
        }
        else if($_REQUEST['action_type'] == 'edit'){
        $condition1=array('applicantApplicationID' => $firstID);
        $condition2=array('applicantApplicationID' => $secondID);
        $insert = $db->update($tblName,$userData1,$condition1);
        $insert = $db->update($tblName,$userData2,$condition2);
        $boolStatus=true;
        }
        if($boolStatus)
        {
            header("Location:index.php?sz=personalinfo");
        }
        else
        {
            header("Location:index.php?sz=programme_choice&msg=unsucc");
        }
}
if(isset($_POST['doExit']))
{
    header("Location:logout.php?logout=true");
}
} catch (PDOException $ex) {
    $db->redirect("index.php?sz=programme_choice&msg=error");
}