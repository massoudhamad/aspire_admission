<script type="text/javascript">
    $(document).ready(function() {
        var titleheader = $('#titleheader').text();
        var programmeID = $("#programmeID").val();
        var roundName = $("#roundName").val();
        var admissionID = $("#admissionID").val();
        var remarkID= $("#remarkID").val();
        var sorted = $("#sorted").val();
        $('#selection_list').DataTable({
            ajax: {
                type: 'GET',
                url: 'data/selection_list_direct.php',
                data: {
                    programmeID: programmeID,
                    admissionID: admissionID,
                    roundName:roundName,
                    remarkID:remarkID
                },
                "serverSide": true,
                cache: false
            },
            "scrollX": true,
            paging: true,
            dom: 'Blfrtip',

            columnDefs: [{
                orderData: [3, sorted],
                targets: [3]
            }],
            "order": [
                [3, "desc"]
            ],

            buttons: [{
                    //extend:'excel',
                    extend: 'excelHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: titleheader,
                    customize: function(csv) {
                        return titleheader + "\n" + csv + "\n";
                    }
                },
                {
                    extend: 'print',
                    title: titleheader,
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    },
                    orientation: 'landscape',
                }

            ]
        });
    });
</script>
<?php
$db = new DBHelper();
?>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>
    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" class="form-control chosen-select" required="">
                    <?php
                    $adYear = $db->getRows('programs', array('order_by' => 'programName ASC'));
                    if (!empty($adYear)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($adYear as $year) {
                            $count++;
                            $programName = $year['programName'];
                            $programID = $year['programID'];
                    ?>
                            <option value="<?php echo $programID; ?>"><?php echo $programName; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>
            <!--  <div class="col-lg-3">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required="">
                              <?php
                                /* $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID']; */
                                ?>
                               <option value="<?php //echo $academic_year_id;
                                                ?>"><?php // echo $academic_year;
                                                    ?></option>
                               <?php //}}
                                ?>
                           </select>
</div> -->

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
                            $admissionInTakeID = $ait['admissionInTakeID'];
                            $admissionName = $ait['admissionName'];
                    ?>
                            <option value="<?php echo $admissionID; ?>">
                                <?php echo $admissionName; ?></option>
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
                <label for="MiddleName">Remarks</label>
                <select name="remarkID" class="form-control" required="">
                    <?php
                    $remark = $db->getRows('remarks', array('order_by' => 'remarkID ASC'));
                    if (!empty($remark)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($remark as $rmk) {
                            $remarkID = $rmk['remarkID'];
                            $remark = $rmk['remark'];
                    ?>
                            <option value="<?php echo $remarkID; ?>">
                                <?php echo $remark; ?></option>
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
            $programmeID = $_POST['programmeID'];
            $admissionID = $_POST['admissionID'];
            $admissionInTakeID = $db->getData('admission_setting', 'admissionInTakeID', 'admissionID', $admissionID);
            $roundName = $_POST['roundName'];
            $remarkID=$_POST['remarkID'];

            $studyLevelID = $db->getData("programs", "studyLevelID", "programID", $programmeID);
            if ($studyLevelID == 1 || $studyLevelID == 2)
                $value = "10";
            else
                $value = "7";

        ?>
            <input type="hidden" id="sorted" value="<?php echo $value; ?>">
            <input type="hidden" id="programmeID" value="<?php echo $programmeID; ?>">
            <input type="hidden" id="roundName" value="<?php echo $roundName; ?>">
            <input type="hidden" id="admissionID" value="<?php echo $admissionID; ?>">
            <input type="hidden" id="remarkID" value="<?php echo $remarkID;?>">

            <div class="col-lg-12">
                <h4><span id="titleheader">List of Approved Applicants for <?php echo $db->getData("programs", "programName", "programID", $programmeID); ?>
                        <?php echo $db->getData('admission_intake', "admissionInTake", "admissionInTakeID", $admissionInTakeID); ?>
                        <?php echo $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID); ?>-Direct Entry</span></h4>
            </div>
            <table id="selection_list" class="display nowrap" cellspacing="0">
                <thead>
                    <tr>
                        <th>SNo</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Combination</th>
                        <th>Form IV</th>
                        <th>Ordinary Subjects</th>
                        <th>Req.Subjects</th>
                        <th>Points</th>
                        <th>Form V</th>
                        <th>Advanced Subjects</th>
                        <th>Points</th>
                        <th>Sec. Choice</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>

    </div>

</div>