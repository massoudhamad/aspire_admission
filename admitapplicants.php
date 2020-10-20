<?php
$db = new DBHelper();
?>
<script type="text/javascript">
    $(document).ready(function() {
        var titleheader = $('#titleheader').text();
        $('#admit').dataTable({
            paging: true,
            scrollX: true,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'csvHtml5',
                    title: titleheader,
                    customize: function(csv) {
                        return titleheader + "\n" + csv + "\n";
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6]
                    }

                }

            ]
        });
    });
</script>

<div class="container">
    <h4>View List of Applicants</h4>
    <hr>


    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: We are unable to save your data in TCU Database.TCU Status: " . $_SESSION['output'] . "</strong>
                    </div>";
                } else {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Data saved successfully</strong>.
                    </div>";
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>TCU Status: " . $_SESSION['output'] . "</strong>.
                    </div>";
                }
            }
            /* if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$_REQUEST['count']." of records has been saved in database</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="unsucc")
                {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
                }
            } */
            ?>


        </div>
    </div>


    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" class="form-control chosen-select" required="">
                    <?php
                    $adYear = $db->getPublishedProgramme();
                    if (!empty($adYear)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($adYear as $year) {
                            $count++;
                            $programMajor = $year['programmeMajor'];
                            $programmeMajorID = $year['programmeMajorID'];
                    ?>
                            <option value="<?php echo $programmeMajorID; ?>"><?php echo $programMajor; ?></option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="col-lg-2">

                <label for="MiddleName">Programme Choice</label>
                <select name="choice" class="form-control" required="">
                    <option value="">Select Choice</option>
                    <option value="1">First Choice</option>
                    <option value="2">Second Choice</option>
                </select>
            </div>

            <div class="col-lg-2">

                <label for="MiddleName">Entry Level</label>
                <select name="entry" class="form-control" required="">
                    <option value="">Select Here</option>
                    <option value="0">Direct</option>
                    <option value="1">Equivalent</option>
                </select>
            </div>

            <div class="col-lg-2">

                <label for="MiddleName">Admission Intake</label>
                <select name="admissionID" class="form-control" required="">
                    <?php
                    $aitake = $db->getRows('admission_setting', array('order_by' => 'academicYearID ASC'));
                    if (!empty($aitake)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($aitake as $ait) {
                            $count++;
                            $admissionID = $ait['admissionID'];
                            $admissionName = $ait['admissionName'];
                    ?>
                            <option value="<?php echo $admissionID; ?>"><?php echo $admissionName; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-2">
                <label for="MiddleName">Admission Round</label>
                <select name="roundName" class="form-control" required="">
                    <?php
                    $round = $db->getRows('round', array('order_by' => 'roundID ASC'));
                    if (!empty($round)) {
                        echo "<option value=''>Please Select Here</option>";
                        echo "<option value='all'>All Round</option>";
                        $count = 0;
                        foreach ($round as $rnd) {
                            $count++;
                            $roundName = $rnd['roundName'];
                    ?>
                            <option value="<?php echo $roundName; ?>"><?php echo $roundName; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>


            <!-- <div class="col-lg-2">

            <label for="MiddleName">Admission Year</label>
            <select name="admissionYearID" class="form-control" required="">
                <?php
                /*  $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear ASC'));
                if(!empty($adYear)){
                    echo"<option value=''>Please Select Here</option>";
                    $count = 0; foreach($adYear as $year){ $count++;
                        $academic_year=$year['academicYear'];
                        $academic_year_id=$year['academicYearID'];
                        ?>
                        <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                    <?php }} */
                ?>
            </select>
        </div> -->

            <!--  <div class="col-lg-2">

            <label for="MiddleName">Admission Intake</label>
            <select name="admissionID" class="form-control" required="">
                <?php
                /* $aitake = $db->getRows('admission_setting',array('order_by'=>'academicYearID ASC'));
                if(!empty($aitake)){
                    echo"<option value=''>Please Select Here</option>";
                    $count = 0; foreach($aitake as $ait){ $count++;
                        $admissionID=$ait['admissionID'];
                        $admissionInTakeID=$ait['admissionInTakeID'];
                        ?>
                        <option value="<?php echo $admissionID;?>"><?php echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);?></option>
                    <?php }} */
                ?>
            </select>
        </div> -->

    </div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-3">
            <label for=""></label>
            <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
        </div>
    </div>
    </form>

    <br><br>
    <div class="row">
        <?php
        if (isset($_POST['doSearch']) == "Search Records") {
            $programmeID = $_POST['programmeID'];
            $entry = $_POST['entry'];
            $choice = $_POST['choice'];
            $admissionID = $_POST['admissionID'];
            $roundName = $_POST['roundName'];
            $applicationYearID = $db->getData("admission_setting", "academicYearID", "admissionID", $admissionID);

            //$applicationYearID = $_POST['admissionYearID'];
            if ($choice == 1)
                $fchoice = "First Choice";
            else
                $fchoice = "Second Choice";
            if ($entry == 1)
                $entrychoice = "Equivalent Entry";
            else
                $entrychoice = "Direct Entry";
        ?>
            <div class="col-lg-12">
                <h4><span id="titleheader">List of Approved Applicants for <?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeID); ?>-
                        <?php echo $fchoice; ?>-<?php echo $entrychoice; ?></span></h4>
            </div>
            <form name="register" id="register" method="post" action="action_admit.php">
                <table id="admit" class="display nowrap" cellspacing="0">

                    <thead>
                        <tr>
                            <th width="5">SNo</th>
                            <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                            <th>Name</th>
                            <th>Sex</th>
                            <th>Form IV</th>
                            <th>Form VI</th>
                            <th>Programmes</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Nationality</th>
                            <th>Impairment</th>
                            <th>Date of Birth</th>
                            <th>Other Form IV</th>
                            <th>Other Form VI</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $applicantsData = $db->getApproved($programmeID, $choice, 2, $entry, $applicationYearID, $admissionID,$roundName);
                        if (!empty($applicantsData)) {
                            $count = 0;
                            foreach ($applicantsData as $data) {
                                $count++;
                                $applicantID = $data['applicantID'];
                                $fname = $data['firstName'];
                                $mname = $data['middleName'];
                                $lname = $data['lastName'];
                                $gender = $data['gender'];
                                $phoneNumber = $data['phoneNumber'];
                                $email = $data['email'];
                                $dob = $data['dob'];
                                $disabiliyStatus = $data['disabilityStatus'];
                                $nationality = $data['citizenship'];
                                $entryQualification = $data['entryQualification'];
                                $name = "$fname $mname $lname";

                                if ($entryQualification == 0)
                                    $category = "A";
                                else
                                    $category = "D";

                                if ($disabiliyStatus == "Yes") {
                                    $disability = $db->getRows("disability", array('where' => array('applicantID' => $applicantID), 'order_by applicantID ASC'));
                                    if (!empty($equivalentresults)) {
                                        foreach ($disability as $disab) {
                                            $dname = $disab['disabilityName'];
                                        }
                                    }
                                } else {
                                    $dname = "None";
                                }


                                $oindexumber = $db->getIndexNumber($applicantID, "Ordinary");
                                if (!empty($oindexumber)) {
                                    $formfour = array();
                                    foreach ($oindexumber as $fnumber) {
                                        $indexNumber = $fnumber['indexNumber'];
                                        $formfour[] = $indexNumber;
                                    }
                                } else {
                                    $formfour[] = "";
                                }



                                $aindexumber = $db->getIndexNumber($applicantID, "Advance");
                                $formsix = array();
                                if (!empty($aindexumber)) {
                                    $formsix = array();
                                    foreach ($aindexumber as $fsixnumber) {
                                        $indexNumber = $fsixnumber['indexNumber'];
                                        $formsix[] = $indexNumber;
                                    }
                                } else {
                                    $formsix[] = "";
                                }

                                $programmeChoice = $db->getAdmittedProgramme($applicantID, 1);
                                if (!empty($programmeChoice)) {
                                    foreach ($programmeChoice as $pChoice) {
                                        $programmeCode = $pChoice['programCode'];
                                        $programmeName = $pChoice['programName'];
                                    }
                                }



                                $programmeFirstChoice = $db->getProgramme($applicantID, 1);
                                if (!empty($programmeFirstChoice)) {
                                    foreach ($programmeFirstChoice as $pChoice) {
                                        $firstChoice = $pChoice['programCode'];
                                    }
                                } else {
                                    $firstChoice = "";
                                }

                                $programmeChoice = $db->getProgramme($applicantID, 2);
                                if (!empty($programmeChoice)) {
                                    foreach ($programmeChoice as $pChoice) {
                                        $secondChoice = $pChoice['programCode'];
                                    }
                                } else {
                                    $secondChoice = "";
                                }

                                $equivalentresults = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Equivalent'), 'order_by applicantID ASC'));
                                if (!empty($equivalentresults)) {
                                    foreach ($equivalentresults as $matokeo) {
                                        $eIndexNumber = $matokeo['indexNumber'];
                                        $avn_number = $matokeo['avn_number'];
                                    }
                                } else {
                                    $eIndexNumber = "";
                                    $avn_number = "";
                                }


                                if ($category == "A")
                                    $findexNumber = $formsix[0];
                                else {
                                    if ($avn_number == "")
                                        $findexNumber = $eIndexNumber;
                                    else
                                        $findexNumber = $avn_number;
                                }


                                $four = array();
                                for ($x = 1; $x < count($formfour); $x++) {
                                    $four[] = $formfour[$x];
                                }

                                $six = array();
                                for ($x = 1; $x < count($formsix); $x++) {
                                    $six[] = $formsix[$x];
                                }
                                $fourfour = implode(",", $four);
                                $sixsix = implode(",", $six);
                        ?>
                        <?php
                                echo "<tr><td>$count</td>
                           <td><input type='checkbox' class='checkbox_class' name='applicantID[]' value='$applicantID'></td>
                           <td>$name</td>
                           <td>$gender</td>
                           <td>" . $formfour[0] . "</td>
                           <td>" . $findexNumber . "</td>
                           <td>" . "$firstChoice,$secondChoice" . "</td>
                           <td>$phoneNumber</td>
                           <td>$email</td>
                           <td>$nationality</td>
                          <td>$dname</td>
                          <td>$dob</td>
                          <td>$fourfour</td>
                          <td>$sixsix</td>
                           </tr>";
                            }
                        }


                        ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-6"></div>
                    <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                    <input type="hidden" name="programmeMajorID" value="<?php echo $programmeID; ?>">
                    <input type="hidden" name="choice" value="<?php echo $choice; ?>">
                    <div class="col-lg-3">
                        <input type="hidden" name="action_type" value="add" />
                        <input type="submit" name="doAdmit" value="Admit Applicants" class="btn btn-success form-control">
                    </div>
                    <!--<div class="col-lg-3">
                 <input type="hidden" name="action_type" value="edit"/>
                <input type="submit" name="doReject" value="Reject Applicants" class="btn btn-danger form-control">
            </div>-->
                </div>
            </form>
        <?php
        }
        ?>

    </div>

</div>