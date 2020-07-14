<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
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
            header("Location:index2.php?sz=education_background&msg=succ");
        }
        else
        {
            header("Location:index2.php?sz=education_background&msg=unsucc");
        }
    }
    else if($_REQUEST['action_type'] == 'dropSubject')
    {
        
        if(!empty($_GET['id'])){
            $condition = array('applicantSubjectID' => $_GET['id']);
            $update = $db->delete($tblSubjects,$condition);
            $statusFlag=true;
            $db->redirect("index2.php?sz=education_background&msg=dropSchool");
        }
        
    }

}
if($_POST['doExit'])
{
    header("Location:index2.php?sz=education_background");
}
} catch (PDOException $ex) {
    $db->redirect("index2.php?sz=olevel&msg=error");
}
