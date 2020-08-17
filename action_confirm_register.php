<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'users';
$tblUserRole='userroles';
$tblApplicants='applicants';

if ($_POST['doProceed'] == 'Proceed to Application') {
    $applicationYearID = $_POST['applicationYearID'];
    $applicationNumber = $_POST['applicationNumber'];
    $exam_body = $_POST['exam_body'];
    $admission_level = $_POST['admission_level'];
    $admissionID = $_POST['admissionID'];
    $exam_year = $_POST['indexYear'];
    $indexNumber = $_POST['indexNumber'];
    $fname = strtoupper($_POST['fname']);
    $mname = strtoupper($_POST['mname']);
    $lname = strtoupper($_POST['lname']);
    $email = $_POST['email'];
    $equivalence_number=$_POST['equivalence_number'];
    //$indexNumber=$indexNumber."/".$exam_year;
    $indexNumber=$indexNumber;

        if($admission_level=="UG") {
            if ($admission_level == '' || $exam_body == '' || $fname == '' || $lname == '') {
                header("Location:index.php");
            } else {

                if ($exam_body == "NECTA") {
                    $indexNumber=$indexNumber;
                    $username = strtoupper($indexNumber);
                } else if ($exam_body == "Others") {
                    $indexNumber=$equivalence_number;
                    $username = strtoupper($indexNumber);
                } else {
                    $username = $email;
                }
                $password = $db->PwdHash(strtoupper(trim($_POST['lname'])));
                if ($db->isFieldExist($tblName, 'userName', $username)) {
                    $boolStatus = false;
                    $msg = "exists";
                } else {
                    $gender = $_POST['gender'];
                    $phoneNumber = $_POST['phoneNumber'];
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
                        'admissionID' => $admissionID,
                        'formfour' => $indexNumber,
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
                        'admissionID' => $admissionID,
                        'yearTaken' => $exam_year,
                        'indexNumber' => $indexNumber,
                        'examinationAuthority' => $exam_body,
                        'examinationLevel' => 'Ordinary',
                        'award' => 'CSEE',
                        'gradeType' => 'Points',
                        'applicantResultStatus' => 0
                    );

                    $insert = $db->insert("applicantresults", $applicantsResultsData);
                }
            }
        }
        else if($admission_level=="PG") {

            $username = $email;
            $password = $db->PwdHash(strtoupper(trim($_POST['lname'])));
            if ($db->isFieldExist($tblName, 'userName', $username)) {
                $boolStatus = false;
                $msg = "exists";
            } else {
                $gender = $_POST['gender'];
                $phoneNumber = $_POST['phoneNumber'];
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
                    'admissionID' => $admissionID,
                    'admissionRound'=>$_POST['admissionRound'],
                    'formfour' => $username,
                    'userID' => $userID
                );

                $insertApplicant = $db->insert($tblApplicants, $applicantsData);
                $applicantID = $insertApplicant;

                $userRolesData = array(
                    'userID' => $userID,
                    'roleID' => 2
                );
                $insert = $db->insert($tblUserRole, $userRolesData);
            }
        }
        else
        {
            header("location:index.php");
        }


            //send mail
            $to = $email;
            $subject = 'Login details for Aspire UAS Online Admission';
            $from = 'info@hmytechnologies.com';

// To send HTML mail, the Content-type header must be set
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
            $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $name="$fname $mname $lname";
// Compose a simple HTML email message
            $message = '<html><body>';
            $message .= '<h1 style="color:#080;">Dear ' . $name . '</h1>';
            $message .= '<p>Welcome to Aspire UAS, member of SkyChuo Enterprise Resource Planning Management Information System for University/College</p>';
            $message .= '<p>To activate your account you must login using username and password below:</p>';
            $message .= '<p style="color:#f40;font-size:18px;">UserName: ' . $username . '<br>Password: ' . strtoupper($lname) . '</p>';
            $message .= '<p>Please do not expose your password to any other person. You may change your password at any time if you wish to do so. </p>';
            $message .= '<p>We hope you enjoy using Aspire UAS and all services offered by other software solutions under SkyChuo package.</p>';
            $message .= '<p></p>';
            $message .= '<p>Warm Regards,</p>';
            $message .= '<p></p>';
            $message .= '<p>_________________________</p>';
            $message .= '<p>SkyChuo Account Management Services </p>';
            $message .= '<p>Muslim University of Morogoro</p>';
            $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
            $message .= '</body></html>';
// Sending email
            mail($to, $subject, $message, $headers);
            $boolStatus = true;

        if ($boolStatus) {
           // if(($exam_body == "NECTA") ||($exam_body=="Others")) {
                $_SESSION['user_session'] = $userID;
                header("Location:app/index.php");
            /*} else {
                header("Location:index.php?msg=succ");
            }*/
        } else {
            header("Location:index.php?msg=$msg");
        }


}
else if ($_POST['doExit'] == 'Exit Application') {
        header("Location:index.php?msg=exit");
    }

} catch(PDOException $ex)
{
    header("Location:index.php?msg=error");
}