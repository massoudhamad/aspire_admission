<script type="text/javascript">
    $(document).ready(function() {
        var titleheader = $('#titleheader').text();
        var admissionID = $("#admissionID").val();
        $('#selection_list').DataTable({
            ajax: {
                type: 'GET',
                url: 'data/nhif_report.php',
                data: {
                    admissionID: admissionID
                },
                "serverSide": true,
                cache: false
            },
            "scrollX": true,
            paging: true,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: titleheader,
                    customize: function(csv) {
                        return titleheader + "\n" + csv + "\n";
                    }
                },
                {
                    extend: 'print',
                    title: titleheader,
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
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
        <form name="" method="post" action="">

            <div class="col-lg-3">

                <label for="MiddleName">Admission Intake</label>
                <select name="admissionID" class="form-control" required="">
                    <?php
                    $aitake = $db->getRows('admission_setting', array('order_by' => 'academicYearID ASC'));
                    if (!empty($aitake)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($aitake as $ait) {
                            $count++;
                            $admissionID = $ait['admissionID'];
                            $admissionName = $ait['admissionName'];
                    ?>
                            <option value="<?php echo $admissionID; ?>">
                                <?php echo $admissionName; ?></option>
                    <?php }
                    }
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
        if (isset($_POST['doSearch']) == "Search Records") {
            $admissionID = $_POST['admissionID'];

        ?>
            <input type="hidden" id="admissionID" value="<?php echo $admissionID; ?>">

            <div class="col-lg-12">
                <h4><span id="titleheader">List of Selected Applicants
            </div>
            <table id="selection_list" class="display nowrap" cellspacing="0">
                <thead>
                    <tr>
                        <th>Form Four</th>
                        <th>RegNumber</th>
                        <th>Admission Date</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>DOB</th>
                        <th>Marital Status</th>
                        <th>Gender</th>
                        <th>Mobile No</th>
                        <th>Programme Name</th>
                        <th>College</th>
                        <th>YearOfStudy</th>
                        <th>Course Duration</th>
                        <th>National ID</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        <?php
        }
        ?>

    </div>

</div>
<!--<script type="text/javascript">
  $(document).ready(function () {
      var titleheader = $('#titleheader').text();
      var programmeID=$("#programmeID").val();
      var academicYearID=$("#academicYearID").val();
            $('#selection_list').DataTable(
                {
                 ajax:
                  {
                        type: 'GET',
                        url: 'data/tcu_enrollment.php',
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
                                columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                            }
                        },
                        {
                          extend:'csvHtml5',
                          title: 'TCU Report',
                          customize: function (csv) {
                          return titleheader+"\n"+  csv +"\n";
                        }
                        },
                        {
                            extend: 'print',
                            title: titleheader,
                            footer: false,
                            exportOptions: {
                            	columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: titleheader,
                            footer: true,
                           exportOptions: {
                        	   columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                            },
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<?php /*
$db = new DBHelper();

*/ ?>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>    
    <br><br>
    <div class="row">
        <table id="selection_list" class="display nowrap" cellspacing="0">
          <thead>
           <tr>
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
               <th>Enrollement Year</th>
               <th>Form IV</th>
               <th>Award Name</th>
               <th>Registration Number</th>
               <th>Institution Code</th>
               <th>Programme Code</th>
           </tr>     
         </thead>
         <tbody>
         </tbody>
        </table>
        
    </div>
    
</div>-->