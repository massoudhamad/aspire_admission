<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
try {
    include '../DB.php';
    $db = new DBHelper();
    //$applicantID=$_REQUEST['id'];
    $tblName = 'applicantresults';
    $tblSubjects='applicantsubjects';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add') {
            $applicantID = $_POST['applicantID'];
            $exam_body = $_POST['exam_body'];
            $indexYear = $_POST['indexYear'];
            $schoolName = $_POST['schoolName'];
            $examinationlevel = $_POST['examinationlevel'];
            $examinationaward = $_POST['examinationaward'];
            $indexNumber = $_POST['indexNumber'];

            if ($exam_body == "NECTA") {
                $indexNumber = $_POST['indexNumber'];

            } else {
                $indexNumber = $_POST['indexNumberOther'];
                $exam_body="Others";
            }
            if ($examinationaward == "formfour")
                $award = "CSEE";
            else
                $award = "ACSEE";
             $applicationYearID = $db->getData('applicants', 'applicationYearID', 'applicantID', $applicantID);
            $admissionID = $db->getData('applicants', 'admissionID', 'applicantID', $applicantID);
            $examNumber = strtoupper($indexNumber);
            if ($db->isIndexNumberExist($examNumber, $admissionID)) {
                $boolStatus = false;
            } else {
                $userData = array(
                    'applicantID' => $applicantID,
                    'applicationYearID'=>$applicationYearID,
                    'schoolName' => $schoolName,
                    'yearTaken' => $indexYear,
                    'indexNumber' => $examNumber,
                    'examinationAuthority' => $exam_body,
                    'examinationLevel' => $examinationlevel,
                    'award' => $award,
                    'gradeType' => 'Points',
                    'applicantResultStatus' => 1,
                    'levelStatus' => 2,//First Sit
                    'resultStatus' => 0//1-Verified or 0-not Verified
                );

                $insert = $db->insert($tblName, $userData);
                $applicantResultID = $insert;
                $points = 0;
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
                if ($boolStatus) {
                    header("Location:index.php?sz=education_background&msg=succ");
                } else {
                    header("Location:index.php?sz=education_background&msg=unsucc");
                }
            }
        }

       /*else if($_REQUEST['action_type'] == 'dropSchool')
        {
            if(!empty($_GET['id'])){
                $condition = array('applicantResultID' => $_GET['id']);

                $update = $db->delete($tblName,$condition);
                $delete=$db->delete($tblSubjects,$condition);
                $statusFlag=true;
                $db->redirect("index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=dropSchool");
            }
        }
        else if($_REQUEST['action_type'] == 'dropSubject')
        {

            if(!empty($_GET['id'])){
                $condition = array('applicantSubjectID' => $_GET['id']);
                $update = $db->delete($tblSubjects,$condition);
                $statusFlag=true;
                $db->redirect("index3.php?sp=educational_background&applicantID=$applicantID&msg=dropSchool");
            }

        }*/

    }
    if(isset($_POST['doProceed']))
    {
        header("Location:index.php?sz=programmechoice");
    }
} catch (PDOException $ex) {
    $db->redirect("index.php?sz=other_ordinary_results&id=".$applicantID."&inumber=".$indexNumber."&msg=error");
}