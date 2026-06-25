<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
try {
    include 'DB.php';
    $db = new DBHelper();

    $status=false;
    if(isset($_POST['doAdmit']) == 'Submit Registered')
    {
        $jj=0;
        foreach($_POST['regNumber'] as $regNumber)
        {
            
            $applicantsData=$db->getRows("uploaded_enrolled",array('where'=>array('registration_number'=>$regNumber)));
            if(!empty($applicantsData))
            {
                $i=0;
                foreach ($applicantsData as $data)
                {
                    $i++;

                    $fname = $data['fname'];
                    $mname = $data['mname'];
                    $lname = $data['surname'];
                    $gender = $data['gender'];
                    $dob = $data['date_of_birth'];
                    $disabiliyStatus = $data['physical_challenges'];
                    $nationality = $data['nationality'];
                    $entryQualification = $data['entry_qualification'];
                    $sponsor = $data['sponsorship'];
                    $registrationNumber = $data['registration_number'];
                    $tcu_status = $data['tcu_status'];
                    $fieldspecialization = $data['field_specialization'];
                    $award_category = $data['award_category'];

                   

                    $api_token = $db->getAPI("TCU", "token");
                    if (!empty($api_token)) {
                        foreach ($api_token as $api) {
                            $token = $api['token'];
                            $user = $api['userName'];
                            $urlform = $api['url'];
                        }
                    }

                    $url = $urlform . "/applicants/submitEnrolledStudents";


                    

            $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <Request>
                <UsernameToken>
                    <Username>' . $user . '</Username>
                    <SessionToken>' . $token . '</SessionToken>
                </UsernameToken>
                <RequestParameters>
                <Fname>'.$fname.'</Fname>
                <Mname>'.$mname.'</Mname>
                <Surname>'.$lname.'</Surname>
                <F4indexno>'.$data['f4_index_number'].'</F4indexno>
                <Gender>'.$gender.'</Gender>
                <Nationality>'.strtoupper($nationality).'</Nationality>
                <DateOfBirth>'. $dob.'</DateOfBirth>
                <ProgrammeCategory>'. $award_category.'</ProgrammeCategory>
                <Specialization>'. $fieldspecialization.'</Specialization>
                <AdmissionYear>2020/2021</AdmissionYear>
                <ProgrammeCode>'.$data['programme_code'].'</ProgrammeCode>
                <RegistrationNumber>'.$registrationNumber.'</RegistrationNumber>
                <ProgrammeName>'. $data['award_name'].'</ProgrammeName>
                <YearOfStudy>First Year</YearOfStudy >
                <StudyMode>Full Time</StudyMode >
                <IsYearRepeat>No</IsYearRepeat >
                <EntryMode>'. $data['entry_qualification'].'</EntryMode>
                <Sponsorship>'.$sponsor.'</Sponsorship >
                <PhysicalChallenges>'. $data['physical_challenges'].'</PhysicalChallenges>
                </RequestParameters>
            </Request>';
                  

           $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $data = curl_exec($ch);
            curl_close($ch);

            $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
            //var_dump($array_data);
            $StatusDescription=$array_data['Response']['ResponseParameters']['StatusDescription'];
            $statusCode=$array_data['Response']['ResponseParameters']['StatusCode'];

                   if($statusCode == 200 || $statusCode == 208)
                    {
                        $userData = array(
                            'tcu_status' => 1
                        ); 
                        $condition = array('registration_number' => $regNumber);
                        $updateapp = $db->update("uploaded_enrolled", $userData, $condition); 
                        $status = true;
                        $jj++;
                    }
            
                }
            }

        }
         if($status)
         {
             header("Location:index3.php?sp=uploaded_enrollment_submission&msg=succ&&msg=succ&".$statusCode."and&".$StatusDescription."&count=".$jj);

         }
         else
         {

             header("Location:index3.php?sp=uploaded_enrollment_submission&msg=".$statusCode."and&".$StatusDescription);
         } 
    }
  } catch (PDOException $ex) {
     $db->redirect("index3.php?sp=uploaded_enrollment_submission&msg=".$statusCode."and&".$StatusDescription);
 }
 