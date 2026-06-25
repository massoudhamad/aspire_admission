<?php
if (session_status() === PHP_SESSION_NONE) session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'departments';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'departmentName'=>$_POST['name'],
            'departmentCode' => $_POST['code'],
            'schoolID' => $_POST['schoolID'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=departments&msg=succ");

    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           $userData = array(
            'departmentName'=>$_POST['name'],
            'departmentCode' => $_POST['code'],
            'schoolID' => $_POST['schoolID'],
            'status'=>$_POST['status']
        );
            $condition = array('departmentID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=departments&msg=edited");
        }
    }
    //block/unlock user status
    elseif ($_REQUEST['action_type'] == 'block') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'status' => 0
            );
            $condition = array('programmeLevelID' => $_GET['id']);
            $update = $db->update("programme_level", $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=application_level&msg=block");
        }
    } elseif ($_REQUEST['action_type'] == 'unblock') {
        if (!empty($_GET['id'])) {
            $userData = array(
                'status' => 1
            );
            $condition = array('programmeLevelID' => $_GET['id']);
            $update = $db->update("programme_level", $userData, $condition);
            $statusFlag = true;
            header("Location:index3.php?sp=application_level&msg=unblock");
        }
    }
}