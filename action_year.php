<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'academicyears';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
            $academicYear=$_POST['academicYear'];
            $academicYearStatus = $_POST['academicYearStatus'];

    if($_REQUEST['action_type'] == 'add'){
         $acadYear=$db->getRows('academicyears',array('where'=>array('academicYear'=>$academicYear),'order_by'=>'academicYear ASC'));
        if(!empty($acadYear))
        {
            $boolStatus=false;
               header("Location:index3.php?sp=academicyear&msg=unsecc"); 
        }else
        {
             if($academicYearStatus==1)
            {
                $year=$db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYear ASC'));
                foreach ($year as $yr) 
                {
                    $academicYearID=$yr['academicYearID'];
                    $academicYear=$yr['academicYear'];

                             $condition = array('academicYearID' => $academicYearID);
                             $userDataStatus = array('academicYearStatus' => 0);
                            $update = $db->update($tblName,$userDataStatus,$condition);
                }
            }
        $userData = array(
            'academicYear'=>$_POST['academicYear'],
            'academicYearStatus' => $_POST['academicYearStatus']
        );
        $insert = $db->insert($tblName,$userData);
        $boolStatus=true;
            header("Location:index3.php?sp=academicyear&msg=secc");
        }

    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            if($academicYearStatus==1)
            {
                $year=$db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYear ASC'));
                foreach ($year as $yr) 
                {
                    $academicYearID=$yr['academicYearID'];
                    $academicYear=$yr['academicYear'];

                             $condition = array('academicYearID' => $academicYearID);
                             $userDataStatus = array('academicYearStatus' => 0);
                            $update = $db->update($tblName,$userDataStatus,$condition);
                }
            }
            $userData = array(
                'academicYearStatus' => $academicYearStatus
            );
            $condition = array('academicYearID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $boolStatus=true;
            if($boolStatus)
            header("Location:index3.php?sp=academicyear&msg=edited");
            else
               header("Location:index3.php?sp=academicyear&msg=fail"); 
        }
    }
}