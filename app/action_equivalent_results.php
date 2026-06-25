<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
include '../DB.php';
$db = new DBHelper();
$tblName = 'applicantresults';
$tblSubjects='equivalentresults';
$applicantID=$_SESSION['applicantID'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $indexYear=$_POST['indexYear'];
        $registrationNumber=$_POST['registrationNumber'];
        $instituteName=$_POST['instituteName'];
        $examinationlevel="Equivalent";
        $programmeName=$_POST['programmeName'];
        $gradeType=$_POST['gradeType'];
        if($gradeType=="GPA")
            $gradePoints=$_POST['gradePoints'];
        else
            $gradePoints=$_POST['gradeValue'];
        $entry_qualification=$_POST['entry_qualification'];
        $qualificationTypeID=$_POST['qualificationTypeID'];
        $applicationYearID = $db->getData('applicants', 'applicationYearID', 'applicantID', $applicantID);
        $userData = array(
            'applicantID'=>$_SESSION['applicantID'],
            'applicationYearID'=>$applicationYearID,
            'schoolName'=>$instituteName,
            'yearTaken'=>$indexYear,
            'indexNumber'=>$registrationNumber,
            'examinationAuthority'=>$qualificationTypeID,
            'examinationLevel'=>$examinationlevel,
            'award'=>$programmeName,
            'gradeType'=>$gradeType,
            'gradePoints'=>$gradePoints,
            'entryqualification'=>$entry_qualification,
            'applicantResultStatus'=>1,
            'levelStatus'=>2,
            'resultStatus'=>0
        );

        $insert = $db->insert($tblName,$userData);
        $appData=array(
            'entryQualification'=>$entry_qualification
        );
        $condition=array('applicantID'=>$_SESSION['applicantID']);
        $update=$db->update("applicants",$appData, $condition);
        
        $boolStatus=true;
         if($boolStatus)
        {
            header("Location:index.php?sz=education_background&msg=succ");
        }
        else
        {
            header("Location:index.php?sz=education_background&msg=unsucc");
        }
    }
    
   else if($_REQUEST['action_type'] == 'dropSchool')
    {
        if(!empty($_GET['id'])){
            $condition = array('applicantResultID' => $_GET['id']);
            
            $update = $db->delete($tblName,$condition);
            $appData=array(
            'entryQualification'=>0
        );
        $conditions=array('applicantID'=>$_SESSION['applicantID']);
        $updates=$db->update("applicants",$appData, $conditions);
            
            $statusFlag=true;
            $db->redirect("index.php?sz=education_background&msg=dropSchool");
        }
    }
}
if($_POST['doExit'])
{
    header("Location:index.php?sz=education_background");
}
}catch (PDOException $ex)
{
    $db->redirect("index.php?sz=equivalent&msg=error");
}