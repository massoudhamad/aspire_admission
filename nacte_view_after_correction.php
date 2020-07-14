<!--list of uploaded-->
<script type="text/javascript">
    $(document).ready(function () {
        $('#selection_list').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'data/nacte_add_correction.php',
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
                        //title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                        }
                    },
                    {
                        extend:'csvHtml5',
                        //title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'print',
                        //title: titleheader,
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        //title: titleheader,
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
    <h4>View List of Applicants who submitted to NACTE after Correction</h4>
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


    <br><br>
    <div class="row">

        <form name="register" id="register" method="post" action="action_upload_list_after_error.php">

            <table id="selection_list" class="display nowrap" cellspacing="0">
                <thead>
                <tr>
                    <th>No.</th>
                    <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                    <th>VerificationID</th>
                    <th>Programme Code</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Form IV</th>
                    <th>Year</th>
                    <th>Form VI</th>
                    <th>Year</th>
                    <th>NTA4</th>
                    <th>Year</th>
                    <th>NTA5</th>
                    <th>NTA5 Year</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
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
                <!--<input type="hidden" name="programmeCode" value="<?php /*echo $programmeCode;*/?>">
                <input type="hidden" name="programmeID" value="<?php /*echo $programmeID;*/?>">
                <input type="hidden" name="admissionID" value="<?php /*echo $admissionID;*/?>">
                <input type="hidden" name="academicYearID" value="<?php /*echo $academicYearID;*/?>">-->
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add_app"/>
                    <input type="submit" name="doAdmit" value="Submit Applicants"
                           class="btn btn-success form-control">
                </div>
            </div>
        </form>
        <?php
        //}
        ?>

    </div>

</div>