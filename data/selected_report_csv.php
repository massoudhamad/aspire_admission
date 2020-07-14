<?php
session_start();
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
        $entryQualification=$data['entryQualification'];
        $phoneNumber=$data['phoneNumber'];

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
                $indexNumberf=$fsixnumber['indexNumber'];
                $formsix[]=$indexNumberf;
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
                $studyLevelID=$pChoice['studyLevelID'];
                $programID=$pChoice['programID'];
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

        if($studyLevelID==1)
            $applicantCategory="U";
        else if($studyLevelID==2)
            $applicantCategory=2;
        else if($studyLevelID==3)
            $applicantCategory=1;
        else if($studyLevelID==5)
            $applicantCategory=2;


        $output['data'][] = array(
            $fname,
            $mname,
            $lname,
            $gender,
            $nationality,
            $dname,
            $dob,
            $formfour[0],
            $formsix[0],
            $eIndexNumber,
            $programmeName,
            'MUM',
            $applicantCategory
        );

    }
}

echo json_encode($output);