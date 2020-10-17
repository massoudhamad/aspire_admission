<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
include 'DB.php';
$db = new DBHelper();
$tblName = 'admission_round';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $semester = $db->getRows('admission_round', array('where' => array('roundStatus' => 1), 'order_by' => 'admissionRoundID ASC'));
        if(!empty($semester))
        {
            foreach ($semester as $sm) {
                $admissionID = $sm['admissionRoundID'];
                $condition = array('admissionRoundID' => $admissionID);
                $userDataStatus = array('roundStatus' => 0);
                $update = $db->update($tblName, $userDataStatus, $condition);
            }
        }
            $userData = array(
                'admissionSettingID'=>$_POST['admissionSettingID'],
                'startDate' => $_POST['startDate'],
                'endDate'=>$_POST['endDate'],
                'admissionRound'=>$_POST['roundName'],
                'roundStatus'=>1
            );
            $insert = $db->insert($tblName,$userData);
            $statusMsg = true;
            header("Location:index3.php?sp=admission_round&msg=succ");
    } elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $semesterStatus=$_POST['status'];
            if($semesterStatus==1)
            {
                $semester = $db->getRows('admission_round', array('where' => array('roundStatus' => 1), 'order_by' => 'admissionRoundID ASC'));
                if (!empty($semester)) {
                    foreach ($semester as $sm) {
                        $admissionID = $sm['admissionRoundID'];
                        $condition = array('admissionRoundID' => $admissionID);
                        $userDataStatus = array('roundStatus' => 0);
                        $update = $db->update($tblName, $userDataStatus, $condition);
                    }
                }
            }

            $userData = array(
                'admissionSettingID' => $_POST['admissionInTakeID'],
                'startDate' => $_POST['startDate'],
                'endDate' => $_POST['endDate'],
                'admissionRound' => $_POST['roundName'],
                'roundStatus' => 1
            );
            $condition = array('admissionRoundID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;

            header("Location:index3.php?sp=admission_round&msg=edited");
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