<div class="row">
    <form name="" method="post" id="register" action="action_un_confirm_applicant_tcu.php">
        <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <strong>Please Confirm your Registration for Admission:<?php echo $_REQUEST['formfour'];?></strong>
                </div></div>
        </div>


            <div class="row">
             <div class="col-lg-4">
                                    <div class="form-group">
                                      <label for="studyLevel">Confirmation Code</label>
                                      <input type="text" name="confirmationCode" value="" class="form-control" required>
                                    </div>
             </div>
            </div>
        <div class="row">
        <div class="col-lg-4">
            <input type="hidden" name="formfour" value="<?php echo $_REQUEST['formfour'];?>">
            <input type="submit" name="doSubmit" value="Confirm" class="btn btn-success form-control" />
            </a>
        </div>
            </div>

    </form>
</div>
</div>