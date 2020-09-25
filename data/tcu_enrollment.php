<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());

/*$programmeID=$_GET['programmeID'];*/
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->getTCUEnrollmentList($academicYearID,$admissionID);
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
                /*$programmeCode=$pChoice['programCode'];
                $programmeName=$pChoice['programName'];*/

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
        /*else if($studyLevelID==4)
        {
            $study="Certificate";
        }*/
        else if($studyLevelID==5)
        {
            $study="NTA5";
        }
        
        //end of O-Level
        
        
       /* $programmeChoice=$db->getTCUEnrollmentProgramme($applicantID);
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
        }*/
        
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
        
        /*if(!empty($programmeCode))
            $programmeCode=$programmeCode;
        else*/
            $programmeCode=$programmeCode;
        
        
        
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
            '2018/2019',
            $formfour[0],
            $programmeName,
            $registrationNumber,
            $_SESSION['orgCode']
        );
        
    }
}

echo json_encode($output);