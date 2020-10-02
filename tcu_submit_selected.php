<script type="text/javascript">
    $(document).ready(function() {
        var titleheader = $('#titleheader').text();
        $('#selection_list').dataTable({
            paging: true,
            scrollX: true,
            dom: 'Blfrtip',
            "lengthMenu": [
                [10, 25, 50, 100, 200, 300, 400, 500, -1],
                [10, 25, 50, 100, 200, 300, 400, 500, "All"]
            ],
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
    /*$(document).ready(function () {
        var titleheader = $('#titleheader').text();
        var programmeID=$("#programmeID").val();
        var academicYearID=$("#academicYearID").val();
        var admissionID=$("#admissionID").val();

        $('#selection_list tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );

        $('#selection_list').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'data/submit_selected_tcu.php',
                        data:{programmeID:programmeID,academicYearID:academicYearID,admissionID:admissionID},
                        "serverSide" : true,
                        cache: false
                    },
                "scrollX":true,
                paging: true,
                dom: 'Blfrtip',

                buttons:[
                    {
                        //extend:'excel',
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                        }
                    },
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'print',
                        title: titleheader,
                        footer: false,
                        exportOptions: {
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                        },
                        orientation: 'landscape',
                    }

                ]
            });


        // Apply the search
        table.columns().every( function () {
            var that = this;

            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );

    });*/
</script>
<?php
$db = new DBHelper();

?>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=submit_selected_tcu' class='close' data-dismiss='alert'>&times;</a>
    <strong>" . $_REQUEST['count'] . " of records has been submitted in TCU</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=submit_selected_tcu' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>

    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Organization Name</label>
                <select name="sectorID" class="form-control chosen-select" required="">
                    <?php
                    $adYear = $db->getRows('sector', array('order_by' => 'sectorName ASC'));
                    if (!empty($adYear)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($adYear as $year) {
                            $count++;
                            $programName = $year['sectorName'];
                            $programID = $year['sectorID'];
                    ?>
                            <option value="<?php echo $programID; ?>"><?php echo $programName; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>

            <div class="col-lg-3">

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

            <div class="col-lg-3">
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
            <div class="col-lg-3">
                <label for=""></label>

                <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
            </div>
        </form>
    </div>

    <br><br>
    <div class="row">

        <?php
        if (isset($_POST['doSearch']) == "Search Records") {
            $academicYearID = $_POST['admissionYearID'];
            $programmeID = $_POST['sectorID'];
            $admissionID = $_POST['admissionID'];
            $academicYearID = $db->getData("admission_setting", "academicYearID", "admissionID", $admissionID);

        ?>
            <!-- <input type="hidden" id="programmeID" value="<?php /*echo $programmeID;*/ ?>">
            <input type="hidden" id="academicYearID" value="<?php /*echo $academicYearID;*/ ?>">
            <input type="hidden" id="admissionID" value="<?php /*echo $admissionID;*/ ?>">-->

            <div class="col-lg-12">
                <h4><span id="titleheader">List of Selected Applicants for <?php echo $db->getData("sector", "sectorName", "sectorID", $programmeID); ?>
                        <?php echo $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID); ?></span></h4>
            </div>
            <form name="register" id="register" method="post" action="action_submit_selected_tcu.php">
                <table id="selection_list" class="display nowrap" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5">SNo</th>
                            <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>NIDA ID</th>
                            <th>Form IV</th>
                            <th>Form V</th>
                            <th>Programmes</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Adm.Status</th>
                            <th>Prog.Admitted</th>
                            <th>Reason</th>
                            <th>Nationality</th>
                            <th>Impairment</th>
                            <th>Date of Birth</th>
                            <th>Other Form IV</th>
                            <th>Other Form VI</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        /*                $applicantsData=$db->getSelectedList($programmeID,$academicYearID,$admissionID);*/
                        $applicantsData = $db->getSubmitSelectedListTCU($programmeID, $academicYearID, $admissionID);
                        if (!empty($applicantsData)) {
                            $i = 0;
                            foreach ($applicantsData as $data) {
                                $i++;
                                $applicantID = $data['applicantID'];
                                $fname = $data['firstName'];
                                $mname = $data['middleName'];
                                $lname = $data['lastName'];
                                $gender = $data['gender'];
                                $dob = $data['dob'];
                                $disabiliyStatus = $data['disabilityStatus'];
                                $nationality = $data['citizenship'];
                                $phoneNumber = $data['phoneNumber'];
                                $email = $data['email'];
                                $entryQualification = $data['entryQualification'];
                                $tcu_status = $data['tcu_status'];
                                $tcu_final = $data['tcu_final'];

                                $nationalID = $db->getRows("applicant_identification", array('where' => array('applicantID' => $applicantID)));
                                if (!empty($nationalID)) {
                                    foreach ($nationalID as $nid) {
                                        $nida = $nid['nationalID'];
                                    }
                                } else {
                                    $nida = "";
                                }

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

                                $name = "$fname $mname $lname";
                        ?>
                        <?php
                                if ($tcu_status == 41) {
                                    $box = "NA";
                                } else {
                                    $box = "<input type='checkbox' class='checkbox_class' name='applicantID[]' value='$applicantID'>";
                                }


                                /*if($tcu_status==2 || $tcu_status==21)
                        {
                            $box="NA";
                        }
                        else if (filter_var($email, FILTER_VALIDATE_EMAIL))
                            $box="<input type='checkbox' class='checkbox_class' name='applicantID[]' value='$applicantID'>";
                        else
                            $box="<a href='action_submit_selected_applicant_tcu.php?action_type=submit_app&applicantID=$applicantID'>Add</a>";*/

                                echo "<tr><td>$i</td>
                           <td>$box</td>
                           <td>$name</td>
                           <td>$nida</td>
                           <td>$gender</td>
                           <td>" . $formfour[0] . "</td>
                           <td>" . $findexNumber . "</td>
                           <td>" . "$firstChoice,$secondChoice" . "</td>
                           <td>$tcu_final</td>
                           <td>$phoneNumber</td>
                           <td>$email</td>
                           <td>Provisional Admission</td>
                           <td>$programmeCode</td>
                           <td>Eligible</td>
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
                <?php
                if ($programmeID == 1) {
                ?>
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add_app" />
                            <input type="submit" name="doAdmit" value="Submit Applicants" class="btn btn-success form-control">
                        </div>
                    </div>
            <?php
                }
            }
            ?>

    </div>

</div>