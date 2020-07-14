<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'applicantresults';
    $tblSubjects='applicantsubjects';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            $applicantID = $_POST['applicantID'];
            $exam_body = "NECTA";
            $indexYear = $_POST['yearTaken'];
            $schoolName = $_POST['schoolName'];
            $examinationlevel = $_POST['examinationlevel'];
            $examinationaward = $_POST['examinationaward'];
            $indexNumber = $_POST['indexNumber'];
            $numberSubjects = $_POST['numbersubjects'];
            if ($examinationaward == "formfour")
                $award = "CSEE";
            else
                $award = "ACSEE";
            $applicationYearID = $db->getData('applicants', 'applicationYearID', 'applicantID', $applicantID);
            $examNumber = strtoupper($indexNumber);
            if ($db->isIndexNumberExist($examNumber, $applicantID)) {
                $boolStatus = false;
            } else {
                /*$userData = array(
                    'schoolName' => $schoolName,
                    'applicationYearID' => $applicationYearID,
                    'yearTaken' => $indexYear,
                    'indexNumber' => $examNumber,
                    'examinationAuthority' => $exam_body,
                    'examinationLevel' => $examinationlevel,
                    'award' => $award,
                    'gradeType' => 'Points',
                    'applicantResultStatus' => 1,
                    'levelStatus' => 1,//First Sit
                    'resultStatus' => 1//Verified or not Verified
                );
                $condition = array('applicantID' => $applicantID);
                $insert = $db->update($tblName, $userData, $condition);*/
                //$applicantResultID=$insert;
                $userData = array(
                    'applicantID'=>$applicantID,
                    'applicationYearID'=>$applicationYearID,
                    'schoolName'=>$schoolName,
                    'yearTaken'=>$indexYear,
                    'indexNumber'=>$examNumber,
                    'examinationAuthority'=>$exam_body,
                    'examinationLevel'=>$examinationlevel,
                    'award'=>$award,
                    'gradeType'=>'Points',
                    'applicantResultStatus' => 0,
                    'levelStatus' => 0,//First Sit
                    'resultStatus' => 1
                );

                $insert = $db->insert($tblName,$userData);
                $applicantResultID=$insert;
                //$applicantResultID = $db->getData('applicantresults', 'applicantResultID', 'applicantID', $applicantID);
                $points = 0;
                foreach ($_POST["subjectCode"] as $code => $subjectCode) {
                    foreach ($_POST["gradeCode"] as $grade => $gradeCode) {
                        if ($code == $grade) {
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
                            if ($db->isSubjectExist($applicantResultID, $subjectCode) == false) {
                                $gradeID = $db->getGradeID($gradeCode, $indexYear, $level);
                                $subjectID = $db->getSubjectID($subjectCode, $stream);
                                $gradePoints = $db->getData("grades", "gradePoint", "gradeID", $gradeID);
                                $applicantResultData = array(
                                    'applicantResultID' => $applicantResultID,
                                    'subjectID' => $subjectID,
                                    'gradeID' => $gradeID,
                                    'points' => $gradePoints,
                                    'status'=>$status
                                );
                                $insert = $db->insert($tblSubjects, $applicantResultData);
                            }
                        }

                    }
                    $boolStatus = true;
                }

            }
            if ($boolStatus) {
                header("Location:index.php?sz=education_background&msg=succ");
            } else {
                header("Location:index.php?sz=education_background&msg=unsucc");
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