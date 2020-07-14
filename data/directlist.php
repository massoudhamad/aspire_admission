<?php 

require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());


$programmeName=$db->getRows("programs");
if(!empty($programmeName))
{
    foreach($programmeName as $pName)
    {
        $programmeName=$pName['programmeName'];
        $output['data'][]=array($programmeName);
$applicantsData=$db->getRows("applicants",array('where'=>array('applicantsRemarksID'=>2),'order_by firstName ASC'));
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
	$actionButton = '
	<div class="btn-group">
	    <a href="index3.php?sp=applicantdetails&applicantID='.$row['applicantID'].'"><span class="glyphicon glyphicon-edit"></span> Details</a>
	</div>';

	$output['data'][] = array(
		$x,
		$name,
		$row['gender'],
                $row['phoneNumber'],
                $row['refNumber'],
                $indexNumber,
                "First Choice",
		$actionButton
	);

	$x++;
}
}
    }
}

// database connection close
//$db->close();

echo json_encode($output);