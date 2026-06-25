<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$applicantsData=$db->getZalongwaList();
if(!empty($applicantsData))
{
    $i=0;
    foreach ($applicantsData as $data)
    {
        $i++;
        $applicantID=$data['applicantID'];
        $registrationNumber=$data['registrationNumber'];
        $refNumber=$data['refNumber'];
        $address=$data['physicalAddress'];
        $fname=$data['firstName'];
        $mname=$data['middleName'];
        $lname=$data['lastName'];
        $gender=$data['gender'];
        $dob=$data['dateOfBirth'];
        $disabiliyStatus=$data['disabilityStatus'];
        $nationality=$data['citizenship'];
        $entryQualification=$data['entryQualification'];
        $name="$lname,$fname $mname";
        
        $programmeChoice=$db->getRegisteredProgramme($applicantID);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $registrationNumber=$pChoice['registrationNumber'];
                $programmeCode=$pChoice['programCode'];
                $programmeName=$pChoice['programName'];
                $major=$pChoice['major'];
                $programmeMajor=$pChoice['programmeMajor'];
                $programmeMajorID=$pChoice['programmeMajorID'];
            }
        }
        
        $campus=$db->getCampus($programmeMajorID);
        if(!empty($campus))
        {
            foreach ($campus as $cp)
            {
                $campusName= $cp['campusName'];
            }
        }
        
        if($entryQualification==1)
        {
            $entry="Equivalent";
        }
        else
        {
            $entry="Direct";
        }
        
        
        
        $output['data'][] = array(
            $name,
            $registrationNumber,
            $gender,
            $dob,
            $entry,
            $campusName,
            $programmeCode,
            $programmeName,
            $address,
            strtoupper($nationality),
            $refNumber
        );
        
    }
}

echo json_encode($output);