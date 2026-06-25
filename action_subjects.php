<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'subjects';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
     $courseCode=$_POST['code'];
     $courseCode= strtoupper($courseCode);
     $courseName=$_POST['name'];
     
     /*if(($db->isFieldExist('subjects','subjectCode',$courseCode)) OR ($db->isFieldExist('subjects','subjectName',$courseName)))
     {
        $statusMsg=false;
        header("Location:index3.php?sp=subjects&msg=unsucc");
    }
    else
    {*/
    if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'subjectCode' => $courseCode,
            'subjectName'=>$courseName,
            'stream' => $_POST['stream'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=subjects&msg=succ");
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
            'subjectCode' => $courseCode,
            'subjectName'=>$courseName,
            'stream' => $_POST['stream'],
            'status'=>$_POST['status']
        );
            $condition = array('subjectID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=subjects&msg=edited");
        }
    }
//}
}