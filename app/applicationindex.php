<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$db = new DBHelper();
$applicantID = $_SESSION['applicantID'];
$userID = $_SESSION['user_session'];
$login = $db->getData("users", "login", "userID", $userID);
if ($login == 0) {
    //header("Location:index.php?sz=success");
?>
    <?php
    $username = $db->getData("users", "userName", "userID", $_SESSION['user_session']);
    $password = $db->getData("users", "lastName", "userID", $_SESSION['user_session']);
    ?>

    <div class="page-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Welcome Page</h1>
            <p>Start your aplication</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-title">Congratulations</h3>
                <p>You have sucessfully created an account. Your username and password are given bellow. You must always use your username and password to login into Admission System. You may change your password whenever you want.</p>
                <h4 class="text text-danger">Username: <?php echo $username; ?></h4>
                <h4 class="text text-danger">Password: <?php echo strtoupper($password); ?></h4>
                <p>
                    Please save this information for next use, You may find these data in your email.
                </p>
                <div class="row">
                    <form name="" method="post" action="action_success.php">
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add" />
                            <input type="submit" name="doSubmit" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>
                    </form>
                </div>


            </div>

        </div>
    </div>
<?php
} else {
?>
    <div class="page-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>Application Dashboard</h1>
            <p>Please use menu at the left side to navigate in the system</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="col-md-12">
                    <?php
                    if (!empty($_REQUEST['msg'])) {
                        echo "<div class='alert alert-danger fade in'><a href='index.php' class='close' data-dismiss='alert'>&times;</a>
                    <strong>" . $_REQUEST['msg'] . "<br>" . $_REQUEST['status'] . "</strong></div>";
                    }
                    ?>
                </div>
                <?php
                $applicantsData = $db->getRows('applicants', array('where' => array('applicantID' => $applicantID), 'order_by' => 'applicantID ASC'));
                if (!empty($applicantsData)) {
                    foreach ($applicantsData as $apps) {
                        $gender = $apps['gender'];
                        $fname = $apps['firstName'];
                        $mname = $apps['middleName'];
                        $lname = $apps['lastName'];
                        $remarkID = $apps['applicantsRemarksID'];
                        $_SESSION['remarkID'] = $remarkID;
                        $publish_status = $apps['publish_status'];
                        $applicationNumber = $apps['applicationNumber'];
                        $admissionLevel = $apps['admissionLevel'];
                        $nacte_status = $apps['nacte_status'];
                        $indexNumber = $apps['formfour'];
                        if ($gender == "Male")
                            $sex = "Mr";
                        else
                            $sex = "Ms";

                        $tcu_final = $apps['tcu_final'];
                        $tcu_confirm = $apps['tcu_confirm'];
                        $tcu_message = $apps['tcu_message'];

                        $name = "$sex $fname  $mname $lname";
                        if (($remarkID == 3  || $remarkID == 6)) {
                            $programmeAdmitted = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'admissionStatus' => 1), 'order_by applicantID ASC'));
                            if (!empty($programmeAdmitted)) {
                                foreach ($programmeAdmitted as $pChoice) {
                                    $applicantApplicationIDFirst = $pChoice['applicantApplicationID'];
                                    $programmeMajorID = $pChoice['programmeMajorID'];
                                }
                            }
                            $regNumber = $db->getData("applicantregistration", "registrationNumber", "applicantID", $applicantID);
                            $campus = $db->getCampus($programmeMajorID);
                            if (!empty($campus)) {
                                foreach ($campus as $cp) {
                                    $campusName = $cp['campusName'];
                                    $campusAddress = $cp['campusAddress'];
                                    $accountNumber = $cp['accountNumber'];
                                    $bankName = $cp['bankName'];
                                    $accountName = $cp['accountName'];
                                    $campusname = "$campusName";
                                }
                            }

                ?>
                            <h3><span class="text-danger">Congratulations,You have been admitted for <?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID); ?></span></h3>
                        <?php
                        } else if ($remarkID == 1) {
                        ?>
                            <h3>Congratulations,Your application has been recorded, you will be notified for any changes of status,please keep login to our system for any updates.</h3>
                            <?php
                        } else if ($remarkID == 3) {
                            if ($db->checkApplicantStudyLevel($applicantID) == 1) {
                            ?>
                                <h3 class="card-title">Please review your status,you may call admission office for more information</h3>
                            <?php
                            } else if ($nacte_status == 1) {
                            ?>
                                <h3><span class="text-danger">Congratulations,You have been admitted for <?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID); ?></span></h3>
                            <?php

                            } else {
                            ?>
                                <h3 class="card-title">Please review your status,you may call admission office for more information</h3>
                            <?php
                            }
                            ?>


                        <?php
                        } else {
                        ?>
                            <h3 class="card-title">Welcome to our online application system,please if you face any problem dont hesitate to contact us</h3>
                        <?php
                        }
                        ?>
                        <div class="table-wrapper-scroll-x">
                            <table class="table table-striped table-bordered table-condensed table-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Application Number</th>
                                        <?php
                                        if ($remarkID == 1 || $remarkID == 2 || $remarkID == 3 || $remarkID == 4 || $remarkID == 5 || $remarkID == 6) {
                                            echo "<th>Ref Number</th>";
                                        }
                                        ?>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <?php if ($remarkID == 6) {
                                        ?>
                                            <th>Registration Number</th>
                                        <?php
                                        }
                                        ?>


                                        <?php
                                        if ($remarkID == 1 || $remarkID == 5 || $remarkID == 2 || $remarkID == 3) {
                                        ?>
                                            <th>Action</th>
                                        <?php }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="font-size: 18px;"><?php echo $name; ?></span></td>

                                        <td><span style="font-size: 18px;">
                                                <?php
                                                echo $applicationNumber; ?>

                                            </span></td>
                                        <?php
                                        if ($remarkID == 1 || $remarkID == 2 || $remarkID == 3 || $remarkID == 4 || $remarkID == 5 || $remarkID == 6) {
                                            echo "<td>" . $db->getData("applicants", "refNumber", "applicantID", $applicantID) . "</td>";
                                        }
                                        ?>
                                        <td><span style="font-size: 18px;"><?php echo $admissionLevel; ?></span></td>
                                        <td><span style="font-size: 18px;">
                                                <?php
                                                $formfour = $db->getFormFour($applicantID);
                                                if ($remarkID == 3) {
                                                    if ($db->checkApplicantStudyLevel($applicantID) == 1) {
                                                        echo "Admitted<br>";
                                                        echo "<b>Programme Admitted:</b><br>";
                                                        $progAdd = $db->getAdmittedProgrammeMajor($applicantID);
                                                        if (!empty($progAdd)) {
                                                            foreach ($progAdd as $pChoice) {
                                                                $programmeCode = $pChoice['programCode'];
                                                                $programmeName = $pChoice['programmeMajor'];
                                                                $programmeMajorIDAdmitted=$pChoice['programmeMajorID'];
                                                            }
                                                        } else {
                                                            $programmeCode = "";
                                                            $programmeName = "";
                                                        }
                                                        echo $programmeName . "<br>";

                                                        if (!empty($tcu_final)) {
                                                            echo $tcu_message . "<br>";
                                                            if ($tcu_final == 225 || $tcu_final == 205) {
                                                ?>
                                                                Please <a href='index.php?sz=confirm_applicant_tcu&formfour=<?php echo $indexNumber; ?>'>Click Here to Confirm</a>
                                                                <br>Click Here to <a href='index.php?sz=request_confirmation_code&formfour=<?php echo $indexNumber; ?>'>Request Confirmation Code</a><br>
                                                            <?php

                                                            } else if ($tcu_final == 212) {
                                                            ?>
                                                                Please <a href='index.php?sz=un_confirm_applicant_tcu&formfour=<?php echo $indexNumber; ?>'>Click Here to Un Confirm</a>
                                                        <?php
                                                            }
                                                        }
                                                        echo "<br>";
                                                        ?>

                                                <?php
                                                    } else {
                                                        echo "Selected";
                                                        echo "<br>";
                                                        echo "<b>Programme Admitted:</b><br>";
                                                        $progAdd = $db->getAdmittedProgrammeMajor($applicantID);
                                                        if (!empty($progAdd)) {
                                                            foreach ($progAdd as $pChoice) {
                                                                $programmeCode = $pChoice['programCode'];
                                                                $programmeName = $pChoice['programmeMajor'];
                                                                $programmeMajorIDAdmitted=$pChoice['programmeMajorID'];
                                                            }
                                                        } else {
                                                            $programmeCode = "";
                                                            $programmeName = "";
                                                        }
                                                        echo $programmeName . "<br>";
                                                    }
                                                } else if ($remarkID == 2 && $db->checkApplicantStudyLevel($applicantID) == 1) {
                                                    echo $db->getData(
                                                        "remarks",
                                                        "remark",
                                                        "remarkID",
                                                        $remarkID
                                                    );
                                                } else {
                                                    echo $db->getData("remarks", "remark", "remarkID", $remarkID);
                                                }
                                                ?>
                                            </span></td>

                                        <?php
                                        if ($remarkID == 1 || $remarkID == 2) {
                                        ?><td>
                                                <a href="printreceipt.php?action=getPDF&applicantID=<?php echo $applicantID; ?>" target="_blank">
                                                    <span class="text text-danger" style="font-size: 18px;">Print Application Receipt</span></a>
                                            </td>
                                            <?php
                                        } else if ($remarkID == 3) {
                                            if ($_SESSION['orgCode'] == "SUMAIT") {
                                                ?><td><?php echo "Please contact Admission Office for your Admission Letter";?><br>
                                            admission@sumait.ac.tz <br>or Contact Phone Number/Whatsapp Number: +255773340066    
                                            </td>
                                            <?php } else if ($_SESSION['orgCode'] == "MUM") {
                                            ?>
                                                <td>
                                                    <a href="printadmissionletter.php?action=getPDF&applicantID=<?php echo $applicantID; ?>" target="_blank">
                                                        <span class="text text-success">Download Admission Letter</span></a>
                                                    <br><br>
                                                </td>
                                            <?php
                                            }
                                            else if($_SESSION['orgCode']=="ZU")
                                            {
                                                if($programmeCode=="ZU022")
                                                {
                                                    ?>
                                                    <td>
                                                    You will be contacted for Admission Letter
                                                    <br><br>
                                                </td>
                                                    <?php
                                                }
                                                else 
                                                {
                                                ?>
                                                <td>
                                                    <a href="printadmissionletterzu.php?action=getPDF&applicantID=<?php echo $applicantID; ?>" target="_blank">
                                                        <span class="text text-success">Download Admission Letter</span></a>
                                                    <br><br>
                                                </td>
                                                <?php 
                                                }
                                            }
                                            //Download 
                                            $upload = $db->getRows('upload', array('where'=>array('schoolID'=>$programmeMajorIDAdmitted),'order_by' => ' academicYearID ASC'));
                                            if(!empty($upload))
                                            {
                                                echo "<td>";
                                            foreach ($upload as $up) {
                                                $count++;
                                                $uploadID = $up['uploadID'];
                                                $academicYearID = $up['academicYearID'];
                                                $title = $up['title'];
                                                $schoolID = $up['schoolID'];
                                                $academicYearID = $up['academicYearID'];
                                                $url = $up['url'];
                                                ?>
                                                <a href="../upload_doc/<?php echo $url; ?>" target="_blank"><?php echo $title;?><i class="fa fa-download"></i></a>
                                            <br>
                                           <?php
                                        }
                                        echo "</td>";
                                        }else 
                                        {
                                            echo "No Document";
                                        }
                                            //end of downloads
                                        }
                                            /* } else {
                                            ?>
                                                <td>
                                                    <a href="printadmissionletter.php?action=getPDF&applicantID=<?php echo $applicantID; ?>" target="_blank">
                                                        <span class="text text-success">Download Admission Letter</span></a> 
                                                
                                         <a href="printfees.pdf?action=getPDF&applicantID=<?php echo $applicantID; ?>" target="_blank">
                                            <span class="text text-success">Download Univ.Fees Structure</span></a>
                                            <br><br>
                                            <a href="offer.pdf" target="_blank">
                                            <span class="text text-success">Download Univ.Instructions</span></a> -->
                                                </td>
                                                <?php
                                                //}
                                                //else
                                                //{
                                                ?>
                                                <!-- <td>
                                        We are waiting for NACTE to prove your names inorder to print Admission Letters
                                    </td> -->
                                        <?php
                                                //}
                                            }
                                        }
                                        /*else if($remarkID==3 && $tcu_final=="Multiple Admission")
                        {
                            echo"<td><a href='index.php?sz=confirm_applicant_tcu&formfour=$formfour'>Click to Confirm</a></td>";
                        }*/
                                        //else if($remarkID==3 && $nacte_status==1)
                                        //{
                                        ?>
                                        <!--  <td>
                                <a href="printadmissionletter.php?action=getPDF&applicantID=<?php //echo $applicantID;
                                                                                            ?>" target="_blank">
                                    <span class="text text-success">Download Admission Letter</span></a>
                                <br><br>
                                <a href="upload_doc/certificate_inst.pdf" target="_blank">
                                    <span class="text text-success">Download Certificate Instructions</span></a>
                                <br>
                                <a href="upload_doc/diploma_inst.pdf" target="_blank">
                                    <span class="text text-success">Download Diploma Instructions</span></a>
                                <br>
                                <a href="upload_doc/medical_inst.pdf" target="_blank">
                                    <span class="text text-success">Download Medical Course Instructions</span></a>
                            </td> -->
                                        <?php
                                        // }
                                        /* else
                        {
                            echo "<td>None</td>";
                        } */
                                        if ($remarkID == 6) {

                                        ?>
                                            <td><span class="text-danger"><?php echo $regNumber; ?></span></td>
                                            <br>
                                            <td><a href="printadmissionletterzu.php?action=getPDF&applicantID=<?php echo $applicantID; ?>" target="_blank">
                                                        <span class="text text-success">Download Admission Letter</span></a>
                                        </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                <?php
                    }
                }
                ?>
                </p>
                <p>
                    <?php
                    if (($remarkID == 3 && $publish_status == 1) || $remarkID == 6) {
                    } else {
                        $comments = $db->getRows("applicantremarks", array('where' => array('applicantID' => $applicantID, 'remarkID' => $remarkID, 'activeStatus' => 1), 'order_by' => 'remarkID'));
                        if (!empty($comments)) {
                            foreach ($comments as $comm) {
                                $appComments = $comm['comments'];
                            }
                            echo "Comments: " . $appComments;
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
                    } ?>
            <?php
            $today = date('Y-m-d');
            $admissionSetting = $db->getAdmissionSetting();
            if (!empty($admissionSetting)) {
                foreach ($admissionSetting as $admin) {
                    $academicYear = $admin['academicYear'];
                    $academicYearID = $admin['academicYearID'];
                    $admissionID = $admin['admissionID'];
                    $admissionName = $admin['admissionName'];
                    $admissionRound = $admin['admissionRound'];
                    $endDate = $admin['endDate'];
                }
            } else {
                $academicYear = "";
                $academicYearID = "";
                $admissionID = "";
                $admissionName = "";
                $admissionRound = "";
                $endDate = "";
            }

            /* $semester= $db->getRows('admission_setting',array(' order_by'=>' startDate ASC'));
    if(!empty($semester)) {
        foreach ($semester as $sm) {
            $academicYearID = $sm['academicYearID'];
            $startDate = $sm['startDate'];
            $endDate = $sm['endDate'];
            $admissionID = $sm['admissionID'];
        }
    } */

            if ($today <= $endDate) {
            ?>
                <?php if ($remarkID == 7 ||  $remarkID == 5) {
                    if ($admissionLevel == "UG") {
                ?>
                        <a href="index.php?sz=education_background"><span class="btn btn-primary align-left">Proceed Application</span></a>
                    <?php
                    } else {
                    ?>
                        <a href="index.php?sz=pg_education"><span class="btn btn-primary align-left">Proceed Application</span></a>
                <?php
                    }
                }
                ?>

                <?php
            } else {
                if ($remarkID == 1 || $remarkID == 5 || $remarkID == 2) {
                ?>
                    You can't make any changes now
            <?php
                }
            }
            ?>
            <br><br>
            </div>
        </div>
    </div>
    <?php
    $programID = $db->getData("programmemajor", "programmeID", "programmeMajorID", $programmeMajorID);
    $departmentID = $db->getData("programs", "departmentID", "programID", $programID);
    $schoolID = $db->getData("departments", "schoolID", "departmentID", $departmentID);
    $upload = $db->getRows('upload', array('order_by' => ' academicYearID ASC'));
    if ($remarkID == 6 || $remarkID == 3) {

    ?>

        <?php


       /*  if (!empty($upload)) {
        ?>
            <div class="row">
                <div class="pull-center">
                    <h4>Documents Downloads</h4>
                </div>
            </div>
            <div class="row">
                <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th width=5>No.</th>
                            <th width=80>Academic Year</th>
                            <th width=200>Title</th>
                            <th width=200>School Name</th>
                            <th width=20>Document</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($upload as $up) {
                            $count++;
                            $uploadID = $up['uploadID'];
                            $academicYearID = $up['academicYearID'];
                            $title = $up['title'];
                            $schoolID = $up['schoolID'];
                            $academicYearID = $up['academicYearID'];
                            $url = $up['url'];

                            $academicYear = $db->getRows('academicyears', array('where' => array('academicYearID' => $academicYearID), ' order_by' => ' academic_year ASC'));
                            foreach ($academicYear as $acy) {
                                $academicYear = $acy['academicYear'];
                            }

                        ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $academicYear; ?></td>
                                <td><?php echo $title; ?></td>
                                <td><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $schoolID); ?></td>
                                <td><a href="../upload_doc/<?php echo $url; ?>" target="_blank"><i class="fa fa-download"></i></a></td>

                            </tr>
                        <?php

                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        } */
        ?>

<?php }
} ?>
</div>