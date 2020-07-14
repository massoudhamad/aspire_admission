<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblApplicants='applicants';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add')
    {
        $user="MUM";
        $token="jQbgVNUWdPk67wZcEv39";
        $url="http://api.tcu.go.tz/applicants/add";
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

        /*
        old system
        $xml='<?xml version="1.0" encoding="UTF-8"?>
         <Request>
         <UsernameToken>
         <username>'.$user.'</username>
         <SessionToken>'.$token.'</SessionToken>
         </UsernameToken>
         <requestParameters>
         <institutionCode>'.$user.'</institutionCode>
         <f4indexno>'.$formfour.'</f4indexno >
         <f6indexno>'.$formsix.'</f6indexno>
         <Category>'.$category.'</Category>
         <Other_f4indexno>'.$other_four.'</Other_f4indexno>
         <Other_f6indexno>'.$other_six.'</Other_f6indexno>
         </requestParameters>
         </Request>';*/

        $output=$db->addApplicantTCU($url, $xml);

        $tcudata=array(
            'tcu_status'=>1
        );
        $condition= array('applicantID'=>$applicantID);
        $updateapplicants=$db->update($tblApplicants,$tcudata,$condition);
        $boolStatus=true;
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