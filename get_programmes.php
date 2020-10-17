<?php 
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */

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
<username>'.$user.'</username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<requestParameters>
<institutioncode>'.$user.'</institutioncode>
</requestParameters>
</Request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/admission/getProgrammes");
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
$data = curl_exec($ch);
// curl_close($ch);
$errNo = curl_errno($ch);
$err = curl_error($ch);
curl_close($ch); 


//basic error reporting if needed
echo '<br/><hr/><br/>';
echo 'error no: ' . $errNo;
echo '<br/>';
echo 'errMsg: ' . $err;
echo '<br/>';


echo 'Response: ';
var_dump($data);
//echo $data;
// $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
// var_dump($array_data);
?>