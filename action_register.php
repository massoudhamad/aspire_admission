<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
//try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'users';
$tblUserRole='userroles';
$tblApplicants='applicants';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $academicYear=$db->getData("academicyears","academicYear","academicYearStatus",1);
        $year=explode("/",$academicYear);
        $year1=$year[0];
        $year1Sub=substr((string)$year1,2,3);//17
        $year2=$year[1];
        $year2Sub=substr((string)$year2,2,3);
        $applicationNumber=$year1Sub.$year2Sub.rand(1,99999);
        if ($db->isFieldExist('applicants', 'applicationNumber',$applicationNumber))
            $applicationNumber=$year1Sub.$year2Sub.rand(1,99999);
        else
            $applicationNumber=$applicationNumber;

        $admission_level=$_POST['admission_level'];
        //if($admission_level=="UG") {
            $exam_body = $_POST['exam_body'];
            if ($exam_body == "NECTA") {
                $indexNumber = strtoupper($_POST['indexNumber']);
                $indexParts = explode('/', $indexNumber);
                $exam_year = isset($indexParts[2]) ? (int)$indexParts[2] : 0;
                $data = $db->fetchNectaResults($indexNumber, 1);
                if ($data === null) {
                    $_SESSION['flash_error'] = 'Unable to reach the NECTA verification service. Please try again later.';
                    header('Location: index.php?msg=neta_unreachable');
                    exit;
                }

                if ($data['status']['code'] == 1) {
                    //if (($data['particulars']['first_name'] == $fname) && ($data['particulars']['middle_name'] == $mname) && ($data['particulars']['last_name'] == $lname)) {
                        //Names
                        $fname=$data['particulars']['first_name'];
                        $mname=$data['particulars']['middle_name'];
                        $lname=$data['particulars']['last_name'];
                        $gender=$data['particulars']['sex'];
                        if($gender=="M")
                            $gender="Male";
                        else
                            $gender="Female";

                        $username = strtoupper($indexNumber);
                        $password = $db->PwdHash(strtoupper(trim($lname)));
                        if ($db->isFieldExist($tblName, 'userName', $username)) {
                            $boolStatus = false;
                            $msg = "exists";
                        } else {
                            $phoneNumber = $_POST['phoneNumber'];
                            $email = $_POST['email'];
                            $applicationYearID = $db->getData("academicyears", "academicYearID", "academicYearStatus", 1);
                            $admissionID=$db->getData("admission_setting","admissionID","yearStatus",1);

                            //add users first
                            $userData = array(
                                'userName' => $username,
                                'password' => $password,
                                'firstName' => $fname,
                                'middleName' => $mname,
                                'lastName' => $lname,
                                'gender' => $gender,
                                'email' => $email,
                                'phoneNumber' => $phoneNumber,
                                'status' => 1,
                                'login' => 0
                            );
                            $insert = $db->insert($tblName, $userData);
                            $userID = $insert;
                            $_SESSION['user_session'] = $userID;
                            $applicantsData = array(
                                'applicationNumber' => $applicationNumber,
                                'firstName' => $fname,
                                'middleName' => $mname,
                                'lastName' => $lname,
                                'gender' => $gender,
                                'phoneNumber' => $phoneNumber,
                                'applicationYearID' => $applicationYearID,
                                'admissionLevel' => $admission_level,
                                'eauthority' => $exam_body,
                                'applicantsRemarksID' => 7,
                                'admissionID'=>$admissionID,
                                'userID' => $userID
                            );

                            $insertApplicant = $db->insert($tblApplicants, $applicantsData);
                            $applicantID = $insertApplicant;

                            $userRolesData = array(
                                'userID' => $userID,
                                'roleID' => 2
                            );
                            $insert = $db->insert($tblUserRole, $userRolesData);

                            $applicantsResultsData = array(
                                'applicantID' => $applicantID,
                                'yearTaken' => $exam_year,
                                'indexNumber' => $indexNumber,
                                'examinationAuthority' => $exam_body,
                                'examinationLevel' => 'Ordinary',
                                'award' => 'CSEE',
                                'gradeType' => 'Points',
                                'applicantResultStatus' => 0
                            );

                            $insert = $db->insert("applicantresults", $applicantsResultsData);

                            $boolStatus = true;
                            $msg = "Username: " . $username . " and Password: " . $lname;
                            mail($email, 'Admission Online', $msg);
                            $_SESSION['user_session'] = $userID;

                        }
                    /*} else {
                        $boolStatus = false;
                        $msg = "details";
                    }*/
                } else {
                    $boolStatus = false;
                    $msg = "index";
                }
                if($boolStatus)
                    header("Location:app/index.php");
                else
                    header("Location:index.php?msg=$msg");
            }
            else if(($exam_body == "Others") || ($exam_body == "NECTAO")) {
                $indexNumber = $_POST['indexNumberOther'];
                $fname = strtoupper($_POST['fname']);
                $mname = strtoupper($_POST['mname']);
                $lname = strtoupper($_POST['lname']);
                $username = strtoupper($indexNumber);
                $password = $db->PwdHash(strtoupper(trim($_POST['lname'])));
                if ($db->isFieldExist($tblName, 'userName', $username)) {
                    $boolStatus = false;
                    $msg = "exists";
                } else {

                    $gender = $_POST['gender'];
                    $phoneNumber = $_POST['phoneNumber'];
                    $email = $_POST['emailad'];
                   /* $appyear = $_POST['applicationyear'];*/
                    $applicationYearID = $db->getData("academicyears", "academicYearID", "academicYearStatus", 1);
                    $admissionID=$db->getData("admission_setting","admissionID","yearStatus",1);

                    //add users first
                    $userData = array(
                        'userName' => $username,
                        'password' => $password,
                        'firstName' => $fname,
                        'middleName' => $mname,
                        'lastName' => $lname,
                        'gender' => $gender,
                        'email' => $email,
                        'phoneNumber' => $phoneNumber,
                        'status' => 1,
                        'login' => 0
                    );
                    $insert = $db->insert($tblName, $userData);
                    $userID = $insert;

                    $applicantsData = array(
                        'applicationNumber'=>$applicationNumber,
                        'firstName' => $fname,
                        'middleName' => $mname,
                        'lastName' => $lname,
                        'gender' => $gender,
                        'phoneNumber' => $phoneNumber,
                        'applicationYearID' => $applicationYearID,
                        'admissionLevel' => $admission_level,
                        'eauthority'=>$exam_body,
                        'applicantsRemarksID' => 7,
                        'admissionID'=>$admissionID,
                        'userID' => $userID
                    );

                    $insertApplicant = $db->insert($tblApplicants, $applicantsData);
                    $applicantID = $insertApplicant;

                    $userRolesData = array(
                        'userID' => $userID,
                        'roleID' => 2
                    );
                    $insert = $db->insert($tblUserRole, $userRolesData);

                    $applicantsResultsData = array(
                        'applicantID' => $applicantID,
                        'applicationYearID' => $applicationYearID,
                        'yearTaken' => $exam_year,
                        'indexNumber' => $indexNumber,
                        'examinationAuthority' => $exam_body,
                        'examinationLevel' => 'Ordinary',
                        'award' => 'CSEE',
                        'gradeType' => 'Points',
                        'applicantResultStatus' => 0
                    );

                    $insert = $db->insert("applicantresults", $applicantsResultsData);
                    $msg2="Username: ".$username." and Password: ".$password;
                    mail($email, 'Admission Online',$msg2);
                    $boolStatus = true;
                    header("Location:index.php?msg=succ");
                }

            }
        //}
    
    }
}

/*}catch (PDOException $ex) {
    header("Location:index.php?msg=error");
}*/