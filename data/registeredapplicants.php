<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->getRegisteeredApplicants($programmeID,$academicYearID,$admissionID);
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
        $admissionStatusID=$data['applicantsRemarksID'];

        $applicantStatus=$db->getData("remarks","remark","remarkID",$admissionStatusID);


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
        
        //A-Level Subjects and Points
        
        $asubjects=$db->getSelectionSubjects($applicantID,"Advance");
        if(!empty($osubjects))
        {
            
            $subjects=array();
            $grade=array();
            $arrpoints=array();
            
            foreach($asubjects as $subject) {
                $subjectID=$subject['subjectID'];
                $gradeID=$subject['gradeID'];
                $points=$subject['points'];
                if(empty($subjects))
                {
                    $subjects[]=$subjectID;
                    $grade[]=$subject['gradeID'];
                    $arrpoints[]=$subject['points'];
                }
                else if(in_array($subjectID,$subjects))
                {
                    for($i=0;$i<sizeof($subjects);$i++)
                    {
                        if($subjects[$i]==$subjectID)
                        {
                            if((int)$arrpoints[$i]<(int)$points)
                            {
                                $subjects[$i]=$subjectID;
                                $grade[$i]=$gradeID;
                                $arrpoints[$i]=$points;
                            }
                        }
                        
                    }
                }
                else
                {
                    $subjects[]=$subjectID;
                    $grade[]=$gradeID;
                    $arrpoints[]=$points;
                }
            }
            $adata=array();$atotalPoints=0;
            for($j=0;$j<sizeof($subjects);$j++)
            {
                $subjectCode=$db->getData("subjects","subjectCode","subjectID",$subjects[$j]);
                $gradeCode=$db->getData("grades","gradeCode","gradeID",$grade[$j]);
                $atotalPoints+=$arrpoints[$j];
                $adata[]=$subjectCode."-".$gradeCode;
            }
        }
        if($atotalPoints==0)
            $atotalPoints="";
        
        //O-level Subjects and Points
       /* $osubjects=$db->getSelectionSubjects($applicantID,"Ordinary");
        if(!empty($osubjects))
        {
            $odata=array();
            $subjects=array();
            $totalPoints=0;
            foreach ($osubjects as $subject) {
                $subjectID=$subject['subjectID'];
                $subjectCode=$subject['subjectCode'];
                $gradeID=$subject['gradeID'];
                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                $gradePoints=$db->getData("grades","gradePoint","gradeID",$gradeID);
                $points=$subject['points'];
                $totalPoints+=$points;
                $odata[]=$subjectCode."-".$grade;
                
            }
            
        }*/
        
        $osubjects=$db->getSelectionSubjects($applicantID,"Ordinary");
        if(!empty($osubjects))
        {
            $subjects=array();
            $grade=array();
            $arrpoints=array();
            
            foreach($osubjects as $subject) {
                $subjectID=$subject['subjectID'];
                $gradeID=$subject['gradeID'];
                $points=$subject['points'];
                if(empty($subjects))
                {
                    $subjects[]=$subjectID;
                    $grade[]=$subject['gradeID'];
                    $arrpoints[]=$subject['points'];
                }
                else if(in_array($subjectID,$subjects))
                {
                    for($i=0;$i<sizeof($subjects);$i++)
                    {
                        if($subjects[$i]==$subjectID)
                        {
                            if((int)$arrpoints[$i]<(int)$points)
                            {
                                $subjects[$i]=$subjectID;
                                $grade[$i]=$gradeID;
                                $arrpoints[$i]=$points;
                            }
                        }
                        
                    }
                }
                else
                {
                    $subjects[]=$subjectID;
                    $grade[]=$gradeID;
                    $arrpoints[]=$points;
                }
            }
            $totalPoints=0;$odata=array();
            for($j=0;$j<sizeof($subjects);$j++)
            {
                //echo $subjects[$j]."-".$grade[$j]."-".$arrpoints[$j];
                $subjectCode=$db->getData("subjects","subjectCode","subjectID",$subjects[$j]);
                $gradeCode=$db->getData("grades","gradeCode","gradeID",$grade[$j]);
                $totalPoints+=$arrpoints[$j];
                $odata[]=$subjectCode."-".$gradeCode;
            }
        }
        
        //end of O-Level
        
        
        $programmeChoice=$db->getAdmittedProgramme($applicantID,1);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $programmeCode=$pChoice['programCode'];
                $programmeName=$pChoice['programName'];
            }
        }
        
        
        $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
        if(!empty($equivalentresults))
        {
            foreach($equivalentresults as $matokeo)
            {
                $eIndexNumber=$matokeo['indexNumber']; 
                $schoolName=$matokeo['schoolName'];
                $yearTaken=$matokeo['yearTaken'];
                $award=$matokeo['award'];
                $gradeType=$matokeo['gradeType'];
                $gradePoints=$matokeo['gradePoints'];
            }
        }
        else
        {
            $eIndexNumber="";
            $schoolName="";
            $yearTaken="";
            $award="";
        }
        
        if($atotalPoints=="")
            $atotalPoints=$gradePoints;
        else
            $atotalPoints=$atotalPoints;

            $regNumber=$db->getData("applicantregistration","registrationNumber","applicantID",$applicantID);
        
        $output['data'][] = array(
            $fname,
            $mname,
            $lname,
            $gender,
            $nationality,
            $phoneNumber,
            $dname,
            $dob,
            $formfour[0],
            $formsix[0],
            $eIndexNumber,
            $programmeCode,
            $programmeName,
            $atotalPoints,
            $totalPoints,
            implode(",",$adata),
            implode(",",$odata),
            $admission_category,
            $applicantStatus,
            $regNumber
        );
        
    }
}

echo json_encode($output);