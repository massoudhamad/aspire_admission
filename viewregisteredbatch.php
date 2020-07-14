<?php
$db = new DBHelper();
?>
<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#admit').dataTable(
            {
                paging: true,
                scrollX:true,
                dom: 'Blfrtip',
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
                            columns: [0, 2,3,4,5,6]
                        }

                    }

                ]
            });
    });
</script>

<script type="text/javascript">
    $(document).ready(function()
    {
        $("#programmeID").change(function()
        {
            var id=$(this).val();
            var dataString = 'id='+ id;
            $.ajax
            ({
                type: "POST",
                url: "ajax_programme_major.php",
                data: dataString,
                cache: false,
                success: function(html)
                {
                    $("#programmeMajorID").html(html);
                }
            });

        });

    });
</script>

<style type="text/css">
    .verticaltext{
        width:1px;
        word-wrap: break-word;
        font-family: monospace /* this is just for good looks */
    }
</style>
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
            <div class="col-lg-4">
                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" id="programmeID" class="form-control chosen-select   " required="">
                    <?php
                    $adYear = $db->getAllProgrammes();
                    if(!empty($adYear)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($adYear as $year){ $count++;
                            $programID=$year['programID'];
                            $programName=$year['programName'];
                            ?>
                            <option value="<?php echo $programID;?>"><?php echo $programName;?></option>
                        <?php }}
                    ?>
                </select>
            </div>
            <div class="col-lg-4">

                <label for="MiddleName">Programme Major</label>
                <select name="programmeMajorID" id="programmeMajorID" class="form-control" required="">
                    <option value="">--Select Here--</option>
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
            </div>-->

            <div class="col-lg-4">

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
                            <option value="<?php echo $admissionID;?>"><?php echo $admissionName;//$db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);?></option>
                        <?php }
                    }
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

    <br><br>
    <div class="row">

        <?php
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $programmeMajorID=$_POST['programmeMajorID'];
            //$applicationYearID=$_POST['admissionYearID'];
            $admissionID=$_POST['admissionID'];
            $programmeID=$_POST['programmeID'];
            ?>
            <div class="col-lg-12">
                <h4><span id="titleheader">List of Registered Applicants for <?php echo $db->getData("programs","programName","programID",$programmeID);?></span></h4>
            </div>
            <form name="" method="post" action="action_unregister.php">
                <table id="admit" class="display nowrap" cellspacing="0">
                    <thead>
                    <tr>
                        <th>First Name</span></th>
                        <th>Middle Name</span></th>
                        <th>Last Name</span></th>
                        <th>Sex</th>
                        <th>Reg.Number</th>
                        <th>DOB</th>
                        <th>Admission No</th>
                        <th>Form IV</th>
                        <th>Entry</th>
                        <th>Phone Number</th>
                        <th>Hosteller</th>
                        <th>Sponsor</th>
                        <th>Nationality</th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    if($programmeMajorID=='all') {
                        $applicantsData = $db->getAllBatchRegistered($programmeID,$admissionID);
                    }
                    else
                    {
                        $applicantsData=$db->getRegisteredBatchApplicants($programmeMajorID,$admissionID);

                    }
                    if(!empty($applicantsData))
                    {
                        $i=0;$_SESSION['applicantID']=array();
                        foreach ($applicantsData as $data)
                        {
                            $i++;
                            $applicantID=$data['applicantID'];
                            $fname=$data['firstName'];
                            $mname=$data['middleName'];
                            $lname=$data['lastName'];
                            $gender=substr($data['gender'],0,1);
                            $regNumber=$data['registrationNumber'];
                            $phoneNumber=$data['phoneNumber'];
                            $dob=$data['dateOfBirth'];
                            $addNumber=$data['applicationNumber'];
                            $entry=$data['entryQualification'];
                            $userID=$data['userID'];
                            $nacte_status=$data['nacte_status'];
                            $sponsor=$data['sponsor'];
                            $hosteller=$data['hosteller'];
                            $nationality=$data['citizenship'];

                            if($entry==0)
                                $entry=1;
                            else
                                $entry=2;

                            $oindexumber=$db->getIndexNumber($applicantID,"Ordinary");
                            if(!empty($oindexumber))
                            {
                                foreach ($oindexumber as $fnumber) {
                                    $indexNumber=$fnumber['indexNumber'];
                                    $formfour=$indexNumber;
                                }

                            }
                            else
                            {
                                $formfour="None";
                            }

                            echo "<tr>";
                            echo"<td>$fname</td><td>$mname</td><td>$lname</td>"
                                . "<td>$gender</td><td>$regNumber</td><td>$dob</td><td>$addNumber</td><td>".$formfour."</td><td>$entry</td><td>$phoneNumber</td><td>$hosteller</td><td>$sponsor</td><td>$nationality</td>";
                            ?>
                            </tr>
                            <?php
                        }
                    }


                    ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-6">

                    </div>
                    <input type="hidden" name="number_applicants" value="<?php echo $i;?>">
                    <input type="hidden" name="programmeMajorID" value="<?php echo $programmeID;?>">
                    <input type="hidden" name="choice" value="<?php echo $choice;?>">

                    <div class="col-lg-3">
                        <input type="submit" name="doPublish" value="Publish Results" class="btn btn-success form-control">
                    </div>
                    <div class="col-lg-3">
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="submit" name="doUpdate" value="Un Enroll" class="btn btn-danger form-control">
                    </div>

                </div>
            </form>
            <?php
        }
        ?>

    </div>

</div>
<div class="modal fade" id="oModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <strong>Loading...</strong>
        </div>
    </div>
</div>