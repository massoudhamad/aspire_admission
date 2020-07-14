<?php
session_start();
include ('../DB.php');
$db=new DBHelper();
$avn=$_POST['avn_number'];
$applicantID=$_SESSION['applicantID'];
if($avn) {
    /*if(strlen($db->getAPIToken())>1) {*/
        $json = file_get_contents("http://41.93.40.137/nacte_api/index.php/api/results/LXLn8KLPfupfrV/a765443108e144df0b6ac9b7523c44d1ee5a32d2/15026014040402/".$avn);
        $data = json_decode($json, true);
        $fname=$db->getData("applicants","firstName","applicantID",$applicantID);
        $mname=$db->getData("applicants","middleName","applicantID",$applicantID);
        $lname=$db->getData("applicants","lastName","applicantID",$applicantID);
if ($data['status']['code'] == 200) {
    foreach($data['params'] as $value)
    {
        if (($value['firstname'] == $fname) && ($value['middlename'] == $mname) && ($value['surname'] == $lname)) {
            ?>
<form name="" action="action_confirm_equivalent_result.php" method="post">
    <div class="col-lg-12">

        <h4>Diploma Results</h4>

        <div class="row">
            <div class="col-lg-6">
                <label for="FirstName">Programme Name</label>
                <input type="text" name="programmeName" id="programmeName" value="<?php echo $value['programme']; ?>"
                       class="form-control" readonly>
            </div>
            <div class="col-lg-6">
                <label for="FirstName">Institute Name</label>
                <input type="text" name="instituteName" id="instituteName" value="<?php echo $value['institution'];?>"
                       class="form-control" readonly>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-4">
                <label for="Physical Address">Registration Number</label>
                <input type="text" name="registrationNumber" id="registrationNumber"
                       value="<?php echo $value['registration_number'];?>" class="form-control" readonly/>
            </div>

            <div class="col-lg-4">
                <label for="Physical Address">AVN Number</label>
                <input type="text" name="avn_number" id="avn_number"
                       value="<?php echo $value['AVN'];?>" class="form-control" readonly/>
            </div>

            <div class="col-lg-4">
                <label for="Email">Graduation Year</label>
                <input type="text" name="indexYear" id="indexYear"
                       value="<?php echo $value['diploma_graduation_year'];?>" class="form-control" readonly/>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-6">
                <label for="Email">GPA</label>
                <input type="text" name="gradePoints" id="gradePoints"
                       value="<?php echo $value['diploma_gpa'];?>" class="form-control" readonly/>
            </div>

            <div class="col-lg-6">
                <label for="Email">Qualification</label>
                <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" readonly>
                    <?php
                    $qualificationType = $db->getRows("qualificationtype",array('where'=>array('qualificationTypeID'=>2)));
                    if (!empty($qualificationType)) {
                        $count = 0;
                        foreach ($qualificationType as $type) {
                            $count++;
                            $qualificationName = $type['qualificationName'];
                            $qualificationID = $type['qualificationTypeID'];
                            ?>
                            <option value="<?php echo $qualificationID;?>" selected><?php echo $qualificationName; ?></option>
                        <?php }
                    } ?>
                </select>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <input type="hidden" name="action_type" value="add"/>
                <input type="hidden" name="examinationlevel" value="Equivalent">
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control"/>
            </div>
            <div class="col-lg-4">
                <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);"
                       class="btn btn-success form-control"/>
            </div>

        </div>
    </div>

    <?php
    }
    else
    {
        echo "<h4 class='text-danger'>Sorry,Invalid Index Number.</h4>";
    }
    }//end of loop
    }
    else
    {
         echo "<h4 class='text-danger'>Sorry,Invalid Index Number.</h4>";
    }
        /*}else
        {
            echo "<h4 class='text-danger'>Sorry,Your Index Number doest not match with personal details.</h4>";
        }*/
    /*} else {
        echo "<h4 class='text-danger'>Sorry,NECTA API results are not obtained, please <a href='index.php?sz=other_ordinary_results&level=".$level."&id=" . $_SESSION['applicantID'] . "&inumber=" . $indexNumber . "'>click here</a> 
    to add results manually</h4>";
    }*/
}
?>
