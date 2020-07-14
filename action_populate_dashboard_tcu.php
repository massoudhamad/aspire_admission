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
        $url="http://api.tcu.go.tz/dashboard/populate";

        $programmeCode=$_REQUEST['programmeCode'];
        $male=$_REQUEST['males'];
        $female=$_REQUEST['female'];

        /*$xml='<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <username>'.$user.'</username>
        <SessionToken>'.$token.'</SessionToken>
        </UsernameToken>
        <requestParameters>
        <institutioncode>'.$user.'</institutioncode >
        <Programme>'.$programmeCode.'</Programme>
        <Males>'.$male.'</Males>
        <Females>'.$female.'</Females>
        </requestParameters>
        </Request>';*/

        $xml='<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>'.$user.'</Username>
        <SessionToken>'.$token.'</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
        <Males>'.$male.'</Males>
        <Females>'.$female.'</Females>
        </RequestParameters>
        </Request>';


        $output=$db->addApplicantTCU($url, $xml);

        $boolStatus=true;
    }
    if($boolStatus)
    {
        header("Location:index3.php?sp=populate_dashboard_tcu&msg=succ");
        $_SESSION['output']=$output;
    }
    else
    {
        header("Location:index3.php?sp=populate_dashboard_tcu&msg=unsucc");
    }
}