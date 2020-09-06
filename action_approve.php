<?php
session_start();
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
         'userID'=>$_SESSION['user_session']
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


        if($db->checkApplicantStudyLevel($applicantID)==1) 
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
        $subject = 'Login details for Aspire UAS Online Admission';
        $from = 'info@hmytechnologies.com';

        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Create email headers
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'CC: '.$orgemail.
            'X-Mailer: PHP/' . phpversion();
        // Compose a simple HTML email message
        $message = '<html><body>';
        $message .= '<h1 style="color:#080;">Email From: '.$orgName.'</h1>';
        $message .= '<h2 style="color:#080;">Dear ' . $name . 'an applicant from '.$orgName. '</h1>';
        $message .= '<p>There is changes in your application portal, please log in using your Username/IndexNumber(S000/0000/YYYY) and Password(LASTNAME) to view the status of your application<br>For more information, please contact'. $studentSupport.'</p>';
        $message .= '<p>We hope you enjoy using Aspire UAS and all services offered by other software solutions under SkyChuo package.</p>';
        $message .= '<p></p>';
        $message .= '<p>Warm Regards,</p>';
        $message .= '<p></p>';
        $message .= '<p>_________________________</p>';
        $message .= '<p>'.$orgName.'</p>';
        $message .= '<p>SkyChuo Account Management Services </p>';
        $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
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