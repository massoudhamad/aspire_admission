<?php
session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'studylevels';
    $tblEducation = 'education_levels';
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            $qualificationTypeID = $_POST['qualificationTypeID'];
            $userData = array(
                'studyLevelName' => $_POST['name'],
                'studyLevelCode' => $_POST['code'],
                'status' => 1
            );
            $insert = $db->insert($tblName, $userData);
            $studyLevelID = $insert;
            $status = 1;
            for ($x = 0; $x < count($qualificationTypeID); $x++) {
                $qualificationData=array(
                    'studyLevelID'=>$_POST['id'],
                    'qualificationTypeID'=>$qualificationTypeID[$x],
                    'status'=>$status
                );
                $add_data=$db->insert($tblEducation,$qualificationData);
               /* $stmt = $db->runQuery("INSERT INTO education_levels (studyLevelID,qualificationTypeID,status) VALUES (:studyID,:qualificationID,:st)");
                $stmt->bindParam(":studyID", $studyLevelID, PDO::PARAM_INT);
                $stmt->bindParam(":qualificationID", $qualificationTypeID[$x], PDO::PARAM_INT);
                $stmt->bindParam(":st", $status, PDO::PARAM_INT);
                $stmt->execute();*/
            }
            $statusMsg = true;
            header("Location:index3.php?sp=plevels&msg=succ");

        } elseif ($_REQUEST['action_type'] == 'edit') {
            $qualificationTypeID = $_POST['qualificationTypeID'];
            if (!empty($_POST['id'])) {
                $userData = array(
                    'studyLevelName' => $_POST['name'],
                    'studyLevelCode' => $_POST['code'],
                    'status' => $_POST['status']
                );
                $condition = array('studyLevelID' => $_POST['id']);
                $update = $db->update($tblName, $userData, $condition);

                $delete = $db->delete($tblEducation, $condition);
                $status = 1;
                for ($x = 0; $x < count($qualificationTypeID); $x++) {

                    $qualificationData=array(
                      'studyLevelID'=>$_POST['id'],
                      'qualificationTypeID'=>$qualificationTypeID[$x],
                      'status'=>$status
                    );
                    $add_data=$db->insert($tblEducation,$qualificationData);
/*
                    $stmt = $db->runQuery("INSERT INTO education_levels (studyLevelID,qualificationTypeID,status) VALUES (:studyID,:qualificationID,:st)");
                    $stmt->bindParam(":studyID", $_POST['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":qualificationID", $qualificationTypeID[$x], PDO::PARAM_INT);
                    $stmt->bindParam(":st", $status, PDO::PARAM_INT);
                    $stmt->execute();*/

                }

                $statusMsg = true;
                header("Location:index3.php?sp=plevels&msg=edited");
            }
        }
    }

