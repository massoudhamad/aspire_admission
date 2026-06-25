<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
//try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'applicantresults';
    $tblSubjects='applicantsubjects_equivalence';
    $applicantID = $_SESSION['applicantID'];
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add') {
        $indexYear = $_POST['academic_year'];
        $registrationNumber = $_POST['reg_number'];
        $examinationlevel = "Equivalent";
        $gradePoints = $_POST['gpa'];
        $entry_qualification = $_POST['entry_qualification'];
        $qualificationTypeID = $_POST['qualificationTypeID'];
        $applicationYearID = $db->getData('applicants', 'applicationYearID', 'applicantID', $applicantID);
        $admissionID = $db->getData('applicants', 'admissionID', 'applicantID', $applicantID);
        $numberSubjects=$_POST['numbersubjects'];
        $examNumber = strtoupper($_POST['reg_number']);

                $userData = array(
                    "schoolName" => "OUT",
                    "applicationYearID" => $applicationYearID,
                    "applicantID"=>$applicantID,
                    "yearTaken" => $indexYear,
                    "indexNumber" => $examNumber,
                    'avn_number' => $_POST['reg_number'],
                    "examinationAuthority" => 2,
                    "examinationLevel" => "Equivalent",
                    "award" => "Foundation Programme",
                    "gradeType" => 'GPA',
                    'gradePoints' => $gradePoints,
                    'entryqualification' => 2,
                    'applicantResultStatus' => 1,
                    'levelStatus' => 2,
                    'resultStatus' => 1
                );
                $insert = $db->insert($tblName, $userData);
                $appData = array(
                    'entryQualification' => 1
                );
                $condition = array('applicantID' => $_SESSION['applicantID']);
                $update = $db->update("applicants", $appData, $condition);

                $applicantResultID=$insert;

                /* if($examinationlevel=="Advance")
                {
                    $insert = $db->insert($tblName, $userData);
                    $applicantResultID = $insert;
                }
                else 
                {
                    $condition = array('applicantID' => $applicantID);
                    $insert = $db->update($tblName, $userData, $condition);
                    $applicantResultID = $db->getData('applicantresults', 'applicantResultID', 'applicantID', $applicantID);
                } */
                
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