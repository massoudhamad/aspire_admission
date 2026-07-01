<?php $db = new DBHelper();

/* LSZ mode: relabel this page and add a tab bar mirroring
   education_background.php so applicants can jump back to Form IV. */
$LSZ_MODE = false;
$LSZ_STUDY_LEVEL_ID = null;
try {
    $org = $db->getRows("organization");
    if (!empty($org[0]['organizationCode']) && strtoupper($org[0]['organizationCode']) === 'LSZ') {
        $LSZ_MODE = true;
    }
    $lvlRow = $db->getRows('applicantstudylevel', array(
        'where' => array('applicantID' => $_SESSION['applicantID'])
    ));
    if (!empty($lvlRow[0])) $LSZ_STUDY_LEVEL_ID = (int)$lvlRow[0]['studyLevelID'];
} catch (Throwable $e) {}

$hasOLevel = false; $hasEquivRow = false;
try {
    $r = $db->getRows('applicantresults', array(
        'where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Ordinary', 'applicantResultStatus' => 1)
    ));
    if (!empty($r)) $hasOLevel = true;
    $r2 = $db->getRows('applicantresults', array(
        'where' => array('applicantID' => $_SESSION['applicantID'], 'examinationLevel' => 'Equivalent')
    ));
    if (!empty($r2)) $hasEquivRow = true;
} catch (Throwable $e) {}

$LSZ_TAB_LABEL = 'Professional Qualification';
if ($LSZ_STUDY_LEVEL_ID === 4)     $LSZ_TAB_LABEL = 'Bachelor Degree of Law';
elseif ($LSZ_STUDY_LEVEL_ID === 5) $LSZ_TAB_LABEL = 'Diploma in Law';
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
<link href="css/validation.css" rel="stylesheet">


<div class="page-title">
    <div>
        <?php if ($LSZ_MODE): ?>
            <h1><i class="fa fa-university"></i> <?php echo htmlspecialchars($LSZ_TAB_LABEL); ?></h1>
            <p>Enter the details of your <?php echo htmlspecialchars($LSZ_TAB_LABEL); ?> certificate.</p>
        <?php else: ?>
            <h1><i class="fa fa-graduation-cap"></i>Equivalent Results</h1>
            <p>Please fill Equivalent Level Results (Certificates,NTAs, Diploma, Advanced Diploma etc)</p>
        <?php endif; ?>
    </div>
</div>

<?php if ($LSZ_MODE): ?>
<div class="col-lg-12" style="margin-bottom:12px;">
    <ul class="nav nav-tabs lsz-edu-tabs" style="border-bottom:2px solid #1D3557; margin-bottom:16px;">
        <li style="margin-bottom:-2px;">
            <a href="index.php?sz=education_background" style="border-radius:6px 6px 0 0;">
                <i class="fa fa-graduation-cap"></i> Form IV (NECTA)
                <?php if ($hasOLevel): ?><span class="label label-success" style="margin-left:6px;">✓ Saved</span><?php endif; ?>
            </a>
        </li>
        <li class="active" style="margin-bottom:-2px;">
            <a href="index.php?sz=equivalent" style="border-radius:6px 6px 0 0;">
                <i class="fa fa-university"></i> <?php echo htmlspecialchars($LSZ_TAB_LABEL); ?>
                <?php if ($hasEquivRow): ?><span class="label label-success" style="margin-left:6px;">✓ Saved</span><?php endif; ?>
            </a>
        </li>
    </ul>
</div>
<?php endif; ?>
<div class="row" style="padding-bottom: 15.5%;">
    <div class="col-lg-12">
    </div>

    <div class="row">
        <div class="col-md-10">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-8">
                        <label for="Email">Qualification</label>
                        <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" required="required">

                            <option value="">Select Qualification Type</option>
                            <?php
                            $qualificationType = $db->getRows('qualificationtype', array('where' => array('qualificationTypeRank' => 2), 'order_by' => 'qualificationTypeRank ASC'));
                            if (!empty($qualificationType)) {
                                $count = 0;
                                foreach ($qualificationType as $type) {
                                    $count++;
                                    $qualificationName = $type['qualificationName'];
                                    $qualificationID = $type['qualificationTypeID'];
                            ?>
                                    <option value="<?php echo $qualificationID; ?>"><?php echo $qualificationName; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="diploma">
                        <form name="form-get-olevel-data" id="form-get-olevel-data" method="post" onsubmit="return equivalentfunction();">
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="inputEmail">AVN Number</label>
                                <div class="col-lg-6">
                                    <input class="form-control" id="avn_number" type="text" name="avn_number">
                                </div>
                                <div class="col-lg-4">
                                    <input type="hidden" name="level" id="level" value="alevel">
                                    <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="OUT">
                        <form name="form-get-olevel-data" id="form-get-olevel-data" method="post" onsubmit="return foundationProgrammefunction();">
                            <div class="form-group">
                                <label class="col-lg-4 control-label" for="inputEmail">Open University Registration Number</label>
                                <div class="col-lg-4">
                                    <input class="form-control" id="out_reg_number" type="text" name="out_reg_number">
                                </div>
                                <div class="col-lg-4">
                                    <input type="hidden" name="level" id="level" value="alevel">
                                    <input type="submit" name="doSubmit" value="View Results" class="btn btn-success form-control" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row"><br><br></div>
                <div class="Others">
                    <form class="form-horizontal" name="register" id="register" action="action_equivalent_results.php" method="post" onsubmit="return validateEquivalent();">
                        <div class="col-lg-12">

                            <h4>Please fill Equivalent Level Results (Certificates,NTAs, Diploma, Advanced Diploma etc)</h4>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-inline">Do you want to be considered for Equivalent Entry Qualification?</label>
                                    <label class="form-inline"> <input type="radio" name="entry_qualification" id="entry_qualification" value="1" checked> Yes </label>
                                    <label class="form-inline"> <input type="radio" name="entry_qualification" id="entry_qualification" value="0"> No </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="FirstName">Programme Name</label>
                                    <input type="text" name="programmeName" id="programmeName" placeholder="Enter Programme Name" class="form-control" required>
                                </div>
                                <div class="col-lg-6">
                                    <label for="FirstName">Institute Name</label>
                                    <input type="text" name="instituteName" id="instituteName" placeholder="Enter Institute Name" class="form-control" required="required">
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="Physical Address">Registration Number</label>
                                    <input type="text" name="registrationNumber" id="registrationNumber" placeholder="Enter Registration Number" class="form-control" required="required" />
                                </div>

                                <div class="col-lg-6">
                                    <label for="Email">Graduation Year</label>
                                    <select name="indexYear" id="indexYear" class="form-control" required="required">
                                        <option value="">Select Year</option>
                                        <?php
                                        $year = date('Y');
                                        $year1 = date('Y') - 40;
                                        for ($x = $year; $x >= $year1; $x--) {
                                            echo "<option value='$x'>$x</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="grade">Grade</label>
                                    <div class="form-inline">
                                        <input type="radio" id="from" name="gradeType" id="gradeType" value="GPA" checked=""> GPA
                                        <select name="gradePoints" id="gpa" class="form-control">
                                            <option value="">--Select GPA--</option>
                                            <?php
                                            for ($x = 3.0; $x <= 5.0; $x += 0.1) {
                                                echo "<option value='$x'>$x</option>";
                                            }
                                            ?>
                                        </select> /
                                        <select name="gradeValue2" id="gpa" class="form-control">
                                            <option value="5" selected="">5</option>
                                            <option value="4">4</option>
                                        </select>
                                        <input type="radio" id="from" name="gradeType" id="gradeType" value="Non GPA"> Non GPA
                                        <select name="gradeValue" id="NonGpa" class="form-control">
                                            <option value="">--Select Non GPA--</option>
                                            <?php
                                            $grade = $db->getRows('grades', array('where' => array('gradeLevel' => '3'), 'order_by' => 'gradeID ASC'));
                                            if (!empty($grade)) {
                                                foreach ($grade as $gd) {
                                                    $gradeCode = $gd['gradeCode'];
                                                    echo "<option value='$gradeCode'>$gradeCode</option>";
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="Email">Qualification</label>
                                    <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" required="required">

                                        <option value="">Select Qualification Type</option>
                                        <?php
                                        $qualificationType = $db->getEquivalentData();
                                        if (!empty($qualificationType)) {
                                            $count = 0;
                                            foreach ($qualificationType as $type) {
                                                $count++;
                                                $qualificationName = $type['qualificationName'];
                                                $qualificationID = $type['qualificationTypeID'];
                                        ?>
                                                <option value="<?php echo $qualificationID; ?>"><?php echo $qualificationName; ?></option>
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
                                    <input type="hidden" name="action_type" value="add" />
                                    <input type="hidden" name="examinationlevel" value="Equivalent">
                                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                                </div>
                                <div class="col-lg-4">
                                    <input type="button" name="doExit" value="Cancel" onclick="window.history.go(-1);" class="btn btn-success form-control" />
                                </div>

                            </div>
                    </form>
                </div>
            </div>

            <div class="row">

                <div class="col-md-10">
                    <div id="result">
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>