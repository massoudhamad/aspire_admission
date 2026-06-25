<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
include 'DB.php';
$db = new DBHelper();
$applicantID=$_POST['applicantID'];
$applicantResultID=$_POST['applicantResultID'];
$tblSubjects='applicantsubjects';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        foreach($_POST["subjectCode"] as $code => $subjectCode){
        foreach($_POST["gradeCode"] as $grade => $gradeCode){
        if ($code==$grade){
            $gradePoints=$db->getData("grades","gradePoint","gradeID",$gradeCode);
            $applicantResultData=array(
                'applicantResultID'=>$applicantResultID,
                'subjectID'=>$subjectCode,
                'gradeID'=>$gradeCode,
                'points'=>$gradePoints
            );
              $insert=$db->insert($tblSubjects,$applicantResultData); 
        }
        }
        }
        $boolStatus=true;
        if($boolStatus)
        {
            header("Location:index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=unsucc");
        }
    }
}
if($_POST['doExit'])
{
    header("Location:index3.php?sp=applicantdetails&applicantID=$applicantID");
}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=applicantdetails&applicantID=$applicantID&msg=error");
}