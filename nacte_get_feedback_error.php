<script type="text/javascript">
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
                            columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14]
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
                            $programID=$year['programCode'];
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
                <h4><span id="titleheader">List of Applicants with Error in <?php echo $db->getData("programs","programName","programCode",$programmeID); ?>
                        <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4>
            </div>
                <table id="admit" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <!--<th>No.</th>-->
                        <th>VerificationID</th>
                        <!--<th>UserID</th>-->
                        <th>ProgrammeID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>MobileNumber</th>
                        <th>Email</th>
                        <th>Form4</th>
                        <th>Form4 Year</th>
                        <th>Form6</th>
                        <th>Form6 Year</th>
                        <th>NTA4 Reg</th>
                        <th>NTA4 Year</th>
                        <th>NTA5 Reg</th>
                        <th>NTA5 Year</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $db=new DBHelper();
                /*$url = "http://41.93.40.137/nacteapi/index.php/api/feedbackcorrection/ape/414635b331df03033330f4559c33c0c507a708b089e229e47805965d3db20d1f-c867fa3555f1bbdce788b7daa05ccc0b1922936e/piac/".$programmeID."-2019-SEPTEMBER";*/

                $api = $db->getAPI("NACTE", "error");
                if (!empty($api)) {
                    foreach ($api as $ap) {
                        $token = $ap['token'];
                        $url = $ap['url'];
                    }
                }

                


                    $url = $url.$programmeID."-2020-SEPTEMBER";
                    //var_dump($url);
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response_json = curl_exec($ch);
                    curl_close($ch);
                    $response = json_decode($response_json, true);

                    var_dump($response);
                    $params = $response['params'];

                    var_dump($params);

                    $count=1;
                    foreach($params as $rp) {
                        $programmeID = $rp['programme_id'];
                        $studentVerificationID = $rp['student_verification_id'];
                        echo "<td>".$rp['student_verification_id']."</td>";
                        /*echo "<td>".$rp['user_id']."</td>";*/
                        echo "<td>".$rp['programme_id']."</td>";
                       /* echo "<td><a href='index3.php?sp=edit_nacte_student&verid='".$studentVerificationID.">".$rp['firstname']." ".$rp['secondname']." ".$rp['surname']."</a></td>";*/
                        echo "<td>".$rp['firstname']."</td>";
                        echo "<td>".$rp['secondname']."</td>";
                        echo "<td>".$rp['surname']."</td>";
                        echo "<td>".$rp['mobile_number']."</td>";
                        echo "<td>".$rp['email_address']."</td>";
                        echo "<td>".$rp['form_four_indexnumber']."</td>";
                        echo "<td>".$rp['form_four_year']."</td>";
                        echo "<td>".$rp['form_six_indexnumber']."</td>";
                        echo "<td>".$rp['form_six_year']."</td>";
                        echo "<td>".$rp['NTA4_reg']."</td>";
                        echo "<td>".$rp['NTA4_grad_year']."</td>";
                        echo "<td>".$rp['NTA5_reg']."</td>";
                        echo "<td>".$rp['NTA5_grad_year']."</td>";
                        echo "<td>".$rp['remarks']."</td>";
                        echo "</tr>";
                        $count++;
                    }
                    ?></tbody>
                </table>
            <?php
        }
        ?>

    </div>

</div>