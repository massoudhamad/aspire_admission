<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicantstudylevel';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        $qualificationTypeID = $_POST['qualificationTypeID'];
        $studyLevelID = $_POST['studyLevelID'];
        $applicantID=$_POST['applicantID'];
        $userData = array(
            'applicantID'=>$applicantID,
            'qualificationTypeID'=>$qualificationTypeID,
            'studyLevelID'=>$studyLevelID
        );
    if($_REQUEST['action_type'] == 'add'){
        if($db->isFieldExist("applicantstudylevel","applicantID",$applicantID))
        {
            $condition=array('applicantStudyLevelID'=>$_REQUEST['applicantStudyLevelID']);
            $update = $db->update($tblName,$userData,$condition);
        }
        else
        {
            $insert = $db->insert($tblName,$userData);
        }
        $boolStatus=true;
    }
    else if($_REQUEST['action_type']=='edit')
    {
        $condition=array('applicantStudyLevelID'=>$_REQUEST['applicantStudyLevelID']);
        $update = $db->update($tblName,$userData,$condition);
        $boolStatus=true;
    }
    
    if($boolStatus)
    {
        if(isset($_POST['doSubmit']))
        {
            $db->redirect("index3.php?sp=applicantdetails&applicantID=$applicantID");
        }
        else
        {
            header("Location:index3.php?sp=edit_study&applicantID=$applicantID&msg=unsucc");
        }
    }
    else
    {
        $db->redirect("index3.php?sp=edit_study&applicantID=$applicantID&msg=unsucc");
    }

}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=edit_study&applicantID=$applicantID&msg=error");
}