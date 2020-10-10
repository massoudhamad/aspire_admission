    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($_REQUEST['msg'])) {
                echo "<div class='alert alert-success fade in'><a href='index3.php?sp=request_confirmation_code' class='close' data-dismiss='alert'>&times;</a>
    <strong>" . $_REQUEST['msg'] . "<br>" . $_REQUEST['status'] . "</strong>.
</div>";
            }
            ?>


        </div>
    </div>

    <div class="row">
        <form name="" method="post" id="register" action="action_request_confirmation_code.php">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <strong>Please Request your Confirmation Code:<?php echo $_REQUEST['formfour']; ?></strong>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="studyLevel">Enter a Valid Form Four Number</label>
                            <input type="text" name="formfour" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="studyLevel">Enter a Valid Mobile Number</label>
                            <input type="text" name="mobile_number" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="studyLevel">Enter a Valid Email Address </label>
                            <input type="text" name="email_address" value="" class="form-control" required email>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="submit" name="doSubmit" value="Confirm" class="btn btn-success form-control" />
                        </a>
                    </div>
                </div>

        </form>
    </div>
    </div>