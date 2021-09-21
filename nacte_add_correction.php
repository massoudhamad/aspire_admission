<?php
$db = new DBHelper();
?>
<div class="container">
    <h4>Upload Corrected File</h4>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data saved in database Successfully</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="unsucc")
                {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>

    <div class="row">
        <form name="" method="post" action="action_upload_nacte_correction_list.php" enctype="multipart/form-data">
            <div class="col-lg-3">
                <label for="MiddleName">Admission Intake</label>
                <select name="admissionID" class="form-control" required="">
                    <?php
                    $aitake = $db->getRows('admission_setting',array('order_by'=>'academicYearID ASC'));
                    if(!empty($aitake)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($aitake as $ait){ $count++;
                            $admissionID=$ait['admissionID'];
                            $admissionInTakeID=$ait['admissionInTakeID'];
                            $admissionName=$ait['admissionName'];
                            ?>
                            <option value="<?php echo $admissionID;?>"><?php echo $admissionName;?></option>
                        <?php }}
                    ?>
                </select>
            </div>

            <div class="col-lg-2">
                <label for="FirstName">Attachment</label>
                <input type='file' name="csv_file" accept=".csv" />
            </div>


            <div class="col-lg-3">
                <label for=""></label>
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doSearch" value="Upload File" class="btn btn-primary form-control" />
            </div>
        </form>
    </div>

    <br><br>
    <div class="row">

        <?php
        ?>

    </div>

</div>