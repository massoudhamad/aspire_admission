<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
include 'DB.php';
$db = new DBHelper();
$tblName = 'admission_setting';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $semester = $db->getRows('admission_setting', array('where' => array('yearStatus' => 1), 'order_by' => 'admissionID ASC'));
        if(!empty($semester))
        {
            foreach ($semester as $sm) {
                $admissionID = $sm['admissionID'];
                $condition = array('admissionID' => $admissionID);
                $userDataStatus = array('yearStatus' => 0);
                $update = $db->update($tblName, $userDataStatus, $condition);
            }
        }
        $academicYear=$db->getData("academicyears","academicYear","academicYearID",$_POST['academicYearID']);
        $admissionIntake=$db->getData("admission_intake","admissionInTake","admissionInTakeID",$_POST['admissionIntakeID']);
        $admissionName=$admissionIntake."-".$academicYear;
            $userData = array(
                'academicYearID'=>$_POST['academicYearID'],
                'startDate' => $_POST['startDate'],
                'endDate'=>$_POST['endDate'],
                'admissionInTakeID'=>$_POST['admissionIntakeID'],
                'admissionName'=>$admissionName,
                'yearStatus'=>1
            );
            $insert = $db->insert($tblName,$userData);
            $statusMsg = true;
            header("Location:index3.php?sp=admission_setting&msg=succ");
    } elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $semesterStatus=$_POST['status'];
            if($semesterStatus==1)
            {
                $semester=$db->getRows('admission_setting',array('where'=>array('yearStatus'=>1),'order_by'=>'admissionID ASC'));
                foreach ($semester as $sm)
                {
                    $admissionID=$sm['admissionID'];
                    $condition = array('admissionID'=>$admissionID);
                    $userDataStatus = array('yearStatus' =>0);
                    $update = $db->update($tblName,$userDataStatus,$condition);
                }
            }

            $academicYear=$db->getData("academicyears","academicYear","academicYearID",$_POST['admissionYearID']);
            $admissionIntake=$db->getData("admission_intake","admissionInTake","admissionInTakeID",$_POST['admissionInTakeID']);
            $admissionName=$admissionIntake."-".$academicYear;
            $userData = array(
                'academicYearID'=>$_POST['admissionYearID'],
                'admissionInTakeID'=>$_POST['admissionInTakeID'],
                'startDate' => $_POST['startDate'],
                'endDate'=>$_POST['endDate'],
                'admissionName'=>$admissionName,
                'yearStatus'=>$semesterStatus
            );
            $condition = array('admissionID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;

            header("Location:index3.php?sp=admission_setting&msg=edited");
        }

    }

    /*elseif($_REQUEST['action_type'] == 'delete'){
        if(!empty($_GET['id'])){
            $condition = array('admissionID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            header("Location:index3.php?sp=admission_setting&msg=deleted");
        }
    }*/
}