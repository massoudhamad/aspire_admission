<?php $db = new DBHelper();
$applicantID = $_REQUEST['applicantID'];
?>
<div class="row">
    <div class="col-lg-12">
        <div class="well">
            <?php
            $applicantsData = $db->getRows('applicants', array('where' => array('applicantID' => $applicantID), 'order_by' => 'applicantID ASC'));
            if (!empty($applicantsData)) {
                foreach ($applicantsData as $apps) {

                    $gender = $apps['gender'];
                    $fname = $apps['firstName'];
                    $mname = $apps['middleName'];
                    $lname = $apps['lastName'];
                    $refNumber = $apps['refNumber'];
                    if ($gender == "Male")
                        $sex = "Mr";
                    else
                        $sex = "Ms";
                    $name = "$sex $fname  $mname $lname";
                }
            }
            ?>
            <h3>Approval of Application</h3>
            <p class="text-justify lead">To approve application for <b><?php echo $name; ?></b>, Ref.Number <b><?php echo $refNumber; ?></b>, Please review the application details provided bellow.
                Once you are satisfied that the application details are correct, please fill in the application fee receipt number issued and click the Approve Button.
            </p>
        </div>
    </div>
</div>
<!-- Education Background-->
<div class="row">
    <div class="col-lg-12">
        <fieldset>
            <div class="well">
                <legend>Academic Background</legend>

                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        $equivalentresults = $db->getRows("academic_background", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                        if (!empty($equivalentresults)) {
                        ?>

                            <div class="col-lg-12">
                                <h3> List of Academic Background Results</h3>

                                <table class="table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Programme Name</th>
                                            <th>Institution Name</th>
                                            <th>Start Year</th>
                                            <th>End Year</th>
                                            <th>CGPA/Class/Division</th>
                                            <th>Quailification</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($equivalentresults as $matokeo) {
                                        ?>
                                            <?php
                                            $academicID = $matokeo['academicID'];
                                            $schoolName = $matokeo['institutionName'];
                                            $startYear = $matokeo['startYear'];
                                            $endYear = $matokeo['endYear'];
                                            $pname = $matokeo['programmeName'];
                                            $gradeType = $matokeo['gpa'];
                                            $qualificationID = $matokeo['qualificationID'];
                                            echo "<tr><td>$pname</td><td>$schoolName</td><td>$startYear</td><td>$endYear</td><td>$gradeType</td>
                                                <td>" . $db->getData("qualificationtype", "qualificationName", "qualificationTypeID", $qualificationID) . "</td>"; ?>
                                            <?php
                                            echo "</tr>"
                                            ?>

                                        <?php

                                        }
                                        ?>
                                    </tbody>
                                </table>


                            </div>

                        <?php
                        } else {
                        ?>
                            <h3><span style="color: red;">No Results Found</span> </h3>
                        <?php } ?>
        </fieldset>
    </div>

</div>
<!-- Program Choice-->
<div class="row">
    <div class="col-lg-12">
        <div class="well">
            <fieldset>
                <legend>Study Programme</legend>
                <?php
                $programmeChoice = $db->getRows("applicantapplication", array('where' => array('applicantID' => $applicantID, 'choice' => 1), 'order_by applicantID ASC'));
                if (!empty($programmeChoice)) {
                    foreach ($programmeChoice as $pChoice) {
                        $applicantApplicationIDFirst = $pChoice['applicantApplicationID'];
                        $firstChoice = $pChoice['programmeMajorID'];
                    }
                }

                $programmeChoice2 = $db->getRows("applicantapplication", array('where' => array('applicantID' => $applicantID, 'choice' => 2), 'order_by applicantID ASC'));
                if (!empty($programmeChoice2)) {
                    foreach ($programmeChoice2 as $pChoice2) {
                        $applicantApplicationIDSecond = $pChoice2['applicantApplicationID'];
                        $secondChoice = $pChoice2['programmeMajorID'];
                    }
                }
                ?>
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




            </fieldset>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="well">
            <fieldset>
                <legend>Working Experience</legend>

                <?php
                $wexprience = $db->getRows("working_experience", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                if (!empty($wexprience)) {
                ?>

                    <div class="col-lg-12">
                        <h3> List of Working Experince</h3>

                        <table class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Position/Title</th>
                                    <th>Employer Name</th>
                                    <th>Address</th>
                                    <th>Start Year</th>
                                    <th>End Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($wexprience as $exp) {
                                    $workID = $exp['workID'];
                                    $employerName = $exp['employerName'];
                                    $startYear = $exp['startYear'];
                                    $endYear = $exp['endYear'];
                                    $pname = $exp['positionName'];
                                    $address = $exp['employerAddress'];
                                    echo "<tr><td>$pname</td><td>$employerName</td><td>$address</td><td>$startYear</td><td>$endYear</td>";
                                ?>
                                <?php
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>

                <?php
                } else {
                ?>
                    <h3><span style="color: red;">No Working Found</span> </h3>
                <?php } ?>
            </fieldset>

        </div>

    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="well">
            <fieldset>
                <legend>Refrees</legend>
                <?php
                $referees = $db->getRows("referees", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                if (!empty($referees)) {
                ?>
                    <h3> List of Referees</h3>

                    <table class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Address</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($referees as $exp) {
                                $refereeID = $exp['refereeID'];
                                $fullName = $exp['fullName'];
                                $email = $exp['email'];
                                $pname = $exp['position'];
                                $address = $exp['address'];
                                $pnumber = $exp['phoneNumber'];
                                echo "<tr><td>$fullName</td><td>$pname</td><td>$address</td><td>$pnumber</td><td>$email</td>";
                            ?>

                            <?php
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else {
                ?>
                    <h3><span style="color: red;">No Refrees Found</span> </h3>
                <?php } ?>
            </fieldset>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php
        $applicantsData = $db->getRows('applicants', array('where' => array('applicantID' => $applicantID), 'order_by' => 'applicantID ASC'));
        if (!empty($applicantsData)) {
            foreach ($applicantsData as $apps) {
                $applicantID = $apps['applicantID'];
                $fname = $apps['firstName'];
                $mname = $apps['middleName'];
                $lname = $apps['lastName'];
                $oname = $apps['otherNames'];
                $gender = $apps['gender'];
                $pobirth = $apps['placeOfBirth'];
                $mstatus = $apps['maritalStatus'];
                $citizenship = $apps['citizenship'];
                $dob = $apps['dateOfBirth'];
                $paddress = $apps['physicalAddress'];
                $pnumber = $apps['phoneNumber'];
                $email = $apps['email'];
                $nkin = $apps['nextOfKinName'];
                $nphone = $apps['nextOfKinPhoneNumber'];
                $naddress = $apps['nextOfKinAddress'];
                $nrelation = $apps['relationship'];
                $dstatus = $apps['disabilityStatus'];
                $dname = $apps['disabilityName'];
                $ddescription = $apps['disabilityDescription'];
                $empStatus = $apps['employmentStatus'];
                $sponsor = $apps['sponsor'];
        ?>


                <div class="well">
                    <fieldset>
                        <legend>Personal Information</legend>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="FirstName">First Name</label>
                                <input type="text" name="fname" value="<?php echo $fname; ?>" class="form-control" required="" readonly="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="MiddleName">Middle Name</label>
                                <input type="text" name="mname" value="<?php echo $mname; ?>" class="form-control" readonly="" />
                            </div>
                            <div class="col-lg-3">
                                <label for="LastName">Last Name</label>
                                <input type="text" name="lname" value="<?php echo $lname; ?>" class="form-control" required="" readonly="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="LastName">Other Names</label>
                                <input type="text" name="oname" value="<?php echo $oname; ?>" class="form-control" readonly="" />
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="Geder">Gender</label>
                                <input type="text" name="gender" value="<?php echo $gender; ?>" class="form-control" disabled="" />
                            </div>
                            <div class="col-lg-3">
                                <label for="Date of Birth">Date of Birth</label>

                                <input type="date" id="pickyDate" name="dob" value="<?php echo $dob; ?>" class="form-control datepicker" required="" disabled="" />

                            </div>

                            <div class="col-lg-3">
                                <label for="Physical Address">Place of Birth</label>
                                <input type="text" name="placeOfBirth" value="<?php echo $pobirth; ?>" class="form-control" required="" readonly="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="Geder">Marital Status</label>
                                <select name="mstatus" class="form-control" required="" disabled="">

                                    <?php
                                    if ($mstatus == "") {
                                    ?><option value="">Select Here</option>
                                    <?php } else {
                                    ?>
                                        <option value="<?php echo $mstatus; ?>" selected><?php echo $mstatus; ?></option>
                                    <?php } ?>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Windowed">Windowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="Physical Address">Nationality</label>

                                <input type="text" name="citizenship" value="<?php echo $citizenship; ?>" class="form-control" required="" disabled="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="Physical Address">Physical Address</label>
                                <input type="text" name="address" value="<?php echo $paddress; ?>" class="form-control" required="" disabled="" />
                            </div>
                            <div class="col-lg-3">
                                <label for="Email">Email</label>
                                <input type="text" name="appemail" value="<?php echo $email; ?>" class="form-control" disabled="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="Phone">Phone Number</label>
                                <input type="text" name="phoneNumber" value="<?php echo $pnumber; ?>" class="form-control" required="" disabled="">
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-lg-3">
                                <label for="Geder">Do you have any disability?</label>
                                <select name="disability" id="disability" class="form-control" required="" disabled="">
                                    <?php
                                    if ($dstatus == "") {
                                    ?>
                                        <option value="" selected="" disabled="">Select Here</option>
                                        <option value="ndio">Yes</option>
                                        <option value="hapana">No</option>
                                    <?php
                                    } else if ($dstatus == 'No') {
                                    ?>
                                        <option value="ndio">Yes</option>
                                        <option value="hapana" selected="">No</option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="ndio" selected="">Yes</option>
                                        <option value="hapana">No</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php
                            if ($dstatus == "Yes") {
                                $disabilityData = $db->getRows("disability", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                                foreach ($disabilityData as $sp) {
                                    $disabilityName = $sp['disabilityName'];
                                    $disabilityDescription = $sp['disabilityDescription'];
                                }
                            }
                            ?>
                            <div class="ndio">
                                <div class="col-lg-3">
                                    <label for="FirstName">Disability Name</label>
                                    <input type="text" name="dname" value="<?php echo $disabilityName; ?>" class="form-control" required="" disabled="" />
                                </div>

                                <div class="col-lg-6">
                                    <label for="MiddleName">Disability Description</label>
                                    <input type="text" name="ddescription" value="<?php echo $disabilityDescription; ?>" class="form-control" required="" disabled="" />
                                </div>

                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Employment Information</legend>

                        <div class="row">


                            <div class="col-lg-3">
                                <label for="Geder">Are You Employed?</label>
                                <select name="employed" id="employed" class="form-control" required="" disabled="">
                                    <?php
                                    if ($empStatus == "") {
                                    ?>
                                        <option value="" selected="" disabled="">Select Here</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    <?php
                                    } else if ($empStatus == 'no') {
                                    ?>
                                        <option value="yes">Yes</option>
                                        <option value="no" selected="">No</option>
                                    <?php
                                    } else {
                                    ?>
                                        <option value="yes" selected="">Yes</option>
                                        <option value="no">No</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php
                            if ($empStatus == "yes") {
                                $sponsorData = $db->getRows("employmentstatus", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                                foreach ($sponsorData as $sp) {
                                    $employer = $sp['employer'];
                                    $placework = $sp['placeOfWork'];
                                    $designation = $sp['designation'];
                                }
                            }
                            ?>
                            <div class="yes">
                                <div class="col-lg-3">
                                    <label for="FirstName">Name of Employer</label>
                                    <input type="text" name="employer" value="<?php echo $employer; ?>" class="form-control" required="" disabled="" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="MiddleName">Address/Place of Work</label>
                                    <input type="text" name="placework" value="<?php echo $placework; ?>" class="form-control" required="" disabled="" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="LastName">Designation</label>
                                    <input type="text" name="designation" value="<?php echo $designation; ?>" class="form-control" required="" disabled="" />
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Emergency Information</legend>

                        <div class="row">
                            <div class="col-lg-3">
                                <label for="FirstName">Name of Next of Kin</label>
                                <input type="text" name="nextName" value="<?php echo $nkin; ?>" class="form-control" required="" disabled="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="MiddleName">Address of Next of Kin </label>
                                <input type="text" name="nextAddress" value="<?php echo $naddress; ?>" class="form-control" required="" disabled="" />
                            </div>
                            <div class="col-lg-3">
                                <label for="LastName">Phone Number</label>
                                <input type="text" name="nextPhoneNumber" value="<?php echo $nphone; ?>" class="form-control" required="" disabled="" />
                            </div>

                            <div class="col-lg-3">
                                <label for="Geder">Relationship</label>
                                <input type="text" name="relationship" class="form-control" value="<?php echo $nrelation; ?>" required="" disabled="" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Sponsorship Information</legend>

                        <div class="row">
                            <div class="col-lg-3">
                                <label for="gender">Sponsor</label>
                                <select name="sponsor" id="sponsor" class="form-control" required="" disabled="">
                                    <?php
                                    if ($sponsor == "") {
                                    ?>
                                        <option value="">Select Here</option>
                                    <?php } else {
                                    ?>
                                        <option value="<?php echo $sponsor; ?>"><?php echo $sponsor; ?></option>
                                    <?php
                                    }
                                    ?>

                                    <option value="Self">Self Financed</option>
                                    <option value="ZHELB">ZHEB</option>
                                    <option value="HESLB">HESLB</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <?php
                            if ($sponsor == "others") {
                                $sponsorData = $db->getRows("sponsor", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                                foreach ($sponsorData as $sp) {
                                    $sponsorname = $sp['sponsorName'];
                                    $sponsoraddress = $sp['sponsorAddress'];
                                    $sponsorphone = $sp['sponsorPhoneNumber'];
                                }
                            }
                            ?>
                            <div class="others">
                                <div class="col-lg-3">
                                    <label for="FirstName">Sponsor's Full Name</label>
                                    <input type="text" name="sponsorname" value="<?php echo $sponsorname; ?>" disabled="" class="form-control" required="" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="MiddleName">Address</label>
                                    <input type="text" name="sponsoraddress" disabled="" value="<?php echo $sponsoraddress; ?>" class="form-control" required="" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="MiddleName">Phone Number</label>
                                    <input type="text" name="sponsorphonenumber" disabled="" value="<?php echo $sponsorphone; ?>" class="form-control" required="" />
                                </div>
                            </div>

                        </div>
                    </fieldset>
                </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="well">
            <fieldset>
                <legend>List of Attached Documents</legend>
                <?php
                $document = $db->getRows("attachment", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                if (!empty($document)) {
                ?>
                        <table class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Document Title</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($document as $doc) {
                                    $attachmentID = $doc['attachmentID'];
                                    $documentType = $doc['documentType'];
                                    $fileUrl = $doc['fileUrl'];
                                    echo "<tr><td>$documentType</td>";
                                ?>
                                    <td><a href="upload_doc/<?php echo $fileUrl; ?>" target="_blank">Download</a>
                                    </td>
                                <?php
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                   
                <?php
                } else {
                ?>
                    <h3><span style="color: red;">No Document Found</span> </h3>
                <?php } ?>
            </fieldset>
        </div>
    </div></div>

    <!-- <div class="row">
        <div class="col-lg-12">
            <div class="well">
                <fieldset>
                    <legend>Comments from Head of Departments</legend>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="studyLevel">Comments</label>
                            <textarea name="comments" class="form-control" required="" disabled></textarea>
                        </div>
                    </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="well">
                <fieldset>
                    <legend>Comments from Dean of Faculty/School</legend>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="studyLevel">Comments</label>
                            <textarea name="comments" class="form-control" required="" disabled></textarea>
                        </div>
                    </div>
            </div>
        </div>
    </div> -->

    <div class="row"><br><br></div>
    <div class="row">
        <div class="col-lg-12">
            <form name="" method="post" id="register" action="action_approve.php">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="qualificationType">Applicant Remarks</label>
                            <select name="remarksID" class="form-control" required="">
                                <?php
                                $applicantRemarksID = $db->getData("applicants", "applicantsRemarksID", "applicantID", $applicantID);
                                ?>
                                <option value="<?php echo $applicantRemarksID; ?>" selected="selected">
                                    <?php echo $db->getData("remarks", "remark", "remarkID", $applicantRemarksID); ?></option>
                                <?php
                                $remarks = $db->getRemarks();
                                if (!empty($remarks)) {
                                    $count = 0;
                                    foreach ($remarks as $rmk) {
                                        $count++;
                                        $remark = $rmk['remark'];
                                        $remarkID = $rmk['remarkID'];
                                ?>
                                        <option value="<?php echo $remarkID; ?>"><?php echo $remark; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <!--                        <div class="col-lg-4">
                        <div class="form-group">
                          <label for="studyLevel">Receipt Number</label>
                          <input type="number" name="receiptNumber" value="" class="form-control" required="">
                        </div></div>-->
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="studyLevel">Comments</label>
                            <textarea name="comments" class="form-control" required=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4"></div>
                <div class="col-lg-3">
                    <?php
                    if (!empty($equiSubjects))
                        $category = "D";
                    else
                        $category = "A";
                    ?>
                    <input type="hidden" name="action_type" value="add" />
                    <input type="hidden" name="applicantID" value="<?php echo $applicantID; ?>">
                    <input type="hidden" name="formfour" value="<?php echo $indexNumber; ?>">
                    <input type="hidden" name="formsix" value="<?php echo $findexNumber; ?>">
                    <input type="hidden" name="appcategory" value="<?php echo $category; ?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                    </a>
                </div>

            </form>
        </div>
    </div>
   

<?php }
        } ?>
