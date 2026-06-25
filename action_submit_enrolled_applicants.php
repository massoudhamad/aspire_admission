<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';
    $academicYearID=$_POST['academicYearID'];
    $admissionID=$_POST['admissionID'];

    $status=false;
    if(isset($_POST['doAdmit']) == 'Submit Registered')
    {
        $jj=0;
        foreach($_POST['applicantID'] as $applicantID)
        {
            
            $applicantsData=$db->getApplicantTCUEnrollmentList($applicantID,$academicYearID,$admissionID);
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
                    $dob=$data['dob'];
                    $disabiliyStatus=$data['disabilityStatus'];
                    $nationality=$data['citizenship'];
                    $entryQualification=$data['entryQualification'];
                    $sponsor=$data['sponsor'];
                    $registrationNumber=$data['registrationNumber'];

                    $dBirth=explode("-",$dob);
                        $birthYear=$dBirth[2];


                    if($disabiliyStatus=="Yes")
                    {
                        $disability=$db->getRows("disability", array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                        if(!empty($equivalentresults))
                        {
                            foreach($disability as $disab)
                            {
                                $dname=$disab['disabilityName'];
                            }
                        }
                    }
                    else {
                        $dname="None";
                    }


                    $oindexumber=$db->getIndexNumber($applicantID,"Ordinary");
                    if(!empty($oindexumber))
                    {
                        $formfour=array();
                        foreach ($oindexumber as $fnumber) {
                            $indexNumber=$fnumber['indexNumber'];
                            $formfour[]=$indexNumber;
                        }

                    }
                    else
                    {
                        $formfour[]="";
                    }

                    $programmeChoice=$db->getAdmittedProgramme($applicantID,1);
                    if(!empty($programmeChoice))
                    {
                        foreach ($programmeChoice as $pChoice)
                        {

                            $programmeCode=$pChoice['programCode'];
                            $programmeName=$pChoice['programName'];
                            $major=$pChoice['major'];
                            $programmeMajor=$pChoice['programmeMajor'];
                            $studyLevelID=$pChoice['studyLevelID'];
                        }
                    }

                    if($studyLevelID==1)
                    {
                        $study="Bachelor";
                    }
                    else if($studyLevelID==2)
                    {
                        $study="Diploma";
                    }
                    else if($studyLevelID==3)
                    {
                        $study="Certificate/NTA4/Diploma in Medical";
                    }

                    else if($studyLevelID==5)
                    {
                        $study="NTA5";
                    }



                    if($major="NA")
                    {
                        $fieldspecialization=$programmeMajor;
                    }
                    else
                    {
                        $fieldspecialization=$major;
                    }

                    if($entryQualification==1)
                    {
                        $entry="Equivalent";
                    }
                    else
                    {
                        $entry="Direct";
                    }


                    $programmeCode=$programmeCode;




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
                <F4indexno>'.$formfour[0].'</F4indexno>
                <Gender>'.$gender.'</Gender>
                <Nationality>'.strtoupper($nationality).'</Nationality>
                <DateOfBirth>'. $birthYear.'</DateOfBirth>
                <ProgrammeCategory>'.$study.'</ProgrammeCategory>
                <Specialization>'.$fieldspecialization.'</Specialization>
                <AdmissionYear>2020/2021</AdmissionYear>
                <ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
                <RegistrationNumber>'.$registrationNumber.'</RegistrationNumber>
                <ProgrammeName>'.$programmeName.'</ProgrammeName>
                <YearOfStudy>First Year</YearOfStudy >
                <StudyMode>Full Time</StudyMode >
                <IsYearRepeat>No</IsYearRepeat >
                <EntryMode>'.$entry.'</EntryMode >
                <Sponsorship>'.$sponsor.'</Sponsorship >
                <PhysicalChallenges>'.$dname.'</PhysicalChallenges>
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

                    if($array_data['Response']['ResponseParameters']['StatusCode'] == 200 || $statusCode == 208)
                    {
                        $userData = array(
                            'tcu_status' => 1
                        );
                        $condition = array('applicantID' => $applicantID);
                        $updateapp = $db->update("applicantregistration", $userData, $condition);
                        $status = true;
                        $jj++;
                    }
            
                }
            }

            //echo $array_data['RESPONSE']['RESPONSEPARAMETERS']['STATUS'];

        }
         if($status)
         {
             header("Location:index3.php?sp=enrollment_submission&msg=succ&&msg=succ&".$statusCode."and&".$StatusDescription."&count=".$jj);

         }
         else
         {

             header("Location:index3.php?sp=enrollment_submission&msg=".$statusCode."and&".$StatusDescription);
         }
    }
 } catch (PDOException $ex) {
     $db->redirect("index3.php?sp=enrollment_submission&msg=".$statusCode."and&".$StatusDescription);
 }
