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
<!--    <div class="row">
        <form name="" method="post" action="">
            <div class="col-lg-3">

                <label for="MiddleName">Programme Name</label>
                <select name="programmeID" class="form-control chosen-select" required="">
                    <?php
/*                    $adYear = $db->getMainProgrammes();
                    if(!empty($adYear)){
                        echo"<option value=''>Please Select Here</option>";
                        $count = 0; foreach($adYear as $year){ $count++;
                            $programCode=$year['programCode'];
                            $programName=$year['programName'];
                            */?>
                            <option value="<?php /*echo $programCode;*/?>"><?php /*echo $programName;*/?></option>
                        <?php /*}}
                    */?>
                </select>
            </div>
    </div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-3">
            <label for=""></label>

            <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
        </div>
    </div>-->
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
   /* if(isset($_POST['doSearch'])=="Search Records") {
        $programmeID = $_POST['programmeID'];

        */?><!--
        <input type="hidden" id="programmeID" value="<?php /*echo $programmeID;*/?>">-->

        <!--<h2>List of Confirmed for TCU Status</h2>
        <hr>-->
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Internal Transfer Applicants for 2019/2020</span></h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <form name="register" id="register" method="post" action="action_internal_transfer_tcu.php">
                <table id="tcu_check_status" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
                        <th>Form Four</th>
                        <th>Form Six</th>
                        <th>Programme Admitted</th>
                        <th>Transfered Programme</th>
                        <th>TCU Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $db=new DBHelper();
                    $transferList=$db->getTransferredListProgrammeCode('Internal');
                    foreach($transferList as $app) {
                        $count++;
                        $formfour = $app['formFour'];
                        $formsix = $app['formSix'];
                        $before = $app['bProgrammeCode'];
                        $after = $app['aProgrammeCode'];
                        ?>
                        <input type='text' hidden name='formsix[]' value='<?php echo $formsix;?>'>
                        <input type='text' hidden name='before[]' value='<?php echo $before;?>'>
                        <input type='text' hidden name='after[]' value='<?php echo $after;?>'>
                        <?php
                        echo "<tr><td>$count</td>";
                            echo" <td><input type='checkbox' class='checkbox_class' name='formfour[]' value='$formfour'></td>";

                        echo "<td>$formfour</td>";
                        echo "<td>$formsix</td>";
                        echo "<td>$before</td>";
                        echo "<td>$after</td>";
                        echo "<td>".$app['tcu_status']."</td>";
                        echo"</tr>";
                        $count++;
                    }
                    ?></tbody>
                </table>
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-3">
                        <input type="hidden" name="action_type" value="add"/>
                        <input type="submit" name="doAdmit" value="Save Records" class="btn btn-success form-control">
                    </div>
                </div>
                </form>
            </div>
        </div>

        <?php
    //}
    ?>
</div>
