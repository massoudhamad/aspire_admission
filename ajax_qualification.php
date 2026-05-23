<?php
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
include("DB.php");
$db=new DBHelper();
$programmeMajorID=$_POST['programmeMajorID'];
if($programmeMajorID)
{
 $studyLevelID=$db->getStudyLevelID($programmeMajorID);
 if(!empty($studyLevelID)) {
     foreach ($studyLevelID as $stID) {
         $studyLevelID=$stID['studyLevelID'];
         if ($studyLevelID == 3)
         {
            $entryQualificationTypeID = 1;
         } else if ($studyLevelID == 2)
         {
                $entryQualificationTypeID = 1;
         }
         else if($studyLevelID==1)
         {
            $entryQualificationTypeID = 2;
         } 
         else if ($studyLevelID == 4)
         {
                $entryQualificationTypeID = 10;
         }
         else if($studyLevelID == 5)
         {
                $entryQualificationTypeID = 3;
         }

         $subject = $db->getRows('qualification', array('where' => array('qualificationTypeID' => $entryQualificationTypeID), 'order_by' => 'qualificationID ASC'));
         if (!empty($subject)) {
             echo "<option value=''>Please Select Here</option>";
             foreach ($subject as $sub) {
                    $qualification = $sub['qualification'];
                    $qualificationID = $sub['qualificationID'];
                    $qualificationTypeID=$sub['qualificationTypeID'];
                    $qualificationType=$db->getData("qualificationtype","qualificationName","qualificationTypeID",$qualificationTypeID);
                 echo "<option value='$qualificationID'>$qualificationType - $qualification</option>";
             }
         }
     }
 }
}
