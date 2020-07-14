<?php
session_start();
try {
include '../DB.php';
$db = new DBHelper();
$applicantResultID=$_POST['applicantResultID'];
$tblSubjects='applicantsubjects';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        foreach ($_POST["subjectCode"] as $code => $subjectCode) {
            foreach ($_POST["gradeCode"] as $grade => $gradeCode) {
                if ((!empty($subjectCode)) || (!empty($gradeCode))) {
                    if ($code == $grade) {
                        if ($db->isSubjectExist($applicantResultID, $subjectCode) == false) {
                            $gradePoints = $db->getData("grades", "gradePoint", "gradeID", $gradeCode);
                            if ($examinationaward == "formfour") {
                                if ($gradeCode == 'F' || $gradeCode == 'E')
                                    $status = 0;
                                else
                                    $status = 1;
                                $stream=1;
                                $level="olevel";
                            }
                            else
                            {
                                if ($gradeCode == 'F')
                                    $status = 0;
                                else
                                    $status = 1;
                                $stream=2;
                                $level="alevel";
                            }
                            $applicantResultData = array(
                                'applicantResultID' => $applicantResultID,
                                'subjectID' => $subjectCode,
                                'gradeID' => $gradeCode,
                                'points' => $gradePoints,
                                'status'=>$status
                            );
                            $insert = $db->insert($tblSubjects, $applicantResultData);
                        }
                    }
                }
            }
            $boolStatus = true;
        }
    /*    foreach($_POST["subjectCode"] as $code => $subjectCode){
        foreach($_POST["gradeCode"] as $grade => $gradeCode){
            if((!empty($subjectCode)) || (!empty($gradeCode)))
            {
                if($code==$grade){
                    if($db->isSubjectExist($applicantResultID,$subjectCode)==false)
                    {
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
        }
        }*/
/*        $boolStatus=true;*/
         if($boolStatus)
        {
            header("Location:index.php?sz=education_background&msg=succ");
        }
        else
        {
            header("Location:index.php?sz=education_background&msg=unsucc");
        }
    }
    else if($_REQUEST['action_type'] == 'dropSubject')
    {
        
        if(!empty($_GET['id'])){
            $condition = array('applicantSubjectID' => $_GET['id']);
            $update = $db->delete($tblSubjects,$condition);
            $statusFlag=true;
            $db->redirect("index.php?sz=education_background&msg=dropSchool");
        }
        
    }

}
if($_POST['doExit'])
{
    header("Location:index.php?sz=education_background");
}
} catch (PDOException $ex) {
    $db->redirect("index.php?sz=education_background&msg=error");
}
