<?php
include("../DB.php");
$db=new DBHelper();
$studyLevelID=$_POST['studyLevelID'];
if($studyLevelID) {
    $applicantID = $_POST['applicantID'];
    $programmes = $db->getProgrammeChoice($studyLevelID, $applicantID);
    
    if (!empty($programmes))
    {
        echo "<option value=''>Select Programmes</option>";
        foreach ($programmes as $pd) {
            $programmeName = $pd['programmeMajor'];
            $programmeID = $pd['programmeMajorID'];
            echo "<option value='$programmeID'>$programmeName</option>";
        }
    }
    else 
    {
        echo "<option value=''>Please check your programmes</option>";
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