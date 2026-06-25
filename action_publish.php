<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'programmemajor';
    //if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
        //{
    $id=$_POST['id'];
    $status=false;
    if(isset($_POST['doAdmit']) == 'Publish')
    {
        if($_POST['id'])
        {
            foreach ($id as $programmeMajorID)
            {
                
                $userData=array(
                    'publishStatus'=>1
                );
                $condition=array('programmeMajorID'=>$programmeMajorID);
                $updateapp=$db->update($tblName, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            //echo $programmeMajorID;
            header("Location:index3.php?sp=publish&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=publish&msg=unsucc");
        }
    }
    else if(isset($_POST['doReject']) == 'Unpublish')
    {
        
        if($_POST['id'])
        {
            foreach ($id as $programmeMajorID)
            {
                
                $userData=array(
                    'publishStatus'=>0
                );
                $condition=array('programmeMajorID'=>$programmeMajorID);
                $update=$db->update($tblName, $userData, $condition);
                $status=true;
            }
        }
        if($status)
        {
            header("Location:index3.php?sp=publish&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=publish&msg=unsucc");
        }
    }
    
    
    //}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=publish&msg=error");
}