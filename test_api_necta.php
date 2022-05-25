<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); 
/* $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.necta.go.tz/api/particulars/individual',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "index_number":"S1291/0029",
    "exam_id": 1,
    "exam_year": 2003,
    "api_key ": "$2y$10$MAC3TC1NKNvq5NHWfAdUJuvpbEpvk.LdsJIPJ6JLpHREYDEj4XcJu"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl); */
$number_kituo="S1291/0029";
$exam_year=2003;
$token1="$2y$10$";
$token2="MAC3TC1NKNvq5NHWfAdUJuvpbEpvk";
$token3=".LdsJIPJ6JLpHREYDEj4XcJu";

$data = array(
    "index_number"=>$number_kituo,
    "exam_year"=>$exam_year,
    "exam_id"=>1,
    "api_key"=>"$2y$10$MAC3TC1NKNvq5NHWfAdUJuvpbEpvk.LdsJIPJ6JLpHREYDEj4XcJu"
);
$payload = json_encode($data);

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://api.necta.go.tz/api/particulars/individual',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS =>$payload,
CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
),
));

$response_json = curl_exec($curl);

curl_close($curl);

echo $response_json;
