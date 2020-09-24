<?php 
$db = new DBHelper();
?>
<script type="text/javascript">
 
  $(document).ready(function () {
         var titleheader = $('#titleheader').text();
            $('#admit').dataTable(
                {
                    paging: true,
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

<div class="container">
    <h4>View List of Applicants</h4>
    <hr>
<div class="row">
    <form name="" method="post" action="">
<div class="col-lg-3">
                        <div class="form-group">
                          <label for="qualificationType">Applicant Remarks</label>
                          <select name="remarksID" class="form-control" required="">
                              <option value="">Select Remarks</option>
                        <?php echo $db->getData("remarks","remark","remarkID",$applicantRemarksID);?></option>
                        <?php
                        $remarks = $db->getRemarks();
                        if(!empty($remarks)){ $count = 0; foreach($remarks as $rmk){ $count++;
                         $remark=$rmk['remark'];
                         $remarkID=$rmk['remarkID'];

                        ?>
                        <option value="<?php echo $remarkID;?>"><?php echo $remark;?></option>
                         <?php
                         
                         }
                        
                        }?>
                          </select>
                        </div></div>
        
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
                        $admissionName=$ait['admissionName'];
                        ?>
                        <option value="<?php echo $admissionID;?>"><?php echo $admissionName;//$db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID);?></option>
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
            $remarksID=$_POST['remarksID'];
            $admissionYearID=$_POST['admissionYearID'];
            $admissionID=$_POST['admissionID'];
            $admissionInTakeID=$db->getData('admission_setting','admissionInTakeID','admissionID',$admissionID);
        ?>
         <div class="col-lg-12">
             <h4><span id="titleheader">List of <?php echo $db->getData("remarks","remark","remarkID",$remarksID) ;?> Applicants in
                     <?php echo $db->getData('admission_intake',"admissionInTake","admissionInTakeID",$admissionInTakeID); ?> <?php echo $db->getData("academicyears","academicYear","academicYearID",$admissionYearID);?></span></h4>
        </div>
        <form name="register" id="register" method="post" action="change_remarks.php">
        <table id="admit" class="display nowrap" cellspacing="0">
            
          <thead>
          <tr>
               <th>No.</th>
            <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
            <th>Name</th>
            <th>Gender</th>
            <th>Phone Number</th>
            <th>Ref.Number</th>
            <th>Programme</th>
            <th>Comments</th>
            <th>Details</th>
            <th>Drop</th>   
     </tr>
         </thead>
         
         <tbody>
         <?php
                $applicantsData=$db->getRows("applicants",array('where'=>array('applicantsRemarksID'=>$remarksID,'applicationYearID'=>$admissionYearID,'admissionID'=>$admissionID),'order_by firstName ASC'));
if(!empty($applicantsData))
{
    $x=0;
    foreach ($applicantsData as $row) 
    {
        $x++;
        $applicantID=$row['applicantID'];
        $_SESSION['applicantID']=array();
        $userID=$row['userID'];
        $indexNumber=$db->getData("users","userName","userID",$userID);
               $fname= $row['firstName'];
               $mname=$row['middleName'];
               $lname=$row['lastName'];
               $refNumber=$row['formfour'];
               $name="$fname $mname $lname";
    
               $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$applicantID,'choice'=>1),'order_by applicantID ASC'));
                      if(!empty($programmeChoice))
                      {
                          foreach ($programmeChoice as $pChoice)
                          {
                              $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                              $firstChoice=$pChoice['programmeMajorID'];
                              $programmeChoice=$db->getData("programmemajor","programmeMajor","programmeMajorID",$firstChoice);
                          }
                      }
               
	 $actionButton = '
	<div class="btn-group">
	    <a href="index3.php?sp=applicantinfo&applicantID='.$row['applicantID'].'"><span class="glyphicon glyphicon-edit"></span> Details</a>
	</div>'; 

        $dropButton = '
	<div class="btn-group">
	    <a href="delete_application.php?action_type=delete&applicantID='.$row['applicantID'].'" class="glyphicon glyphicon-trash" onclick="return confirm("Are you sure You want to delete this applicant?");">Drop</a>
    </div>';

                    $appremarks = $db->getRows("applicantremarks", array('where' => array('applicantID' => $applicantID)));
                    if (!empty($appremarks)) {
                        foreach ($appremarks as $remark) {
                            $userID = $remark['userID'];
                            $processDate = $remark['processDate'];
                            $comments=$remark['comments'];
                        }
                    } else {
                        $userID = "";
                        $processDate = "";
                    }

        echo "<tr><td>$x</td><td><input type='checkbox' class='checkbox_class' name='id[]' value='$applicantID'></td><td>$name</td><td>".$row['gender']."</td><td>".$row['phoneNumber']."</td><td>".$row['formfour']."</td><td>".$programmeChoice."</td><td>$comments</td><td>".$actionButton."</td><td>".$dropButton."</td></tr>";
}
}
                
                 
         ?> 
         </tbody>
        </table>
            <div class="row">
            <div class="col-lg-6"></div>
            <input type="hidden" name="number_applicants" value="<?php echo $i;?>">
             <div class="col-lg-3">
                 <input type="hidden" name="action_type" value="add"/>
                <input type="submit" name="doUpdate" value="Change" class="btn btn-primary form-control">
            </div>
                <div class="col-lg-3">
                    <input type="hidden" name="action_type" value="drop_app"/>
                    <input type="submit" name="doDrop" value="Drop" class="btn btn-danger form-control">
                </div>
        </div>
            </form>
        <?php
        }
        ?>
        
    </div>
    
</div>