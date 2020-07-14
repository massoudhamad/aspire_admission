<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include("DB.php");
$db=new DBHelper();

//$indexNumber="S0163/0035/2008";
$user="MUM";
$token="jQbgVNUWdPk67wZcEv39";
//$api="http://197.149.178.22";

// $formfour=$indexNumber;
// $formsix="S1064/0529/2011";
// $category="A";
// $other_four="";
// $other_six="";

//check_status

/*$url=$api."/applicants/checkStatus";

$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno>'.$indexNumber.'</f4indexno>
</RequestParameters>
</Request>';

$output = $db->getTCUStatus($url,$xml);

$array_data= json_decode(json_encode(simplexml_load_string($output)),true);
$foutput=$array_data['Response']['ResponseParameters']['f4indexno'];
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];

echo $status."<br>".$status_descript;*/

//Add Applicant

/*$url=$api."/applicants/add";
$formfour=$indexNumber;
$formsix="S1064/0529/2011";
$category="A";
$other_four="";
$other_six="";


$xml='<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>'.$user.'</Username>
        <SessionToken>'.$token.'</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <f4indexno>'.$formfour.'</f4indexno >
        <f6indexno>'.$formsix.'</f6indexno>
        <Category>'.$category.'</Category>
        <Otherf4indexno>'.$other_four.'</Otherf4indexno>
        <Otherf6indexno>'.$other_six.'</Otherf6indexno>
        </RequestParameters>
        </Request>';

        $output=$db->addApplicantTCU($url, $xml);

        $array_data= json_decode(json_encode(simplexml_load_string($output)),true);
        $status=$array_data['Response']['ResponseParameters']['StatusCode'];
        $status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];


        echo $status."<br>".$status_descript;*/

   //Submit Applicant Programme Choices

/*
 <?xml version=”1.0” encoding=”UTF-8”?>
<Request>
<UsernameToken>
<Username>UDSM</Username>
<SessionToken>OTcyMURGMTY5QTRENU3MUJ</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno> S1001/0012/2018</f4indexno >
<f6indexno> S1001/0562/2018 </f6indexno>
<SelectedProgrammes>UD023, UD038, UD022</SelectedProgrammes>
<MobileNumber> 0766345678</MobileNumber>
<OtherMobileNumber> 0766345678</OtherMobileNumber>
<EmailAddress> steve2014@hotmail.com </EmailAddress>
<Category>A</Category>
<AdmissionStatus>provisional admission </AdmissionStatus>
<ProgrammeAdmitted>UD038</ProgrammeAdmitted>
<Reason>eligible</Reason>
<Nationality >Tanzanian</Nationality>
<Impairment>None</Impairment>
<DateOfBirth>1980-12-09</DateOfBirth>
<Otherf4indexno>S0001/0001/2009, S0001/0001/2010</Otherf4indexno>
<Otherf6indexno> S0001/0501/2009, S0001/0501/2010</Otherf6indexno>
</RequestParameters>
</Request>
 */

/*$appProg = "SUM01,SUM03";
$formindexnumber=$indexNumber;
$findexNumber=$formsix;
$phoneNumber="0773500429";
$email="massoudhamad@gmail.com";
$programmeAdmittedCode="SMU03";
$nationality="Tanzania";
$othermobile="";
$category="A";
$dname="None";
$dob="1999-01-02";
$fourfour="";
$sixsix="";
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
$url=$api."/applicants/submitProgramme";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);
curl_close($ch);

$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];

echo $status."<br>".$status_descript;*/

//Confirm Applicant Selection
/*
 <?xml version="1.0" encoding="UTF-8"
<Request>
<UsernameToken>
<Username>DM</Username>
<SessionToken>OTcyMURGMTY5QTRENU3MUJ</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno> S1001/0012/2018</f4indexno>
<ConfirmationCode>A5267Y</ConfirmationCode>
</RequestParameters>
</Request>*/

/*$confirmationcode="A5267Y";
$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
    <UsernameToken>
        <Username>'.$user.'</Username>
        <SessionToken>'.$token.'</SessionToken>
    </UsernameToken>
    <RequestParameters>
        <f4indexno>'.$formfour.'</f4indexno>
        <ConfirmationCode>'.$confirmationcode.'</ConfirmationCode>
    </RequestParameters>
</Request>';

$ch = curl_init();
$url=$api."/admission/confirm";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];

echo $status. "<br>". $status_descript;*/

//Unconfirm Admission-error
/*$confirmationcode="A5267Y";
$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
    <Username>'.$user.'</Username>
    <SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno>'.$formfour.'</f4indexno>
<ConfirmationCode>'.$confirmationcode.'</ConfirmationCode>
</RequestParameters>
</Request>';

$ch = curl_init();
$url=$api."/admission/unconfirm";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];

echo $status. "<br>". $status_descript;*/

//Resubmit Applicant Details
/*$appProg = "MUM01,MUM03";
$formindexnumber=$indexNumber;
$findexNumber=$formsix;
$phoneNumber="0773500429";
$email="massoudhamad@gmail.com";
$programmeAdmittedCode="MUM03";
$nationality="Tanzania";
$othermobile="";
$category="A";
$dname="None";
$dob="1999-01-02";
$fourfour="";
$sixsix="";
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
$url=$api."/applicants/resubmit";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);
curl_close($ch);

$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];
echo $status."<br>".$status_descript;*/

//Populate Dashboard
/*<?xml version=”1.0” encoding=”UTF-8”?>
<Request>
<UsernameToken>
<Username>DM</Username>
<SessionToken>OTcyMURGMTY5QTRENU3MUJ</SessionToken>
</UsernameToken>
<RequestParameters>
<ProgrammeCode>DM038</ProgrammeCode>
<Males>45</Males>
<Females>60</Females>
</RequestParameters>
</Request>*/
/*$male=45;
$female=60;
$programmeCode="SUM03";
$xml='<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>'.$user.'</Username>
        <SessionToken>'.$token.'</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
        <Males>'.$male.'</Males>
        <Females>'.$female.'</Females>
        </RequestParameters>
        </Request>';
$url=$api."/dashboard/populate";
$output=$db->addApplicantTCU($url, $xml);
$array_data=json_decode(json_encode(simplexml_load_string($output)),true);
$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];

echo $status."<br>".$status_descript;*/

//Get Admitted Applicants
/*<?xml version=”1.0” encoding=”UTF-8”?>
<Request>
<UsernameToken>
<Username>DM</Username>
<SessionToken>OTcyMURGMTY5QTRENU3MUJ</SessionToken>
</UsernameToken>
<RequestParameters>
<ProgrammeCode>DM023</ProgrammeCode>
</RequestParameters>
</Request>*/

/*$programmeCode="MUM03";
$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
</RequestParameters>
</Request>';

$ch = curl_init();
$url=$api."/admission/getAdmitted";

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);

$status=$array_data['Response']['ResponseParameters']['StatusCode'];
$status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];

$applicants=$array_data['Response']['ResponseParameters']['Applicant'];
var_dump($applicants);

$count=0;
foreach($array_data as $dt)
{
    var_dump($dt);
    $applicants=$dt['ResponseParameters']['Applicant'];
    foreach($applicants as $app) {
    $count++;
    $formfour = $app['f4indexno'];
    $formsix = $app['f6indexno'];
    $mobileNumber = $app['MobileNumber'];
    $email = $app['EmailAddress'];
    $status = $app['AdmissionStatus'];

    $output['data'][] = array(
        $count,
        $formfour,
        $formsix,
        $mobileNumber,
        $email,
        $status
    );
    }
}
echo json_encode($output);*/

//Get Programmes with Admitted Candidates
/*$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
</Request>';

$ch = curl_init();
$url=$api."/admission/getProgrammes";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
$data = curl_exec($ch);
curl_close($ch);
$array_data=json_decode(json_encode(simplexml_load_string($data)),true);
$count=0;
foreach($array_data as $dt) {
    $count++;
    $progCode = $dt['ResponseParameters']['Programme'];
    echo $dt['ResponseParameters']['StatusCode']."<br>";
    echo $dt['ResponseParameters']['StatusDescription']."<br>";
    foreach($progCode as $pg)
    {
        $pcode=$pg['ProgrammeCode'];
        $number=$pg['NumberOfApplicant'];

        echo $pcode." - ".$number."<br>";
    }

}*/

//Get applicants’ Admission Status
/*$programmeCode="MUM01";
$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
</RequestParameters>
</Request>';

$ch = curl_init();
$url=$api."/applicants/getStatus";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
$data = curl_exec($ch);
curl_close($ch);

$array_data=json_decode(json_encode(simplexml_load_string($data)),true);

var_dump($array_data);

$count=0;
foreach($array_data as $dt) {
    $count++;
    $applicants=$dt['ResponseParameters']['Applicant'];
    echo $dt['ResponseParameters']['StatusCode']."<br>";
    echo $dt['ResponseParameters']['StatusDescription']."<br>";
    echo $dt['ResponseParameters']['ProgrammeCode']."<br>";
    if(!empty($applicants)) {
        foreach ($applicants as $app) {
            $formfour = $app['f4indexno'];
            $statusCode = $app['AdmissionStatusCode'];
            $statusDesc = $app['AdmissionStatusDescription'];

            echo $formfour . " - " . $statusCode . "-" . $statusDesc . "<br>";
        }
    }


}*/

//transfer 
//http://api.tcu.go.tz/admission/submitInterInstitutionalTransfers

/*$programmeCode="MUM01";
$formindex="S1623/0005/2015";
$formsix="";
$xml='<?xml version="1.0" encoding="UTF-8"?>
<Request>
<UsernameToken>
<Username>'.$user.'</Username>
<SessionToken>'.$token.'</SessionToken>
</UsernameToken>
<RequestParameters>
<f4indexno>'.$formindex.'</f4indexno>
<f6indexno>'.$formsix.'</f6indexno>
<CurrentProgrammeCode>'..'</CurrentProgrammeCode>
<PreviousProgrammeCode>'..'</PreviousProgrammeCode>
</RequestParameters>
</Request>';*/

/*$ch = curl_init();
$url=$api."/applicants/getStatus";
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
$data = curl_exec($ch);
curl_close($ch);

$array_data=json_decode(json_encode(simplexml_load_string($data)),true);*/


//Get applicants’ Admission Status
// $programmeCode="MUM01";
/* $xml='<?xml version="1.0" encoding="UTF-8"?>
// <Request>
// <UsernameToken>
// <Username>'.$user.'</Username>
// <SessionToken>'.$token.'</SessionToken>
// </UsernameToken>
// <RequestParameters>
// <ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
// </RequestParameters>
// </Request>';*/



// $ch = curl_init();
// $url=$api."/applicants/getStatus";
// curl_setopt($ch, CURLOPT_URL,$url);
// curl_setopt($ch, CURLOPT_POST,1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
// $data = curl_exec($ch);
// curl_close($ch);
// //var_dump($data);

// $array_data=json_decode(json_encode(simplexml_load_string($data)),true);

// $applicants=$array_data['Response']['ResponseParameters']['Applicant'];


//var_dump($applicants);

/*$count=0;
foreach($array_data as $dt) {
    $count++;
    $applicants=$dt['ResponseParameters']['Applicant'];*/
/*foreach($applicants as $app)
{
    $formfour = $app['f4indexno'];
    $status = $app['AdmissionStatusCode'];
    $status_des=$app['AdmissionStatusDescription'];
    echo $formfour." - ".$status."-".$status_des."<br>";
}*/

//}


$xml = '<?xml version="1.0" encoding="UTF-8"?>
            <Request>
                <UsernameToken>
                    <Username>' . $user . '</Username>
                    <SessionToken>' . $token . '</SessionToken>
                </UsernameToken>
                <RequestParameters>
                <Fname>'."Rahma".'</Fname>
                <Mname>'."Othuman".'</Mname>
                <Surname>'."MBAYA".'</Surname>
                <F4indexno>'."S5044/0019/2017".'</F4indexno>
                <Gender>'."F".'</Gender>
                <Nationality>'."TANZANIA".'</Nationality>
                <DateOfBirth>'."2000-12-12".'</DateOfBirth>
                <ProgrammeCategory>'."1".'</ProgrammeCategory>
                <Specialization>'."Certificate in Procurement and Logistic Management".'</Specialization>
                <AdmissionYear>2019/2020</AdmissionYear>
                <ProgrammeCode>'."1".'</ProgrammeCode>
                <RegistrationNumber>'."2019-02-02067".'</RegistrationNumber>
                <ProgrammeName>'."Certificate in Procurement and Logistic Management".'</ProgrammeName>
                <YearOfStudy>First Year</YearOfStudy >
                <StudyMode>Full Time</StudyMode >
                <IsYearRepeat>No</IsYearRepeat >
                <EntryMode>'."Direct".'</EntryMode >
                <Sponsorship>'."None".'</Sponsorship >
                <PhysicalChallenges>'."None".'</PhysicalChallenges>
                </RequestParameters>
            </Request>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/applicants/submitEnrolledStudents");
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $data = curl_exec($ch);
            curl_close($ch);

            $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
            var_dump($array_data);
          //$array_data['Response']['ResponseParameters']['StatusCode']=="200")


?>