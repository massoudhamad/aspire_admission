<script type="text/javascript">
    /*$(document).ready(function () {
        var titleheader = $('#titleheader').text();
        var programmeID=$("#programmeID").val();
       /!* var academicYearID=$("#academicYearID").val();
        var admissionID=$("#admissionID").val();*!/
        $('#tcu_check_status').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'data/get_status_tcu.php',
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
                            columns:[0,1,2,3,4]
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
                            columns:[0,1,2,3,4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns:[0,1,2,3,4]
                        },
                        /!*orientation: 'landscape',*!/
                    }

                ]
            });
    });*/

    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#admit').dataTable(
            {
                paging: true,
                scrollX:true,
                dom: 'Blfrtip',
                "lengthMenu": [[10, 25, 50,100,200,300,400,500, -1], [10, 25, 50,100,200,300,400,500, "All"]],
                buttons:[
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns: [0, 2,3,4]
                        }

                    },
                    {
                        //extend:'excel',
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns:[0,2,3,4]
                        }
                    },
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
                    $adYear = $db->getStudyLevelProgrammes(2);
                    if(!empty($adYear)){
                        echo"<option value=''>Please Select Here</option>";
                        echo"<option value='all'>All Programmes</option>";
                        $count = 0; foreach($adYear as $year){ $count++;
                            $programCode=$year['programID'];
                            $programName=$year['programName'];
                            ?>
                            <option value="<?php echo $programCode;?>"><?php echo $programName;?></option>
                        <?php }
                    }
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
                        <?php }}
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
    </div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-3">
            <label for=""></label>

            <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
        </div>
    </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=tcu_view_status' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$_REQUEST['count']." of records has been saved in database</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="unsucc")
                {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=tcu_view_status' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="app_succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=tcu_view_status' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully Confirmed</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="app_unsucc")
                {
                    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=tcu_view_status' class='close' data-dismiss='alert'>&times;</a>
    <strong>Unsuccessfully Confirmed</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>
    <?php
    if(isset($_POST['doSearch'])=="Search Records") {
        $programmeID = $_POST['programmeID'];
        $academicYearID=$_POST['admissionYearID'];
        $admissionID=$_POST['admissionID'];
        ?>
        <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">

        <h2>List of Admitted and Selected/Not Selected By NACTE</h2>
        <hr>
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Admitted Applicants for <?php echo $db->getData("programs","programName","programCode",$programmeID); ?></span></h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <!--<form name="register" id="register" method="post" action="action_submit_tcu.php">-->
                <table id="admit" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Form Four</th>
                        <th>Phone Number</th>
                        <th>MUM Status</th>
                        <th>NACTE Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    /* $db=new DBHelper();*/
                    $count=1;
                    $applicants=$db->getDataNACTEView($programmeID,$academicYearID,$admissionID);
                    if(!empty($applicants)) {
                        foreach ($applicants as $app) {
                            $fname = $app['firstName'];
                            $mname = $app['middleName'];
                            $lname = $app['lastName'];
                            $applicantID = $app['applicantID'];
                            $phoneNumber = $app['phoneNumber'];
                            $nacte_status=$app['nacte_status'];
                            $remarksID=$app['applicantsRemarksID'];

                            $mumStatus=$db->getData("remarks","remark","remarkID",$remarksID);

                            if($nacte_status==1)
                                $nacteStatus="Selected";
                            else
                                $nacteStatus="Not";

                            $name = "$fname $mname $lname";

                            $oindexumber = $db->getIndexNumber($applicantID, "Ordinary");
                            if (!empty($oindexumber)) {
                                $formfour = array();
                                foreach ($oindexumber as $fnumber) {
                                    $indexNumber = $fnumber['indexNumber'];
                                    $formfour[] = $indexNumber;
                                }

                            } else {
                                $formfour[] = "";
                            }

                            echo "<tr><td>$count</td>
                            <td>$name</td>
                            <td>$formfour[0]</td>
                            <td>$phoneNumber</td>
                            <td>$mumStatus</td>
                            <td>$nacteStatus</td>
                            
                            </tr>";
                            $count++;
                        }
                    }
                    ?></tbody>
                </table>
                </form>
            </div>
        </div>

        <?php
    }
    ?>
</div>
