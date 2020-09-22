<?php $db = new DBHelper(); ?>
<div class="container">
    <div class="content">
        <?php
        if (!empty($_REQUEST['msg'])) {
            if ($_REQUEST['msg'] == "succ") {
                echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Admission Setting data has been inserted successfully</strong>.
</div>";
            } else if ($_REQUEST['msg'] == "deleted") {
                echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Admission Setting Data has been delete successfully</strong>.
</div>";
            }
        }
        ?>
        <div id="semestercourse">
            <h3>Admission Letter Setting</h3>
            <div class="row">
                <form name="" method="post" action="action_admission_setting.php">
                    <div class="col-lg-3">
                        <label for="MiddleName">Admission Intake</label>
                        <select name="admissionSettingID" class="form-control" required="">
                            <?php
                            $adYear = $db->getRows('admission_setting', array('order_by' => 'academicYearID DESC'));
                            if (!empty($adYear)) {
                                echo "<option value=''>Please Select Here</option>";
                                $count = 0;
                                foreach ($adYear as $year) {
                                    $count++;
                                    $admissionName = $year['admissionName'];
                                    $admissionID = $year['admissionID'];
                            ?>
                                    <option value="<?php echo $admissionID; ?>"><?php echo $admissionName; ?></option>
                            <?php }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="email">Study Level</label>
                            <select name="studyLevelID" class="form-control">
                                <option value="">Select Here</option>
                                <?php
                                $programme_level = $db->getRows('studylevels', array('order_by' => 'studyLevelName ASC'));
                                if (!empty($programme_level)) {
                                    $count = 0;
                                    foreach ($programme_level as $level) {
                                        $count++;
                                        $programme_level = $level['studyLevelName'];
                                        $programme_level_id = $level['studyLevelID'];
                                ?>
                                        <option value="<?php echo $programme_level_id; ?>"><?php echo $programme_level; ?></option>
                                <?php }
                                } ?>
                            </select>

                        </div>
                    </div>

                    <div class="col-lg-3">
                        <label for="FirstName">Orientation Date</label>
                        <input type="date" name="orientationDate" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label for="FirstName">Registration Date</label>
                        <input type="date" name="registrationDate" class="form-control">
                    </div>

            </div>
            <div calss="row">
                <div class="col-lg-9"></div>
                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="hidden" name="action_type" value="add" />
                    <input type="submit" name="doFind" value="Save Records" class="btn btn-primary form-control" /></div>
                </form>
            </div>


            <br>
            <div class="row"><br></div>

            <div class="row">
                <?php
                $semester = $db->getRows('admission_letter_setting', array(' order_by' => ' admissionID ASC'));

                if (!empty($semester)) {
                ?>
                    <table id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Admission Intake</th>
                                <th>Study Level</th>
                                <th>Orientation Date</th>
                                <th>Registration Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 0;
                            foreach ($semester as $sm) {
                                $count++;
                                $admissionID = $sm['admissionID'];
                                $studyLevelID = $sm['studyLevelID'];
                                $startDate = $sm['orientationDate'];
                                $endDate = $sm['registrationDate'];
                                $admissionSettingID = $sm['admissionSettingID'];
                                if ($semesterStatus == 1)
                                    $status = "<span class='label label-success'>Active</span>";
                                else
                                    $status = "<span class='label label-danger'>Not Active</span>";


                                $admissionInTake = $db->getData("admission_setting", "admissionName", "admissionID", $admissionSettingID);

                            ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $admissionInTake; ?></td>
                                    <td><?php echo $db->getData("studylevels","studyLevelName","studyLevelID",$studyLevelID); ?></td>
                                    <td><?php echo $startDate; ?></td>
                                    <td><?php echo $endDate; ?></td>
                                <td><a href="action_admission_letter_setting.php?action_type=delete&id=<?php echo $db->my_simple_crypt($admissionID,'e'); ?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Semester Setting?');"></a></td>
                                </tr>
                            
                            <?php

                            }
                            ?>
                        </tbody>
                    </table>

                <?php
                }
                ?>
            </div>
        </div>


    </div>
</div>