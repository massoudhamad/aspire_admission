<?php $db = new DBHelper();
if (isset($_POST['doProceed'])) {
    $db->redirect("index.php?sz=programmechoice");
}
?>
<script src="js/jquery-1.4.2.min.js"></script>

<script type="text/javascript">
    /*$('#myModal').on('hidden.bs.modal', function () {
        location.reload();
    });*/
</script>
<div class="row">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-graduation-cap"></i>Educational Background</h1>
            <p>Add your results based on the instruction from form</p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='index.php?sz=pg_education' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=pg_education' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
                } else if ($_REQUEST['msg'] == "dropSchool") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=pg_education' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                } else if ($_REQUEST['msg'] == "error") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=pg_education' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                }
            }
            ?>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">

                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add Academic Background</button>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <?php
                $equivalentresults = $db->getRows("academic_background", array('where' => array('applicantID' => $_SESSION['applicantID']), 'order by applicantID ASC'));
                if (!empty($equivalentresults)) {
                ?>

                    <div class="col-lg-12">
                        <h3> List of Academic Background Results</h3>
                        <table class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Reg.Number</th>
                                    <th>Programme Name</th>
                                    <th>Institution Name</th>
                                    <th>Start Year</th>
                                    <th>End Year</th>
                                    <th>CGPA/Class/Division</th>
                                    <th>Quailification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($equivalentresults as $matokeo) {
                                ?>
                                    <?php
                                    $regNumber = $matokeo['registrationNumber'];
                                    $academicID = $matokeo['academicID'];
                                    $schoolName = $matokeo['institutionName'];
                                    $startYear = $matokeo['startYear'];
                                    $endYear = $matokeo['endYear'];
                                    $pname = $matokeo['programmeName'];
                                    $gradeType = $matokeo['gpa'];
                                    $qualificationID = $matokeo['qualificationID'];
                                    echo "<tr><td>$regNumber</td><td>$pname</td><td>$schoolName</td><td>$startYear</td><td>$endYear</td><td>$gradeType</td>
                                                <td>" . $db->getData("qualificationtype", "qualificationName", "qualificationTypeID", $qualificationID) . "</td>"; ?>
                                    <td>
                                        <a href="action_academic_background.php?action_type=dropSchool&id=<?php echo $db->my_simple_crypt($academicID, 'e'); ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to drop this results?');">Drop</a>
                                    </td>
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
            <!-- End of Equivant Results-->

            <form name="" method="post" action="action_academic_background.php">
                <div class="col-lg-3">
                    <?php if (!empty($equivalentresults)) { ?>
                        <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    </form>
</div>
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="" method="post" action="action_academic_background.php">
                    <h4 class="modal-title" id="myModalLabel">Add University Qualification (Bachelor/Master/PhD)</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="email">Programme Name</label>
                            <input type="text" id="programmeName" name="programmeName" placeholder="Eg. Bachelor of Law(LLB)" class="form-control" required/>
                        </div>

                        <div class="form-group">
                            <label for="email">Institution Name</label>
                            <input type="text" id="code" name="instituteName" placeholder="Eg. Zanzibar University" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Registration Number</label>
                            <input type="text" id="reg_number" name="registrationNumber" placeholder="Eg. 2030001" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Start Year</label>
                            <select name="startYear" id="startYear" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php
                                $year = date('Y');
                                $year1 = date('Y') - 40;
                                for ($x = $year; $x >= $year1; $x--) {
                                    echo "<option value='$x'>$x</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">End Year</label>
                            <select name="endYear" id="endYear" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php
                                $year = date('Y');
                                $year1 = date('Y') - 40;
                                for ($x = $year; $x >= $year1; $x--) {
                                    echo "<option value='$x'>$x</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">CGPA/Class/Division/%</label>
                            <input type="text" id="cgpa" name="cgpa" placeholder="Eg. 4.5/80%/PASS" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Qualification Name</label>
                            <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" required="required">

                                <option value="">Select Qualification Type</option>
                                <?php
                                $qualificationType = $db->getRows('qualificationtype', array('where' => array('qualificationTypeRank' => 2), 'order by rank ASC'));
                                if (!empty($qualificationType)) {
                                    $count = 0;
                                    foreach ($qualificationType as $type) {
                                        $count++;
                                        $qualificationName = $type['qualificationName'];
                                        $qualificationID = $type['qualificationTypeID'];
                                ?>
                                        <option value="<?php echo $qualificationID; ?>"><?php echo $qualificationName; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="action_type" value="add" />
                        <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>