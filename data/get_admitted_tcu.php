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
<InstitutionCode>'.$user.'</InstitutionCode >
<Programme>'.$programmeCode.'</Programme>
</requestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/admission/getAdmitted");
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);

$applicants=$array_data['requestParameters']['Applicant'];
$count=0;
foreach($applicants as $app) {
    $count++;
    $formfour = $app['F4indexno'];
    $formsix = $app['F6indexno'];
    $mobileNumber=$app['Mobilenumber'];
    $email = $app['Emailaddress'];
    $status=$app['Admissionstatus'];

    $output['data'][] = array(
        $count,
        $formfour,
        $formsix,
        $mobileNumber,
        $email,
        $status
    );
}


echo json_encode($output);