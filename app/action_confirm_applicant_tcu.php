<?php
require_once '../DB.php';
$db=new DBHelper();

$api_token = $db->getAPI("TCU", "token");
if (!empty($api_token)) {
    foreach ($api_token as $api) {
        $token = $api['token'];
        $user = $api['userName'];
        $urlform = $api['url'];
    }
}

$formfour=$_POST['formfour'];
$confirmationcode=$_POST['confirmationCode'];

$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno>'.$formfour.'</f4indexno>
<ConfirmationCode>'.$confirmationcode.'</ConfirmationCode>
</RequestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/admission/confirm");
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_desc=$array_data['Response']['ResponseParameters']['StatusDescription'];

if($status != 217)
{
    $userData = array(
        'tcu_final' => $status,
        'tcu_message'=>$status_desc
    );
    $condition = array('formfour' => $formfour,'admissionID'=>1);
    $updateapp = $db->update("applicants", $userData, $condition);
}
header("Location:index.php?msg=".$status."&status=".$status_desc);


