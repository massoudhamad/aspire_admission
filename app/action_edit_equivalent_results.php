<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$applicantID=$_REQUEST['applicantID'];
$tblName = 'applicantresults';
$tblSubjects='equivalentresults';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $applicantID=$_POST['applicantID'];
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
        $admissionID = $db->getData('applicants', 'admissionID', 'applicantID', $applicantID);
        $userData = array(
            'applicantID'=>$applicantID,
            'applicationYearID'=>$applicationYearID,
            'admissionID'=>$admissionID,
            'schoolName'=>$instituteName,
            'yearTaken'=>$indexYear,
            'indexNumber'=>$registrationNumber,
            'examinationAuthority'=>$qualificationTypeID,
            'examinationLevel'=>$examinationlevel,
            'award'=>$programmeName,
            'gradeType'=>$gradeType,
            'gradePoints'=>$gradePoints,
            'entryqualification'=>$entry_qualification
        );

        $insert = $db->insert($tblName,$userData);
        $applicantResultID=$insert;
        $points=0;
        foreach($_POST["subjectCode"] as $code => $subjectCode){
        foreach($_POST["gradeCode"] as $grade => $gradeCode){
        if ($code==$grade){
            $applicantResultData=array(
                'applicantResultID'=>$applicantResultID,
                'subjectName'=>$subjectCode,
                'grade'=>$gradeCode
            );
              $insert=$db->insert($tblSubjects,$applicantResultData); 
        }
        }
        }
        
        
        $appData=array(
            'entryQualification'=>$entry_qualification
        );
        $condition=array('applicantID'=>$applicantID);
        $update=$db->update("applicants",$appData, $condition);
        
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
    
   else if($_REQUEST['action_type'] == 'dropSchool')
    {
        if(!empty($_GET['id'])){
            $condition = array('applicantResultID' => $_GET['id']);
            
            $update = $db->delete($tblName,$condition);
            $delete=$db->delete($tblSubjects,$condition);
            
            $appData=array(
            'entryQualification'=>0
        );
        $conditions=array('applicantID'=>$applicantID);
        $updates=$db->update("applicants",$appData, $conditions);
            
            $statusFlag=true;
            $db->redirect("index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=dropSchool");
        }
    }
    else if($_REQUEST['action_type'] == 'dropSubject')
    {
        
        if(!empty($_GET['id'])){
            $condition = array('equivalentResultsID' => $_GET['id']);
            $update = $db->delete($tblSubjects,$condition);
            $statusFlag=true;
            $db->redirect("index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=dropSchool");
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