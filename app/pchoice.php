<?php
$db=new DBHelper();

?>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">
<script>
    $(document).ready(function()
    {
        $("#studyLevelID").change(function()
        {
            var studyLevelID=$(this).val();
            var applicantID=$("#applicantID").val();
            var dataString = 'studyLevelID='+ studyLevelID +'&applicantID='+applicantID;
            $('#myPleaseWait').modal('show');
            $.ajax
            ({
                type: "POST",
                url: "ajax_programmechoice.php",
                data: dataString,
                cache: false,
               /* beforeSend: function () {
                    $('#programmeID').html('<img src="../assets/img/loader.gif" alt="" >');
                },*/
                success: function(html)
                {
                    $('#myPleaseWait').modal('hide');
                    $("#programmeID").html(html);
                }
            });

        });

    });


    $(document).ready(function()
    {
        $("#studyID").change(function()
        {
            var studyID=$(this).val();
            var applicantID=$("#applicantID").val();
            var dataString = 'studyLevelID='+ studyID +'&applicantID='+applicantID;
            $('#myPleaseWait').modal('show');
            $.ajax
            ({
                type: "POST",
                url: "ajax_programmechoice.php",
                data: dataString,
               /* cache: false,
                beforeSend: function () {
                    $('#progID').html('<img src="../assets/img/loader.gif" alt="" >');
                },*/
                success: function(html)
                {
                    $('#myPleaseWait').modal('hide');
                    $("#progID").html(html);
                }
            });

        });

    });


</script>
<script>
    function validateForm()
    {
        var studyLevelID=document.getElementById("studyLevelID");
        var programmeID=document.getElementById("programmeID");
        var studyID=document.getElementById("studyID");
        var progID=document.getElementById("progID");
        if(studyLevelID.value=='')
        {
            alert("Please choose your first study level");
            return false;
        }
        else if(programmeID.value=='')
        {
            alert("Please choose your first programme");
            return false;
        }
        else if(studyID.value=='')
        {
            alert("Please choose your second study level");
            return false;
        }
        else if(progID.value=='')
        {
            alert("Please choose your second programe");
            return false;
        }
        else {
            return true;
        }


    }
</script>

<div class="row" style="padding-bottom: 23%;">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-list"></i>Programme Choice</h1>
            <p>Please choose your study programme</p>
        </div>
    </div>

    <!-- Priority explainer + colour-coded card frames for the two choice columns. -->
    <div class="col-lg-12" style="margin-bottom:14px;">
        <div class="alert alert-info" style="border-left:4px solid #C9A227; margin-bottom:0;">
            <strong><i class="fa fa-info-circle"></i> How your choices are considered</strong>
            <p style="margin:6px 0 0;">Pick the programme you want most as <strong>First Choice</strong>. Your <strong>Second Choice</strong> is used only if you don't qualify for your first. You can change these anytime before you submit your application.</p>
        </div>
    </div>
    <style>
        /* Frame the two side-by-side columns as numbered priority cards. */
        .pc-priority-styles .col-md-6:nth-of-type(1) > .row {
            border-left: 4px solid #1B3A5C;
            background: #fff;
            border-radius: 6px;
            padding: 14px 16px 4px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: relative;
        }
        .pc-priority-styles .col-md-6:nth-of-type(2) > .row {
            border-left: 4px solid #2D6A4F;
            background: #fff;
            border-radius: 6px;
            padding: 14px 16px 4px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: relative;
        }
        .pc-priority-styles .col-md-6:nth-of-type(1) > .row::before {
            content: "① Priority";
            position: absolute;
            top: -10px;
            left: 12px;
            background: #1B3A5C;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .pc-priority-styles .col-md-6:nth-of-type(2) > .row::before {
            content: "② Fallback";
            position: absolute;
            top: -10px;
            left: 12px;
            background: #2D6A4F;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        /* Stack the two columns on small screens for readability */
        @media (max-width: 768px) {
            .pc-priority-styles .col-md-6 { width: 100% !important; margin-bottom: 14px; }
        }
    </style>

    <?php
    //$applicantResultStatus=$db->getData("applicantresults","applicantResultStatus","applicantID",$_SESSION['applicantID']);
    $applicantResult=$db->getRows('applicantresults',array('where'=>array('applicantID'=>$_SESSION['applicantID'],'levelStatus'=>1)));
    $applicantResultStatus = 1;   // default: assume verified, hide the warning banner
    foreach((array)$applicantResult as $ars)
    {
        $applicantResultStatus=$ars['applicantResultStatus'];
    }
    // $admissionLevel is hydrated later at line 210+; read it directly from session here.
    $__adm = isset($_SESSION['admissionLevel']) ? $_SESSION['admissionLevel'] : '';
    if($applicantResultStatus==0 && $__adm=="UG")
    {
        //echo $applicantResultStatus;
        ?>
        <p>
            <span class="text-danger" style="font-size: 18px;">Please fill Education Background first then select Study Plan</span>
        </p>
    <?php
    }
    else {
        ?>
        <form class="form-horizontal" name="register" id="register" action="action_programme_choice.php" method="post" onsubmit="return validateForm();">
            <div class="col-lg-12">
                <div class="well">
                    <fieldset>
                        <legend>Please Choose Study Programme</legend>
                        <div class="col-lg-12">
                            <?php
                            if (!empty($_REQUEST['msg'])) {
                                if ($_REQUEST['msg'] == "unsucc") {
                                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Invalid Data, Try again later</strong>.
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
                        $applicantID = $_SESSION['applicantID'];
                        $admissionLevel = $_SESSION['admissionLevel'];
                        // ICHAS admissionLevel values (BC / OD / TC) follow the same UG
                        // NECTA-driven flow as the legacy "UG" -- widen the branch so
                        // Basic/Ordinary/Technician applicants also get the study-level
                        // and programme dropdowns.
                        $__UG_LIKE = in_array($admissionLevel, array('UG','BC','OD','TC'), true);
                        if ($__UG_LIKE) {
                            $study = $db->getStudyLevels($applicantID);
                            if (!empty($study)) {
                                $level = array();
                                foreach ($study as $st) {
                                    $elevel = $st['examinationLevel'];
                                    if ($elevel == "Ordinary") {
                                        if (!in_array("3", $level))
                                            $level[] = "3";
                                    } else if ($elevel == "Advance") {
                                        if (!in_array("2", $level))
                                            $level[] = "2";
                                        if (!in_array("1", $level))
                                            $level[] = "1";
                                    } else if ($elevel == "Equivalent") {
                                        $qualificationID = $db->getEquivalentStudyLevels($applicantID);
                                        if ($qualificationID == 1) {
                                            if (!in_array("2", $level))
                                                $level[] = "2";
                                        } else if ($qualificationID == 2 || $qualificationID == 3) {
                                            if (!in_array("2", $level))
                                                $level[] = "2";
                                            if (!in_array("1", $level))
                                                $level[] = "1";
                                        }
                                        else if ($qualificationID == 9) {
                                            if (!in_array("2", $level))
                                                $level[] = "2";
                                            if (!in_array("1", $level))
                                                $level[] = "1";
                                            if (!in_array("5", $level))
                                                $level[] = "5";
                                        }
                                    }
                                }
                            }
                            /* ICHAS: for the current intake only Ordinary Diplomas
                               are open. Regardless of what qualifications the
                               applicant has, restrict the Study Level dropdown to
                               levels that actually have at least one published
                               programme. This way the applicant can pick OD (8)
                               and the rule engine downstream filters the specific
                               programmes they qualify for. */
                            try {
                                $_publishedLevels = array();
                                $stmt = (new Database())->dbConnection()->query(
                                    "SELECT DISTINCT p.studyLevelID
                                     FROM programs p
                                     JOIN programmemajor pm ON pm.programmeID = p.programID
                                     WHERE pm.publishStatus = 1 AND p.programStatus = 1"
                                );
                                if ($stmt) {
                                    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $_publishedLevels[] = (string)$r['studyLevelID'];
                                    }
                                }
                                if (!empty($_publishedLevels)) {
                                    // Merge published levels into the applicant's derived list.
                                    $level = array_values(array_unique(array_merge((array)$level, $_publishedLevels)));
                                    // And drop any level that has no published programme.
                                    $level = array_values(array_intersect($level, $_publishedLevels));
                                }
                            } catch (Throwable $e) {}
                       } else if ($admissionLevel == "PG") {
                            $study = $db->getPGStudyLevels($applicantID);
                            if (!empty($study)) {
                                $level = array();
                                foreach ($study as $st) {
                                    $qid = $st['qualificationID'];
                                    if ($qid == 9 || $qid == 10)
                                        $level[] = "5";
                                    else if ($qid == 11)
                                        $level[] = "6";
                                }
                            }
                        }
                        ?>
                        <?php

                        $applicantID = $_SESSION['applicantID'];
                        // $studyLevelID=$db->getData("applicantstudylevel","studyLevelID","applicantID",$_SESSION['applicantID']);
                        $programmeChoice = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'choice' => 1), 'order_by applicantID ASC'));
                        if (!empty($programmeChoice)) {
                            foreach ($programmeChoice as $pChoice) {
                                $applicantApplicationIDFirst = $pChoice['applicantApplicationID'];
                                $firstMajor = $pChoice['programmeMajorID'];
                            }
                        }
                        /* getStudyLevelID() returns an array of rows; we need
                           the scalar studyLevelID for the <option value=>. */
                        $_slRows1 = $db->getStudyLevelID($firstMajor);
                        $stduyLevelID1 = !empty($_slRows1[0]['studyLevelID']) ? $_slRows1[0]['studyLevelID'] : '';

                        $programmeChoice2 = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'choice' => 2), 'order_by applicantID ASC'));
                        if (!empty($programmeChoice2)) {
                            foreach ($programmeChoice2 as $pChoice2) {
                                $applicantApplicationIDSecond = $pChoice2['applicantApplicationID'];
                                $secondMajor = $pChoice2['programmeMajorID'];
                            }
                        }
                        $_slRows2 = $db->getStudyLevelID($secondMajor);
                        $stduyLevelID2 = !empty($_slRows2[0]['studyLevelID']) ? $_slRows2[0]['studyLevelID'] : '';
                        ?>
                        <input type="hidden" name="applicantID" id="applicantID" value="<?php echo $applicantID;?>">

                        <?php
                       if ($__UG_LIKE) {
                            ?>
                            <div class="row pc-priority-styles">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="FirstName">First Choice Study Level</label>
                                                <select name="studyLevelID" id="studyLevelID" class="form-control"
                                                        required="required">
                                                    <?php
                                                    if (!empty($programmeChoice)) {

                                                        ?>
                                                        <option value="<?php echo $stduyLevelID1; ?>"
                                                                selected="selected"><?php echo $db->getData("studylevels", "studyLevelName", "studyLevelID", $stduyLevelID1); ?>
                                                        </option>

                                                        <?php
                                                    } else {
                                                        echo "<option value=\"\">Select Qualification Type</option>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if (!empty($level)) {
                                                        foreach ($level as $lv) {
                                                            $studyLevelName = $db->getData("studylevels", "studyLevelName", "studyLevelID", $lv);
                                                            ?>
                                                            <option value="<?php echo $lv; ?>"><?php echo $studyLevelName; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="FirstName">First Choice Programme Name</label>
                                                <select name="programmeID" id="programmeID" class="form-control"
                                                        required="required">
                                                    <?php
                                                    if (!empty($programmeChoice)) {
                                                        ?>
                                                        <option value="<?php echo $firstMajor; ?>"
                                                                selected="selected"><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstMajor); ?></option>
                                                        <?php
                                                    } else {
                                                        echo "<option value=''>Please Select Here</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="FirstName">Second Choice Study Level</label>
                                                <select name="studyID" id="studyID" class="form-control"
                                                        required="required">
                                                    <?php
                                                    if (!empty($programmeChoice)) {

                                                        ?>
                                                        <option value="<?php echo $stduyLevelID2; ?>"
                                                                selected="selected"><?php echo $db->getData("studylevels", "studyLevelName", "studyLevelID", $stduyLevelID2); ?>
                                                        </option>

                                                        <?php
                                                    } else {
                                                        echo "<option value=\"\">Select Qualification Type</option>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if (!empty($level)) {
                                                        foreach ($level as $lv) {
                                                            $studyLevelName = $db->getData("studylevels", "studyLevelName", "studyLevelID", $lv);
                                                            ?>
                                                            <option value="<?php echo $lv; ?>"><?php echo $studyLevelName; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="FirstName">Second Choice Programme Name</label>
                                                <select name="progID" id="progID" class="form-control"
                                                        required="required">
                                                    <?php
                                                    if (!empty($programmeChoice2)) {
                                                        ?>
                                                        <option value="<?php echo $secondMajor; ?>"
                                                                selected="selected"><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondMajor); ?></option>
                                                        <?php
                                                    } else {
                                                        echo "<option value=''>Please Select Here</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php
                       } else if ($admissionLevel == "PG") {
                            ?>

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="FirstName">First Choice Programme Name</label>
                                                <select name="programmeID" id="programmeID" class="form-control"
                                                        required="required">
                                                    <?php
                                                   if (!empty($programmeChoice)) {
                                                        ?>
                                                        <option value="<?php echo $firstMajor; ?>"
                                                                selected="selected"><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstMajor); ?></option>
                                                        <?php
                                                    } else {
                                                        echo "<option value=''>Please Select Here</option>";
                                                    }
                                                   // if (!empty($level)) {
                                                     //   foreach ($level as $lv) {
                                                            $programmeData = $db->getProgrammeMajor(4);
                                                            if (!empty($programmeData)) {
                                                                foreach ($programmeData as $pd) {
                                                                    $programmeName = $pd['programmeMajor'];
                                                                    $programmeID = $pd['programmeMajorID'];
                                                                    echo "<option value='$programmeID'>$programmeName</option>";
                                                                }
                                                            } else {
                                                                echo "<option value=''>No Programme</option>";
                                                            }
                                                       // }
                                                    /*} else {
                                                        echo "<option value=''>No Programe</option>";
                                                    }*/

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="FirstName">Second Choice Programme Name</label>
                                                <select name="progID" id="progID" class="form-control"
                                                        required="required">
                                                    <?php
                                                   if (!empty($programmeChoice2)) {
                                                        ?>
                                                        <option value="<?php echo $secondMajor; ?>"
                                                                selected="selected"><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondMajor); ?></option>
                                                        <?php
                                                    } else {
                                                        echo "<option value=''>Please Select Here</option>";
                                                    }
                                                    /*if (!empty($level)) {
                                                        foreach ($level as $lv) {
                                                    */        $programmeData = $db->getProgrammeMajor(4);
                                                            if (!empty($programmeData)) {
                                                                foreach ($programmeData as $pd) {
                                                                    $programmeName = $pd['programmeMajor'];
                                                                    $programmeID = $pd['programmeMajorID'];
                                                                    echo "<option value='$programmeID'>$programmeName</option>";
                                                                }
                                                            } else {
                                                                echo "<option value=''>No Programme</option>";
                                                            }
                                                        /*}
                                                    } else {
                                                        echo "<option value=''>No Programe</option>";
                                                    }*/


                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <?php
                       }
                        ?>
                    </fieldset>

                </div>
            </div>
            <div class="col-lg-3"></div>

            <div class="row">
                <div class="col-lg-12"><br><br></div>
            </div>
            <div class="col-lg-9"></div>
            <?php
            if (!empty($programmeChoice) || !empty($programmeChoice2)) {
                ?>
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="edit"/>
                    <input type="hidden" name="firstID" value="<?php echo $applicantApplicationIDFirst; ?>">
                    <input type="hidden" name="secondID" value="<?php echo $applicantApplicationIDSecond; ?>">
                    <input type="submit" name="doSubmit" value="Save & Continue" class="btn btn-success form-control"/>
                </div>
                <?php
            } else {
                ?>
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="submit" name="doSubmit" value="Save & Continue" class="btn btn-success form-control"/>
                </div>
            <?php } ?>


        </form>
        <?php
    }
    ?>
</div>

