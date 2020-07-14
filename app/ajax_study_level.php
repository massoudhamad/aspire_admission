<?php
include("../DB.php");
$db=new DBHelper();
$qualificationTypeID=$_POST['qualificationTypeID'];
if($qualificationTypeID)
{
 $education_level = $db->getRows('education_levels',array('where'=>array('qualificationTypeID'=>$qualificationTypeID),'order_by'=>'studyLevelID ASC'));
 if(!empty($education_level)){ 
   echo"<option value=''>Please Select Here</option>";
   foreach($education_level as $level)
    { 
       $studyLevelID=$level['studyLevelID'];
       $studyLevel=$db->getRows("studylevels",array('where'=>array('studyLevelID'=>$studyLevelID,'status'=>1),'order_by'=>'studyLevelName ASC'));
       if(!empty($studyLevel))
       {
           foreach($studyLevel as $lvl)
           {
               $studyLevelName=$lvl['studyLevelName'];
               $studyLevelID=$lvl['studyLevelID'];
               echo "<option value='$studyLevelID'>$studyLevelName</option>";
           }
       }
        
    }
      
    }
}
?>