<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        var programmeID=$("#programmeID").val();
        var academicYearID=$("#academicYearID").val();
        var admissionID=$("#admissionID").val();
        $('#selection_list').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'data/nacte_upload_report.php',
                        data:{programmeID:programmeID,academicYearID:academicYearID,admissionID:admissionID},
                        "serverSide" : true,
                        cache: false
                    },
                "scrollX":true,
                paging: true,
                dom: 'Blfrtip',
                "lengthMenu": [[10, 25, 50,100,200,300,400,500, -1], [10, 25, 50,100,200,300,400,500, "All"]],
                buttons:[
                    {
                        //extend:'excel',
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                        }
                    },
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'print',
                        title: titleheader,
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                        },
                        orientation: 'landscape',
                    }

                ]
            });
    });
</script>
<?php
$db = new DBHelper();
?>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$_REQUEST['count']." of records has been saved in database</strong>.
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
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" class="form-control chosen-select" required="">
                    <?php
                    $adYear = $db->getRows('programs',array('where'=>array('organizationID'=>2),'order_by'=>'programName ASC'));
                    if(!empty($adYear)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($adYear as $year){ $count++;
                            $programName=$year['programName'];
                            $programID=$year['programID'];
                            ?>
                            <option value="<?php echo $programID;?>"><?php echo $programName;?></option>
                        <?php }}
                    ?>
                </select>
            </div>
            <div class="col-lg-3">

                <label for="MiddleName">Admission Year</label>
                <select name="admissionYearID" class="form-control" required="">
                    <?php
                    $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear ASC'));
                    if(!empty($adYear)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($adYear as $year){ $count++;
                            $academic_year=$year['academicYear'];
                            $academic_year_id=$year['academicYearID'];
                            ?>
                            <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                        <?php }
                    }
                    ?>
                </select>
            </div>

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
                            ?>
                            <option value="<?php echo $admissionID;?>"><?php echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);?></option>
                        <?php }}
                    ?>
                </select>
            </div>


            <div class="col-lg-3">
                <label for=""></label>

                <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
            </div>
        </form>
    </div>

    <br><br>
    <div class="row">

        <?php
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $academicYearID=$_POST['admissionYearID'];
            $programmeID=$_POST['programmeID'];
            $admissionID=$_POST['admissionID'];

            $programmeCode=$db->getData("programs","programCode","programID",$programmeID);

            ?>
            <input type="hidden" id="sorted" value="<?php echo $value;?>">
            <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">
            <input type="hidden" id="academicYearID" value="<?php echo $academicYearID;?>">
            <input type="hidden" id="admissionID" value="<?php echo $admissionID;?>">




            <div class="col-lg-12">
                <h4><span id="titleheader">List of Approved Applicants for <?php echo $db->getData("programs","programName","programID",$programmeID); ?>
                        <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?>-Direct Entry</span></h4>
            </div>
            <form name="register" id="register" method="post" action="action_submit_nacte_upload_list.php">
                <div class="row">
                    <div class="col-lg-12">
                        Payment Reference Number: <input type="text" name="payment_reference_number" required>
                    </div>
                </div>
                <table id="selection_list" class="display nowrap" cellspacing="0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Disability</th>
                        <th>Form IV</th>
                        <th>Year</th>
                        <th>Form VI</th>
                        <th>Year</th>
                        <th>NTA4</th>
                        <th>Year</th>
                        <th>NTA5</th>
                        <th>NTA5 Year</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Region</th>
                        <th>Distict</th>
                        <th>Next Of Kin</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Relationship</th>
                        <th>Next of Kin Region</th>
                        <th>Nationality</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-9"></div>
                    <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                    <input type="hidden" name="programmeCode" value="<?php echo $programmeCode;?>">
                    <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                    <input type="hidden" name="admissionID" value="<?php echo $admissionID;?>">
                    <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                    <div class="col-lg-3">
                        <input type="hidden" name="action_type" value="add_app"/>
                        <input type="submit" name="doAdmit" value="Submit Applicants"
                               class="btn btn-success form-control">
                    </div>
                </div>
            </form>
            <?php
        }
        ?>

    </div>

</div>