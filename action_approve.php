<?php
if (session_status() === PHP_SESSION_NONE) session_start();
//ini_set('display_errors', 1);
//error_reporting(E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicantremarks';
$tblApplicants='applicants';
$applicantID=$_REQUEST['applicantID'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    $formfour = $_POST['formfour'];
    $formsix = $_POST['formsix'];
    $category = $_POST['appcategory'];
    $other_four="";
    $other_six="";


     $userData = array(
         'applicantID'=>$applicantID,
         'remarkID'=>$_POST['remarksID'],
         'comments'=>$_POST['comments'],
         'activeStatus'=>1,
         'userID'=> $_POST['userID']
        ); 
    if($_REQUEST['action_type'] == 'add')
    {
           $applicantdata=array(
              'applicantsRemarksID'=>$_POST['remarksID']  
            );
            $condition= array('applicantID'=>$applicantID);
            $updateapplicants=$db->update($tblApplicants,$applicantdata,$condition);
            $appRemarksData=array(
              'activeStatus'=>0  
            );
            $updateAppRemarks=$db->update($tblName,$appRemarksData,$condition);
            
            $insert = $db->insert($tblName,$userData); 


        if(($db->checkApplicantStudyLevel($applicantID)==1) && ($_POST['remarksID']==2)) 
        {
            $api_token = $db->getAPI("TCU", "token");
            if (!empty($api_token)) {
                foreach ($api_token as $api) {
                    $token = $api['token'];
                    $user = $api['userName'];
                    $urlform = $api['url'];
                }
            }

        $url = $urlform."/applicants/add";
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>' . $user . '</Username>
        <SessionToken>' . $token . '</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <f4indexno>' . $formfour . '</f4indexno >
        <f6indexno>' . $formsix . '</f6indexno>
        <Category>' . $category . '</Category>
        <Otherf4indexno>' . $other_four . '</Otherf4indexno>
        <Otherf6indexno>' . $other_six . '</Otherf6indexno>
        </RequestParameters>
        </Request>';

            $output = $db->addApplicantTCU($url, $xml);
            $array_data = json_decode(json_encode(simplexml_load_string($output)), true);
            $status = $array_data['Response']['ResponseParameters']['StatusCode'];
            $status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];

            if ($status == 200) {
                $tcudata = array(
                    'tcu_status' => 1
                );
                $condition = array('applicantID' => $applicantID);
                $updateapplicants = $db->update($tblApplicants, $tcudata, $condition);
                $boolStatus = true;
                $msgs= $status_descript;
            }
            else 
            {
                $msgs = $status_descript;
            }
        }
        else
        {
            $msgs="NAN";

        }


        //send mail
        $org = $db->getRows("organization");
        if (!empty($org)) {
            foreach ($org as $og) {
                $orgName = $og['organizationName'];
                $studentSupport = $og['student_support'];
                $orgemail=$og['organizationEmail'];
            }
        }
        $applicantdata=$db->getRows("applicants",array('where'=>array('applicantID'=>$applicantID)));
        if(!empty($applicantdata))
        {
            foreach($applicantdata as $dt)
            {
                $userID=$dt['userID'];
                $fname=$dt['firstName'];
                $lname=$dt['lastName'];

            }
        }
        $name=$fname." ".$lname;
        $email=$db->getData('users','email','userID',$userID);
        $to = $email;
        $subject = 'Application Update — ' . $orgName;
        $from = $orgemail ?: 'info@ichas.ac.tz';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $message  = '<html><body style="font-family:Arial,sans-serif;color:#1F2937;">';
        $message .= '<h2 style="color:#1B3A5C;">' . htmlspecialchars($orgName) . '</h2>';
        $message .= '<p>Dear ' . htmlspecialchars($name) . ',</p>';
        $message .= '<p>There has been an update to your application. Please log in to the admission portal using your Username (Index Number, e.g. S000/0000/YYYY) and your Password (your LAST NAME) to view the status of your application.</p>';
        $message .= '<p>For assistance, please contact ' . htmlspecialchars((string)$studentSupport) . '.</p>';
        $message .= '<p>Warm regards,<br>Admissions Office<br>' . htmlspecialchars($orgName) . '</p>';
        $message .= '</body></html>';
        // Sending email
        mail($to, $subject, $message, $headers);

        $boolStatus = true;

    }
       if($boolStatus)
        {
            header("Location:index3.php?sp=approve&msg=succ");
            $_SESSION['output'] = $msgs;
        }
        else
        {
            header("Location:index3.php?sp=approve&msg=unsucc");
        } 
}