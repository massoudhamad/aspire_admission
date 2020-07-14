<?php
include("../DB.php");
$db=new DBHelper();
$studyLevelID=$_POST['studyLevelID'];
if($studyLevelID) {
    $applicantID = $_POST['applicantID'];
    $programmes = $db->getProgrammeChoice($studyLevelID, $applicantID);
    echo "<option value=''>Select Programme</option>";
    if (!empty($programmes))
    {
        foreach ($programmes as $pd) {
            $programmeName = $pd['programmeMajor'];
            $programmeID = $pd['programmeMajorID'];
            echo "<option value='$programmeID'>$programmeName</option>";
        }
    }
    $programmeData=$db->programmeChoice($studyLevelID,$applicantID);
    if(!empty($programmeData))
    {
        foreach($programmeData as $pd)
        {
            $programmeName=$pd['programmeMajor'];
            $programmeID=$pd['programmeMajorID'];
            echo "<option value='$programmeID'>$programmeName</option>";
        }
    }
}
?>