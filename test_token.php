<?php
    ini_set ('display_errors', 1);
    error_reporting (E_ALL | E_STRICT);

    $url = "https://api.necta.go.tz/api/public/auth/MjAxNzA3MTcxMTMyNTN5OUMkTldoVC5HcyRPUmkwWW0wZWRQb2x2cG1qeSR5YlVsRCQkT1RlSk9JbG1ZJDJwcEFveG14R0pUbEpUTEtoQVQ4ODJkRS51eCRUeUVlLnUwMUxHRTJKRVZHTXNMeCRvRUx5d2xVZCRDVDU1cG1KRWpKVmc2TmR1SiRDZm0uRE1UOTN5c3V4SnkubHZUVk9KYmh4VTIuQ3lUbiRubGxBTEpNQWIyUFRPSjBmUFBFaGxNaHU=";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response_json = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response_json, true);

    $token=$response['token'];
    echo $token."<br>";
    $apiNumber="S4002-0678/2/2020";


        $url = "https://api.necta.go.tz/api/public/results/" . $apiNumber . "/" . $token;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response_json = curl_exec($ch);

        var_dump($response_json);

    /* $json = "https://api.necta.go.tz/api/public/particulars/" . $apiNumber . "/" . $token;

    $chd = curl_init($json);
    curl_setopt($chd, CURLOPT_HTTPGET, true);
    curl_setopt($chd, CURLOPT_RETURNTRANSFER, true);
    $response_data = curl_exec($chd);
    curl_close($chd);
    $data = json_decode($response_data, true);
   
    if ($data['status']['code']==1) {
        $fname = $data['particulars']['first_name'];
        $mname = $data['particulars']['middle_name'];
        $lname = $data['particulars']['last_name'];
        $gender = $data['particulars']['sex'];

        echo $fname;
    }
    else
    {
        echo "hjsadsahj";
    } */

?>