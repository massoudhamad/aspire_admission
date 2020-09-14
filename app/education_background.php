<?php $db = new DBHelper();
?>
<!--<script src="js/jquery-1.4.2.min.js"></script>-->
<div class="row">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-graduation-cap"></i>Educational Background</h1>
            <p>Add your results based on the instruction from form</p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
                } else if ($_REQUEST['msg'] == "dropSchool") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                } else if ($_REQUEST['msg'] == "dropSubject") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, Subject has been droped</strong>.
                    </div>";
                } else if ($_REQUEST['msg'] == "error") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                }
            }
            ?>
        </div>
        <?php
        $results = $db->getRows("applicantresults", array('where' => array('applicantID' => $_SESSION['applicantID'], 'applicantResultStatus' => 1), 'examinationLevel' => 'Ordinary', 'order_by applicantID ASC'));
        if (!empty($results)) {
        ?>
            <div class="row">

                <?php

                $results = $db->getRows("applicantresults", array('where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Ordinary'), 'order_by applicantID ASC'));
                if (!empty($results)) {
                ?>
                    <div class="col-lg-12">
                        <fieldset>
                            <legend>List of Registered Subjects for Ordinary Level (Form IV)</legend>
                            <?php
                            foreach ($results as $matokeo) {
                            ?>
                                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>School Name</th>
                                            <th>Index Number</th>
                                            <th>Year</th>
                                            <th>Examination Autority</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $applicantResultID = $matokeo['applicantResultID'];
                                        $schoolName = $matokeo['schoolName'];
                                        $yearTaken = $matokeo['yearTaken'];
                                        $indexNumber = $matokeo['indexNumber'];
                                        $exam_authority = $matokeo['examinationAuthority'];
                                        $resultStatus = $matokeo['resultStatus'];
                                        $levelStatus = $matokeo['levelStatus'];

                                        if ($resultStatus == 1)
                                            $vstatus = "Verified";
                                        else
                                            $vstatus = "Not Verified";
                                        echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>$yearTaken</td><td>$exam_authority</td><td>$vstatus</td>";
                                        ?>
                                        <td>
                                            <?php
                                            if ($resultStatus == 1) {
                                                echo "No";
                                            } else {
                                                if ($levelStatus == 1) {
                                                    echo "No";
                                                } else {
                                            ?>
                                                    <a href="action_ordinary_level.php?action_type=dropSchool&id=<?php echo $applicantResultID; ?>" onclick="return confirm('Are you sure you want to drop this school and all results?');"><span class="btn btn-danger">Drop</a>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </td>

                                        <?php
                                        echo "</tr>"
                                        ?>
                                    </tbody>
                                </table>

                                <?php
                                if ($exam_authority == "NECTA") {
                                    $resultSubjects = $db->getRows("applicantsubjects", array('where' => array('applicantResultID' => $applicantResultID), 'order_by applicantResultID ASC'));
                                    if (!empty($resultSubjects)) {
                                ?>
                                        <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Subject Name</th>
                                                    <th>Grade</th>
                                                    <th>Points</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $count = 0;
                                                $totalPoints = 0;
                                                foreach ($resultSubjects as $subject) {
                                                    $count++;
                                                    $applicantSubjectID = $subject['applicantSubjectID'];
                                                    $subjectID = $subject['subjectID'];
                                                    $gradeID = $subject['gradeID'];
                                                    $grade = $db->getData("grades", "gradeCode", "gradeID", $gradeID);
                                                    $points = $subject['points'];
                                                    $totalPoints = $totalPoints + $points;
                                                    echo "<tr><td>$count</td><td>" . $db->getData("subjects", "subjectName", "subjectID", $subjectID) . "</td><td>$grade</td><td>$points</td>"; ?>
                                                    <td>
                                                        <?php
                                                        if ($resultStatus == 0) {
                                                        ?>
                                                            <a href="action_ordinary_level.php?action_type=dropSubject&id=<?php echo $applicantSubjectID; ?>" class="text-danger" onclick="return confirm('Are you sure you want to drop this subject?');">Drop</a>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            No
                                                        <?php
                                                        } ?>
                                                    </td>
                                                <?php
                                                    echo "</tr>";
                                                } ?>
                                                <tr>
                                                    <td colspan="3">Total Points</td>
                                                    <td><?php echo $totalPoints; ?></td>

                                                </tr>

                                            </tbody>
                                        </table>
                                    <?php
                                    } ?>

                                    <?php
                                    if ($resultStatus != 1) {
                                    ?>
                                        <div class="row">
                                            <div class="col-lg-10"></div>
                                            <div class="col-lg-2 pull-right">
                                                <a href="index.php?sz=newsubject&id=<?php echo $applicantResultID; ?>&indexYear=<?php echo $yearTaken; ?>&level=olevel"><span class="btn btn-primary">Add Other Subject</span></a>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    //start
                                    $resultSubjectseq = $db->getRows("applicantsubjects_equivalence", array('where' => array('applicantResultID' => $applicantResultID), 'order_by applicantResultID ASC'));
                                    if (!empty($resultSubjectseq)) {
                                    ?>
                                        <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Subject Name</th>
                                                    <th>Grade</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $count = 0;
                                                $totalPoints = 0;
                                                foreach ($resultSubjectseq as $subject) {
                                                    $count++;
                                                    $subjectName = $subject['subjectName'];
                                                    $gradeCode = $subject['gradeCode'];
                                                    echo "<tr><td>$count</td><td>$subjectName</td><td>$gradeCode</td><td>$points</td>"; ?>

                                                <?php
                                                    echo "</tr>";
                                                } ?>

                                            </tbody>
                                        </table>
                                <?php
                                    }


                                    //end
                                }
                                ?>
                            <?php
                            }
                            ?>
                        </fieldset>
                    </div>

                <?php
                }
                if ($exam_authority == "NECTA") {
                ?>
                    <div class="col-lg-12">
                        <label class="">To add Ordinary Level (Form Four(IV)) Results for another Sitting, Please Click Here</label>
                        <a href="index.php?sz=other_ordinary"><i class="glyphicon glyphicon-hand-right"></i> <span class="btn btn-primary">Add Other School</span></a>
                    </div>
                <?php
                } ?>

                <div class="col-lg-12">
                    <hr>
                </div>
                <!--Advanced Results-->

                <?php
                if ($exam_authority == "NECTA") {
                    if ($db->countGrades($_SESSION['applicantID'], 'Ordinary') >= 4) {
                ?>

                        <div class="col-lg-12">
                            <fieldset>
                                <legend>Advanced Level Results</legend>

                                <?php
                                $results = $db->getRows("applicantresults", array('where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Advance'), 'order_by applicantID ASC'));
                                if (!empty($results)) {
                                ?>

                                    <div class="col-lg-12">
                                        <fieldset>
                                            <legend>List of Registered Subjects for Advanced Level (Form FVI)</legend>
                                            <?php
                                            foreach ($results as $matokeo) {
                                            ?>
                                                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>School Name</th>
                                                            <th>Index Number</th>
                                                            <th>Year</th>
                                                            <th>Examination Autority</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $applicantResultID = $matokeo['applicantResultID'];
                                                        $schoolName = $matokeo['schoolName'];
                                                        $yearTaken = $matokeo['yearTaken'];
                                                        $indexNumber = $matokeo['indexNumber'];
                                                        $exam_authority = $matokeo['examinationAuthority'];
                                                        $resultStatus = $matokeo['resultStatus'];
                                                        if ($resultStatus == 1) {
                                                            $vstatus = "Verified";
                                                        } else {
                                                            $vstatus = "Not Verified";
                                                        }
                                                        echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>$yearTaken</td><td>$exam_authority</td><td>$vstatus</td>"; ?>

                                                        <td>
                                                            <?php
                                                            if ($resultStatus == 0) {
                                                            ?>
                                                                <a href="action_ordinary_level.php?action_type=dropSchool&id=<?php echo $applicantResultID; ?>" onclick="return confirm('Are you sure you want to drop this school and all results?');">
                                                                    <span class="btn btn-danger">Drop</a>
                                                            <?php
                                                            } else {
                                                                echo "No";
                                                            } ?>
                                                        </td>
                                                        <?php
                                                        echo "</tr>"
                                                        ?>
                                                    </tbody>
                                                </table>

                                                <?php
                                                $resultSubjects = $db->getRows("applicantsubjects", array('where' => array('applicantResultID' => $applicantResultID), 'order_by applicantResultID ASC'));
                                                if (!empty($resultSubjects)) {
                                                ?>
                                                    <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Subject Name</th>
                                                                <th>Grade</th>
                                                                <th>Points</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $count = 0;
                                                            $totalPoints = 0;
                                                            foreach ($resultSubjects as $subject) {
                                                                $count++;
                                                                $applicantSubjectID = $subject['applicantSubjectID'];
                                                                $subjectID = $subject['subjectID'];
                                                                $gradeID = $subject['gradeID'];
                                                                $grade = $db->getData("grades", "gradeCode", "gradeID", $gradeID);
                                                                $points = $subject['points'];
                                                                $totalPoints = $totalPoints + $points;
                                                                echo "<tr><td>$count</td><td>" . $db->getData("subjects", "subjectName", "subjectID", $subjectID) . "</td><td>$grade</td><td>$points</td>"; ?>
                                                                <td>
                                                                    <?php
                                                                    if ($resultStatus == 0) {
                                                                    ?>
                                                                        <a href="action_ordinary_level.php?action_type=dropSubject&id=<?php echo $applicantSubjectID; ?>" class="text-danger" onclick="return confirm('Are you sure you want to drop this subject?');">Drop</a>
                                                                    <?php
                                                                    } else {
                                                                        echo "No";
                                                                    } ?></td>
                                                            <?php
                                                                echo "</tr>";
                                                            } ?>
                                                            <tr>
                                                                <td colspan="3">Total Points</td>
                                                                <td><?php echo $totalPoints; ?></td>

                                                                <td>
                                                                    <?php
                                                                    if ($resultStatus == 0) {
                                                                    ?>
                                                                        <a href="index.php?sz=newsubject&id=<?php echo $applicantResultID; ?>&indexYear=<?php echo $yearTaken; ?>&level=alevel">
                                                                            <span class="btn btn-primary">Add Other Subject</span</a> <?php
                                                                                                                                    } else {
                                                                                                                                        echo "No";
                                                                                                                                    } ?> </td> </tr> </tbody> </table> <?php
                                                                                                                                                                    }
                                                                                                                                                                } ?> </fieldset> </div> <?php
                                                                                                                } ?> <label class="col-lg-12 control-label">If you have Advanced Level Results, Click Here <a href="index.php?sz=alevel"><span class="btn btn-primary"> Add Advanced Level Results</span></a></label>

                                        </fieldset>
                                    </div>
                                    <!--End of Advanced Level Results-->
                                    <div class="col-lg-12">
                                        <hr>
                                    </div>
                                    <!--Equivalent Results-->
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <legend>Equivalent Results</legend>

                                            <?php
                                            $equivalentresults = $db->getRows("applicantresults", array('where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Equivalent'), 'order_by applicantID ASC'));
                                            if (!empty($equivalentresults)) {
                                            ?>

                                                <div class="col-lg-12">
                                                    <fieldset>
                                                        <legend>List of Equivalent Results(Certificate,NTAs,Diploma,Adv.Diploma,Degree)</legend>
                                                        <?php
                                                        foreach ($equivalentresults as $matokeo) {
                                                        ?>
                                                            <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Institute Name</th>
                                                                        <th>Reg. Number</th>
                                                                        <th>AVN Number</th>
                                                                        <th>Year Taken</th>
                                                                        <th>Programme Name</th>
                                                                        <th>Qualification</th>
                                                                        <th>Grade Type</th>
                                                                        <th>Points</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $applicantResultID = $matokeo['applicantResultID'];
                                                                    $schoolName = $matokeo['schoolName'];
                                                                    $yearTaken = $matokeo['yearTaken'];
                                                                    $indexNumber = $matokeo['indexNumber'];
                                                                    $exam_authority = $matokeo['examinationAuthority'];
                                                                    $exam_level = $matokeo['examinationLevel'];
                                                                    $award = $matokeo['award'];
                                                                    $gradeType = $matokeo['gradeType'];
                                                                    $gradePoints = $matokeo['gradePoints'];
                                                                    echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>" . $matokeo['avn_number'] . "</td><td>$yearTaken</td><td>$award</td><td>" . $db->getData("qualificationtype", "qualificationName", "qualificationTypeID", $exam_authority) . "</td><td>$gradeType</td><td>$gradePoints</td>"; ?>
                                                                    <td>
                                                                        <?php
                                                                        if ($matokeo['resultStatus'] == 1) {
                                                                            echo "No";
                                                                        } else {
                                                                        ?>
                                                                            <a href="action_equivalent_results.php?action_type=dropSchool&id=<?php echo $applicantResultID; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to drop this results?');">Drop</a>
                                                                        <?php
                                                                        } ?>
                                                                    </td>
                                                                    <?php
                                                                    echo "</tr>"
                                                                    ?>
                                                                </tbody>
                                                            </table>

                                                        <?php
                                                        } ?>
                                                    </fieldset>
                                                </div>

                                            <?php
                                            } else {
                                            ?>
                                                <label class="col-lg-12 control-label">If you have Equivalent Level Results, Click Here <a href="index.php?sz=equivalent"><span class="btn btn-primary"> Add Equivalent Level Results</span></a></label>
                                            <?php
                                            } ?>
                                        </fieldset>
                                    </div>
                                    <!-- End of Equivant Results-->

                                    <form name="" method="post" action="action_education_background.php">
                                        <div class="col-lg-9"></div>

                                        <div class="col-lg-3">
                                            <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                                        </div>

                                    </form>

                        </div>
                    <?php
                    } else {
                        echo "<h4>To be able to proceed with application, you must have at least four (4) subjects passed at simple pass level. Please make sure that you meet this requirement before proceeding</h4>";
                    }
                } else {
                    //start
                    ?>
                    <div class="col-lg-12">
                        <fieldset>
                            <legend>Advanced Level Results</legend>

                            <?php
                            $resultseq = $db->getRows("applicantresults", array('where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Advance'), 'order_by applicantID ASC'));
                            if (!empty($results)) {
                            ?>

                                <div class="col-lg-12">
                                    <fieldset>
                                        <legend>List of Registered Subjects for Advanced Level (Form FVI)</legend>
                                        <?php
                                        foreach ($resultseq as $mat) {
                                        ?>
                                            <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>School Name</th>
                                                        <th>Index Number</th>
                                                        <th>Year</th>
                                                        <th>Examination Autority</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $applicantResultID = $matokeo['applicantResultID'];
                                                    $schoolName = $matokeo['schoolName'];
                                                    $yearTaken = $matokeo['yearTaken'];
                                                    $indexNumber = $matokeo['indexNumber'];
                                                    $exam_authority = $matokeo['examinationAuthority'];
                                                    $resultStatus = $matokeo['resultStatus'];
                                                    if ($resultStatus == 1) {
                                                        $vstatus = "Verified";
                                                    } else {
                                                        $vstatus = "Not Verified";
                                                    }
                                                    echo "<tr><td>$schoolName</td><td>$indexNumber</td><td>$yearTaken</td><td>$exam_authority</td><td>$vstatus</td>"; ?>
                                                    <?php
                                                    echo "</tr>"
                                                    ?>
                                                </tbody>
                                            </table>

                                            <?php
                                            $resultSubjectseq = $db->getRows("applicantsubjects_equivalence", array('where' => array('applicantResultID' => $applicantResultID), 'order_by applicantResultID ASC'));
                                            if (!empty($resultSubjectseq)) {
                                            ?>
                                                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Subject Name</th>
                                                            <th>Grade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $count = 0;
                                                        $totalPoints = 0;
                                                        foreach ($resultSubjectseq as $subject) {
                                                            $count++;
                                                            $subjectName = $subject['subjectName'];
                                                            $gradeCode = $subject['gradeCode'];
                                                            echo "<tr><td>$count</td><td>$subjectName</td><td>$gradeCode</td><td>$points</td>"; ?>

                                                        <?php
                                                            echo "</tr>";
                                                        } ?>

                                                    </tbody>
                                                </table>
                                        <?php
                                            }
                                        } ?>
                                    </fieldset>
                                </div> <?php
                                    } ?> <label class="col-lg-12 control-label">If you have Advanced Level Results, Click Here <a href="index.php?sz=alevel"><span class="btn btn-primary"> Add Advanced Level Results</span></a></label>

                        </fieldset>
                    </div>
                    <!--End of Advanced Level Results-->
                    <form name="" method="post" action="action_education_background.php">
                        <div class="col-lg-9"></div>

                        <div class="col-lg-3">
                            <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>

                    </form>
            <?php
                    //end
                }
            } else {

                //echo '<script>window.location="index.php?sz=olevel"</script>';
                echo '<script>window.location="index.php?sz=confirm_ordinary_results"</script>';
                //header("Location:index2.php?sz=olevel");
                //$db->redirect("index2.php?sz=olevel");
            }
            ?>
            </div>
            </form>
    </div>
    <!--<div class="modal fade" id="oModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <strong>Loading...</strong>
        </div>
    </div>
</div>
<div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
             <strong>Loading...</strong>
        </div>
    </div>
</div>-->