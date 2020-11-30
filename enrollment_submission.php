<script type="text/javascript">

    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#selection_list').dataTable(
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
                            columns: [0, 2,3,4,5,6]
                        }

                    }

                ]
            });
    });

    /*$(document).ready(function () {
        var titleheader = $('#titleheader').text();
        var programmeID=$("#programmeID").val();
        var academicYearID=$("#academicYearID").val();
        var admissionID=$("#admissionID").val();
        $('#selection_list').DataTable(
            {
                ajax:
                    {
                        type: 'GET',
                        url: 'data/tcu_enrollment.php',
                        data:{academicYearID:academicYearID,admissionID:admissionID,programmeID:programmeID},
                        "serverSide" : true,
                        cache: false
                    },
                "scrollX":true,
                paging: true,
                dom: 'Blfrtip',
                buttons:[
                    {
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
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
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                        },
                        orientation: 'landscape',
                    }

                ]
            });
    });*/
</script>
<?php
$db = new DBHelper();

?>
<div class="container">
    <h4>List of Enrolled Students </h4>
    <hr>
    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Organization Name</label>
                <select name="sectorID" class="form-control chosen-select" required="">
                    <?php
                                $adYear = $db->getRows('sector',array('order_by'=>'sectorName ASC'));
                                if(!empty($adYear)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($adYear as $year){ $count++;
                                        $programName=$year['sectorName'];
                                        $programID=$year['sectorID'];
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
            $programmeID=$_POST['sectorID'];
            $admissionID=$_POST['admissionID'];

            $academicYear= $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);

            ?>
             <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">
          <input type="hidden" id="academicYearID" value="<?php echo $academicYearID;?>">
            <input type="hidden" id="admissionID" value="<?php echo $admissionID;?>">

            <div class="col-lg-12">
                <h4><span id="titleheader">List of Enrolled Applicants for <?php echo $db->getData("sector","sectorName","sectorID",$programmeID); ?>
                        <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4>
            </div>
             <form name="register" id="register" method="post" action="action_submit_enrolled_applicants.php">
            <table id="selection_list" class="display nowrap" cellspacing="0">
                <thead>
                <tr>
                    <th>No</th>
                    <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Nationality</th>
                    <th>Date of Birth</th>
                    <th>Award Category</th>
                    <th>Field Specilization</th>
                    <th>Year of Study</th>
                    <th>Study Mode</th>
                    <th>Is Year Repeat</th>
                    <th>Entry Qualification</th>
                    <th>Sponsorship</th>
                    <th>Physical Challenges</th>
                    <th>Form IV</th>
                    <th>Award Name</th>
                    <th>Registration Number</th>
                    <th>Institution Code</th>
                    <th>Enrollement Year</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $applicantsData=$db->getTCUEnrollmentList($academicYearID,$admissionID);
                if(!empty($applicantsData))
                {
                    $i=0;
                    foreach ($applicantsData as $data)
                    {
                        $i++;
                        $applicantID=$data['applicantID'];
                        $fname=$data['firstName'];
                        $mname=$data['middleName'];
                        $lname=$data['lastName'];
                        $gender=$data['gender'];
                        $dob=$data['dob'];
                        $disabiliyStatus=$data['disabilityStatus'];
                        $nationality=$data['citizenship'];
                        $entryQualification=$data['entryQualification'];
                        $sponsor=$data['sponsor'];
                        $registrationNumber=$data['registrationNumber'];
                        $tcu_status=$data['tcu_status'];



                        if($disabiliyStatus=="Yes")
                        {
                            $disability=$db->getRows("disability", array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                            if(!empty($equivalentresults))
                            {
                                foreach($disability as $disab)
                                {
                                    $dname=$disab['disabilityName'];
                                }
                            }
                        }
                        else {
                            $dname="None";
                        }


                        $oindexumber=$db->getIndexNumber($applicantID,"Ordinary");
                        if(!empty($oindexumber))
                        {
                            $formfour=array();
                            foreach ($oindexumber as $fnumber) {
                                $indexNumber=$fnumber['indexNumber'];
                                $formfour[]=$indexNumber;
                            }

                        }
                        else
                        {
                            $formfour[]="";
                        }

                        $programmeChoice=$db->getAdmittedProgramme($applicantID,1);
                        if(!empty($programmeChoice))
                        {
                            foreach ($programmeChoice as $pChoice)
                            {
                                /*$programmeCode=$pChoice['programCode'];
                                $programmeName=$pChoice['programName'];*/

                                $programmeCode=$pChoice['programCode'];
                                $programmeName=$pChoice['programName'];
                                $major=$pChoice['major'];
                                $programmeMajor=$pChoice['programmeMajor'];
                                $studyLevelID=$pChoice['studyLevelID'];
                            }
                        }

                        if($studyLevelID==1)
                        {
                            $study="Bachelor";
                        }
                        else if($studyLevelID==2)
                        {
                            $study="Diploma";
                        }
                        else if($studyLevelID==3)
                        {
                            $study="Certificate/NTA4/Diploma in Medical";
                        }
                        /*else if($studyLevelID==4)
                        {
                            $study="Certificate";
                        }*/
                        else if($studyLevelID==5)
                        {
                            $study="NTA5";
                        }

                        //end of O-Level


                        /* $programmeChoice=$db->getTCUEnrollmentProgramme($applicantID);
                         if(!empty($programmeChoice))
                         {
                             foreach ($programmeChoice as $pChoice)
                             {
                                 $registrationNumber=$pChoice['registrationNumber'];
                                 $programmeCode=$pChoice['programCode'];
                                 $programmeName=$pChoice['programName'];
                                 $major=$pChoice['major'];
                                 $programmeMajor=$pChoice['programmeMajor'];
                             }
                         }*/

                        if($major="NA")
                        {
                            $fieldspecialization=$programmeMajor;
                        }
                        else
                        {
                            $fieldspecialization=$major;
                        }

                        if($entryQualification==1)
                        {
                            $entry="Equivalent";
                        }
                        else
                        {
                            $entry="Direct";
                        }

                        /*if(!empty($programmeCode))
                            $programmeCode=$programmeCode;
                        else*/
                        $programmeCode=$programmeCode;

                        if($tcu_status==1) {
                            $box = "NA";
                        }
                        else {
                            $box = "<input type='checkbox' class='checkbox_class' name='applicantID[]' value='$applicantID'>";
                        }




                        echo "<tr><td>$i</td>
                           <td>$box</td>
                            <td>$fname</td>
                            <td>$mname</td>
                            <td>$lname</td>
                            <td>$gender</td>
                            <td>".strtoupper($nationality)."</td>
                            <td>$dob</td>
                            <td>$study</td>
                            <td>$fieldspecialization</td>
                            <td>First Year</td>
                            <td>Full Time</td>
                            <td>No</td>
                            <td>$entry</td>
                            <td>$sponsor</td>
                            <td>$dname</td>
                            <td>$formfour[0]</td>
                            <td>$programmeName</td>
                            <td>$registrationNumber</td>
                            <td>".$_SESSION['orgCode']."</td>
                            <td>$academicYear</td>";

                    }
                }
                ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-lg-6"></div>
                <input type="hidden" name="number_applicants" value="<?php echo $count; ?>">
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="add_app"/>
                    <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                    <input type="hidden" name="admissionID" value="<?php echo $admissionID;?>">
                    <input type="submit" name="doAdmit" value="Submit Registered"
                           class="btn btn-success form-control">
                </div>
            </div>

             </form>

            <?php
        }
        ?>

    </div>

</div>