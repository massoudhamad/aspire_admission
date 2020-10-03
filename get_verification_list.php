<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#tcu_check_status').dataTable(
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
    <?php
    if(isset($_POST['doSearch'])=="Search Records") {
        $programmeID = $_POST['programmeID'];

        ?>
        <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">

        <!--<h2>List of Confirmed for TCU Status</h2>
        <hr>-->
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Confirmed Applicants for <?php echo $db->getData("programs","programName","programCode",$programmeID); ?></span></h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <!--<form name="register" id="register" method="post" action="action_confirm_tcu_data.php">-->
                    <table id="tcu_check_status" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <!--<th width="10"><input type="checkbox" name="select_all" id="select_all"></th>-->
                            <th>Form Four</th>
                            <th>Admission Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                        $db=new DBHelper();
            $api_token = $db->getAPI("TCU", "token");
            if (!empty($api_token)) {
                foreach ($api_token as $api) {
                    $token = $api['token'];
                    $user = $api['userName'];
                    $urlform = $api['url'];
                }
            }
                        $programmeCode=$programmeID;


                        $xml='<?xml version="1.0" encoding="UTF-8"?>
                        <Request>
                            <UsernameToken>
                                <Username>'.$user.'</Username>
                                <SessionToken>'.$token.'</SessionToken>
                            </UsernameToken>
                            <RequestParameters>
                                <ProgrammeCode>'.$programmeCode.'</ProgrammeCode>
                            </RequestParameters>
                        </Request>';

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,"http://api.tcu.go.tz/applicants/getApplicantVerificationStatus");
                        curl_setopt($ch, CURLOPT_POST,1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                        $data = curl_exec($ch);

                        curl_close($ch);
                        $array_data=json_decode(json_encode(simplexml_load_string($data)),true);
                        $applicants=$array_data['Response']['ResponseParameters']['Applicant'];
                        $count=0;
                        foreach($applicants as $app) {
                            $count++;
                            $formfour = $app['f4indexno'];
                            $status=$app['AdmissionStatus'];

                            ?>
                            <input type="text" hidden name="status[]" value="<?php echo $status;?>">
                            <?php
                            echo "<tr><td>$count</td>";
                           /* echo" <td><input type='checkbox' class='checkbox_class' name='formfour[]' value='$formfour'></td>";*/
                            echo "<td>$formfour</td>
                        <td>$status</td>
                        </tr>";
                            $count++;
                        }
                        ?></tbody>
                    </table>
                    <div class="row">
                        <div class="col-lg-6"></div>
<!--                        <div class="col-lg-3">-->
<!--                            <input type="hidden" name="action_type" value="add"/>-->
<!--                            <input type="submit" name="doAdmit" value="Save Records" class="btn btn-success form-control">-->
<!--                        </div>-->
                    </div>
                </form>
            </div>
        </div>

        <?php
    }
    ?>
</div>
