<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';

    $boolStatus=false;
    if (isset($_POST['doAdmit']) == 'Submit Applicants') {
        $jj=0;
        foreach ($_POST['applicantID'] as $applicantID) {
            //getbasic information of applicants
            //$applicantdetails=$db->getSubmitSelectedListNonDegreeTCU($applicantID);
            $applicantdetails = $db->getApplicantDetails($applicantID);
            if (!empty($applicantdetails)) {
                foreach ($applicantdetails as $data) {
                    $fname = $data['firstName'];
                    $lname = $data['lastName'];
                    $dob = $data['dob'];
                    $disabiliyStatus = $data['disabilityStatus'];
                    $gender=$data['gender'];
                    $nationality = $data['citizenship'];
                    $entryQualification = $data['entryQualification'];
                }
            } else {
                $dob = "";
                $disabiliyStatus = "";
                $nationality = "";
                $entryQualification = "";
            }

            if (empty($nationality)) {
                $nationality = "Tanzanian";
            } else {
                $nationality = $nationality;
            }

            if ($entryQualification == 0) {
                $category = "Advance";
            } else {
                $category = "Certificate";
            }


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



            $aindexumber = $db->getIndexNumber($applicantID, "Advance");
            $formsix = array();
            if (!empty($aindexumber)) {
                $formsix = array();
                foreach ($aindexumber as $fsixnumber) {
                    $indexNumber = $fsixnumber['indexNumber'];
                    $formsix[] = $indexNumber;
                }
            } else {
                $formsix[] = "";
            }

            $programmeChoice = $db->getAdmittedProgramme($applicantID, 1);
            if (!empty($programmeChoice)) {
                foreach ($programmeChoice as $pChoice) {
                    $programmeCode = $pChoice['programCode'];
                    $programmeName = $pChoice['programName'];
                }
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


            if ($category == "Advance") {
                $findexNumber = $formsix[0];
            } else {
                if ($avn_number == "") {
                    $findexNumber = $eIndexNumber;
                } else {
                    $findexNumber = $avn_number;
                }
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

            if(empty($eIndexNumber))
            {
                if ($category == "Advance") {
                    $eIndexNumber=$findexNumber;
                } else {
                    if ($avn_number == "") {
                        $eIndexNumber= $eIndexNumber;
                    } else {
                        $eIndexNumber = $avn_number;
                    }
                }
            }

            $api_token = $db->getAPI("TCU", "token");
            if (!empty($api_token)) {
                foreach ($api_token as $api) {
                    $token = $api['token'];
                    $user = $api['userName'];
                    $urlform=$api['url'];
                }
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <Request>
                <UsernameToken>
                    <Username>' . $user . '</Username>
                    <SessionToken>' . $token . '</SessionToken>
                </UsernameToken>
                <RequestParameters>
                    <F4indexno>' . $formfour[0] . '</F4indexno >
                    <F6indexno>' . $findexNumber . '</F6indexno>
                    <CertificateRegNumb>' . $eIndexNumber . '</CertificateRegNumb>
                    <Gender>'. $gender. '</Gender>
                    <Nationality>' . $nationality . '</Nationality>
                    <Impairment>' . $dname . '</Impairment>
                    <DateOfBirth>' . $dob . '</DateOfBirth>
                    <ApplicantCategory>'.$category. '</ApplicantCategory>
                    <ProgrammeName>' . $programmeName . '</ProgrammeName>
                    <ProgrammeCode>' . $programmeCode . '</ProgrammeCode>
                </RequestParameters>
            </Request>';

            //var_dump($xml);

            $url = $urlform. "/applicants/submitAdmittedNonDegree";


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $data = curl_exec($ch);
            //var_dump($data);
            curl_close($ch);

            $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
            $status = $array_data['Response']['ResponseParameters']['StatusCode'];
            $status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];

            if($array_data['Response']['ResponseParameters']['StatusCode']=="200")
            {
                $userData = array(
                    'tcu_status' => 12
                );
                $condition = array('applicantID' => $applicantID);
                $updateapp = $db->update("applicants", $userData, $condition);
                $boolStatus = true;
                $msgs = $status_descript;
                $jj++;
            }
            else
            {
                $msgs = $status_descript;
            }  
        }
    }
                if ($boolStatus) {
                    header("Location:index3.php?sp=submit_nondegree&msg=succ&count=".$jj);
                    $_SESSION['output'] = $msgs;
                } else {
                    header("Location:index3.php?sp=submit_nondegree&msg=unsucc");
                    $_SESSION['output'] = $msgs;
                }
        
} catch (PDOException $ex) {
    //echo "Error".$ex->getMessage();
    $db->redirect("index3.php?sp=submit_nondegree&msg=error");
}