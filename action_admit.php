<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$tblApplication='applicantapplication';
$tblRemarks='applicantremarks';

    $programmeMajorID=$_POST['programmeMajorID'];
    $choice=$_POST['choice'];
     $status=false;
     if(isset($_POST['doAdmit']) == 'Admit Applicants')
     {
         $jj=0;
         foreach($_POST['applicantID'] as $applicantID)
         {
                     $userData = array(
                         'admissionStatus' => 1
                     );
                     $condition = array('applicantID' => $applicantID, 'programmeMajorID' => $programmeMajorID, 'choice' => $choice);
                     $updateapp = $db->update($tblApplication, $userData, $condition);

                     $conditions = array('applicantID' => $applicantID);
                     $data = array(
                         'applicantsRemarksID' => 3
                     );
                     $updateapplicants = $db->update($tblName, $data, $conditions);

                     $appData = array(
                         'applicantID' => $applicantID,
                         'remarkID' => 3,
                         'programID' => $programmeMajorID
                     );
                     $insert = $db->insert($tblRemarks, $appData);
                     $status = true;
                     $jj++;

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
        $message .= '<p>There is changes in your application portal, please log in using your Username/IndexNumber(S000/0000/YYYY/YYYY) and Password(LASTNAME) to view the status of your application<br>For more information, please contact'. $studentSupport.'</p>';
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
            if($status)
            {
                header("Location:index3.php?sp=admitapplicants&msg=succ&count=".$jj);

            }
            else
            {

                header("Location:index3.php?sp=admitapplicants&msg=unsucc");
            } 
     }
    else if(isset($_POST['doReject']) == 'Reject Applicants')
    {
         if(isset($_POST['id']))
        {
            foreach ($id as $applicantID)
            {
                $userData=array(
                    'admissionStatus'=>1
                );
                $condition=array('applicantID'=>$applicantID,'programmeMajorID'=>$programmeMajorID,'choice'=>$choice);
                $updateapp=$db->update($tblApplication, $userData, $condition);
                
                $conditions=array('applicantID'=>$applicantID);
                $data=array(
                    'applicantsRemarksID'=>4
                );
                $updateapplicants=$db->update($tblName, $data, $conditions);
                
                $appData=array(
                    'applicantID'=>$applicantID,
                    'remarkID'=>4,
                    'programID'=>$programmeMajorID
                );
                $insert=$db->insert($tblRemarks,$appData);
               $status=true;
            }
        }
        
            if($status)
            {
                header("Location:index3.php?sp=admitapplicants&msg=succ");
            }
            else
            {
                header("Location:index3.php?sp=admitapplicants&msg=unsucc");
            } 
    }

} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=admitapplicants&msg=error");
}