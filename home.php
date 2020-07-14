<?php
$db=new DBHelper();
$applicantID=$_SESSION['applicantID'];
$applicantRemarksID=$db->getData("applicants","applicantsRemarksID", "applicantID",$applicantID);

$today=date('Y-m-d');
$semester= $db->getRows('admission_setting',array(' order_by'=>' startDate ASC'));
if(!empty($semester))
{
    foreach($semester as $sm)
    {
        $academicYearID=$sm['academicYearID'];
        $startDate=$sm['startDate'];
        $endDate=$sm['endDate'];
        $admissionID=$sm['admissionID'];
    }
}

if($today<=$endDate)
{
    if($applicantRemarksID !=0)
    {
        header("Location:index2.php?sz=applicationindex");
    }
    else
    {
        //include 'level.php';
        header("Location:index2.php?sz=level");
    }
}
else 
{
    header("Location:index2.php?sz=applicationindex");
}
?>

