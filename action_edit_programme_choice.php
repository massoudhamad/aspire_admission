<?php
session_start();
try {
include 'DB.php';
$applicantID=$_POST['applicantID'];
$db = new DBHelper();
$tblName = 'applicantapplication';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        $firstChoice=$_POST['programmeMajorID'];
        $secondChoice=$_POST['secondProgrammeMajorID'];
        $firstID=$_REQUEST['firstID'];
        $secondID=$_REQUEST['secondID'];
        
        $userData1= array(
            'applicantID'=>$applicantID,
            'programmeMajorID'=>$firstChoice,
            'choice'=>1,
            'admissionStatus'=>0
        );
         $userData2= array(
            'applicantID'=>$applicantID,
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
            header("Location:index3.php?sp=applicantdetails&applicantID=$applicantID");
        }
        else
        {
            header("Location:index3.php?sp=edit_programme_choice&applicantID=$applicantID&msg=unsucc");
        }
}
if(isset($_POST['doExit']))
{
    header("Location:index3.php?sp=applicantdetails&applicantID=$applicantID");
}
} catch (PDOException $ex) {
    $db->redirect("Location:index3.php?sp=edit_programme_choice&applicantID=$applicantID&msg=error");
}