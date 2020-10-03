<?php
require_once '../DB.php';
$db = new DBHelper();
$output = array('data' => array());
        $api_token = $db->getAPI("TCU", "token");
        if (!empty($api_token)) {
            foreach ($api_token as $api) {
                $token = $api['token'];
                $user = $api['userName'];
                $urlform=$api['url'];
            }
        }
        $url=$urlform. "/admission/getProgrammes";
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>' . $user . '</Username>
        <SessionToken>' . $token . '</SessionToken>
        </UsernameToken>
        </Request>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
        $data = curl_exec($ch);
        curl_close($ch);
        $array_data = json_decode(json_encode(simplexml_load_string($data)), true);
        foreach ($array_data as $dt) {
            $progCode = $dt['ResponseParameters']['Programme'];
            foreach ($progCode as $pg) {
                $pcode=$pg['ProgrammeCode'];
                $number=$pg['NumberOfApplicant'];

                $output['data'][] = array(
                $pg['ProgrammeCode'],
                $pg['NumberOfApplicant']
                );
            }
        }

// database connection close
//$db->close();
echo json_encode($output);
