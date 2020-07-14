<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$status=false;
if(isset($_REQUEST['action_type'])=='submit_app') {
    $applicantID = $_REQUEST['applicantID'];

    $applicantdetails = $db->getApplicantDetails($applicantID);
    if (!empty($applicantdetails)) {
        foreach ($applicantdetails as $data) {
            $fname = $data['firstName'];
            $lname = $data['lastName'];
            $dob = $data['dob'];
            $disabiliyStatus = $data['disabilityStatus'];
            $nationality = $data['citizenship'];
            $phoneNumber = $data['phoneNumber'];
            $entryQualification = $data['entryQualification'];
        }
    } else {
        $dob = "";
        $disabiliyStatus = "";
        $nationality = "";
        $phoneNumber = "";
        $email = "";
        $entryQualification = "";
    }

    if (empty($nationality))
        $nationality = "Tanzanian";
    else
        $nationality = $nationality;

    $date = explode("-", $dob);
    $date1 = $date[2];
    $date2 = $date[1];
    $email = "$fname$date1$date2@gmail.com";


    $email=strtolower($email);
    if ($disabiliyStatus == "Yes") {
        $disability = $db->getRows("disability", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
        if (!empty($equivalentresults)) {
            foreach ($disability as $disab) {
                $dname = $disab['disabilityName'];
            }
        }
    } else {
        $dname = "None";
    }

    if ($entryQualification == 0)
        $category = "A";
    else
        $category = "D";

    $oindexumber = $db->getIndexNumber($applicantID, "Ordinary");
    if (!empty($oindexumber)) {
        $formfour = array();
        foreach ($oindexumber as $fnumber) {
            $indexNumber = $fnumber['indexNumber'];
            $formfour[] = $indexNumber;
        }

    } else {
        $formfour[] = "";
    }

    $formindexnumber = $formfour[0];


    $aindexumber = $db->getIndexNumber($applicantID, "Advance");
    $formsix = array();
    if (!empty($aindexumber)) {
        $formsix = array();
        foreach ($aindexumber as $fsixnumber) {
            $ffindexNumber = $fsixnumber['indexNumber'];
            $formsix[] = $ffindexNumber;
        }

    } else {
        $formsix[] = "";
    }


    $programmeFirstChoice = $db->getProgramme($applicantID, 1);
    if (!empty($programmeFirstChoice)) {
        foreach ($programmeFirstChoice as $pChoice) {
            $firstChoice = $pChoice['programCode'];
        }
    } else {
        $firstChoice = "";
    }

    $programmeChoice = $db->getProgramme($applicantID, 2);
    if (!empty($programmeChoice)) {
        foreach ($programmeChoice as $pChoice) {
            $secondChoice = $pChoice['programCode'];
        }
    } else {
        $secondChoice = "";
    }

    $equivalentresults = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Equivalent'), 'order_by applicantID ASC'));
    if (!empty($equivalentresults)) {
        foreach ($equivalentresults as $matokeo) {
            $eIndexNumber = $matokeo['indexNumber'];
            $avn_number = $matokeo['avn_number'];
        }
    } else {
        $eIndexNumber = "";
        $avn_number = "";

    }


    if ($category == "A")
        $findexNumber = $formsix[0];
    else {
        if ($avn_number == "")
            $findexNumber = $eIndexNumber;
        else
            $findexNumber = $avn_number;
    }


    $four = array();
    for ($x = 1; $x < count($formfour); $x++) {
        $four[] = $formfour[$x];
    }

    $six = array();
    for ($x = 1; $x < count($formsix); $x++) {
        $six[] = $formsix[$x];
    }
    $fourfour = implode(",", $four);
    $sixsix = implode(",", $six);

    $programmeAdmitted = $db->getAdmittedProgramme($applicantID, 1);
    if (!empty($programmeAdmitted)) {
        foreach ($programmeAdmitted as $padmitted) {
            $programmeAdmittedCode = $padmitted['programCode'];
            $programmeAdmittedName = $padmitted['programName'];
        }
    }
    /*else
    {
        $programmeAdmittedCode="";
    }*/

    $appProg = "$firstChoice,$secondChoice";
    $user = "MUM";
    $token = "jQbgVNUWdPk67wZcEv39";
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<Request>
    <UsernameToken>
        <username>' . $user . '</username>
        <SessionToken>' . $token . '</SessionToken>
    </UsernameToken>
    <requestParameters>
        <institutionCode>' . $user . '</institutionCode>
        <f4indexno>' . $formindexnumber . '</f4indexno >
        <f6indexno>' . $findexNumber . '</f6indexno>
        <Selectedprogrammes>' . $appProg . '</Selectedprogrammes>
        <Mobilenumber>' . $phoneNumber . '</Mobilenumber>
        <Emailaddress>' . $email . '</Emailaddress>
        <AdmissionStatus>provisional admission </AdmissionStatus>
        <Programme_admitted>' . $programmeAdmittedCode . '</Programme_admitted>
        <reason>eligible</reason>
        <nationality>' . $nationality . '</nationality>
        <impairment>' . $dname . '</impairment>
        <dateOfBirth>' . $dob . '</dateOfBirth>
        <Other_f4indexno>' . $fourfour . '</Other_f4indexno>
        <Other_f6indexno>' . $sixsix . '</Other_f6indexno>
    </requestParameters>
</Request>';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/applicants/submitProgramme");
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $data = curl_exec($ch);
    curl_close($ch);

    $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
    if($array_data['RESPONSE']['RESPONSEPARAMETERS']['ERROR_CODE']=="200")
    {
        $userData = array(
            'email' => $email,
            'tcu_status' => 21
        );
        $condition = array('applicantID' => $applicantID);
        $updateapp = $db->update("applicants", $userData, $condition);
        $status = true;
    }

    if ($status) {
        header("Location:index3.php?sp=submit_selected_tcu&msg=succ");
    } else {
        header("Location:index3.php?sp=submit_selected_tcu&msg=unsucc");
    }
}
?>