<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$tblApplication='applicantapplication';
$tblRemarks='applicantremarks';


    if(isset($_POST['doUpdate']) == 'Un Enroll') {
        if ($_POST['id']) {
            $programmeMajorID=$_POST['programmeMajorID'];
            $id=$_POST['id'];
            $status=false;
            foreach ($id as $applicantID) {
                $userData = array(
                    'admissionStatus' => 0
                );
                $condition = array('applicantID' => $applicantID, 'programmeMajorID' => $programmeMajorID);
                $updateapp = $db->update($tblApplication, $userData, $condition);

                $conditions = array('applicantID' => $applicantID);
                $data = array(
                    'applicantsRemarksID' => 2
                );
                $updateapplicants = $db->update($tblName, $data, $conditions);

                $appData = array(
                    'applicantID' => $applicantID,
                    'remarkID' => 2,
                    'programID' => $programmeMajorID
                );
                $insert = $db->insert($tblRemarks, $appData);
                $status = true;
            }
        }

        if($status)
        {
            header("Location:index3.php?sp=viewadmittedapplicants&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=viewadmittedapplicants&msg=unsucc");
        }
    }
    else  if(isset($_POST['doPublish']) == 'Publish Results') {
        if ($_POST['id']) {
            $jj=0;
            foreach ($_POST['id'] as $applicantID) {
                $userData = array(
                    'nacte_status' => 1
                );
                $condition = array('applicantID' => $applicantID);
                $updateapp = $db->update($tblName, $userData, $condition);
                $status = true;
                $jj++;
            }
        }

        if($status)
        {
            header("Location:index3.php?sp=viewadmittedapplicants&msg=succ&count=".$jj);
        }
        else
        {
            header("Location:index3.php?sp=viewadmittedapplicants&msg=unsucc");
        }
    }
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=viewadmittedapplicants&msg=error");
}