<?php
include("DB.php");
$db=new DBHelper();
$programmeID=$_POST['id'];
if($programmeID)
{
    $majorProgramme = $db->getRows('programmemajor',array('where'=>array('programmeID'=>$programmeID),'order_by'=>'programmeMajorID ASC'));
    if(!empty($majorProgramme)){
        echo"<option value='all'>All Programmes</option>";
        foreach($majorProgramme as $mProg)
        {
            $programmeMajor=$mProg['programmeMajor'];
            $programmeMajorID=$mProg['programmeMajorID'];
            echo "<option value='$programmeMajorID'>$programmeMajor</option>";
        }

    }
}
else
{
    echo "<option value=''>$programmeID</option>";
}
?>