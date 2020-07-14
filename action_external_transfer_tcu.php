<?php
require_once 'DB.php';
$db=new DBHelper();
$user="MUM";
$token="jQbgVNUWdPk67wZcEv39";

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
</RequestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/admission/submitInterInstitutionalTransfers");
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
        'applicantID' => 111111111,
        'formFour'=> $formfour,
        'formSix' => $formsix,
        'bProgrammeCode' => $bProgrammeCode,
        'aProgrammeCode'=>$aProgrammeCode,
        'transferType'=>'External',
        'statusCode'=>$status,
        'tcu_status'=>$status_desc
    );
    $updateapp = $db->insert("applicant_transfer", $traData);
//}
header("Location:index3.php?sp=external_transfer&msg=".$status."&status=".$status_desc);


