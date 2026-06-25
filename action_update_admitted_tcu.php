<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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
            foreach ($_POST['formfour'] as $ffour => $four) {
                    set_time_limit(0);
                        $userData = array(
                            'tcu_final' => "Admitted",
                            'formfour'=>$four
                        );
                        $appID=$db->getApplicantID($four);
                        foreach($appID as $applID)
                        {
                            $applicantID=$applID['applicantID'];
                            $condition = array('applicantID' => $applicantID);
                            $updateapp = $db->update($tblName, $userData, $condition);
                        }
                        $status = true;
                        $jj++;
            }
        }
    }
    if($status)
    {
        header("Location:index3.php?sp=get_admitted_tcu&msg=succ&count=".$jj);

    }
    else
    {

        header("Location:index3.php?sp=get_admitted_tcu&msg=unsucc");
    }

} catch (PDOException $ex) {
    //echo "Error".$ex->getMessage();
    $db->redirect("index3.php?sp=get_admitted_tcu&msg=error");
}