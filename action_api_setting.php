<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'api_setting';
if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    if ($_REQUEST['action_type'] == 'add') {
        $userData = array(
            'userName' => $_POST['username'],
            'token' => $_POST['token'],
            'tokenType' => $_POST['tokenType'],
            'organizationName' => $_POST['organizationName'],
            'url' => $_POST['url']
        );
        $insert = $db->insert($tblName, $userData);
        $statusMsg = true;
        header("Location:index3.php?sp=api_setting&msg=succ");

    } elseif ($_REQUEST['action_type'] == 'edit') {
        if (!empty($_POST['id'])) {
            $userData = array(
            'userName' => $_POST['username'],
            'token' => $_POST['token'],
            'tokenType' => $_POST['tokenType'],
            'organizationName' => $_POST['organizationName'],
            'url'=>$_POST['url']
            );
            $condition = array('apiSettingID' => $_POST['id']);
            $update = $db->update($tblName, $userData, $condition);
            $statusMsg = true;
            header("Location:index3.php?sp=api_setting&msg=edited");
        }
    }
}
