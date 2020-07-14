<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->getTransferList($programmeID,$academicYearID,$admissionID);
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
        $entryQualification=$data['entryQualification'];
        $phoneNumber=$data['phoneNumber'];

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



        //end of O-Level


        $transfered_programme=$db->getTransfferedProgramme($applicantID,1);
        if(!empty($transfered_programme))
        {
            foreach ($transfered_programme as $tp)
            {
                $transferedName=$tp['programmeMajor'];
            }
        }


        $programmeChoice=$db->getTransfferedProgramme($applicantID,3);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $programmeName=$pChoice['programmeMajor'];
            }
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

        if($atotalPoints=="")
            $atotalPoints=$gradePoints;
        else
            $atotalPoints=$atotalPoints;

        if($entryQualification==1)
            $formsix=$avn_number;
        else
            $formsix=$formsix[0];

        $output['data'][] = array(
            $i,
            $fname,
            $mname,
            $lname,
            $gender,
            $formfour[0],
            $formsix,
            $programmeName,
            $transferedName,
            $phoneNumber
        );

    }
}

echo json_encode($output);