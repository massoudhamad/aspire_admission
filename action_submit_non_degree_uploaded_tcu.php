<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';

    $boolStatus=false;
    if (isset($_POST['doAdmit']) == 'Submit Applicants') {
        $jj=0;
        foreach ($_POST['id'] as $id) {
            $applicantsData = $db->getRows("applicants_non_degree", array('where'=>array('id'=>$id)));
            if (!empty($applicantsData)) {
                $i = 0;
                $api_token = $db->getAPI("TCU", "token");
                if (!empty($api_token)) {
                    foreach ($api_token as $api) {
                        $token = $api['token'];
                        $user = $api['userName'];
                        $urlform = $api['url'];
                    }
                }
                foreach ($applicantsData as $data) {
                    $i++;
                    $name = $data['name'];
                    $formfour = $data['formfour'];
                    $formsix = $data['fromsix'];
                    $certreg = $data['certreg'];
                    $gender = $data['gender'];

                    $dob = $data['dob'];
                    $disabiliyStatus = $data['imapairment'];
                    $nationality = $data['nationality'];
                    $programmeCode = $data['progcode'];
                    $programmeName = $data['progname'];
                    $entryQualification = $data['category'];
                    $tcu_status = $data['tcu_status'];

                    if ($entryQualification == 1) {
                        $category = "Advance";
                    } else {
                        $category = "Certificate";
                    }
            

            

                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <Request>
                <UsernameToken>
                    <Username>' . $user . '</Username>
                    <SessionToken>' . $token . '</SessionToken>
                </UsernameToken>
                <RequestParameters>
                    <F4indexno>' . $formfour . '</F4indexno >
                    <F6indexno>' . $formsix . '</F6indexno>
                    <CertificateRegNumb>' . $certreg . '</CertificateRegNumb>
                    <Gender>'. $gender. '</Gender>
                    <Nationality>' . $nationality . '</Nationality>
                    <Impairment>' . $disabiliyStatus . '</Impairment>
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

                    $array_data=json_decode(json_encode(simplexml_load_string($data)), true);
                    $status = $array_data['Response']['ResponseParameters']['StatusCode'];
                    $status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];

                    if ($array_data['Response']['ResponseParameters']['StatusCode']=="200") {
                        $userData = array(
                    'tcu_status' => 12
                );
                        $condition = array('applicants_non_degree' => $id);
                        $updateapp = $db->update("applicants_non_degree", $userData, $condition);
                        $boolStatus = true;
                        $msgs = $status_descript;
                        $jj++;
                    } else {
                        $msgs = $status_descript;
                    }
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
            }
        
} catch (PDOException $ex) {
    //echo "Error".$ex->getMessage();
    $db->redirect("index3.php?sp=submit_nondegree&msg=error");
}