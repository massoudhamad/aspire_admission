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
            $gradePoints=$_POST['gradePoints'];
            $entry_qualification=$_POST['entry_qualification'];
            $qualificationTypeID=$_POST['qualificationTypeID'];
            $applicationYearID = $db->getData('applicants', 'applicationYearID', 'applicantID', $applicantID);
            $admissionID = $db->getData('applicants', 'admissionID', 'applicantID', $applicantID);
            $userData = array(
                'applicantID'=>$_SESSION['applicantID'],
                'applicationYearID'=>$applicationYearID,
                'admissionID'=>$admissionID,
                'schoolName'=>$instituteName,
                'yearTaken'=>$indexYear,
                'indexNumber'=>$registrationNumber,
                'avn_number'=>$_POST['avn_number'],
                'examinationAuthority'=>$qualificationTypeID,
                'examinationLevel'=>$examinationlevel,
                'award'=>$programmeName,
                'gradeType'=>"GPA",
                'gradePoints'=>$gradePoints,
                'entryqualification'=>$entry_qualification,
                'applicantResultStatus'=>1,
                'levelStatus'=>2,
                'resultStatus'=>1
            );

            $insert = $db->insert($tblName,$userData);
            $appData=array(
                'entryQualification'=>1
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

    }
    if($_POST['doExit'])
    {
        header("Location:index.php?sz=education_background");
    }
}catch (PDOException $ex)
{
    $db->redirect("index.php?sz=equivalent&msg=error");
}