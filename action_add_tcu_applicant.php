<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblApplicants='applicants';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add')
    {
        $api_token = $db->getAPI("TCU", "token");
        if (!empty($api_token)) {
            foreach ($api_token as $api) {
                $token = $api['token'];
                $user = $api['userName'];
                $urlform=$api['url'];
            }
        }
        $url=$urlform."/applicants/add";
        $applicantID=$_REQUEST['applicantID'];
        $formfour=$_REQUEST['formfour'];
        $formsix=$_REQUEST['formsix'];
        $category=$_REQUEST['appcategory'];
        $other_four=$_REQUEST['other_four'];
        $other_six=$_REQUEST['other_six'];

        $xml='<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>'.$user.'</Username>
        <SessionToken>'.$token.'</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <f4indexno>'.$formfour.'</f4indexno >
        <f6indexno>'.$formsix.'</f6indexno>
        <Category>'.$category.'</Category>
        <Otherf4indexno>'.$other_four.'</Otherf4indexno>
        <Otherf6indexno>'.$other_six.'</Otherf6indexno>
        </RequestParameters>
        </Request>';

        $output=$db->addApplicantTCU($url, $xml);

        $array_data = json_decode(json_encode(simplexml_load_string($output)), true);
        $status = $array_data['Response']['ResponseParameters']['StatusCode'];
        $status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];

        if ($status==200) {
            $tcudata=array(
            'tcu_status'=>1
        );
            $condition= array('applicantID'=>$applicantID);
            $updateapplicants=$db->update($tblApplicants, $tcudata, $condition);
            $boolStatus=true;
        }
        else {
            $boolStatus=false;
        }
    }
    if($boolStatus)
    {
        header("Location:index3.php?sp=add_applicant_tcu&msg=succ");
        $_SESSION['output']=$output;
    }
    else
    {
        header("Location:index3.php?sp=add_applicant_tcu&msg=unsucc");
    }
}