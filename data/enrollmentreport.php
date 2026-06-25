<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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
            //end of O-Level
            
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
        //end form 6
        
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
            
           $fdata=array();
            for($i=1;$i<count($formfour);$i++)
            {
                $fdata[]=$formfour[$i];
            }
            
       
                    
                    $output['data'][] = array(
                        $fname,
                        $mname,
                        $lname,
                        $gender,
                        $formfour[0],
                        implode(",",$fdata),
                        $formsix[0],
                        $eIndexNumber,
                        $programmeCode,
                        $programmeName,
                        'Muslim University Of Morogoro',
                        'MUM',
                        $registrationNumber,
                        
                    );
                    
    }
}

echo json_encode($output);