<div class="row">
    <div class="col-md-12">
        <?php
        if (!empty($_REQUEST['msg'])) {
            if ($_REQUEST['msg'] == "succ") {
                echo "<div class='alert alert-success fade in'><a href='index3.php?sp=confirm_app_individual' class='close' data-dismiss='alert'>&times;</a>
    <strong> Status Code = " . $_REQUEST['code'] . " and Description = " . $_REQUEST['status'] . " submitted in TCU</strong>.
</div>";
            } else if ($_REQUEST['msg'] == "unsucc") {
                echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=confirm_app_individual' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
            }
        }
        ?>


    </div>
</div>


<div class="row">
    <form name="" method="post" id="register" action="action_confirm_applicant_tcu.php">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <strong>Please Enter your Details</strong>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="studyLevel">Form Four Index Number</label>
                        <input type="text" name="formfour" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="studyLevel">Confirmation Code</label>
                        <input type="text" name="confirmationCode" class="form-control" required>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-4">
                    <input type="hidden" name="formfour" value="<?php echo $_REQUEST['formfour']; ?>">
                    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
                    </a>
                </div>
            </div>

    </form>
</div>
</div>

<div class="row">
    <hr>
</div>
<!-- <div class="row">
    <div class="col-lg-12">
        <h4><span id="titleheader">List of Confirmed Applicant by University</span></h4>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Form Four</th>
                        <th>Confirmation Code</th>
                        <th>TCU Status</th>
                    </tr>
                </thead>

                <tbody> -->
<?php

/* $db = new DBHelper();
                    $transferList = $db->getRows("applicants",array('where'=>array('tcu_confirmation_code'=>'>0')));
                    foreach ($transferList as $app) {
                        $count++;
                        $formfour = $app['formFour'];
                        $confirmation_code = $app['tcu_confirmation_code'];

                        echo "<tr><td>$count</td>";
                        echo "<td>$formfour</td>";
                        echo "<td>$confirmation_code</td>";
                        echo "<td>" . $app['tcu_status'] . "</td>";
                        echo "</tr>";
                        $count++;
                    } */
?></tbody>
<!-- </table>

        </div>
    </div>
</div> -->