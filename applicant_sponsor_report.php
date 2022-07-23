<script type="text/javascript">
  $(document).ready(function () {
      var titleheader = $('#titleheader').text();
      var programmeID=$("#programmeID").val();
      var academicYearID=$("#academicYearID").val();
      var admissionID=$("#admissionID").val();
      var sponsor=$("#sponsor").val();
      //alert(programmeID);
      //alert(academicYearID);
            $('#selection_list').DataTable(
                {
                 ajax:
                  {
                        type: 'GET',
                        //url: 'data/selection_list_direct.php?programmeID='+ programmeID +'academicYearID='+ academicYearID,
                        //data:'programmeID='+programmeID+'academicYearID='+academicYearID,
                        url: 'data/sponsor_report.php',
                        data:{programmeID:programmeID,academicYearID:academicYearID,admissionID:admissionID,sponsor:sponsor},
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
                                columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13]
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
                            	columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: titleheader,
                            footer: true,
                           exportOptions: {
                        	   columns:[0,1,2,3,4,5,6,7,8,9,10,11,12,13]
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

                <label for="MiddleName">Organization Name</label>
                <select name="sectorID" class="form-control chosen-select" required="">
                    <?php
                    $adYear = $db->getRows('sector', array('order_by' => 'sectorName ASC'));
                    if (!empty($adYear)) {
                        echo "<option value=''>Please Select Here</option>";
                        $count = 0;
                        foreach ($adYear as $year) {
                            $count++;
                            $programName = $year['sectorName'];
                            $programID = $year['sectorID'];
                    ?>
                            <option value="<?php echo $programID; ?>"><?php echo $programName; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-3">

                <label for="MiddleName">Admission Year</label>
                <select name="sponsor" class="form-control" required="">
                    <option value="">Select Here</option>
                    <option value="Self">Self Financed</option>
                                    <option value="ZHELB">ZHEB</option>
                                    <option value="HESLB">HESLB</option>
                                    <option value="4">Direct Aid</option>
                                    <option value="others">Others</option>
                </select>
            </div>
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
                            $admissionInTakeID = $ait['admissionInTakeID'];
                            $admissionName = $ait['admissionName'];
                    ?>
                            <option value="<?php echo $admissionID; ?>"><?php echo $admissionName; ?></option>
                            <!--<option value="<?php /*echo $admissionID;*/ ?>"><?php /*echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);*/ ?></option>-->
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
        if(isset($_POST['doSearch'])=="Search Records")
        {
           $programmeID = $_POST['sectorID'];
           $admissionID = $_POST['admissionID'];
           $sponsor=$_POST['sponsor'];
           $academicYearID = $db->getData('admission_setting', 'academicYearID', 'admissionID', $admissionID);
           $admissionName = $db->getData('admission_setting', 'admissionName', 'admissionID', $admissionID);

            
        ?>
        <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">
         <input type="hidden" id="academicYearID" value="<?php echo $academicYearID;?>">
            <input type="hidden" id="admissionID" value="<?php echo $admissionID;?>">
            <input type="hidden" id="sponsor" value="<?php echo $sponsor;?>">

        <div class="col-lg-12">
            <h4><span id="titleheader">List of Applicants for <?php echo $db->getData("sector","sectorName","sectorID",$programmeID); ?>
            <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4> 
        </div>
        <table id="selection_list" class="display nowrap" cellspacing="0">
          <thead>
           <tr>
               <th>First Name</th>
               <th>Middle Name</th>
               <th>Last Name</th>
               <th>Gender</th>
               <th>DoB</th>
               <th>Email</th>
               <th>Phone Number</th>
               <th>Form IV</th>
               <th>Form IV Div</th>
               <th>Form IV Points</th>
               <th>Form VI</th>
               <th>Form VI Div</th>
               <th>Form VI Points</th>
               <th>Prog.Name</th>
               <th>Applicant Category</th>
               <th>Region</th>
               <th>Comments</th>
           </tr>     
         </thead>
         <tbody>
             
         <?php
              
         ?> 
         </tbody>
        </table>
        <?php
        }
        ?>
        
    </div>
    
</div>