 <div class="row">
     <div class="col-md-12">
         <?php
            if (!empty($_REQUEST['msg'])) {
                //if ($_REQUEST['msg'] == "succ") {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$_REQUEST['status']."</strong>.
</div>";
                } else if ($_REQUEST['msg'] == "unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$_REQUEST['status']."</strong>.
</div>";
                }
            //}
            ?>


     </div>
 </div>
 <div class="row">
     <form name="" method="post" id="register" action="action_internal_transfer_tcu_update.php">
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
                         <label for="studyLevel">Form Six/AVN Number</label>
                         <input type="text" name="formsix" class="form-control" required>
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-lg-4">
                     <div class="form-group">
                         <label for="studyLevel">Previous Programme Code</label>
                         <input type="text" name="bProgrammeCode" class="form-control" required>
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-lg-4">
                     <div class="form-group">
                         <label for="studyLevel">Current Programme Code</label>
                         <input type="text" name="aProgrammeCode" class="form-control" required>
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-lg-4">
                     <div class="form-group">
                         <label for="studyLevel">Gender</label>
                         <select name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                         </select>
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
         <h4><span id="titleheader">List of Internal Transfer Applicants</span></h4>
     </div>
     <hr>
     <div class="row">
         <div class="col-md-12">
             <!--<form name="register" id="register" method="post" action="action_external_transfer_tcu.php">-->
             <table id="example" class="display" cellspacing="0" width="100%">
                 <thead>
                     <tr>
                         <th>No.</th>
                         <!--<th width="10"><input type="checkbox" name="select_all" id="select_all"></th>-->
                         <th>Form Four</th>
                         <th>Form Six</th>
                         <th>Programme Admitted</th>
                         <th>Transfered Programme</th>
                         <th>TCU Status</th>
                     </tr>
                 </thead>

                 <tbody>
                     <?php

                        $db = new DBHelper();
                        $transferList = $db->getTransferredListProgrammeCode('Internal');
                        foreach ($transferList as $app) {
                            $count++;
                            $formfour = $app['formFour'];
                            $formsix = $app['formSix'];
                            $before = $app['bProgrammeCode'];
                            $after = $app['aProgrammeCode'];

                            echo "<tr><td>$count</td>";

                            echo "<td>$formfour</td>";
                            echo "<td>$formsix</td>";
                            echo "<td>$before</td>";
                            echo "<td>$after</td>";
                            echo "<td>" . $app['tcu_status'] . "</td>";
                            echo "</tr>";
                            $count++;
                        }
                        ?></tbody>
             </table>

         </div>
     </div>
 </div>