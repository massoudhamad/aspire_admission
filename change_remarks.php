<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$tblApplication='applicantapplication';
$tblRemarks='applicantremarks';
//if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
//{
     $programmeMajorID=$_POST['programmeMajorID'];
     $id=$_POST['id'];
     $status=false;
     //if(isset($_REQUEST['action_type']) == 'add')
    if(isset($_POST['doUpdate']) == 'Change')
     {
        if($_POST['id'])
        {
            foreach ($id as $applicantID)
            {
                $userData=array(
                    'admissionStatus'=>0
                );
                $condition=array('applicantID'=>$applicantID,'programmeMajorID'=>$programmeMajorID);
                $updateapp=$db->update($tblApplication, $userData, $condition);
                
                $conditions=array('applicantID'=>$applicantID);
                $data=array(
                    'applicantsRemarksID'=>1
                );
                $updateapplicants=$db->update($tblName, $data, $conditions);
                
                $appData=array(
                    'applicantID'=>$applicantID,
                    'remarkID'=>1,
                    'programID'=>$programmeMajorID
                );
                $insert=$db->insert($tblRemarks,$appData);
               $status=true;
            }
        }
         if($status)
         {
             header("Location:index3.php?sp=viewbyremarks&msg=succ");
         }
         else
         {
             header("Location:index3.php?sp=viewbyremarks&msg=unsucc");
         }
     }/*else if(isset($_REQUEST['action_type']) == 'drop_app')*/
    else if(isset($_POST['doDrop']) == 'Drop')
     {
         if($_POST['id'])
         {
             foreach ($id as $applicantID)
             {
                 $userID=$db->getData("applicants","userID","applicantID",$applicantID);
                 $condition_app = array('applicantID' => $applicantID);
                 $condition_user = array('userID' => $userID);
                 $applicantResultID=$db->getData("applicantresults","applicantResultID","applicantID",$applicantID);
                 $cond_subject=array('applicantResultID'=>$applicantResultID);
                 $delete_p=$db->delete("applicantapplication",$condition_app);
                 $delete_rmk=$db->delete("applicantremarks",$condition_app);
                 $delete_sub=$db->delete("applicantsubjects",$cond_subject);
                 $delete_res=$db->delete("applicantresults",$condition_app);
                 $delete_app=$db->delete("applicants",$condition_app);
                 $delete_user=$db->delete("users",$condition_user);
                 $status=true;
             }
         }
         if($status)
         {
             header("Location:index3.php?sp=viewbyremarks&msg=succ");
         }
         else
         {
             header("Location:index3.php?sp=viewbyremarks&msg=unsucc");
         }
     }


 
//}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=viewbyremarks&msg=error");
}