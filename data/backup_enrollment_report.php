<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];

$applicantsData=$db->getEnrollmentList($programmeID,$academicYearID);
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
        $qualificationTypeID=$data['qualificationTypeID'];
        $entryQualification=$data['entryQualification'];
        $sponsor=$data['sponsor'];
        $studyLevelID=$data['studyLevelID'];
        
        //$admission_category=$db->getData("qualificationtype","applicant_category","qualificationTypeID",$qualificationTypeID);
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
            $study="Diploma";
        }
        else if($studyLevelID==4)
        {
            $study="Certificate";
        }
        else if($studyLevelID==5)
        {
            $study="Master";
        }
        
        //end of O-Level
        
        
        $programmeChoice=$db->getEnrollmentProgramme($applicantID,$academicYearID);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $registrationNumber=$pChoice['registrationNumber'];
                $programmeCode=$pChoice['programCode'];
                $programmeName=$pChoice['programName'];
                $major=$pChoice['major'];
                $programmeMajor=$pChoice['programmeMajor'];
            }
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
        
        
        
        $output['data'][] = array(
            $fname,
            $mname,
            $lname,
            $gender,
            strtoupper($nationality),
            $dob,
            $study,
            $fieldspecialization,
            'First Year',
            'Full Time',
            'No',
            $entry,
            $sponsor,
            $dname,
            '2017',
            $formfour[0],
            $programmeName,
            $registrationNumber,
            'MUM'
        );
        
    }
}

echo json_encode($output);