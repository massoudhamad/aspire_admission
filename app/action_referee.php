<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'referees';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            $name=$_POST['name'];
            $positionName=$_POST['positionName'];
            $address=$_POST['address'];
            $email=$_POST['email'];
            $pnumber=$_POST['pnumber'];
            $userData = array(
                'applicantID'=>$_SESSION['applicantID'],
                'position' => $positionName,
                'fullName'=>$name,
                'address' =>$address,
                'email'=>$email,
                'phoneNumber'=>$pnumber
            );

            $insert = $db->insert($tblName,$userData);
            $applicantResultID=$insert;

            $boolStatus=true;
            if($boolStatus)
            {
                header("Location:index.php?sz=referees&msg=succ");
            }
            else
            {
                header("Location:index.php?sz=referees&msg=unsucc");
            }
        }

        else if($_REQUEST['action_type'] == 'dropSchool')
        {
            $id=$db->my_simple_crypt($_GET['id'],'d');
            if(!empty($id)){
                $condition = array('refereeID' => $id);
                $update = $db->delete($tblName,$condition);
                $statusFlag=true;
                $db->redirect("index.php?sz=referees&msg=dropSchool");
            }
        }

    }
}catch (PDOException $ex)
{
    echo "Data Error".$ex->getMessage();
}