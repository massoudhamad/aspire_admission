<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('DB.php');
$output = array();
$db=new DBHelper();
$academicYear=$db->getRows("academicyears",array('order_by academicYear ASC'));
$academicYearCount=$db->getRows("academicyears",array('order_by academicYear ASC','return_type'=>'count'));
$i=0;
$data=array();
$filtered_rows = $academicYearCount;

foreach($academicYear as $row)
{
        $i++;
        $status=$row['academicYearStatus'];
	$sub_array = array();
        $sub_array[]=$i;
	$sub_array[] = $row["academicYear"];
	if($status==1)
            $sub_array[]="Active";
        else
            $sub_array[]="Not Active";
	$sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update">Update</button>';
	
	$data[] = $sub_array;
}
$output = array(
	"draw"			=>intval($_POST["draw"]),
        "recordsTotal"		=> $filtered_rows,
	"recordsFiltered"	=> $filtered_rows,
	"data"			=> $data
);
echo json_encode($output);
?>