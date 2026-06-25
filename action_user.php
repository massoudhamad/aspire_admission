<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
include 'DB.php';
$db = new DBHelper();
$tblName = 'users';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
  
    if($_REQUEST['action_type'] == 'add')
    {
        /*$username=$_POST['fname'].".".$_POST['lname'];
        $password=$db->PwdHash(strtoupper($_POST['lname']));*/
        $email=$_POST['email'];
        $fname=addslashes($_POST['fname']);
        $mname=addslashes($_POST['mname']);
        $lname=addslashes($_POST['lname']);
        //$pwd=$db->generate_password(8);
        //$pwd = $db->generate_password(8);
        $username=$email;
        $password=$db->PwdHash(strtoupper($lname));
        $userData = array(
            'firstName' => $fname,
            'middleName' => $mname,
            'lastName' => $lname,
            'phoneNumber' => $_POST['phoneNumber'],
            'email' => $_POST['email'],
            'gender'=>$_POST['gender'],
            'sectionID'=>$_POST['schoolID'],
            'userName'=>$username,
            'password'=>$password,
            'login'=>1,
            'status'=>1
        );

            if($db->isFieldExist('users','userName',$username))
            {
                $boolStatus=false;
            }
            else
            {
            $insert = $db->insert($tblName,$userData);
            $userID=$insert;
            $roleData=array(
                'userID'=>$userID,
                'roleID'=>$_POST['roleID']
            );
            $insertRole=$db->insert("userroles",$roleData);

                //send mail
                $to = $email;
                $subject = 'Login details for Aspire UAS-Online Admission';
                $from = 'admissions@hmy.ac.tz';

// To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                $name="$fname $lname";
// Compose a simple HTML email message
                $message = '<html><body>';
                /* $message = '<h1>Welcome to StAR,the extended Student Academic Register</h1>';*/
                $message .= '<h1 style="color:#080;">Dear ' . $name . '</h1>';
                $message .= '<p>Welcome to Aspire UAS, member of SkyChuo Enterprise Resource Planning Management Information System for University/College</p>';
                $message .= '<p>To activate your account you must login using username and password below:</p>';
                $message .= '<p style="color:#f40;font-size:18px;">UserName: ' . $username . '<br>Password: ' . $password . '</p>';
                $message .= '<p>Please do not expose your password to any other person. You may change your password at any time if you wish to do so. </p>';
                $message .= '<p>We hope you enjoy using Aspire UAS and all services offered by other software solutions under SkyChuo package.</p>';
                $message .= '<p></p>';
                $message .= '<p>Warm Regards,</p>';
                $message .= '<p></p>';
                $message .= '<p>_________________________</p>';
                $message .= '<p>SkyChuo Account Management Services </p>';
                // $message .= '<p>College of Science, Technology, Engineering, Arts and Matehematics</p>';
                $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
                $message .= '</body></html>';
// Sending email
                mail($to, $subject, $message, $headers);
            $boolStatus=true;
           }
        if($boolStatus)
        {
            header("Location:index3.php?sp=user&msg=secc");
        }
        else
        {
            header("Location:index3.php?sp=user&msg=unsecc");
        }
   
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           //$email=$_POST['email'];
            $sectionID=$_POST['schoolID'];
            $userData = array(
                'firstName' => addslashes($_POST['fname']),
                'middleName' => addslashes($_POST['mname']),
                'lastName' => addslashes($_POST['lname']),
                'phoneNumber' => $_POST['phone'],
                'gender'=>$_POST['gender'],
                'sectionID'=>$sectionID,
                'status'=>1
        );
            $condition = array('userID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            
            $roleData=array(
                'userID'=>$_POST['id'],
                'roleID'=>$_POST['roleID']
            );
            
            $insertRole=$db->update("userroles",$roleData,$condition);
            $statusFlag=true;
             header("Location:index3.php?sp=user&msg=edited");
        }
    }elseif($_REQUEST['action_type'] == 'block'){
        if(!empty($_GET['id'])){
            $userData=array(
            'status'=>0
            );
            $condition = array('userID' => $_GET['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusFlag=true;
             header("Location:index3.php?sp=user&msg=block");
        }
    }
    elseif($_REQUEST['action_type'] == 'unblock'){
        if(!empty($_GET['id'])){
            $userData=array(
            'status'=>1
            );
            $condition = array('userID' => $_GET['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusFlag=true;
             header("Location:index3.php?sp=user&msg=unblock");
        }
    }
    elseif($_REQUEST['action_type'] == 'reset')
    {
        if(!empty($_GET['id'])){
             $users = $db->getRows('users',array('where'=>array('userID'=>$_GET['id']),'order_by'=>'userID DESC'));
             if(!empty($users))
             {
                foreach ($users as $us) {
                    $lname=trim($us['lastName']);
                }
             }
            $userData=array(
            'password'=>$db->PwdHash(strtoupper($lname)),
            'login'=>0
            );
            $condition = array('userID' => $_GET['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusFlag=true;
            header("Location:index3.php?sp=user&msg=reset");
        }
    }
}