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
            $admissionID=$_POST['admissionID'];
            foreach ($_POST['formfour'] as $key => $four) {
                    $four=$_POST['formfour'][$key];
                    $status=$_POST['status'][$key];
                    $status_des=$_POST['status_des'][$key];
                    set_time_limit(0);
                        $userData = array(
                            'tcu_final' => $status,
                            'tcu_message'=>$status_des
                         );
                         $condition = array('formfour'=>$four,'admissionID'=>$admissionID);
                        $updateapp = $db->update($tblName, $userData, $condition);
                         $status = true;
                         $jj++;
                }
            $count++;
            }
        }
      if($status)
        {
            header("Location:index3.php?sp=get_status_tcu&msg=succ&count=".$jj);

        }
        else
        {

            header("Location:index3.php?sp=get_status_tcu&msg=unsucc");
        } 

} catch (PDOException $ex) {
    //echo "Error".$ex->getMessage();
    $db->redirect("index3.php?sp=get_status_tcu&msg=error");
}