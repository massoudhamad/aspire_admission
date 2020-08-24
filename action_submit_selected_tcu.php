<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';

    $status=false;
    if(isset($_POST['doAdmit']) == 'Submit Applicants')
    {
        $jj=0;
        foreach($_POST['applicantID'] as $applicantID)
        {
            //getbasic information of applicants
            $applicantdetails=$db->getApplicantDetails($applicantID);
            if(!empty($applicantdetails)) {
                foreach ($applicantdetails as $data) {
                    $fname = $data['firstName'];
                    $lname = $data['lastName'];
                    $dob = $data['dob'];
                    $disabiliyStatus = $data['disabilityStatus'];
                    $nationality = $data['citizenship'];
                    $phoneNumber = $data['phoneNumber'];
                    $email = $data['email'];
                    $entryQualification = $data['entryQualification'];
                }
            }
            else
            {
                $dob = "";
                $disabiliyStatus = "";
                $nationality = "";
                $phoneNumber = "";
                $email = "";
                $entryQualification = "";
            }

            if(empty($nationality))
                $nationality="Tanzanian";
            else
                $nationality=$nationality;


            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email=$email;
                $tcu_status=41;
            }
            else {
                $date = explode("-", $dob);
                $date1 = $date[2];
                $date2 = $date[1];
                $email = "$lname$date1$date2@gmail.com";
                $tcu_status=41;
            }
            $email=strtolower(trim($email));

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

            if ($entryQualification == 0)
                $category = "A";
            else
                $category = "D";

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

            $formindexnumber=$formfour[0];


            $aindexumber=$db->getIndexNumber($applicantID,"Advance");
            $formsix=array();
            if(!empty($aindexumber))
            {
                $formsix=array();
                foreach ($aindexumber as $fsixnumber) {
                    $ffindexNumber=$fsixnumber['indexNumber'];
                    $formsix[]=$ffindexNumber;
                }

            }
            else
            {
                $formsix[]="";
            }




            $programmeFirstChoice=$db->getProgramme($applicantID,1);
            if(!empty($programmeFirstChoice))
            {
                foreach ($programmeFirstChoice as $pChoice)
                {
                    $firstChoice=$pChoice['programCode'];
                }
            }
            else
            {
                $firstChoice="";
            }

            $programmeChoice=$db->getProgramme($applicantID,2);
            if(!empty($programmeChoice))
            {
                foreach ($programmeChoice as $pChoice)
                {
                    $secondChoice=$pChoice['programCode'];
                }
            }
            else
            {
                $secondChoice="";
            }

            $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
            if(!empty($equivalentresults))
            {
                foreach($equivalentresults as $matokeo)
                {
                    $eIndexNumber=$matokeo['indexNumber'];
                    $avn_number=$matokeo['avn_number'];
                }
            }
            else
            {
                $eIndexNumber="";
                $avn_number="";

            }


            if($category=="A")
                $findexNumber=$formsix[0];
            else
            {
                if($avn_number=="")
                    $findexNumber=$eIndexNumber;
                else
                    $findexNumber=$avn_number;
            }


            $four=array();
            for($x=1;$x<count($formfour);$x++)
            {
                $four[]=$formfour[$x];
            }

            $six=array();
            for($x=1;$x<count($formsix);$x++)
            {
                $six[]=$formsix[$x];
            }
            $fourfour=implode(",",$four);
            $sixsix=implode(",",$six);

            $programmeAdmitted=$db->getAdmittedProgramme($applicantID,1);
            if(!empty($programmeAdmitted))
            {
                foreach ($programmeAdmitted as $padmitt)
                {
                    $programmeAdCode=$padmitt['programCode'];
                    $programmeAdName=$padmitt['programName'];
                }
            }

            $appProg="$firstChoice,$secondChoice";
            $othermobile="";
            $programmeAdmittedCode=$programmeAdCode;

            $api_token = $db->getAPI("TCU", "token");
            if (!empty($api_token)) {
                foreach ($api_token as $api) {
                    $token = $api['token'];
                    $user = $api['userName'];
                }
            }
            
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <Request>
                <UsernameToken>
                    <Username>' . $user . '</Username>
                    <SessionToken>' . $token . '</SessionToken>
                </UsernameToken>
                <RequestParameters>
                    <f4indexno>' . $formindexnumber . '</f4indexno >
                    <f6indexno>' . $findexNumber . '</f6indexno>
                    <SelectedProgrammes>' . $appProg . '</SelectedProgrammes>
                    <MobileNumber>' . $phoneNumber . '</MobileNumber>
                    <OtherMobileNumber>'. $othermobile .'</OtherMobileNumber>
                    <EmailAddress>' . $email . '</EmailAddress>
                    <Category>'.$category.'</Category>
                    <AdmissionStatus>provisional admission </AdmissionStatus>
                    <ProgrammeAdmitted>' . $programmeAdmittedCode . '</ProgrammeAdmitted>
                    <Reason>eligible</Reason>
                    <Nationality>' . $nationality . '</Nationality>
                    <Impairment>' . $dname . '</Impairment>
                    <DateOfBirth>' . $dob . '</DateOfBirth>
                    <Otherf4indexno>' . $fourfour . '</Otherf4indexno>
                    <Otherf6indexno>' . $sixsix . '</Otherf6indexno>
                </RequestParameters>
            </Request>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://api.tcu.go.tz/applicants/submitProgramme");
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $data = curl_exec($ch);
            curl_close($ch);

            $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
            var_dump($array_data);
            if($array_data['Response']['ResponseParameters']['StatusCode']=="200")
            {
                $userData = array(
                    'email'=>$email,
                    'tcu_status' => $tcu_status
                );
                $condition = array('applicantID' => $applicantID);
                $updateapp = $db->update("applicants", $userData, $condition);
                $status = true;
                $jj++;
            }


        }
       /* if($status)
        {
            header("Location:index3.php?sp=submit_selected_tcu&msg=succ&count=".$jj);

        }
        else
        {

            header("Location:index3.php?sp=submit_selected_tcu&msg=unsucc");
        }*/
    }
} catch (PDOException $ex) {
    echo "Error".$ex->getMessage();
    //$db->redirect("index3.php?sp=admitapplicants&msg=error");
}