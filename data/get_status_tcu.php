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
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/applicants/getStatus");
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
$data = curl_exec($ch);
curl_close($ch);
//echo $data;
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);

$applicants=$array_data['RESPONSEPARAMETERS']['APPLICANT'];
$count=0;
foreach($applicants as $app) {
    $count++;
    $formfour = $app['F4INDEXNO'];
    $formsix = $app['F6INDEXNO'];
    $status = $app['ADMISSIONSTATUS'];

    if($status=="Multiple Admission")
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
        $formsix,
        $status,
        $confirm
    );
}
// database connection close
//$db->close();

echo json_encode($output);