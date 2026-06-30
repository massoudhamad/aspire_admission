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

    <?php
    /* Sanity check: a Programmes page only works once the applicant has
       (a) chosen a study level, and (b) entered some education data so
       getProgrammeChoice() can filter on it. If either is missing, surface
       a friendly "Go to step X" link so the applicant has a clear path
       forward instead of staring at an empty dropdown. */
    $_haveStudyLevel = $db->getRows('applicantstudylevel',
        array('where' => array('applicantID' => $_SESSION['applicantID'])));
    $_haveResults = $db->getRows('applicantresults',
        array('where' => array('applicantID' => $_SESSION['applicantID'])));

    if (empty($_haveStudyLevel) || empty($_haveResults)) {
        echo '<div class="alert alert-warning" style="margin:14px 0;">';
        echo '<h4 style="margin-top:0;"><i class="fa fa-info-circle"></i> Before choosing a programme</h4>';
        echo '<p style="margin:8px 0;">You must complete the previous steps first:</p>';
        echo '<ul style="margin:8px 0 12px 18px;">';
        if (empty($_haveStudyLevel)) {
            echo '<li><strong>Study Level not set.</strong> <a href="index.php?sz=level" class="btn btn-sm btn-warning" style="margin-left:6px;">Set Study Level &rarr;</a></li>';
        }
        if (empty($_haveResults)) {
            echo '<li><strong>Education Background not entered.</strong> <a href="index.php?sz=education_background" class="btn btn-sm btn-warning" style="margin-left:6px;">Enter Results &rarr;</a></li>';
        }
        echo '</ul>';
        echo '<p style="margin:8px 0 0;">Once both are saved, return to this page to pick your programme.</p>';
        echo '</div>';
    }

    //$applicantResultStatus=$db->getData("applicantresults","applicantResultStatus","applicantID",$_SESSION['applicantID']);
    $applicantResult=$db->getRows('applicantresults',array('where'=>array('applicantID'=>$_SESSION['applicantID'],'levelStatus'=>1)));
    foreach($applicantResult as $ars)
    {
        $applicantResultStatus=$ars['applicantResultStatus'];
    }
    if($applicantResultStatus==0 && $admissionLevel=="UG")
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
                        if ($admissionLevel == "UG") {
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
                        $stduyLevelID1 = $db->getStudyLevelID($firstMajor);

                        $programmeChoice2 = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'choice' => 2), 'order_by applicantID ASC'));
                        if (!empty($programmeChoice2)) {
                            foreach ($programmeChoice2 as $pChoice2) {
                                $applicantApplicationIDSecond = $pChoice2['applicantApplicationID'];
                                $secondMajor = $pChoice2['programmeMajorID'];
                            }
                        }
                        $stduyLevelID2 = $db->getStudyLevelID($secondMajor);
                        ?>
                        <input type="hidden" name="applicantID" id="applicantID" value="<?php echo $applicantID;?>">

                        <?php
                       if ($admissionLevel == "UG") {
                            ?>
                            <div class="row">
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
                                                        <option value="<?php echo $studyLevelID1; ?>"
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
                                                        <option value="<?php echo $studyLevelID2; ?>"
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

