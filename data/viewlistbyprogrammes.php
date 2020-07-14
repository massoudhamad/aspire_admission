<?php
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
//$programmeID=isset($_POST['programmeID']);
$programmeID=$_GET['programmeID'];
$admissionID=$_GET['admissionID'];
//$programmeID=9;
$applicantsData=$db->getApplicantByProgrammes($programmeID,$admissionID);
if(!empty($applicantsData))
{
    $x=0;
    foreach ($applicantsData as $row) 
    {
        $x++;
        $applicantID=$row['applicantID'];
        $userID=$row['userID'];
        $indexNumber=$row['userName'];
         $fname= $row['firstName'];
         $mname=$row['middleName'];
         $lname=$row['lastName'];
         $remarksID=$row['applicantsRemarksID'];
         $name="$fname $mname $lname";
	$actionButton = '
	<div class="btn-group">
	    <a href="index3.php?sp=applicantinfo&applicantID='.$row['applicantID'].'"><span class="glyphicon glyphicon-edit"></span> Details</a>
	</div>';

	$output['data'][] = array(
		$x,
		$name,
		$row['gender'],
                $row['phoneNumber'],
                $row['refNumber'],
                $indexNumber,
                $db->getData("remarks","remark","remarkID",$remarksID),
		$actionButton
	);
}
}

// database connection close
//$db->close();

echo json_encode($output);