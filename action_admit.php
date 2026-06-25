<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$tblApplication='applicantapplication';
$tblRemarks='applicantremarks';

    $programmeMajorID=$_POST['programmeMajorID'];
    $choice=$_POST['choice'];
     $status=false;
     if(isset($_POST['doAdmit']) == 'Admit Applicants')
     {
         $jj=0;
         foreach($_POST['applicantID'] as $applicantID)
         {
                     $userData = array(
                         'admissionStatus' => 1
                     );
                     $condition = array('applicantID' => $applicantID, 'programmeMajorID' => $programmeMajorID, 'choice' => $choice);
                     $updateapp = $db->update($tblApplication, $userData, $condition);

                     $conditions = array('applicantID' => $applicantID);
                     $data = array(
                         'applicantsRemarksID' => 3
                     );
                     $updateapplicants = $db->update($tblName, $data, $conditions);

                     $appData = array(
                         'applicantID' => $applicantID,
                         'remarkID' => 3,
                         'programID' => $programmeMajorID
                     );
                     $insert = $db->insert($tblRemarks, $appData);
                     $status = true;
                     $jj++;
         }
            if($status)
            {
                header("Location:index3.php?sp=admitapplicants&msg=succ&count=".$jj);

            }
            else
            {

                header("Location:index3.php?sp=admitapplicants&msg=unsucc");
            } 
     }
    else if(isset($_POST['doReject']) == 'Reject Applicants')
    {
         if(isset($_POST['id']))
        {
            foreach ($id as $applicantID)
            {
                $userData=array(
                    'admissionStatus'=>1
                );
                $condition=array('applicantID'=>$applicantID,'programmeMajorID'=>$programmeMajorID,'choice'=>$choice);
                $updateapp=$db->update($tblApplication, $userData, $condition);
                
                $conditions=array('applicantID'=>$applicantID);
                $data=array(
                    'applicantsRemarksID'=>4
                );
                $updateapplicants=$db->update($tblName, $data, $conditions);
                
                $appData=array(
                    'applicantID'=>$applicantID,
                    'remarkID'=>4,
                    'programID'=>$programmeMajorID
                );
                $insert=$db->insert($tblRemarks,$appData);
               $status=true;
            }
        }
        
            if($status)
            {
                header("Location:index3.php?sp=admitapplicants&msg=succ");
            }
            else
            {
                header("Location:index3.php?sp=admitapplicants&msg=unsucc");
            } 
    }

} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=admitapplicants&msg=error");
}