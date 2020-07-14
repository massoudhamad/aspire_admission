<?php
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());

$applicantsData=$db->getRows("applicants",array('where'=>array('applicantsRemarksID'=>1),'order_by firstName ASC'));
if(!empty($applicantsData))
{
    $x=0;
    foreach ($applicantsData as $row) 
    {
        $x++;
        $applicantID=$row['applicantID'];
        $userID=$row['userID'];
        $indexNumber=$db->getData("users","userName","userID",$userID);
         $fname= $row['firstName'];
         $mname=$row['middleName'];
         $lname=$row['lastName'];
         $name="$fname $mname $lname";
          $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$applicantID,'choice'=>1),'order_by applicantID ASC'));
                      if(!empty($programmeChoice))
                      {
                          foreach ($programmeChoice as $pChoice)
                          {
                              $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                              $firstChoice=$pChoice['programmeMajorID'];
                              $programmeChoice=$db->getData("programmemajor","programmeMajor","programmeMajorID",$firstChoice);
                          }
                      }
	$actionButton = '
	<div class="btn-group">
	    <a href="index3.php?sp=applicantdetails&applicantID='.$row['applicantID'].'"><span class="glyphicon glyphicon-edit"></span> Approve</a>
	</div>';

	$output['data'][] = array(
		$x,
		$name,
		$row['gender'],
                $row['phoneNumber'],
                $row['refNumber'],
                $indexNumber,
                $programmeChoice,
		$actionButton
	);
}
}

// database connection close
//$db->close();

echo json_encode($output);
