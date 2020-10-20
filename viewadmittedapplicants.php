<?php
$db = new DBHelper();
?>
<script type="text/javascript">
    $(document).ready(function() {
        var titleheader = $('#titleheader').text();
        $('#admit').dataTable({
            paging: false,
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
<!--<script>
    var th = document.getElementsByTagName('th')[0];
    th.innerHTML = '<span>' + th.innerHTML.split('').join('</span><span>') + '</span>';
  </script>-->

<!--<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
-->
<script type="text/javascript">
    $(document).ready(function() {
        $("#programmeID").change(function() {
            var id = $(this).val();
            var dataString = 'id=' + id;
            $.ajax({
                type: "POST",
                url: "ajax_programme_major.php",
                data: dataString,
                cache: false,
                success: function(html) {
                    $("#programmeMajorID").html(html);
                }
            });

        });

    });
</script>

<style type="text/css">
    .verticaltext {
        width: 1px;
        word-wrap: break-word;
        font-family: monospace
            /* this is just for good looks */
    }
</style>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>" . $_REQUEST['count'] . " of records has been saved in database</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
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
                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" id="programmeID" class="form-control chosen-select   " required="">
                    <?php
                    $adYear = $db->getAllProgrammes();
                    if (!empty($adYear)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($adYear as $year) {
                            $count++;
                            $programID = $year['programID'];
                            $programName = $year['programName'];
                    ?>
                            <option value="<?php echo $programID; ?>"><?php echo $programName; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-3">

                <label for="MiddleName">Major Name</label>
                <select name="programmeMajorID" id="programmeMajorID" class="form-control" required="">
                    <option value="">--Select Here--</option>
                    <?php
                    /*                               $adYear = $db->getPublishedProgramme();
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                   echo"<option value='all'>All Programmes</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $programMajor=$year['programmeMajor'];
                                $programmeMajorID=$year['programmeMajorID'];
                               */ ?>
                    <!--
                               <option value="<?php /*echo $programmeMajorID;*/ ?>"><?php /*echo $programMajor;*/ ?></option>
                               --><?php /*}}
           */ ?>
                </select>
            </div>
            <!-- <div class="col-lg-3">

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
        </div>

        <div class="col-lg-2">

            <label for="MiddleName">Admission Intake</label>
            <select name="admissionID" class="form-control" required="">
                <?php
                /*  $aitake = $db->getRows('admission_setting',array('order_by'=>'academicYearID ASC'));
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
            $programmeMajorID = $_POST['programmeMajorID'];
            $admissionID = $_POST['admissionID'];
            $programmeID = $_POST['programmeID'];
            $roundName = $_POST['roundName'];
            $applicationYearID = $db->getData("admission_setting", "academicYearID", "admissionID", $admissionID);

            if ($roundName == 'all')
                $roundoutput = "All Rounds";
            else
                $roundoutput = $roundName;

        ?>
            <div class="col-lg-12">
                <?php if ($programmeMajorID == 'all') { ?>
                    <h4><span id="titleheader">List of Admitted Applicants of <?php echo $db->getData("programs", "programName", "programID", $programmeID); ?>-<?php echo $db->getData("admission_setting", "admissionName", "admissionID", $admissionID); ?>-<?php echo $roundoutput; ?></span></h4>
                <?php } else {
                ?>
                    <h4><span id="titleheader">List of Admitted Applicants of <?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID); ?>-<?php echo $db->getData("admission_setting", "admissionName", "admissionID", $admissionID); ?>-<?php echo $roundoutput; ?></span></h4>
                <?php
                } ?>
            </div>
            <form name="register" id="register" method="post" action="action_unenroll.php">
                <table id="admit" class="display nowrap" cellspacing="0">

                    <thead>
                        <tr>
                            <th width="5">SNo</th>
                            <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                            <th>Name</span></th>
                            <th>Sex</th>
                            <th>Phone Number</th>
                            <th>Index Number</th>
                            <th>Ref.Number</th>
                            <th>TCU Status</th>
                            <!--<th>Action</th>-->
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($programmeMajorID == 'all') {
                            $applicantsData = $db->getAllAdmitted($programmeID, 3, 1, $applicationYearID, $admissionID, $roundName);
                        } else {
                            $applicantsData = $db->getAdmitted($programmeMajorID, 3, 1, $applicationYearID, $admissionID, $roundName);
                        }
                        if (!empty($applicantsData)) {
                            $i = 0;
                            $_SESSION['applicantID'] = array();
                            foreach ($applicantsData as $data) {
                                $i++;
                                $applicantID = $data['applicantID'];
                                $fname = $data['firstName'];
                                $mname = $data['middleName'];
                                $lname = $data['lastName'];
                                $gender = $data['gender'];
                                $refNumber = $data['refNumber'];
                                $phoneNumber = $data['phoneNumber'];
                                $userID = $data['userID'];
                                $nacte_status = $data['nacte_status'];
                                $tcu_status = $data['tcu_final'];
                                $tcu_message=$data['tcu_message'];
                                $name = "$fname $mname $lname";

                                echo "<tr><td>$i</td><td>";
                                if ($nacte_status == 0)
                                    echo "<input type='checkbox' class='checkbox_class' name='id[]' value='$applicantID'>";
                                else
                                    echo "NA";
                                echo "</td><td>$name</td>"
                                    . "<td>$gender</td><td>$phoneNumber</td><td>" . $db->getData("users", "userName", "userID", $userID) . "</td><td>$refNumber</td><td>$tcu_message</td>";
                        ?>
                                <!--                     <td><a data-toggle="modal" href="transferapplicant.php?id=<?php /*echo $applicantID;*/ ?>&programmeMajorID=<?php /*echo $programmeID;*/ ?>" data-target="#oModal" class="btn btn-link">Transfer</a></td>
--> <?php
                            }
                        }


    ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-6">

                    </div>
                    <input type="hidden" name="number_applicants" value="<?php echo $i; ?>">
                    <input type="hidden" name="programmeMajorID" value="<?php echo $programmeID; ?>">
                    <input type="hidden" name="choice" value="<?php echo $choice; ?>">

                    <div class="col-lg-3">
                        <input type="submit" name="doPublish" value="Publish Results" class="btn btn-success form-control">
                    </div>
                    <div class="col-lg-3">
                        <!--                 <input type="hidden" name="action_type" value="add"/>
--> <input type="submit" name="doUpdate" value="Un Enroll" class="btn btn-danger form-control">
                    </div>

                </div>
            </form>
        <?php
        }
        ?>

    </div>

</div>
<div class="modal fade" id="oModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <strong>Loading...</strong>
        </div>
    </div>
</div>