<?php
$db = new DBHelper();
$applicantID = $_SESSION['applicantID'];
?>
<script type="text/javascript" src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="js/bootbox.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.submitapplication').click(function(e) {

            e.preventDefault();

            var appID = $(this).attr('data-id');

            bootbox.dialog({
                message: "Are you <b>SURE</b> you want to Submit your Application?<br>Once You Submit Your Application, You can't make any Changes",
                title: "<i class='glyphicon glyphicon-trash'></i> Confirm Submittion!",
                buttons: {
                    danger: {
                        label: "Cancel",
                        className: "btn-success",
                        callback: function() {
                            $("[data-dismiss=modal]").trigger({
                                type: "click"
                            });
                        }
                    },
                    success: {
                        label: "Submit",
                        className: "btn-success",
                        callback: function() {
                            $.post('action_submitapplication.php', {
                                    'applicantID': appID
                                })
                                .done(function(response) {
                                    bootbox.alert(response);
                                    if (response) {
                                        //$('#confirm').submit();
                                        document.location.href = "index.php"
                                    }
                                    //
                                    //parent.fadeOut('slow');
                                })
                                .fail(function() {
                                    bootbox.alert('Something Went Wrog ....');
                                });

                        }
                    }
                }
            });
        });

    });
</script>
<div class="page-title">
    <div>
        <h1><i class="fa fa-dashboard"></i>Submit Application</h1>
        <p>Please review your application before submitting</p>
    </div>
</div>
<?php $LSZ_STEP = 'submit'; include __DIR__ . '/_lsz_guide.php'; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h3 class="card-title">Final Stage of Your Application</h3>
            <?php
            $applicantsData = $db->getRows('applicants', array('where' => array('applicantID' => $applicantID), 'order_by' => 'applicantID ASC'));
            if (!empty($applicantsData)) {
                foreach ($applicantsData as $apps) {
                    $gender = $apps['gender'];
                    $fname = $apps['firstName'];
                    $mname = $apps['middleName'];
                    $lname = $apps['lastName'];
                    $remarkID = $apps['applicantsRemarksID'];
                    $applicationNumber = $apps['applicationNumber'];
                    $admissionLevel = $apps['admissionLevel'];
                    $appinfostatus = $apps['appinfostatus'];
                    if ($gender == "Male")
                        $sex = "Mr";
                    else
                        $sex = "Ms";
                    $name = "$sex $fname  $mname $lname";
            ?>
                    <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Application Number</th>
                                <th>Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span style="font-size: 18px;"><?php echo $name; ?></span></td>
                                <td><span style="font-size: 18px;">
                                        <?php
                                        echo $applicationNumber;
                                        ?>
                                    </span></td>
                                <td><span style="font-size: 18px;"><?php echo $admissionLevel; ?></span></td>
                                <td><span style="font-size: 18px;">
                                        <?php
                                        if ($remarkID == 3) {
                                            echo "Provisional Admission";
                                        } else if ($remarkID == 2 && $db->checkApplicantStudyLevel($applicantID) == 1) {
                                            echo "Pending";
                                        } else {
                                            echo $db->getData("remarks", "remark", "remarkID", $remarkID);
                                        }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            <?php
                }
            }
            ?>
            </p>


            <?php
            $programmeChoice = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'choice' => 1), 'order_by applicantID ASC'));
            if (!empty($programmeChoice)) {
                foreach ($programmeChoice as $pChoice) {
                    $applicantApplicationIDFirst = $pChoice['applicantApplicationID'];
                    $firstChoice = $pChoice['programmeMajorID'];
                }
            }

            $programmeChoice2 = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'choice' => 2), 'order_by applicantID ASC'));
            if (!empty($programmeChoice2)) {
                foreach ($programmeChoice2 as $pChoice2) {
                    $applicantApplicationIDSecond = $pChoice2['applicantApplicationID'];
                    $secondChoice = $pChoice2['programmeMajorID'];
                }
            }

            $payments = $db->getRows("applicant_payment", array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by applicantID ASC'));
            if (!empty($payments)) {
                foreach ($payments as $pay) {
                }
            }

            ?>
            <?php
            if (!empty($programmeChoice)) {
            ?>
                <p><span style="font-size: 18px;">Programme(s) Applied:</span> </p>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="FirstName">First Choice Programme</label>
                        <input type="text" name="" value="<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstChoice); ?>" class="form-control" disabled="">

                    </div>

                    <div class="col-lg-6">
                        <label for="Physical Address">Second Choice Programme</label>
                        <input type="text" name="" value="<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondChoice); ?>" class="form-control" disabled="">

                    </div>

                </div>
            <?php }
            ?>
            <br><br>
            <p><span style="font-size: 24px;"><strong>Declaration:</strong></span> </p>
            <div class="row">
                <div class="col-lg-12"><span style="font-size:18px">
                        I hereby attest that I have personally filled in this Application Form and the information contained herein is complete and accurate to the best of my knowledge.
                        I understand that withholding or giving false information will render my application for admission invalid. I further understand that
                        I may be required to appear for an interview or to undergo such tests as required by the University Board of Admission as a condition for admission to the programme of
                        study for which I have applied e.g. Language Test, Medical Check-up, etc.</span>
                </div>

            </div>
            <br><br>
            <?php
            $org = $db->getRows("organization");
            if (!empty($org)) {
                foreach ($org as $og) {
                    $orgName = $og['organizationName'];
                    $orgPhone = $og['organizationPhone'];
                    $studentSupport = $og['student_support'];
                }
            }
            $studyLevelID = $db->getStudyLevelID($firstChoice);
            $appfees = $db->getData("applicationfees", "fees", "studyLevelID", $studyLevelID);
            $campus = $db->getCampus($firstChoice);
            if (!empty($campus)) {
                foreach ($campus as $cp) {
                    $campusName = $cp['campusName'];
                    $campusAddress = $cp['campusAddress'];
                    $accountNumber = $cp['accountNumber'];
                    $bankName = $cp['bankName'];
                    $accountName = $cp['accountName'];
                    $campusname = "$campusName,$campusAddress";
                }
            }
            ?>


            <!-- <div class="row">
                <p><span style="font-size: 24px;"><strong>Please pay application fee through <?php //echo $bankName; ?> Bank/Agent with the following detail:</strong></span> </p>
                <div class="col-lg-12"><span style="font-size:18px">
                        Account Number: <?php //echo $accountNumber; ?><br>
                        Account Name: <?php //echo $accountName; ?><br>

                        <p><span style="font-size: 24px;"><strong>Fee Categories:</span></p>
                        <?php
                        /* $feescategories = $db->getRows("applicationfees", array('order by studyLevelID DESC'));
                        foreach ($feescategories as $fee) {
                            echo "TSHs. " . $fee['fees'] . "/= for " . $db->getData("studylevels", "studyLevelName", "studyLevelID", $fee['studyLevelID']) . "<br>";
                        } */
                        ?>
                        Send your pay-in slip through WhatsApp Numbers: <?php //echo $studentSupport; ?>
                    </span>
                </div>

            </div> -->

            <?php
            $attachment = $db->getRows("attachment", array('where' => array('applicantID' => $applicantID), 'order by attachmentID DESC'));
            if ($_SESSION['admissionLevel'] == "PG") {
                if (empty($programmeChoice) && (empty($programmeChoice2))) {
            ?>
                    <h2 class="text-danger">Sorry,please choose your study plan in step 3(Study Plan)</h2>
                <?php
                } elseif ($appinfostatus == 0) {
                ?>
                    <h2 class="text-danger">Sorry,please complete your profile(Personal Information) in step 4</h2>
                <?php
                } else if (empty($attachment)) {
                ?>
                    <h2 class="text-danger">Sorry,please attach your Bachelor/Master/Personal Picture</h2>
                    <?php }
                    else {
                        ?>
                    <div class="row">
                        <div class="col-lg-9"></div>
                        <?php
                        if ($remarkID == 7 || $remarkID == 5 || $remarkID == 1) {
                        ?>
                            <form name="" method="post" action="">
                                <div class="col-lg-3">
                                    <a class="submitapplication" data-id="<?php echo $_SESSION['applicantID']; ?>" href="javascript:void(0)">
                                        <input type="submit" name="doProceed" value="Submit Application" class="btn btn-success form-control" /></a>
                                </div>
                            </form>
                    <?php
                        }
                    }
                    ?>
                    </div>
                    <?php
                } else {
                    if (empty($programmeChoice) && (empty($programmeChoice2))) {
                    ?>
                        <h2 class="text-danger">Sorry,please choose your study plan in step 3(Study Plan)</h2>
                    <?php
                    } elseif ($appinfostatus == 0) {
                    ?>
                        <h2 class="text-danger">Sorry,please complete your profile(Personal Information) in step 4</h2>
                    <?php
                    } elseif ($db->checkApplicantStudyLevel($_SESSION['applicantID']) != 1) {
                        ?><div class="row">
                            <div class="col-lg-9"></div>
                            <?php
                            if ($remarkID == 7 || $remarkID == 5 || $remarkID == 1) {
                            ?>
                                <form name="" method="post" action="">
                                    <div class="col-lg-3">
                                        <a class="submitapplication" data-id="<?php echo $_SESSION['applicantID']; ?>" href="javascript:void(0)">
                                            <input type="submit" name="doProceed" value="Submit Application" class="btn btn-success form-control" /></a>
                                    </div>
                                </form>
                            <?php
                            } ?>
                        </div><?php
                                } else {

                        ?>
                <div class="row">
                    <div class="col-lg-9"></div>
                    <?php
                        if ($remarkID == 7 || $remarkID == 5 || $remarkID == 1) {
                    ?>
                        <form name="" method="post" action="">
                            <div class="col-lg-3">
                                <a class="submitapplication" data-id="<?php echo $_SESSION['applicantID']; ?>" href="javascript:void(0)">
                                    <input type="submit" name="doProceed" value="Submit Application" class="btn btn-success form-control" /></a>
                            </div>
                        </form>
                    <?php
                        }
                                ?>
        </div>
    </div>
<?php
                    }
                }
?>
</div>
</div>
</div>