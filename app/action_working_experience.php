<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'working_experience';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            $employerName=$_POST['employer'];
            $positionName=$_POST['positionName'];
            $employerAddress=$_POST['address'];
            $startYear=$_POST['startYear'];
            $endYear=$_POST['endYear'];
            $userData = array(
                'applicantID'=>$_SESSION['applicantID'],
                'positionName' => $positionName,
                'employerName'=>$employerName,
                'employerAddress' =>$employerAddress,
                'startYear'=>$startYear,
                'endYear'=>$endYear
            );

            $insert = $db->insert($tblName,$userData);
            $applicantResultID=$insert;

            $boolStatus=true;
            if($boolStatus)
            {
                header("Location:index.php?sz=working&msg=succ");
            }
            else
            {
                header("Location:index.php?sz=working&msg=unsucc");
            }
        }

        else if($_REQUEST['action_type'] == 'dropSchool')
        {
            $id=$db->my_simple_crypt($_GET['id'],'d');
            if(!empty($id)){
                $condition = array('workID' => $id);
                $update = $db->delete($tblName,$condition);
                $statusFlag=true;
                $db->redirect("index.php?sz=working&msg=dropSchool");
            }
        }

    }
    /*if($_POST['doExit'])
    {
        header("Location:index.php?sz=pg_education");
    }*/
}catch (PDOException $ex)
{
    echo "Data Error".$ex->getMessage();
}