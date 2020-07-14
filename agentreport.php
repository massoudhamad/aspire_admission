<?php 
$db = new DBHelper();
?>
<script type="text/javascript">
 
  $(document).ready(function () {
         var titleheader = $('#titleheader').text();
            $('#admit').dataTable(
                {
                    paging: false,
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
<div class="row">
        <div class="col-md-12">
            <br>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Department data has been inserted successfully</strong>.
</div>";
  }
  else
  {
    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="container">
    <h4>View List of Applicants</h4>
    <hr>
    
    
<form name="" method="post" action="">   
<div class="row">
   
<div class="col-lg-3">

                            <label for="MiddleName">Agent Name</label>
                            <select name="agentID" class="form-control chosen-select" required>
                              <?php
                               $adYear = $db->getRows('agents',array('order_by'=>'agentName ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $programName=$year['agentName'];
                                $programID=$year['agentID'];
                               ?>
                               <option value="<?php echo $programID;?>"><?php echo $programName;?>-<?php echo $year['agentAddress']?></option>
                               <?php }}
           ?>
                           </select>
</div>
<div class="col-lg-3">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
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
    </div>
    </form>
    
    <br><br>
    <div class="row">
        
        <?php 
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $agentID=$_POST['agentID'];
            $academicYearID=$_POST['admissionYearID'];
            $admissionID=$_POST['admissionID'];
            $admissionInTakeID=$db->getData('admission_setting','admissionInTakeID','admissionID',$admissionID);

            ?>
         <div class="col-lg-12">
            <h4><span id="titleheader">List of Applicants for <?php echo $db->getData("agents","agentName","agentID",$agentID);?>-
                     <?php echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID); ?> <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4>
        </div>
        <table id="admit" class="display nowrap">
            
          <thead>
           <tr>
               <th width="5">SNo</th>
               <th>Name</th>
               <th>Sex</th>
               <th>Phone Number</th>
               <th>Programme Name</th>
               <th>Index Number</th>
               <th>Ref.Number</th>
               <th>Status</th>
           </tr>     
         </thead>
         
         <tbody>
         <?php
         $applicantsData=$db->getApplicantByAgents($agentID,$academicYearID,$admissionID);
                 if(!empty($applicantsData))
                 {
                     foreach ($applicantsData as $data)
                     {
                         $i++;
                         $applicantID=$data['applicantID'];
                         $fname=$data['firstName'];
                         $mname=$data['middleName'];
                         $lname=$data['lastName'];
                         $gender=$data['gender'];
                         $refNumber=$data['refNumber'];
                         $phoneNumber=$data['phoneNumber'];
                         $userID=$data['userID'];
                         $indexNumber=$data['userName'];
                         $programName=$data['programName'];
                         $name="$fname $mname $lname";
                         $applicantRemarksID=$data['applicantsRemarksID'];
                         $remark=$db->getData("remarks","remark","remarkID",$applicantRemarksID);
                         echo "<tr><td>$i</td><td>$name</td>"
                                 . "<td>$gender</td><td>$phoneNumber</td><td>$programName</td><td>$indexNumber</td><td>$refNumber</td>
                                  <td>$remark</td></tr>";
                     }
                 }
                
                 
         ?> 
         </tbody>
        </table>
       
        <?php
        }
        ?>
        
    </div>
   
</div>