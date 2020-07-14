<?php 
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$users = $db->getRows('users',array('order_by'=>'userID DESC'));
 if(!empty($users)){ $count = 0; foreach($users as $user){ $count++;
 $userID=$user['userID'];
$fname=$user['firstName'];
$mname=$user['middleName'];
$lname=$user['lastName'];
$username=$user['userName'];
$email=$user['email'];
$name="$fname $mname $lname";
$phoneNumber=$user['phoneNumber'];

$userRole=$db->getRows("userroles",array('where'=>array('userID'=>$userID),'order_by roleID ASC'));
if(!empty($userRole))
{
    //$count=0;
    foreach($userRole as $role)
    {
        $roleID=$role['roleID'];
        $roleName=$db->getData("roles","roleName", "roleID",$roleID);
        if($roleID==4 || $roleID==6)
        {
            $roleName=$roleName."-".$db->getData("schools","schoolCode","schoolID",$user['sectionID']);
        }
        else if($roleID==5)
        {
                $roleName = $roleName . "-" . $db->getData("schools", "schoolCode", "schoolID", $user['sectionID']);
        }
        else 
        {
                $roleName = $roleName;
        }
    }
}



if($user['status']==1)
{
  $status="Active";
  $blockButton='
   <div class="btn-group">
         <a href="action_user.php?action_type=block&id='.$user['userID'].'" class="fa fa-unlock" onclick="return confirm("Are you sure You want to Block This User?");"></a>
    </div>';
}
else
{
    $status="Not Active";
    $blockButton='
    <div class="btn-group">
         <a href="action_user.php?action_type=unblock&id='.$user['userID'].'" class="glyphicon glyphicon-lock" onclick="return confirm("Are you sure You want to Unblock This User?");"></a>
    </div>';
}
	$resetButton = '
	<div class="btn-group">
	     <a href="action_user.php?action_type=reset&id='.$user['userID'].'" class="glyphicon glyphicon-trash" onclick="return confirm("Are you sure you want to Reset Password of this User?");"></a>
	</div>';
	
	$editButton = '
	<div class="btn-group">
	     <a href="index3.php?sp=edit_user&id='.$user['userID'].'" class="glyphicon glyphicon-edit"></a>
	</div>';
	
	
    $applicantID=$db->getData("applicants","applicantID","userID",$userID);
    if($roleID==2)
        $namelink = '<a href="index3.php?sp=applicantdetails&applicantID='.$applicantID.'">'.$name.'</a>';
    else 
        $namelink=$name;
	

	
	$output['data'][] = array(
	    $count,
		$namelink,
        $username,
        $email,
	    $phoneNumber,
	    $roleName,
	    $status,
	    $blockButton,
		$resetButton,
	    $editButton
	);

	//$x++;
}
}

// database connection close


echo json_encode($output);
//$db->close();