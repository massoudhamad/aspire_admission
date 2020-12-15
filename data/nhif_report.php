<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$admissionID=$_GET['admissionID'];
//$admissionID = 12;
$applicantsData=$db->getNHIFReport($admissionID);
if(!empty($applicantsData))
{
    $i=0;
    foreach ($applicantsData as $data)
    {
        $i++;
        $applicantID=$data['applicantID'];
        $fname=$data['firstName'];
        $mname=$data['middleName'];
        $lname=$data['lastName'];
        $gender=$data['gender'];
        $dob=$data['dateOfBirth'];
        $nationality=$data['citizenship'];
        $phoneNumber=$data['phoneNumber'];
        $registrationNumber=$data['registrationNumber'];
        $formfour=$data['formfour'];
        $maritalStatus=$data['maritalStatus'];
        $programmeID=$data['programmeID'];


        $programme = $db->getStudyLevelID($programmeID);
        if (!empty($programme)) {
            foreach ($programme as $cp) {
                $schoolCode = $cp['schoolCode'];
                $programmeName=$cp['programmeMajor'];
                $proDuration=$cp['programDuration'];

            }
        } else {
            $schoolCode = "";
            $programmeName = "";
        }

        $idnumber = $db->getRows("applicant_identification", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
        if (!empty($idnumber)) {
            foreach ($idnumber as $id) {
                $nationalID = $id['nationalID'];
            }
        } else {
            $nationalID = "";
        }

        if ($gender== "M")
        $gender = "Male";
        else
            $gender = "Female";

        $cDate = $data['createdDate'];
        $date = new DateTime($cDate);
        $doj = $date->format('Y-m-d');
     
            
        $output['data'][] = array(
            $formfour,
            $registrationNumber,
            $doj,
            $fname,
            $mname,
            $lname,
            $dob,
            $maritalStatus,
            $gender,
            $phoneNumber,
            $programmeName,
            $schoolCode,
            1,
            $proDuration,
            $nationalID
        );
        
    }
}

echo json_encode($output);