<?php $db = new DBHelper();

/* Detect LSZ tenant + the applicant's chosen study level so we can
   render an LSZ-specific guide card at the top of the page. */
$LSZ_MODE = false;
$LSZ_STUDY_LEVEL_ID = null;
$LSZ_QUALIFICATION_ID = null;
try {
    $org = $db->getRows("organization");
    if (!empty($org[0]['organizationCode']) && strtoupper($org[0]['organizationCode']) === 'LSZ') {
        $LSZ_MODE = true;
    }
    $lvlRow = $db->getRows('applicantstudylevel', array(
        'where' => array('applicantID' => $_SESSION['applicantID'])
    ));
    if (!empty($lvlRow[0])) {
        $LSZ_STUDY_LEVEL_ID   = (int)$lvlRow[0]['studyLevelID'];
        $LSZ_QUALIFICATION_ID = (int)$lvlRow[0]['qualificationTypeID'];
    }
} catch (Throwable $e) {}

/* Has the applicant already saved a qualification (Equivalent) row? */
$hasEquivRow = false;
try {
    $r = $db->getRows('applicantresults', array(
        'where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Equivalent')
    ));
    if (!empty($r)) $hasEquivRow = true;
} catch (Throwable $e) {}

/* Has the applicant saved O-level (Form IV)? */
$hasOLevel = false;
try {
    $r = $db->getRows('applicantresults', array(
        'where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Ordinary', 'applicantResultStatus' => 1)
    ));
    if (!empty($r)) $hasOLevel = true;
} catch (Throwable $e) {}

/* Map LSZ study levels to the next-form CTA the applicant needs.
   studyLevelID 4 = Professional of Law (CLP)   -> need Degree of Law
   studyLevelID 5 = Vakil Course                -> need Diploma in Law */
$LSZ_NEXT_CTA_LABEL = null;
$LSZ_NEXT_CTA_HINT  = null;
if ($LSZ_MODE) {
    if ($LSZ_STUDY_LEVEL_ID === 4) {
        $LSZ_NEXT_CTA_LABEL = 'Add Your Bachelor Degree of Law';
        $LSZ_NEXT_CTA_HINT  = 'The Certified Legal Professional (CLP) programme requires a Bachelor Degree of Law (or higher). Add the certificate details now.';
    } elseif ($LSZ_STUDY_LEVEL_ID === 5) {
        $LSZ_NEXT_CTA_LABEL = 'Add Your Diploma in Law';
        $LSZ_NEXT_CTA_HINT  = 'The Vakil Course requires a Diploma in Law (or equivalent). Add the diploma details now.';
    }
}
?>
<!--<script src="js/jquery-1.4.2.min.js"></script>-->
<div class="row">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-graduation-cap"></i>Educational Background</h1>
            <p><?php echo $LSZ_MODE ? 'Confirm your Form IV results, then add your professional qualification.' : 'Add your results based on the instruction from form'; ?></p>
        </div>
    </div>

    <?php if ($LSZ_MODE): ?>
    <div class="col-lg-12" style="margin-bottom:12px;">
        <!-- Tabbed navigation between Form IV (NECTA) and the manually-entered
             professional qualification. Each tab is its own route in
             main_index.php so both pages keep their own JS + validation. -->
        <ul class="nav nav-tabs lsz-edu-tabs" style="border-bottom:2px solid #1D3557; margin-bottom:16px;">
            <li class="active" style="margin-bottom:-2px;">
                <a href="index.php?sz=education_background" style="border-radius:6px 6px 0 0;">
                    <i class="fa fa-graduation-cap"></i> Form IV (NECTA)
                    <?php if ($hasOLevel): ?><span class="label label-success" style="margin-left:6px;">✓ Saved</span><?php endif; ?>
                </a>
            </li>
            <li style="margin-bottom:-2px;">
                <a href="index.php?sz=equivalent" style="border-radius:6px 6px 0 0;">
                    <i class="fa fa-university"></i>
                    <?php
                    if ($LSZ_STUDY_LEVEL_ID === 4)      echo 'Bachelor Degree of Law';
                    elseif ($LSZ_STUDY_LEVEL_ID === 5)  echo 'Diploma in Law';
                    else                                echo 'Professional Qualification';
                    ?>
                    <?php if ($hasEquivRow): ?><span class="label label-success" style="margin-left:6px;">✓ Saved</span><?php endif; ?>
            </a>
            </li>
        </ul>

        <?php if ($LSZ_NEXT_CTA_LABEL && !$hasEquivRow && $hasOLevel): ?>
            <div class="alert alert-info" style="border-left:4px solid #C9A227;">
                <strong><i class="fa fa-info-circle"></i>
                    <?php echo htmlspecialchars($LSZ_NEXT_CTA_HINT); ?>
                </strong>
                <span> Switch to the "<?php echo $LSZ_STUDY_LEVEL_ID === 4 ? 'Bachelor Degree of Law' : 'Diploma in Law'; ?>" tab above to add it.</span>
            </div>
        <?php elseif ($hasEquivRow && $hasOLevel): ?>
            <div class="alert alert-success">
                <strong><i class="fa fa-check-circle"></i> Both your Form IV and your professional qualification are on file.</strong>
                <span> You can continue to the next step.</span>
            </div>
        <?php elseif (!$hasOLevel): ?>
            <div class="alert alert-info">
                <strong><i class="fa fa-info-circle"></i> Confirm your Form IV results below first.</strong>
                <?php if ($LSZ_NEXT_CTA_LABEL): ?>
                    <span> After that switch to the "<?php echo $LSZ_STUDY_LEVEL_ID === 4 ? 'Bachelor Degree of Law' : 'Diploma in Law'; ?>" tab to add your professional qualification.</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
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
                            <legend>Equivalance Advanced Level Results</legend>

                            <?php
                            $resultseqa = $db->getRows("applicantresults", array('where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Advance'), 'order_by applicantID ASC'));
                            if (!empty($resultseqa)) {
                            ?>

                                <div class="col-lg-12">
                                    <fieldset>
                                        <legend>List of Registered Subjects for Advanced Level (Form FVI)</legend>
                                        <?php
                                        foreach ($resultseqa as $mata) {
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
                                                    $applicantResultID = $mata['applicantResultID'];
                                                    $schoolName = $mata['schoolName'];
                                                    $yearTaken = $mata['yearTaken'];
                                                    $indexNumber = $mata['indexNumber'];
                                                    $exam_authority = $mata['examinationAuthority'];
                                                    $resultStatus = $mata['resultStatus'];
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
                    <!--Equivalent Results-->
                    <div class="col-lg-12">
                        <hr>
                    </div>
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
                    <!-- End of Equivalent Results -->
                    <?php
                    //end
                    ?>
                    <form name="" method="post" action="action_education_background.php">
                        <div class="col-lg-9"></div>

                        <div class="col-lg-3">
                            <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>

                    </form>

            <?php
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