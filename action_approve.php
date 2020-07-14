<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicantremarks';
$tblApplicants='applicants';
$applicantID=$_REQUEST['applicantID'];
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
     $userData = array(
         'applicantID'=>$applicantID,
         'remarkID'=>$_POST['remarksID'],
         'receiptNumber'=>$_POST['receiptNumber'],
         'comments'=>$_POST['comments'],
         'activeStatus'=>1
        );
    if($_REQUEST['action_type'] == 'add')
    {
            $applicantdata=array(
              'applicantsRemarksID'=>$_POST['remarksID']  
            );
            $condition= array('applicantID'=>$applicantID);
            $updateapplicants=$db->update($tblApplicants,$applicantdata,$condition);
            $appRemarksData=array(
              'activeStatus'=>0  
            );
            $updateAppRemarks=$db->update($tblName,$appRemarksData,$condition);
            
            $insert = $db->insert($tblName,$userData);


        /*if($db->checkApplicantStudyLevel($applicantID)==1) {
            $user = "MUM";
            $token = "jQbgVNUWdPk67wZcEv39";
            $url = "http://api.tcu.go.tz/applicants/add";
            $formfour = $_REQUEST['formfour'];
            $formsix = $_REQUEST['formsix'];
            $category = $_REQUEST['appcategory'];

            $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <username>' . $user . '</username>
        <SessionToken>' . $token . '</SessionToken>
        </UsernameToken>
        <requestParameters>
        <institutionCode>' . $user . '</institutionCode>
        <f4indexno>' . $formfour . '</f4indexno >
        <f6indexno>' . $formsix . '</f6indexno>
        <Category>' . $category . '</Category>
        <Other_f4indexno></Other_f4indexno>
        <Other_f6indexno></Other_f6indexno>
        </requestParameters>
        </Request>';

            $output = $db->addApplicantTCU($url, $xml);

            $tcudata = array(
                'tcu_status' => 1
            );
            $condition = array('applicantID' => $applicantID);
            $updateapplicants = $db->update($tblApplicants, $tcudata, $condition);
        }
        else
        {
            $output="Successfully Saved";

        }
        $_SESSION['output'] = $output;*/
            $boolStatus=true;
    }
        if($boolStatus)
        {
            header("Location:index3.php?sp=approve&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=approve&msg=unsucc");
        }
}