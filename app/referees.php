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
            <h1><i class="fa fa-graduation-cap"></i>Refrees</h1>
            <p>Please give the names of <b><span style="color:red;"> two</span></b> academic referees</p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ") {
                    echo "<div class='alert alert-success fade in'><a href='index.php?sz=referees' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Congratulations, Successfully you saved your Data</strong>.
                    </div>";
                }
                else if($_REQUEST['msg']=="unsucc") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=referees' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error: Not able to save your Data. Looks like your Index Number is already used.</strong>
                    </div>";
                }
                else if($_REQUEST['msg']=="dropSchool") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=referees' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Success, School Data has been droped</strong>.
                    </div>";
                }
                else if($_REQUEST['msg']=="error") {
                    echo "<div class='alert alert-danger fade in'><a href='index.php?sz=referees' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sorry, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                }
            }
            ?>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">

                    <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add Referees</button>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <?php
                $referees = $db->getRows("referees", array('where' => array('applicantID' => $_SESSION['applicantID']), 'order_by applicantID ASC'));
                if (!empty($referees)) {
                    ?>

                    <div class="col-lg-12">
                        <h3>  List of Referees</h3>

                        <table class="table table-striped" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Address</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($referees as $exp) {
                                $refereeID = $exp['refereeID'];
                                $fullName = $exp['fullName'];
                                $email = $exp['email'];
                                $pname = $exp['position'];
                                $address=$exp['address'];
                                $pnumber=$exp['phoneNumber'];
                                echo "<tr><td>$fullName</td><td>$pname</td><td>$address</td><td>$pnumber</td><td>$email</td>";
                                ?>
                                <td>
                                    <a href="action_referee.php?action_type=dropSchool&id=<?php echo $db->my_simple_crypt($refereeID,'e'); ?>"
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
                    <h3><span style="color: red;">No Refrees Found</span> </h3>
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
                <script type="text/javascript">
                    function validateForm()
                    {
                        var fullname=document.referee.name;
                        var positionName=document.referee.positionName;
                        var address=document.referee.address;
                        var email=document.referee.email;
                        var reg_email=/^+$;
                        var pnumber=document.referee.pnumber;

                        if(fullname.value=="")
                        {
                            alert('Please fill Referee name');
                            fullname.focus();
                            return false;
                        }
                        else if(positionName.value=="")
                        {
                            alert('Please fill Position');
                            positionName.focus();
                            return false;
                        }
                        else if(address.value=="")
                        {
                            alert('Please fill Address');
                            address.focus();
                            return false;
                        }
                        else if(email.value=="")
                        {
                            alert('Please fill Email');
                            email.focus();
                            return false;
                        }
                        else if(pnumber.value=="")
                        {
                            alert('Please fill Phone Number');
                            pnumber.focus();
                            return false;
                        }
                        else
                        {
                            return true;
                        }
                    }
                </script>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <form name="referee" method="post" action="action_referee.php" onsubmit="return validateForm();">
                    <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter Referee Name" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="email">Position Name</label>
                            <input type="text" id="positionName" name="positionName" placeholder="Position/Title Name" class="form-control" />
                        </div>



                        <div class="form-group">
                            <label for="email">Address</label>
                            <input type="text" id="address" name="address" placeholder="Address" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" placeholder="Email" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="email">Phone Number</label>
                            <input type="text" id="pnumber" name="pnumber" placeholder="Phone Number" class="form-control" />
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


