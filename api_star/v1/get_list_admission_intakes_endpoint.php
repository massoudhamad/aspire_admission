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


// Get Addission Intakes
$adIntakes = $db->getRows('admission_setting', array('order_by' => 'academicYearID DESC'));
$adIntakesArr = [];
if (!empty($adIntakes)) {
  foreach ($adIntakes as $data) {
    $admission_name = $data['admissionName'];
    $admission_id = $data['admissionID'];
    $adIntakesArr[$admission_id] = $admission_name;
  }
}


//Return Response
$resData = array('intakes' => $adIntakesArr);
$this->returnResponseData($resData);

/////////////////////////////////////////////////////////////////