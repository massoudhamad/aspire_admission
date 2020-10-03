<?php
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];

$api_token = $db->getAPI("TCU", "token");
if (!empty($api_token)) {
    foreach ($api_token as $api) {
        $token = $api['token'];
        $user = $api['userName'];
        $urlform = $api['url'];
    }
}

$url = $urlform . "/applicants/getStatus";

$programmeCode=$programmeID;
$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<username>'.$user.'</username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
</RequestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
$data = curl_exec($ch);
curl_close($ch);
//echo $data;
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status = $array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];
$applicants= $array_data['Response']['ResponseParameters']['Applicant'];
$count=0;
foreach($applicants as $app) {
    $count++;
    $formfour = $app['f4indexno'];
    $admissionStatusCode=$app['AdmissionStatusCode'];
    $admissionStatusDescription = $app['AdmissionStatusDescription'];

    if($admissionStatusCode==225)//Multiple Admission
    {
        $confirm="<a href='index3.php?sp=confirm_applicant_tcu&formfour=$formfour'>Confirm</a>";
    }
    else
    {
        $confirm="No";
    }
    $checkbox="<input type='checkbox' class='checkbox_class' name='applicantID[]' value='$applicantID'>";
    $output['data'][] = array(
        $count,
        $formfour,
        $admissionStatusCode,
        $admissionStatusDescription,
        $confirm
    );
}
// database connection close
//$db->close();

echo json_encode($output);