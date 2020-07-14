<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        var programmeID=$("#programmeID").val();
        $('#tcu_check_status').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'data/get_admitted_tcu.php',
                        data:{programmeID:programmeID},
                        "serverSide" : true,
                        cache: false
                    },
                "scrollX":true,
                paging: true,
                dom: 'Blfrtip',

                buttons:[
                    {
                        //extend:'excel',
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns:[0,1,2,3,4,5]
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
                            columns:[0,1,2,3,4,5]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns:[0,1,2,3,4,5]
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
    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" class="form-control chosen-select" required="">
                    <?php
                    $adYear = $db->getMainProgrammes();
                    if(!empty($adYear)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($adYear as $year){ $count++;
                            $programCode=$year['programCode'];
                            $programName=$year['programName'];
                            ?>
                            <option value="<?php echo $programCode;?>"><?php echo $programName;?></option>
                        <?php }}
                    ?>
                </select>
            </div>
            <!--<div class="col-lg-3">

                <label for="MiddleName">Admission Year</label>
                <select name="admissionYearID" class="form-control" required="">
                    <?php
            /*                    $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear ASC'));
                                if(!empty($adYear)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($adYear as $year){ $count++;
                                        $academic_year=$year['academicYear'];
                                        $academic_year_id=$year['academicYearID'];
                                        */?>
                            <option value="<?php /*echo $academic_year_id;*/?>"><?php /*echo $academic_year;*/?></option>
                        <?php /*}}
                    */?>
                </select>
            </div>

            <div class="col-lg-2">

                <label for="MiddleName">Admission Intake</label>
                <select name="admissionID" class="form-control" required="">
                    <?php
            /*                    $aitake = $db->getRows('admission_setting',array('order_by'=>'academicYearID ASC'));
                                if(!empty($aitake)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($aitake as $ait){ $count++;
                                        $admissionID=$ait['admissionID'];
                                        $admissionInTakeID=$ait['admissionInTakeID'];
                                        */?>
                            <option value="<?php /*echo $admissionID;*/?>"><?php /*echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);*/?></option>
                        <?php /*}}
                    */?>
                </select>
            </div>-->
    </div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-3">
            <label for=""></label>

            <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
        </div>
    </div>
    </form>

    <div class="col-lg-12">
        <?php
        /* if(!empty($_REQUEST['msg']))
         {
             if($_REQUEST['msg']=="unsucc") {
                 echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                         <strong>Sorry, Error: We are unable to save your data in TCU Database.</strong>
                     </div>";
             }
             else {
                 echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                         <strong>".$_SESSION['output']."</strong>.
                     </div>";
             }
         }*/
        ?>
    </div>
    <?php
    if(isset($_POST['doSearch'])=="Search Records") {
        $programmeID = $_POST['programmeID'];
        /*  $applicationYearID = $_POST['admissionYearID'];
          $admissionID = $_POST['admissionID'];*/
        ?>
        <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">
        <!-- <input type="hidden" id="academicYearID" value="<?php /*echo $applicationYearID;*/?>">
        <input type="hidden" id="admissionID" value="<?php /*echo $admissionID;*/?>">-->

        <h2>List of Addmitted for TCU Status</h2>
        <hr>
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Admitted Applicants for <?php echo $db->getData("programs","programName","programCode",$programmeID); ?>
                    <?php //echo $db->getData("academicyears","academicYear","academicYearID",$applicationYearID);?></span></h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table id="tcu_check_status" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Form Four</th>
                        <th>Form Six</th>
                        <th>Mobile Number</th>
                        <th>Email Address</th>
                        <th>Admission Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        <?php
    }
    ?>
</div>
