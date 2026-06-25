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
                $subject = 'Your ICHAS Admission System — Login Details';
                $from = 'info@ichas.ac.tz';

// To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                $name="$fname $lname";
// Compose a simple HTML email message
                $message  = '<html><body style="font-family:Arial,sans-serif;color:#1F2937;">';
                $message .= '<h2 style="color:#1B3A5C;">Welcome to the ICHAS Admission System</h2>';
                $message .= '<p>Dear ' . htmlspecialchars($name) . ',</p>';
                $message .= '<p>A user account has been created for you. To activate your account, log in with the credentials below:</p>';
                $message .= '<p style="background:#F4F6F9;border-left:4px solid #C9A227;padding:12px 16px;font-size:15px;">';
                $message .= '<strong>Username:</strong> ' . htmlspecialchars($username) . '<br>';
                $message .= '<strong>Password:</strong> ' . htmlspecialchars($password) . '</p>';
                $message .= '<p>For your security, please change your password after your first login and do not share these credentials.</p>';
                $message .= '<p>Warm regards,<br>Admissions Office<br>Imperial College of Health and Allied Sciences</p>';
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