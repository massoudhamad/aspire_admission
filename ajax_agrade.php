<?php
include("DB.php");
$db=new DBHelper();
$indexYear=$_POST['indexYear'];
if($indexYear)
{
    if($indexYear==2014 or $indexYear==2015)
        $gradeRange=2014;
    else 
        $gradeRange=2013;
 $grade = $db->getRows('grades',array('where'=>array('gradeRangeYear'=>$gradeRange,'gradeLevel'=>2),'order_by'=>'gradeID ASC'));
 if(!empty($grade)){ 
   echo"<option value=''>Please Select Here</option>";
   foreach($grade as $gd)
    { 
       $gradeID=$gd['gradeID'];
       $gradeCode=$gd['gradeCode'];
       echo "<option value='$gradeID'>$gradeCode</option>";
        
    }
      
    }
}
?>