<?php
session_start();
include ('../DB.php');
$db=new DBHelper();
$indexNumber=$_POST['indexNumber'];
$level=$_POST['level'];
$applicantID=$_SESSION['applicantID'];
if($indexNumber) {
    if(strlen($db->getAPIToken())>1) {
        $iNumber = explode("/", $indexNumber);
        $centerNumber = $iNumber[0];
        $number = $iNumber[1];
        $yearTaken = $iNumber[2];
        if($level=="olevel") {
            $iyear = 1;
            $examinationaward="formfour";
            $examinationlevel="Ordinary";
        }
        else {
            $iyear = 2;
            $examinationaward="formsix";
            $examinationlevel="Advance";
        }
        $apiNumber = $centerNumber . "-" . $number . "/".$iyear."/" . $yearTaken;
        $token=$db->getAPIToken();
        $json = file_get_contents("https://api.necta.go.tz/api/public/results/" . $apiNumber . "/" . $token);
        $data = json_decode($json, true);
        $fname=$db->getData("applicants","firstName","applicantID",$applicantID);
        $mname=$db->getData("applicants","middleName","applicantID",$applicantID);
        $lname=$db->getData("applicants","lastName","applicantID",$applicantID);
        if ($data['status']['code'] == 1) {
            if (($data['particulars']['first_name'] == $fname) && ($data['particulars']['middle_name'] == $mname) && ($data['particulars']['last_name'] == $lname)) {
            $namef = $data['particulars']['first_name'];
            $namem = $data['particulars']['middle_name'];
            $namel = $data['particulars']['last_name'];
            $name = "$namef $namem $namel";
            ?>
            <form name="" action="action_confirm_other_results.php" method="post">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Index Number</th>
                        <th>School Name</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td><input type="text" value="<?php echo $name; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="index_number"
                                   value="<?php echo $data['particulars']['index_number']; ?>" class="form-control"
                                   readonly></td>
                        <td><input type="text" name="schoolName"
                                   value="<?php echo $data['particulars']['center_name']; ?>" class="form-control"
                                   readonly></td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Grade</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data as $value) {
                        if (is_array($value)) {
                            foreach ($value as $v) {
                                if (is_array($v)) {
                                    $count = 0;
                                    foreach ($v as $vv) {
                                        if (is_array($vv)) {
                                            $subjectName = $vv['subject_name'];
                                            $grade = $vv['grade'];
                                            $count++;
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="subjectCode[]"
                                                           value="<?php echo $vv['subject_code']; ?>"
                                                           class="form-control" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="subjectName[]"
                                                           value="<?php echo $vv['subject_name']; ?>"
                                                           class="form-control" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="gradeCode[]"
                                                           value="<?php echo $vv['grade']; ?>" class="form-control"
                                                           readonly>
                                                </td>
                                            </tr>

                                            <?php
                                        }
                                    }
                                }

                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    <input type="hidden" name="applicantID" value="<?php echo $applicantID; ?>">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="hidden" name="examinationlevel" value="<?php echo $examinationlevel;?>">
                    <input type="hidden" name="examinationaward" value="<?php echo $examinationaward;?>">
                    <input type="hidden" name="indexNumber" value="<?php echo $indexNumber; ?>">
                    <input type="hidden" name="yearTaken" value="<?php echo $yearTaken; ?>">
                    <input type="hidden" name="numbersubjects" value="<?php echo $count; ?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control"/>
                </div>
            </form>
            <?php

            //}
            ?>
            </div>

            <?php
        }
        else
        {
            echo "<h4 class='text-danger'>Sorry,Invalid Index Number.</h4>";
        }
        }else
        {
            echo "<h4 class='text-danger'>Sorry,Your Index Number doest not match with personal details.</h4>";
        }
        } else {
            echo "<h4 class='text-danger'>Sorry,NECTA API results are not obtained, please <a href='index.php?sz=other_ordinary_results&level=".$level."&id=" . $_SESSION['applicantID'] . "&inumber=" . $indexNumber . "'>click here</a> 
    to add results manually</h4>";
        }
    }
    ?>
