<?php $db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>

<script type="text/javascript">
    $('#myModal').on('hidden.bs.modal', function () {
        location.reload();
    });
</script>
<div class="row">
    <div class="page-title">
        <div>
            <h1><i class="fa fa-graduation-cap"></i>Working Experience</h1>
            <p>Add your working experince based on the instruction from form</p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ") {
                    echo "<div class='alert alert-success fade in'><a href='index.php?sz=working' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                }
                else if($_REQUEST['msg']=="unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=working' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
                }
                else if($_REQUEST['msg']=="dropSchool") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=working' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                }
                else if($_REQUEST['msg']=="error") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=working' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                }
            }
            ?>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">

                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add Working Experience</button>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <?php
                $wexprience = $db->getRows("working_experience", array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by applicantID ASC'));
                if (!empty($wexprience)) {
                    ?>

                    <div class="col-lg-12">
                        <h3>  List of Working Experince</h3>

                        <table class="table table-striped" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Position/Title</th>
                                <th>Employer Name</th>
                                <th>Address</th>
                                <th>Start Year</th>
                                <th>End Year</th>
                                <th>Action</th>
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
                                $address=$exp['employerAddress'];
                                echo "<tr><td>$pname</td><td>$employerName</td><td>$address</td><td>$startYear</td><td>$endYear</td>";
                                ?>
                                <td>
                                    <a href="action_working_experience.php?action_type=dropSchool&id=<?php echo $db->my_simple_crypt($workID,'e'); ?>"
                                       class="glyphicon glyphicon-trash"
                                       onclick="return confirm('Are you sure you want to drop this information?');">Drop</a>
                                </td>
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
            <!-- End of Equivant Results-->

            <!--<form name="" method="post" action="">
                <div class="col-lg-3">
                    <input type="submit" name="doProceed" value="Proceed to Application"
                           class="btn btn-success form-control"/>
                </div>
            </form>
-->
        </div>
    </div>
    </form>
</div>
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="" method="post" action="action_working_experience.php">
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="email">Position/Title Name</label>
                            <input type="text" id="positionName" name="positionName" placeholder="Position/Title Name" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="email">Employer Name</label>
                            <input type="text" id="employer" name="employer" placeholder="Employer Name" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="email">Employer Address</label>
                            <input type="text" id="address" name="address" placeholder="Employer Address" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="email">Start Year</label>
                            <select name="startYear" id="startYear" class="form-control" required="required">
                                <option value="">Select Year</option>
                                <?php
                                $year=date('Y');
                                $year1=date('Y')-40;
                                for($x=$year;$x>=$year1;$x--)
                                {
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
                                $year=date('Y');
                                $year1=date('Y')-40;
                                for($x=$year;$x>=$year1;$x--)
                                {
                                    echo "<option value='$x'>$x</option>";
                                }
                                ?>
                            </select>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
                        <!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->
                        </form>
                    </div>
                </div>
            </div>
        </div>


