<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'programmemaping';
$programmeID=$_POST['programmeID'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'programmeID'=>$_POST['programmeID'],
            'courseID'=>$_POST['courseID'],
            'semesterID' => $_POST['semisterID'],
            'studyYear' => $_POST['studyYear'],
            'courseStatusID' => $_POST['courseStatusID'],
            'courseStatus' => $_POST['courseStatusID']
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=pmapping&msg=succ&action=getRecords&programmeID=$programmeID");
    }elseif($_REQUEST['action_type'] == 'delete'){
        $programmeID=$_GET['programmeID'];
        if(!empty($_GET['id'])){
            $condition = array('programmeMappingID' => $_GET['id']);
            $delete = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=pmapping&msg=deleted&action=getRecords&programmeID=$programmeID");
        }
    }
}