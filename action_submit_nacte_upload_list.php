<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
require_once 'DB.php';
$db=new DBHelper();
$params = array('params' => array());
$programmeID=$_POST['programmeID'];
$academicYearID=$_POST['academicYearID'];
$admissionID=$_POST['admissionID'];
$programmeCode=$_POST['programmeCode'];
$payment_reference_number=$_POST['payment_reference_number'];

$status=false;
if(isset($_POST['doAdmit']) == 'Submit Applicants') {
    $jj = 0;
    foreach ($_POST['applicantID'] as $applicantID) {
        $applicantsData = $db->getApplicantNacteAdmittedList($applicantID,$programmeID, $admissionID);
        //var_dump($applicantsData);
        if (!empty($applicantsData)) {
            $x = 0;
            foreach ($applicantsData as $row) {
                $x++;
                $applicantID = $row['applicantID'];
                $userID = $row['userID'];
                $indexNumber = $db->getData("users", "userName", "userID", $userID);
                $fname = $row['firstName'];
                $mname = $row['middleName'];
                $lname = $row['lastName'];
                $gender = $row['gender'];
                $phoneNumber = $row['phoneNumber'];
                $dob = $row['dob'];
                $email = $row['email'];

                if(empty($email))
                    {
                        $email=strtolower($lname."_".$fname."@gmail.com");
                    }

                $disabilityStatus = $row['disabilityStatus'];
                $districtID = $row['districtID'];

                $districtName = $db->getData("district", "districtName", "districtID", $districtID);
                $regionID = $db->getData("district", "regionID", "districtID", $districtID);

                $regionName = $db->getData("region", "regionName", "regionID", $regionID);
                //O-Level
                $olevel = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Ordinary'), 'order_by applicantID ASC'));
                if (!empty($olevel)) {
                    $formfour = array();
                    $yearO = array();
                    foreach ($olevel as $matokeo) {
                        $yearTakenO = $matokeo['yearTaken'];
                        $yearO[] = $yearTakenO;
                        $indexNumberO = $matokeo['indexNumber'];
                        $number = explode("/", $indexNumberO);
                        $centerNumber = $number[0];
                        $iNumber = $number[1];
                        $year = $number[2];
                        //$yearTakenO[]=$year;
                        $indNumber = $centerNumber . "/" . $iNumber;
                        $formfour[] = $indNumber;
                    }
                } else {
                    $yearO = "-";
                    $indexNumberO = "-";
                    $formfour = "";

                }
                //A-level

                $alevel = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Advance'), 'order_by applicantID ASC'));

                if (!empty($alevel)) {
                    $formsix = array();
                    $yaken = array();
                    foreach ($alevel as $matokeo) {
                        $yearTakenA = $matokeo['yearTaken'];
                        $indexNumberA = $matokeo['indexNumber'];
                        $number = explode("/", $indexNumberA);
                        $centerNumber = $number[0];
                        $iNumber = $number[1];
                        $year = $number[2];
                        //$yearTakenA=$year;
                        $indNumber = $centerNumber . "/" . $iNumber;
                        $formsix[] = $indNumber;
                        $yaken[] = $yearTakenA;

                    }
                } else {
                    $yaken = "-";
                    $indexNumberA = "-";
                    $formsix = "";
                }

                $equivalentresults = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Equivalent'), 'order_by applicantID ASC'));
                if (!empty($equivalentresults)) {
                    foreach ($equivalentresults as $matokeo) {
                        $yearTaken = $matokeo['yearTaken'];
                        $indexNumber = $matokeo['indexNumber'];
                    }
                } else {
                    $yearTaken = "-";
                    $indexNumber = "-";
                }


                $equivalentresults4 = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Equivalent', 'examinationAuthority' => 1), 'order_by applicantID ASC'));
                if (!empty($equivalentresults4)) {
                    foreach ($equivalentresults4 as $matokeo) {
                        $yearTaken4 = $matokeo['yearTaken'];
                        $indexNumber4 = $matokeo['indexNumber'];
                    }
                } else {
                    $yearTaken4 = "-";
                    $indexNumber4 = "-";
                }

                $api = $db->getAPI("NACTE", "verification");
                if (!empty($api)) {
                    foreach ($api as $ap) {
                        $token = $ap['token'];
                        $url = $ap['url'];
                    }
                }

               //API URL
               //$url = 'http://41.93.40.137/nacteapi/index.php/api/upload1';
               //$url= 'https://www.nacte.go.tz/nacteapi/index.php/api/upload';



                //create a new cURL resource    
                $ch = curl_init($url);
                //setup request to send json via POST       
                $data = array(
                    'heading'=>array(
                    'authorization' => $token,
                    'intake' => 'SEPT',
                    'programme_id' => $programmeCode,
                    'application_year' => '2022',
                    'level'=>'5',
                    'payment_reference_number' => $payment_reference_number,
                    ),
                    'students'=>array( 
                        ['particulars'=>array(
                            'firstname' => $fname,
                            'secondname' => $mname,
                            'surname' => $lname,
                            'DOB' => $dob,
                            'gender' => $gender,
                            'impairement' => $disabilityStatus,
                            'form_four_indexnumber' => implode(",", $formfour),
                            'form_four_year' => implode(",", $yearO),
                            'form_six_indexnumber' => implode(",", $formsix),
                            'form_six_year' => implode(",", $yaken),
                            'NTA4_reg' => '',
                            'NTA4_grad_year' => '',
                            'NTA5_reg' => '',
                            'NTA5_grad_year' => '',
                            'email_address' => $email,
                            'mobile_number' => $phoneNumber,
                            'address' => $row['physicalAddress'],
                            'region' => $regionName,
                            'district' => $districtName,
                            'nationality' => $row['citizenship'],
                            'next_kin_name' => $row['nextOfKinName'],
                            'next_kin_address' => $row['nextOfKinAddress'],
                            'next_kin_email_address'=>'',
                            'next_kin_phone' => $row['nextOfKinPhoneNumber'],
                            'next_kin_region' => $regionName,
                            'next_kin_relation' => $row['relationship']
                        )],
                    )       
                );
                $payload = json_encode(array($data));

                //attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                //set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

                //return response instead of outputting
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //execute the POST request
                $result = curl_exec($ch);

                //close cURL resource
                curl_close($ch);
                //$output= json_decode($result);
                //if($output['code']==200)
                $status = true;
                $jj++;

                var_dump($result);

            }
        }
    }
   /* if($status)
    {
        header("Location:index3.php?sp=upload_list_nacte&msg=succ&count=".$jj);
    }
    else
    {
        header("Location:index3.php?sp=upload_list_nacte&msg=unsucc");
    }*/
}

