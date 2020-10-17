<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';


if(isset($_POST['doAdmit']) == 'Save Records')
{
    $jj=0;
    if(!empty($_POST['formfour'])) {
        $count=0;
        foreach ($_POST['formfour'] as $ffour => $four) {
            foreach ($_POST['status'] as $stt => $st) {
                if ($ffour == $stt) {
                    if($st=="confirmed")
                    {
                        $stcode=214;
                    }
                    else
                    {
                        $stcode=213;
                    }
                    $userData = array(
                        'tcu_message' => $st,
                        'tcu_final'=>$stcode
                        );
                    $condition = array('formfour' => $four,'admissionID'=>12);
                    $updateapp = $db->update($tblName, $userData, $condition);
                    $status = true;
                    $jj++;
                }
            }
            $count++;
        }
    }
}
if($status)
 {
     header("Location:index3.php?sp=get_list_of_confirmed_tcu&msg=succ&count=".$jj);

 }
 else
 {

     header("Location:index3.php?sp=get_list_of_confirmed_tcu&msg=unsucc");
 }

} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=get_list_of_confirmed_tcu&msg=error");
}