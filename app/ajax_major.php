<?php
include("DB.php");
$db=new DBHelper();
$programmeID=$_POST['programmeID'];
if($programmeID)
{
 $majorProgramme = $db->getRows('programmemajor',array('where'=>array('programmeID'=>$programmeID),'order_by'=>'programmeMajorID ASC'));
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