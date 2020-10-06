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
    
    
    
<div class="row">
    <form name="" method="post" action="">
<div class="col-lg-4">

                            <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control chosen-select" required="">
                              <?php
                               $adYear = $db->getRows('programs',array('order_by'=>'programID ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $programName=$year['programName'];
                                $programID=$year['programID'];
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
    </div>
    </form>
    
    <br><br>
    <div class="row">
        
        <?php 
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $programmeID=$_POST['programmeID'];
            $academicYearID=$_POST['admissionYearID'];
        ?>
         <div class="col-lg-12">
            <h4><span id="titleheader">List of Admitted Applicants for <?php echo $db->getData("programs","programName","programID",$programmeID);?>-
                <?php echo $db->getData("academicyears","academicYear","academicYearID",$academicYearID);?></span></h4> 
        </div>
        <form name="register" id="register" method="post" action="action_register_applicant.php">
        <table id="admit" class="display nowrap" cellspacing="0">
            
          <thead>
           <tr>
               <th width="5">SNo</th>
<!--               <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
-->               <th>Name</th>
<th>Index Number</th>
               <th>Sex</th>
               <th>Phone Number</th>
               <th>Index Number</th>
               <th>Ref.Number</th>
           </tr>     
         </thead>
         
         <tbody>
         <?php
                 $applicantsData=$db->getAdmittedForRegistration($programmeID,$academicYearID);
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
                         $name="$fname $mname $lname";
                         $formfour=$data['formfour'];
                         
                         echo "<tr><td>$i</td>";
                         /*<td><input type='checkbox' class='checkbox_class' name='id[]' value='$applicantID'></td>*/
                                echo "<td><a href='index3.php?sp=view_applicant_info&applicantID=$applicantID'>$name</a></td>";
                                 echo "<td>$gender</td><td>$formfour</td><td>$phoneNumber</td><td>".$db->getData("users","userName","userID",$userID)."</td><td>$refNumber</td></tr>";
                     }
                 }
                
                 
         ?> 
         </tbody>
        </table>
        <div class="row">
            <div class="col-lg-6"></div>
            <input type="hidden" name="number_applicants" value="<?php echo $i;?>">
            <input type="hidden" name="academicYearID" value=<?php echo $academicYearID;?>>
            <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
            <input type="hidden" name="choice" value="<?php echo $choice;?>">
            <div class="col-lg-3">
                <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doAdmit" value="Register Applicants" class="btn btn-success form-control">
            </div>
            <!--  <div class="col-lg-3">
                 <input type="hidden" name="action_type" value="edit"/>
                <input type="submit" name="doReject" value="Reject Applicants" class="btn btn-danger form-control">
            </div> -->
        </div>
            </form>
        <?php
        }
        ?>
        
    </div>
   
</div>