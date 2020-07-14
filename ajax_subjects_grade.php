<?php
include("DB.php");
$db=new DBHelper();
$programmeMajorID=$_POST['programmeMajorID'];
if($programmeMajorID)
{
 $studyLevelID=$db->getStudyLevelID($programmeMajorID);
 if(!empty($studyLevelID)) {
     foreach ($studyLevelID as $stID) {
         $studyLevelID = $stID['studyLevelID'];
         if (($studyLevelID == 4) || ($studyLevelID == 3)) {
             $level = 1;
             $gradeRangeYear = 2013;
         } else if (($studyLevelID == 1) || ($studyLevelID == 2)) {
             $level = 2;
             $gradeRangeYear = 2013;
         }

         $subject = $db->getRows('grades', array('where' => array('gradeLevel' => $level, 'gradeRangeYear' => 2013), 'order_by' => 'gradeID ASC'));
         if (!empty($subject)) {
             echo "<option value=''>Please Select Here</option>";
             foreach ($subject as $sub) {
                 $gradeCode = $sub['gradeCode'];
                 $gradeID = $sub['gradeID'];
                 echo "<option value='$gradeID'>$gradeCode</option>";

             }
         }

     }
 }
}
?>