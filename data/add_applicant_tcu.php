<?php
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());

$applicationYear=$db->getRows('academicyears',array('where'=>array('academicYearStatus'=>1),'order_by'=>'academicYearID'));
foreach ($applicationYear as $appYear) {
    $applicationYearID=$appYear['academicYearID'];
}

$activeInTake=$db->getRows("admission_setting",array('where'=>array('academicYearID'=>$applicationYearID,'yearStatus'=>1),'order_by academicYearID'));
if(!empty($activeInTake)) {
    foreach ($activeInTake as $intake) {
        $admissionID = $intake['admissionID'];
        $admissionInTakeID = $intake['admissionInTakeID'];
    }
}

$applicantsData=$db->getTCUApplicantList($applicationYearID,$admissionID);
if(!empty($applicantsData))
{
    $count=0;
    foreach ($applicantsData as $row)
    {
        $count++;
        $applicantID=$row['applicantID'];
        $fname= $row['firstName'];
        $mname=$row['middleName'];
        $lname=$row['lastName'];
        $entryQualification=$row['entryQualification'];
        $tcu_status=$row['tcu_status'];
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

        $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
        if(!empty($equivalentresults))
        {
            foreach($equivalentresults as $matokeo)
            {

                $eIndexNumber=$matokeo['avn_number'];

            }
        }
        else
        {
            $eIndexNumber="";
        }

        if($category=="A")
            $findexNumber=$formsix[0];
        else
            $findexNumber=$eIndexNumber;

       /* if($tcu_status==0) {
            $actionButton = '
	<div class="btn-group">
	    <a href="action_add_tcu_applicant.php?action_type=add&applicantID=' . $row['applicantID'] . '&formfour=' . $indexNumber . '&formsix=' . $findexNumber . '&appcategory=' . $category . '"><span class="glyphicon glyphicon-edit"></span>Add</a>
	</div>';
        }
        else
        {
            $actionButton = '
	<div class="btn-group">
	    <span class="glyphicon glyphicon-edit">No</span>
	</div>';
        }*/
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

       if($tcu_status==0) {
            $actionButton = '
	<div class="btn-group">
	    <a href="action_add_tcu_applicant.php?action_type=add&applicantID=' . $row['applicantID'] . '&formfour=' . $indexNumber . '&formsix=' . $findexNumber . '&appcategory=' . $category . '&other_four='.$fourfour.'&other_six='.$sixsix.'"><span class="glyphicon glyphicon-edit"></span>Add</a>
	</div>';
        }
        else
        {
            $actionButton = '
	<div class="btn-group">
	    <span class="glyphicon glyphicon-edit">No</span>
	</div>';
        }
        
        $output['data'][] = array(
            $count,
            $name,
            $row['gender'],
            $category,
            $formfour[0],
            $findexNumber,
            $fourfour,
            $sixsix,
            $firstChoice,
            $secondChoice,
            $actionButton
        );
    }
}

// database connection close
//$db->close();

echo json_encode($output);