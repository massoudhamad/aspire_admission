<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
include 'DB.php';
$db = new DBHelper();
$tblName = 'upload';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
{
        if($_REQUEST['action_type'] == 'delete')
        {
            $condition = array('uploadID' => $_REQUEST['id'],'academicYearID'=>$_REQUEST['yearID']);
            $update = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=document_upload&msg=deleted");
        }
}

