<?php
// ini_set("display_errors", 1);

// WIthout Composer Autolaod
require_once '../jwt_firebase/php-jwt/src/JWT.php';
require_once '../jwt_firebase/php-jwt/src/SignatureInvalidException.php';
require_once '../jwt_firebase/php-jwt/src/BeforeValidException.php';
require_once '../jwt_firebase/php-jwt/src/ExpiredException.php';
require_once '../jwt_firebase/php-jwt/src/JWK.php';

use \Firebase\JWT\JWT;

session_start();
include_once("../DB.php");
$db = new DBHelper();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $this->throwError(REQUEST_METHOD_NOT_VALID, REQUEST_METHOD_NOT_VALID_MESSAGE);
}

$this->request = file_get_contents('php://input', 'r');
$requestData = json_decode($this->request, true);

// $this->request = file_get_contents('php://input', 'r');
// parse_str($this->request, $requestData);

//print_r($requestData);
//exit();

//1 - Check validity of parameter but only for needed parameters
if (!array_key_exists("client_id", $requestData) || !array_key_exists("client_secret", $requestData) || !array_key_exists("grant_type", $requestData)) {
  $this->throwError(INVALID_PARAMETERS_REQUEST, INVALID_PARAMETERS_REQUEST_MESSAGE);
}

if (
  empty($requestData['client_id']) || empty($requestData['client_secret']) || empty($requestData['grant_type'])
) {
  $this->throwError(REQUIRED_USER_PASS, REQUIRED_USER_PASS_MESSAGE);
}

// Validate UserId and Secret
$client_id = $requestData['client_id'];
$client_secret = $requestData['client_secret'];
$grant_type = $requestData['grant_type'];

// $checkAuth = $db->getRows(
//   "api_registration",
//   array('where' => array('client_id' => $client_id, 'client_secret' => $client_secret, 'grant_type' => $grant_type))
// );

$checkAuth = $db->getRows(
  "api_setting",
  array('where' => array('userName' => $client_id, 'token' => $client_secret, 'tokenType' => $grant_type))
);
//

if (empty($checkAuth)) {
  $this->throwError(INVALID_USER_PASS, INVALID_USER_PASS_MESSAGE);
  exit();
}

// GENERATE JWT TOKEN rest.php
$this->generateToken($client_id);