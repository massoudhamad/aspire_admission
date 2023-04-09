<?php
//ini_set("display_errors", 1);
//require '../vendor/autoload.php';

// WIthout Composer Autolaod
require_once '../jwt_firebase/php-jwt/src/JWT.php';
require_once '../jwt_firebase/php-jwt/src/SignatureInvalidException.php';
require_once '../jwt_firebase/php-jwt/src/BeforeValidException.php';
require_once '../jwt_firebase/php-jwt/src/ExpiredException.php';
require_once '../jwt_firebase/php-jwt/src/JWK.php';

use \Firebase\JWT\JWT;

session_start();
require("../DB.php");
$db = new DBHelper();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $this->throwError(REQUEST_METHOD_NOT_VALID, REQUEST_METHOD_NOT_VALID_MESSAGE);
}

// VALIDATE TOKEN rest.php
$userPayload = $this->validateToken();

$this->request = file_get_contents('php://input', 'r');
$requestData = json_decode($this->request, true);

// Check validity of parameter but only for needed parameters
if (!array_key_exists("program_id", $requestData)) {
  $this->throwError(INVALID_PARAMETERS_REQUEST, INVALID_PARAMETERS_REQUEST_MESSAGE);
}

if (empty($requestData['program_id'])) {
  $this->throwError(REQUIRED_USER_PASS, REQUIRED_USER_PASS_MESSAGE);
}

// Validate UserId and Secret
$programmeID = $requestData['program_id'];

$majorProgramme = $db->getRows('programmemajor', array('where' => array('programmeID' => $programmeID), 'order_by' => 'programmeMajorID ASC'));
$allProgrammeMajorArr = [];
if (!empty($majorProgramme)) {
  foreach ($majorProgramme as $mProg) {
    $program_major_name = $mProg['programmeMajor'];
    $program_major_id = $mProg['programmeMajorID'];
    $allProgrammeMajorArr[$program_major_id] = $program_major_name;
  }
}

// Return Response
$resData = array('programs_major' => $allProgrammeMajorArr);
$this->returnResponseData($resData);

/////////////////////////////////////////////////////////////////