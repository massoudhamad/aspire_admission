
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
try{
include '../DB.php';
$db=new DBHelper();
$tblName="applicants";
$tblRemarks='applicantremarks';
if($_REQUEST['applicantID'])
{
	$applicantID=$_REQUEST['applicantID'];
        $processDate=date('Y-m-d H:i:s');
        $remarksData=array(
          'applicantID'=>$applicantID,
          'remarkID'=>1,
          'programID'=>0,
          'processDate'=>$processDate,
          'activeStatus'=>1
        );
        $insert=$db->insert($tblRemarks,$remarksData);
        $year=date('y');
        $year2=$year+1;
        $orgNumber=$db->getOrganizationValue("organizationReference");
        $refNumber = $orgNumber."/".$applicantID."/".$year."-".$year2;
        $data=array(
          'applicantsRemarksID'=>1,
          'refNumber'=>$refNumber
        );
        $condition= array('applicantID'=>$applicantID);
        $update=$db->update($tblName, $data, $condition);
        mail($email,"Online Admission System","Thank You for submitting your application, you will get message notification for any changes in your application");
	if ($update) {
                   echo "Congratulations, your application has been successfully submitted!!!";
        //header("Location:index.php");
	}
}
}catch (PDOException $x)
{

}
?>