<?php
//ini_set("display_errors", 1);
ini_set('max_execution_time', 3600); //3600 seconds
//ini_set('memory_limit', '16M'); // change 16M to your desired number
ini_set('memory_limit', -1); // unlimited
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
if (!array_key_exists("reg_number", $requestData) || !array_key_exists("admission_id", $requestData)) {
  $this->throwError(INVALID_PARAMETERS_REQUEST, INVALID_PARAMETERS_REQUEST_MESSAGE);
}

// Check Parameters
if (empty($requestData['reg_number']) || empty($requestData['admission_id'])) {
  $this->throwError(REQUIRED_USER_PASS, REQUIRED_USER_PASS_MESSAGE);
}

$reg_number = $requestData['reg_number'];
$admission_id = $requestData['admission_id'];


// Get All Addission Programmes Major
$applicants = $db->getRegisteredBatchSingleApplicantAllDetails($reg_number, $admission_id);


// 
$allApplicants = [];
if (!empty($applicants)) {
  foreach ($applicants as $data) {
    $applicantID = $data['applicantID'];
    $fname = $data['firstName'];
    $mname = $data['middleName'];
    $lname = $data['lastName'];
    $gender = substr($data['gender'], 0, 1);
    //
    $phoneNumber1 = trim($data['phoneNumber']);
    $phoneNumber2 = str_replace(' ', '', $phoneNumber1);
    $phoneNumber = "0" . substr($phoneNumber2, -9);
    //
    $sponsor = trim($data['sponsor']);
    if ($sponsor == "HESLB") {
      $sponsor = 1;
    } elseif ($sponsor == "ZHELB") {
      $sponsor = 2;
    } elseif ($sponsor == "Self") {
      $sponsor = 3;
    } else {
      $sponsor = 4;
    }
    //
    $dob = $data['dateOfBirth'];
    $addNumber = $data['applicationNumber'];
    $entry = $data['entryQualification'];
    $userID = $data['userID'];
    $nacte_status = $data['nacte_status'];
    $hosteller = $data['hosteller'];
    $nationality = $data['citizenship'];
    $registrationNumber = $data['registrationNumber'];
    $studentPicture = !empty($data['studentPicture']) ? $data['studentPicture'] : "";
    $religion = $data['religion'];
    //
    $placeOfBirth = strtoupper($data['placeOfBirth']);
    $maritalStatus = $data['maritalStatus'];
    $residencyStatus = $data['residencyStatus'];
    $postalAddress = $data['postalAddress'];
    $physicalAddress = $data['physicalAddress'];
    $email = $data['email'];
    $nextOfKinName = strtoupper($data['nextOfKinName']);
    $nextOfKinPhoneNumber = $data['nextOfKinPhoneNumber'];
    $nextOfKinAddress = $data['nextOfKinAddress'];
    $relationship = strtoupper($data['relationship']);
    $disabilityStatus = $data['disabilityStatus'];
    $employmentStatus = $data['employmentStatus'];
    $programmeMajor = $data['programmeMajor'];
    $admissionName = $data['admissionName'];
    //
    if ($entry == 0) {
      $entry = 1;
    } else {
      $entry = 2;
    }
    //
    // $oindexumber = $db->getIndexNumber($applicantID, "Ordinary");
    // if (!empty($oindexumber)) {
    //   foreach ($oindexumber as $fnumber) {
    //     $indexNumber = $fnumber['indexNumber'];
    //     $formfour = $indexNumber;
    //   }
    // } else {
    //   $formfour = "None";
    // }

    $oindexumber = $db->getIndexNumber($applicantID, "Ordinary");
    if (!empty($oindexumber)) {
      $formfour = array();
      foreach ($oindexumber as $fnumber) {
        $indexNumber = $fnumber['indexNumber'];
        $formfour[] = $indexNumber;
      }
    } else {
      $formfour[] = "None";
    }

    // sort formfour index array by yeartaken
    if (!empty($formfour)) {
      usort($formfour, function ($a, $b) {
        $aLastChar = substr($a, -4);
        $bLastChar = substr($b, -4);
        return ($aLastChar < $bLastChar) ? -1 : 1;
      });
    }
    $formfour = $formfour[0];
    //
    $nida = $db->getRows("applicant_identification", array('where' => array('applicantID' => $applicantID)));
    if (!empty($nida)) {
      foreach ($nida as $nd) {
        $nationalID = $nd['nationalID'];
      }
    } else {
      $nationalID = "";
    }

    // Encode Image to base64
    $path = '../student_images/' . $studentPicture;
    $base64EncodeImage = "";
    if (file_exists($path) && !empty($studentPicture)) {
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      $base64EncodeImage = 'data:image/' . $type . ';base64,' . base64_encode($data);
    } else {
      $base64EncodeImage = "";
    }
    //-
    $singleApplicant = [
      'firstName' => $fname,
      'middleName' => $mname,
      'lastName' => $lname,
      'gender' => $gender,
      'regNumber' => $registrationNumber,
      'dob' => $dob,
      'addmissionNo' => $addNumber,
      'formIVindex' => $formfour,
      'entry' => $entry,
      'phoneNumber' => $phoneNumber,
      'hosteller' => $hosteller,
      'sponsor' => $sponsor,
      'nationality' => $nationality,
      'placeOfBirth' => $placeOfBirth,
      'maritalStatus' => $maritalStatus,
      'studentPicture' => $studentPicture,
      'physicalAddress' => $physicalAddress,
      'email' => $email,
      'nextOfKinName' => $nextOfKinName,
      'nextOfKinPhoneNumber' => $nextOfKinPhoneNumber,
      'nextOfKinAddress' => $nextOfKinAddress,
      'relationship' => $relationship,
      'disabilityStatus' => $disabilityStatus,
      'employmentStatus' => $employmentStatus,
      // 'programmeMajor' => $programmeMajor,
      // 'admissionName' => $admissionName,
      'religion' => $religion,
      'base64EncodeImage' => $base64EncodeImage
    ];
    //
    $allApplicants[] = $singleApplicant;
  }
}


//Get program major name
//$program_mid = $db->getData("applicantregistration", "programmeMajorID", "registrationNumber", $reg_number);
// if (empty($program_mid)) {
//   $program_mid = $db->getData("applicantregistration", "programmeID", "registrationNumber", $reg_number);
// }
$program_mid = $db->getData("applicantregistration", "programmeID", "registrationNumber", $reg_number);

$programmemajorName = $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $program_mid);
$admissionName = $db->getData("admission_setting", "admissionName", "admissionID", $admission_id);
// Return Response
$resData = array(
  'progMajorName' => $programmemajorName,
  'admissionName' => $admissionName,
  'registered' => $allApplicants
);

$this->returnResponseData($resData);

/////////////////////////////////////////////////////////////////