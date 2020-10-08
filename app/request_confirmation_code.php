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
                    <input type="hidden" name="formfour" value="<?php echo $_REQUEST['formfour']; ?>">
                    <input type="submit" name="doSubmit" value="Confirm" class="btn btn-success form-control" />
                    </a>
                </div>
            </div>

    </form>
</div>
</div>