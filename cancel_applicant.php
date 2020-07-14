<div class="row">
    <div class="col-lg-12">
       <?php
        if(!empty($_REQUEST['msg']))
        {
        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
            <strong>".$_REQUEST['msg']."-".$_REQUEST['status']."</strong>
        </div>";
        }
        ?>
    </div>
</div>
<div class="row">
    <form name="" method="post" id="register" action="action_cancel_applicant_tcu.php">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <strong>Please Cancel Admission, NB: <span class="text-danger">Please if you cancel, no way you can return again</strong>
                    </div></div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="studyLevel">Index/Form Four  Number</label>
                        <input type="text" name="formfour" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <input type="submit" name="doSubmit" value="Cancel" class="btn btn-danger form-control" />
                    </a>
                </div>
            </div>

    </form>
</div>
</div>