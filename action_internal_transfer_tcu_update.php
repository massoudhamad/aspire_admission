<?php
require_once 'DB.php';
$db=new DBHelper();
$api_token = $db->getAPI("TCU", "token");
if (!empty($api_token)) {
    foreach ($api_token as $api) {
        $token = $api['token'];
        $user = $api['userName'];
        $urlform = $api['url'];
    }
}

$formfour=$_POST['formfoursec'];
$formsix=$_POST['formsix'];
$bProgrammeCode=$_POST['bProgrammeCode'];
$aProgrammeCode=$_POST['aProgrammeCode'];


$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno>'.$formfour.'</f4indexno>
<f6indexno>'.$formsix.'</f6indexno>
<CurrentProgrammeCode>'.$aProgrammeCode.'</CurrentProgrammeCode>
<PreviousProgrammeCode>'.$bProgrammeCode.'</PreviousProgrammeCode>
<Gender>'.$gender.'</Gender>
</RequestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/admission/submitInternalTransfers");
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
$data = curl_exec($ch);
curl_close($ch);

$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_desc=$array_data['Response']['ResponseParameters']['StatusDescription'];


//if($status == 200)
//{
$traData = array(
    'applicantID' => 111111112,
    'formFour'=> $formfour,
    'formSix' => $formsix,
    'bProgrammeCode' => $bProgrammeCode,
    'aProgrammeCode'=>$aProgrammeCode,
    'transferType'=>'Internal',
    'statusCode'=>$status,
    'tcu_status'=>$status_desc
);
$updateapp = $db->insert("applicant_transfer", $traData);
//}
header("Location:index3.php?sp=internal_transfer&msg=".$status."&status=".$status_desc);


?>