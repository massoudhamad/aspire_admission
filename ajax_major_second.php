<?php
include("DB.php");
$db=new DBHelper();
$secondProgrammeID=$_POST['secondProgrammeID'];
if($secondProgrammeID)
{
 $majorProgramme = $db->getRows('programmemajor',array('where'=>array('programmeID'=>$secondProgrammeID),'order_by'=>'programmeMajorID ASC'));
 if(!empty($majorProgramme)){ 
   echo"<option value=''>Choose Combination or Major</option>";
   foreach($majorProgramme as $mProg)
    { 
       $major=$mProg['major'];
       $programmeMajor=$mProg['programmeMajor'];
       if($major=="NA")
           $major=$programmeMajor;
       else
           $major=$major;
       $programmeMajorID=$mProg['programmeMajorID'];
       echo "<option value='$programmeMajorID'>$major</option>";
        
    }
      
    }
}
?>