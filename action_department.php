<?php
session_start();
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
}