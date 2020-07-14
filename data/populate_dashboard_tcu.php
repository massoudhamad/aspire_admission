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




/*$programmes=$db->getProgramme($applicantID,1);
if(!empty($programmes))
{
    foreach ($programmes as $programme)
    {
        $firstChoice=$pChoice['programCode'];
    }
}*/

$programmes=$db->getProgrammeTCU();
if(!empty($programmes))
{
    $count=0;
    foreach ($programmes as $row)
    {
        $count++;
        $programID=$row['programID'];
        $programCode= $row['programCode'];
        $programName=$row['programName'];

        $male=$db->getProgrammeCountTCU($programID,1,$applicationYearID,$admissionID,'Male');
        $female=$db->getProgrammeCountTCU($programID,1,$applicationYearID,$admissionID,'Female');

       /* $aprmale=$db->getProgrammeCountTCU($programID,2,$applicationYearID,$admissionID,'Male');
        $aprfemale=$db->getProgrammeCountTCU($programID,2,$applicationYearID,$admissionID,'Female');

        $addmale=$db->getProgrammeCountTCU($programID,3,$applicationYearID,$admissionID,'Male');
        $addfemale=$db->getProgrammeCountTCU($programID,3,$applicationYearID,$admissionID,'Female');

        $rejmale=$db->getProgrammeCountTCU($programID,4,$applicationYearID,$admissionID,'Male');
        $rejfemale=$db->getProgrammeCountTCU($programID,4,$applicationYearID,$admissionID,'Female');

        $regmale=$db->getProgrammeCountTCU($programID,6,$applicationYearID,$admissionID,'Male');
        $regfemale=$db->getProgrammeCountTCU($programID,6,$applicationYearID,$admissionID,'Female');

        $incmale=$db->getProgrammeCountTCU($programID,5,$applicationYearID,$admissionID,'Male');
        $incfemale=$db->getProgrammeCountTCU($programID,5,$applicationYearID,$admissionID,'Female');

        $male=$appmale+$aprmale+$addmale+$rejmale+$regmale+$incmale;
        $female=$appfemale+$aprfemale+$addfemale+$rejfemale+$regfemale+$incmale;*/
        $total=$male+$female;


        /*if($tcu_status==0) {
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

/*
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
        }*/

        $actionButton = '
	<div class="btn-group">
	    <a href="action_populate_dashboard_tcu.php?action_type=add&programmeID=' . $programID . '&programmeCode=' .$programCode. '&males=' . $male . '&female=' . $female . '"><span class="glyphicon glyphicon-edit"></span>Add</a>
	</div>';

        $output['data'][] = array(
            $count,
            $programCode,
            $programName,
            $male,
            $female,
            $total,
            $actionButton
        );
    }
}

// database connection close
//$db->close();

echo json_encode($output);