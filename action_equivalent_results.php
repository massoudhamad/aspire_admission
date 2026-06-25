<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicantresults';
$tblSubjects='equivalentresults';
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
        $userData = array(
            'applicantID'=>$_SESSION['applicantID'],
            'applicationYearID'=>1,
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
        $condition=array('applicantID'=>$_SESSION['applicantID']);
        $update=$db->update("applicants",$appData, $condition);
        
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
    
   else if($_REQUEST['action_type'] == 'dropSchool')
    {
        if(!empty($_GET['id'])){
            $condition = array('applicantResultID' => $_GET['id']);
            
            $update = $db->delete($tblName,$condition);
            $delete=$db->delete($tblSubjects,$condition);
            
            $appData=array(
            'entryQualification'=>0
        );
        $conditions=array('applicantID'=>$_SESSION['applicantID']);
        $updates=$db->update("applicants",$appData, $conditions);
            
            $statusFlag=true;
             $db->redirect("index2.php?sz=education_background&msg=dropSchool");
        }
    }
    else if($_REQUEST['action_type'] == 'dropSubject')
    {
        
        if(!empty($_GET['id'])){
            $condition = array('equivalentResultsID' => $_GET['id']);
            $update = $db->delete($tblSubjects,$condition);
            $statusFlag=true;
            $db->redirect("index2.php?sz=education_background&msg=dropSubject");
        }
        
    }

}
if($_POST['doExit'])
{
    header("Location:index2.php?sz=education_background");
}
}catch (PDOException $ex)
{
    echo "Data Error".$ex->getMessage();
    //$db->redirect("index2.php?sz=equivalent&msg=error");
}