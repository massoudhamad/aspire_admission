<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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
    //$equivalence_number=$_POST['equivalence_number'];
    //$indexNumber=$indexNumber."/".$exam_year;
    $indexNumber=$indexNumber;
        
        // Accept UG plus ICHAS certificate/diploma levels (Basic Cert, Ordinary Diploma, Technician Cert)
        if (in_array($admission_level, ['UG', 'BC', 'OD', 'TC'])) {
            if ($admission_level == '' || $exam_body == '' || $fname == '' || $lname == '') {
                header("Location:index.php?msg=111");
            } else {

                if ($exam_body == "NECTA") {
                    $indexNumber=$indexNumber;
                    $username = strtoupper($indexNumber);
                } else if ($exam_body == "Others") {
                    $indexNumber=$indexNumber;
                    $username = strtoupper($indexNumber);
                } else {
                    $username = $email;
                }
                $password = $db->PwdHash(strtoupper(trim($_POST['lname'])));
                if ($db->isFieldExist($tblName, 'userName', $username)) {
                    $boolStatus = false;
                    $msg = "indexexists";
                }else if($db->isFieldExist($tblName, 'email', $email))
                {
                    $boolStatus = false;
                    $msg = "emailexists";
                }else {
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
                        'admissionRound'=> $_POST['admissionRound'],
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
            header("location:index.php?msg=222");
        }


            //send mail
            $to = $email;
            $subject = 'Your ICHAS Admission Portal — Login Details';
            $from = 'info@ichas.ac.tz';

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $name = "$fname $mname $lname";

            $message  = '<html><body style="font-family:Arial,sans-serif;color:#1F2937;">';
            $message .= '<h2 style="color:#1B3A5C;">Welcome to the Imperial College of Health and Allied Sciences (ICHAS) Admission Portal</h2>';
            $message .= '<p>Dear ' . htmlspecialchars($name) . ',</p>';
            $message .= '<p>Your application account has been created. To log in and complete your application, use the credentials below:</p>';
            $message .= '<p style="background:#F4F6F9;border-left:4px solid #C9A227;padding:12px 16px;font-size:15px;">';
            $message .= '<strong>Username:</strong> ' . htmlspecialchars($username) . '<br>';
            $message .= '<strong>Password:</strong> ' . htmlspecialchars(strtoupper($lname)) . '</p>';
            $message .= '<p>For your security, please do not share these credentials with anyone. You may change your password at any time after logging in.</p>';
            $message .= '<p>Warm regards,<br>Admissions Office<br>Imperial College of Health and Allied Sciences</p>';
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
      error_log('[action_confirm_register] PDO error: ' . $ex->getMessage() . ' at ' . $ex->getFile() . ':' . $ex->getLine());
      header("Location:index.php?msg=error");
}