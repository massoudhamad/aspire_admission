<div class="row">
    <form name="" method="post" id="register" action="action_external_transfer_tcu.php">
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
                        <input type="text" name="formfoursec" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="studyLevel">Confirmation Code</label>
                        <input type="text" name="confirmation_code" class="form-control" required>
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
<div class="row">
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

                <tbody>
                    <?php

                    $db = new DBHelper();
                    $transferList = $db->getTransferredListProgrammeCode('External');
                    foreach ($transferList as $app) {
                        $count++;
                        $formfour = $app['formFour'];
                        $confirmation_code = $app['confirmation_code'];

                        echo "<tr><td>$count</td>";
                        echo "<td>$formfour</td>";
                        echo "<td>$confirmation_code</td>";
                        echo "<td>" . $app['tcu_status'] . "</td>";
                        echo "</tr>";
                        $count++;
                    }
                    ?></tbody>
            </table>

        </div>
    </div>
</div>