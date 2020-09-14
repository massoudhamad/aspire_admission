<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
//try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'applicantresults';
    $tblSubjects='applicantsubjects_equivalence';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add') {
            $applicantID = $_POST['applicantID'];
            $exam_body = "Others";
            $indexYear = $_POST['yearTaken'];
            $schoolName = $_POST['schoolName'];
            $examinationlevel = $_POST['examinationlevel'];
            $examinationaward = $_POST['examinationaward'];
            $indexNumber = $_POST['indexNumber'];
            $numberSubjects=$_POST['numbersubjects'];
            if ($examinationaward == "formfour")
                $award = "CSEE";
            else
                $award = "ACSEE";
            $applicationYearID = $db->getData('applicants', 'applicationYearID', 'applicantID', $applicantID);
            $examNumber = strtoupper($indexNumber);

                $userData = array(
                    "schoolName" => addslashes($schoolName),
                    "applicationYearID" => $applicationYearID,
                    "yearTaken" => $indexYear,
                    "indexNumber" => $examNumber,
                    "examinationAuthority" => $exam_body,
                    "examinationLevel" => $examinationlevel,
                    "award" => $award,
                    "gradeType" => 'Points',
                    "applicantResultStatus" => 1,
                    "levelStatus" => 1,//First Sit
                    "resultStatus" => 1//Verified or not Verified
                );
                $condition = array('applicantID' => $applicantID);
                $insert = $db->update($tblName, $userData, $condition);
                $applicantResultID = $db->getData('applicantresults', 'applicantResultID', 'applicantID', $applicantID);
                $points = 0;
            foreach($_POST["subjectName"] as $code => $subjectName) {
                foreach ($_POST["gradeCode"] as $grade => $gradeCode) {
                    if($code==$grade) {
                            $applicantResultData = array(
                                'applicantResultID' => $applicantResultID,
                                'applicantID'=>$applicantID,
                                'subjectName' => $subjectName,
                                'gradeCode' => $gradeCode
                                );
                            $insert = $db->insert($tblSubjects, $applicantResultData);
                    }
                }
                $boolStatus = true;
            }

            if ($boolStatus) {
                header("Location:index.php?sz=education_background&msg=succ");
            } else {
                header("Location:index.php?sz=confirm_ordinary_results&msg=unsucc");
            }

            }
    }
   /* if($_POST['doExit'])
    {
        header("Location:index.php?sz=education_background");
    }*/
/*} catch (PDOException $ex) {
    $db->redirect("index.php?sz=confirm_ordinary_results&msg=error");
}*/