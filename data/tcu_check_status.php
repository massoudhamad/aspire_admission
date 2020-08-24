<?php
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());

$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->checkStatusTCU($programmeID,$academicYearID,$admissionID);
if(!empty($applicantsData))
{
    $x=0;
    foreach ($applicantsData as $row)
    {
        $x++;
        $applicantID=$row['applicantID'];
        $fname= $row['firstName'];
        $mname=$row['middleName'];
        $lname=$row['lastName'];
        $entryQualification=$row['entryQualification'];

        if($entryQualification==0)
            $category="A";
        else
            $category="D";
        $name="$fname $mname $lname";


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
            $indexNumber="";
        }


        $aindexumber=$db->getIndexNumber($applicantID,"Advance");
        $formsix=array();
        if(!empty($aindexumber))
        {
            $formsix=array();
            foreach ($aindexumber as $fsixnumber) {
                $findexNumber=$fsixnumber['indexNumber'];
                $formsix[]=$findexNumber;
            }

        }
        else
        {
            $formsix[]="";
            $findexNumber="";
        }


        $firstChoice=$db->getProgramme($applicantID,1);
        if(!empty($firstChoice))
        {
            foreach ($firstChoice as $pChoice)
            {
                $firstChoice=$pChoice['programCode'];
            }
        }

        $programmeChoice=$db->getProgramme($applicantID,2);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $secondChoice=$pChoice['programCode'];
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
        $url = $urlform."/applicants/checkStatus";
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>' . $user . '</Username>
        <SessionToken>' . $token . '</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <f4indexno>' . $indexNumber . '</f4indexno>
        </RequestParameters>
        </Request>';

       $array_data= json_decode(json_encode(simplexml_load_string($db->getTCUStatus($url,$xml))),true);
        $status=$array_data['Response']['ResponseParameters']['Status'];
        $status_descript=$array_data['Response']['ResponseParameters']['StatusDescription'];
        $output['data'][] = array(
            $x,
            $name,
            $row['gender'],
            $category,
            $indexNumber,
            $findexNumber,
            $firstChoice,
            $secondChoice,
            $status,
            $status_descript
        );
    }
}

// database connection close
//$db->close();

echo json_encode($output);