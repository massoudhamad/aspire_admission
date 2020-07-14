<?php
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$user="MUM";
$token="jQbgVNUWdPk67wZcEv39";
$programmeCode=$programmeID;

$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
    <username>'.$user.'</username>
    <SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<requestParameters>
    <InstitutionCode>'.$user.'</InstitutionCode>
    <Programme>'.$programmeCode.'</Programme>
</requestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/applicants/getConfirmed");
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
/*$status=$array_data['responseParameters']['Status'];*/
$applicant=$array_data['ResponseParameters']['Applicant'];
$count=0;
foreach($applicant as $app) {
    $count++;
    $formfour = $app['f4indexno'];
    $formsix = $app['f6indexno'];
    $diploma=$app['diplomaregno'];
    $institution=$app['institutioncode'];
    $programme=$app['Programme'];
    $status=$app['Admissionstatus'];

    $output['data'][] = array(
        $count,
        $formfour,
        $formsix,
        $diploma,
        $institution,
        $programme,
        $status
    );
}


echo json_encode($output);