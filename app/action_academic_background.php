<?php
session_start();
try {
    include '../DB.php';
    $db = new DBHelper();
    $tblName = 'academic_background';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            $instituteName=$_POST['instituteName'];
            $programmeName=$_POST['programmeName'];
            $cgpa=$_POST['cgpa'];
            $qualificationTypeID=$_POST['qualificationTypeID'];
            $startYear=$_POST['startYear'];
            $endYear=$_POST['endYear'];
            $userData = array(
                'applicantID'=>$_SESSION['applicantID'],
                'programmeName' => $programmeName,
                'institutionName'=>$instituteName,
                'startYear'=>$startYear,
                'endYear'=>$endYear,
                'gpa'=>$cgpa,
                'qualificationID'=>$qualificationTypeID
            );

            $insert = $db->insert($tblName,$userData);
            $applicantResultID=$insert;

            $boolStatus=true;
            if($boolStatus)
            {
                header("Location:index.php?sz=pg_education&msg=succ");
            }
            else
            {
                header("Location:index.php?sz=pg_education&msg=unsucc");
            }
        }

        else if($_REQUEST['action_type'] == 'dropSchool')
        {
            $id=$db->my_simple_crypt($_GET['id'],'d');
            if(!empty($id)){
                $condition = array('academicID' => $id);
                $update = $db->delete($tblName,$condition);
                $statusFlag=true;
                $db->redirect("index.php?sz=pg_education&msg=dropSchool");
            }
        }

    }
    if($_POST['doExit'])
    {
        header("Location:index.php?sz=pg_education");
    }
}catch (PDOException $ex)
{
    echo "Data Error".$ex->getMessage();
}