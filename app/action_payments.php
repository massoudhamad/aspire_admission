<?php
session_start();
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
            if (!empty($payments)) {
                header("Location:index.php?sz=payments&msg=exist");
                } else
                {
                    $payment_updates=$db->getRows("applicant_payment",array('where'=>array('applicantID'=>$_SESSION['applicantID'])));
                    if(!empty($payment_updates))
                    {
                        $condition=array("applicantID"=>$_SESSION['applicantID']);
                        $updates = $db->update("applicant_payment",$userData,$condition);
                        //header("Location:index.php?sz=programmechoice");
                    }
                    else 
                    {
                        $insert = $db->insert("applicant_payment",$userData);
                        //header("Location:index.php?sz=programmechoice");
                    }
                }
        }
    }
    if(isset($_POST['doProceed']))
    {
        /* header("Location:index.php?sz=programmechoice"); */
        header("Location:index.php?sz=programmechoice");
    }
}catch (PDOException $ex)
{
    echo "Data Error".$ex->getMessage();
}