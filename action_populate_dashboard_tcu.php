<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
include 'DB.php';
$db = new DBHelper();
$tblApplicants='applicants';

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add')
    {

        $programmeCode=$_REQUEST['programmeCode'];
        $male=$_REQUEST['males'];
        $female=$_REQUEST['female'];

        $api_token = $db->getAPI("TCU", "token");
        if (!empty($api_token)) {
            foreach ($api_token as $api) {
                $token = $api['token'];
                $user = $api['userName'];
                $urlform = $api['url'];
            }
        }

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


        //$output=$db->addApplicantTCU($url, $xml);
        $url = $urlform . "/dashboard/populate";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $data = curl_exec($ch);
        //var_dump($data);
        curl_close($ch);

        $array_data = json_decode(json_encode(simplexml_load_string($data)), true);
        $status = $array_data['Response']['ResponseParameters']['StatusCode'];
        $status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];

        if ($array_data['Response']['ResponseParameters']['StatusCode'] == "200") {
            $boolStatus = true;
        }

    }
    if($boolStatus)
    {
        header("Location:index3.php?sp=populate_dashboard_tcu&msg=succ");
        $_SESSION['output']= $status_descript;
    }
    else
    {
        header("Location:index3.php?sp=populate_dashboard_tcu&msg=unsucc");
    }
}