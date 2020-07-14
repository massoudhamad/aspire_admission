<script type="text/javascript">
  $(document).ready(function () {
      var titleheader = $('#titleheader').text();
      var programmeID=$("#programmeID").val();
      var academicYearID=$("#academicYearID").val();
      //alert(programmeID);
      //alert(academicYearID);
            $('#selection_list').DataTable(
                {
                 ajax:
                  {
                        type: 'GET',
                        //url: 'data/selection_list_direct.php?programmeID='+ programmeID +'academicYearID='+ academicYearID,
                        //data:'programmeID='+programmeID+'academicYearID='+academicYearID,
                        url: 'data/enrollmentreport.php',
                        data:{programmeID:programmeID,academicYearID:academicYearID},
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
                                columns:[0,1,2,3,4,5,6,7,8,9,10,11,12]
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
                            	columns:[0,1,2,3,4,5,6,7,8,9,10,11,12]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: titleheader,
                            footer: true,
                           exportOptions: {
                        	   columns:[0,1,2,3,4,5,6,7,8,9,10,11,12]
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
<div class="col-lg-4">

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
<div class="col-lg-4">

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
 <div class="col-lg-4">
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
            
        ?>
        <input type="hidden" id="programmeID" value="<?php echo $programmeID;?>">
         <input type="hidden" id="academicYearID" value="<?php echo $academicYearID;?>">
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Selected Applicants for <?php echo $db->getData("sector","sectorName","sectorID",$programmeID); ?>
            <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4> 
        </div>
        <table id="selection_list" class="display nowrap" cellspacing="0">
          <thead>
           <tr>
               <th>First Name</th>
               <th>Middle Name</th>
               <th>Last Name</th>
               <th>Gender</th>
               <th>Form IV</th>
               <th>Other Form IV</th>
               <th>Form VI</th>
               <th>AVN</th>
               <th>Programme Code</th>
               <th>Programme Name</th>
               <th>Institution Name</th>
               <th>Institution Code</th>
               <th>Registration Number</th>
               
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