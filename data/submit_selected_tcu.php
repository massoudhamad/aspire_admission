<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->getSelectedList($programmeID,$academicYearID,$admissionID);
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
        $phoneNumber=$data['phoneNumber'];
        $email=$data['email'];
        $entryQualification=$data['entryQualification'];

        if($entryQualification==0)
            $category="A";
        else
            $category="D";


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



        $aindexumber=$db->getIndexNumber($applicantID,"Advance");
        $formsix=array();
        if(!empty($aindexumber))
        {
            $formsix=array();
            foreach ($aindexumber as $fsixnumber) {
                $indexNumber=$fsixnumber['indexNumber'];
                $formsix[]=$indexNumber;
            }

        }
        else
        {
            $formsix[]="";
        }

        $programmeChoice=$db->getAdmittedProgramme($applicantID,1);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $programmeCode=$pChoice['programCode'];
                $programmeName=$pChoice['programName'];
            }
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

        $output['data'][] = array(
            $fname,
            $mname,
            $lname,
            $gender,
            $formfour[0],
            $findexNumber,
            "$firstChoice,$secondChoice",
            $phoneNumber,
            $email,
            'Provisional Admission',
            $programmeCode,
            'Eligible',
            $nationality,
            $dname,
            $dob,
            $fourfour,
            $sixsix
        );

    }
}

echo json_encode($output);