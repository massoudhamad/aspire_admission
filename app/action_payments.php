<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
    include '../DB.php';
    $db = new DBHelper();
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            $paymentMethod=$_POST['payment'];
            $amount=$_POST['amount'];
            $token=$_POST['token'];
            $userData = array(
                'applicantID'=>$_SESSION['applicantID'],
                'paymentMethod'=>$paymentMethod,
                'amount'=>$amount,
                'token'=>$token,
                'paymentStatus'=>1
            );
            $payments = $db->getRows("applicant_payment", array('where' => array('token' => $token)));
            if (empty($payments)) {
                $insert = $db->insert("applicant_payment",$userData);
                header("Location:index.php?sz=submit");
                } else
                {
                    header("Location:index.php?sz=payments&msg=exist");
                }
        }
    }
}catch (PDOException $ex)
{
    echo "Data Error".$ex->getMessage();
}