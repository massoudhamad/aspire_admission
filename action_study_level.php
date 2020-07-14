<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicantstudylevel';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        $qualificationTypeID = $_POST['qualificationTypeID'];
        $studyLevelID = $_POST['studyLevelID'];
        $userData = array(
            'applicantID'=>$_SESSION['applicantID'],
            'qualificationTypeID'=>$qualificationTypeID,
            'studyLevelID'=>$studyLevelID
        );
    if($_REQUEST['action_type'] == 'add'){
        if($db->isFieldExist("applicantstudylevel","applicantID",$_SESSION['applicantID']))
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
            $db->redirect("index2.php?sz=education_background");
        }
        else
        {
            header("Location:logout.php?logout=true");
        }
    }
    else
    {
        $db->redirect("index2.php?sz=home&msg=unsucc");
    }

}
} catch (PDOException $ex) {
    $db->redirect("index2.php?sz=home&msg=error");
}