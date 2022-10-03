<?php
session_start();
require_once 'DB.php';
$db=new DBHelper();
$params = array('params' => array());


$status=false;
if(isset($_POST['doAdmit']) == 'Submit Applicants') {
    $jj = 0;
    foreach ($_POST['verificationID'] as $verificationID) {
        $applicantsData = $db->getRows('applicants_nacte_list',array('where'=>array('student_verification_id'=>$verificationID),'order_by student_verification_id ASC'));
        if (!empty($applicantsData)) {
            $x = 0;
            foreach ($applicantsData as $row) {
                $x++;
                $verID=$row['student_verification_id'];
                $programmeID = $row['programme_id'];
                $firstName=$row['firstname'];
                $middleName=$row['secondname'];
                $lastName=$row['surname'];
                $mobileNumber=$row['mobile_number'];
                $email=$row['email_address'];
                $formfour=$row['form_four_indexnumber'];
                $formfouryear=$row['form_four_year'];
                $formsix=$row['form_six_indexnumber'];
                $formsixyear=$row['form_six_year'];
                $nta4=$row['NTA4_reg'];
                $nta4year=$row['NTA4_grad_year'];
                $nta5=$row['NTA5_reg'];
                $nta5year=$row['NTA5_grad_year'];
                $phoneNumber=$row['mobile_number'];
                $email=$row['email_address'];


                $api = $db->getAPI("NACTE", "verification");
                if (!empty($api)) {
                    foreach ($api as $ap) {
                        $token = $ap['token'];
                        //$url = $ap['url'];
                    }
                }

                //API URL
                $url = 'https://www.nacte.go.tz/nacteapi/index.php/api/addcorrection';
                //create a new cURL resource
                $ch = curl_init($url);
                //setup request to send json via POST

                /*"authorization": "<authorization>",
"student_verification_id": "<student_verification_id>",
"programme_id": "<programme_id>",
"firstname": "<firstname>",
"secondname": "<secondname>",
"surname": "<surname>",
"mobile_number": "<mobile_number>",
"email_address": "<email_address>",
"form_four_indexnumber": "<form_four_indexnumber>",
"form_four_year": "<form_four_year>",
"form_six_indexnumber": "<form_six_indexnumber>",
"form_six_year": <form_six_year>,
"NTA4_reg": "<NTA4_reg>",
"NTA4_grad_year": "<NTA4_grad_year>",
"NTA5_reg": "<NTA5_reg>",
"NTA5_grad_year": "<NTA5_grad_year>",*/

                $data = array(
                    'authorization' => $token,
                    'student_verification_id'=>$verificationID,
                    'programme_ID'=>$programmeID,
                    'firstname' => $firstName,
                    'secondname' => $middleName,
                    'surname' => $lastName,
                    'mobile_number' => $phoneNumber,
                    'email_address' => $email,
                    'form_four_indexnumber' => $formfour,
                    'form_four_year' => $formfouryear,
                    'form_six_indexnumber' => $formsix,
                    'form_six_year' => $formsixyear,
                    'NTA4_reg' => $nta4,
                    'NTA4_grad_year' => $nta4year,
                    'NTA5_reg' => $nta5,
                    'NTA5_grad_year' => $nta5year,

                );
                $payload = json_encode(array("user" => $data));

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
         header("Location:index3.php?sp=nacte_add_correction&msg=succ&count=".$jj);
     }
     else
     {
         header("Location:index3.php?sp=nacte_add_correction&msg=unsucc");
     }  */
}

