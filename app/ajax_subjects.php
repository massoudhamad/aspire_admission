<?php
include("DB.php");
$db=new DBHelper();
$programmeMajorID=$_POST['programmeMajorID'];
if($programmeMajorID)
{
 $studyLevelID=$db->getStudyLevelID($programmeMajorID);
 if(!empty($studyLevelID)){ 
   if(($studyLevelID==4)||($studyLevelID==3))
   {
       $level=1;
   }
   else if(($studyLevelID==1)||($studyLevelID==2))
   {
       $level=2;
   }
   
    $subject = $db->getRows('subjects',array('where'=>array('stream'=>$level),'order_by'=>'subjectID ASC'));
    if(!empty($subject)){ 
    echo"<option value=''>Please Select Here</option>";
    foreach($subject as $sub)
    { 
      $subjectName=$sub['subjectName'];
      $subjectCode=$sub['subjectID'];
      echo "<option value='$subjectCode'>$subjectName</option>";

    }
   }
      
    }
}
?>