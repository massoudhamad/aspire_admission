<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->getApplicantsList($programmeID,$academicYearID,$admissionID,1);
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
        $phoneNumber=$data['phoneNumber'];
        $districtID=$data['districtID'];


        $regionID=$db->getData("district","regionID","districtID",$districtID);
        $regName=$db->getData("region","regionName","regionID",$regionID);
        if($entryQualification==1)
            $admission_category="D";
        else
            $admission_category="A";
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
            $dname="";
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
                $programmeData=$pChoice['programCode'];
            }
        }
        
        
        $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
        if(!empty($equivalentresults))
        {
            foreach($equivalentresults as $matokeo)
            {
                
                $eIndexNumber=$matokeo['indexNumber'];
               
            }
        }
        else
        {
            $eIndexNumber="";
        }
        
        $output['data'][] = array(
            $fname,
            $mname,
            $lname,
            $gender,
            $nationality,
            $phoneNumber,
            $dname,
            $dob,
            implode(",",$formfour),
            implode(",",$formsix),
            $eIndexNumber,
            $firstChoice,
            $programmeData,
            'MUM',
            $admission_category,
            $regName
        );
        
    }
}

echo json_encode($output);