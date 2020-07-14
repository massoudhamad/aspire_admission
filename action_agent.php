<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'agents';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'agentName'=>$_POST['name'],
            'agentAddress' => $_POST['address'],
            'phoneNumber' => $_POST['phone'],
            'regionID' => $_POST['regionID'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=agents&msg=succ");

    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           $userData = array(
            'agentName'=>$_POST['name'],
            'agentAddress' => $_POST['address'],
            'phoneNumber' => $_POST['phone'],
            'regionID' => $_POST['regionID'],
            'status'=>$_POST['status']
        );
            $condition = array('agentID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=agents&msg=edited");
        }
    }
}