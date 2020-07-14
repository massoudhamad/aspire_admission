<?php 
$db=new DBHelper();
$applicantID=$_SESSION['applicantID'];
?>
<div class="row">
    <div class="col-lg-12">
    <div class="well">
        <h4>Application Submitted</h4>
        <p class="text-justify">
         <?php
           $applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'applicantID ASC'));
           if(!empty($applicantsData)){ 
           foreach($applicantsData as $apps)
           {
               
               $gender=$apps['gender'];
               $fname=$apps['firstName'];
               $mname=$apps['middleName'];
               $lname=$apps['lastName'];
               if($gender=="Male")
                   $sex="Mr";
               else 
                   $sex="Ms";
               $name="$sex $fname  $mname $lname";
          ?>
            Congratulation <b><?php echo $name;?></b>. Your application has been received.
         <?php
           }
         }
         ?>
        </p>
        <h4>Application Summary</h4>
        <p>Ref.Number:<b class="text text-danger"><?php echo $db->getData("applicants","refNumber","applicantID",$applicantID); ?></b></p>
        <p>Programme(s) Applied:
        <?php 
                      $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'choice'=>1),'order_by applicantID ASC'));
                      if(!empty($programmeChoice))
                      {
                          foreach ($programmeChoice as $pChoice)
                          {
                              $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                              $firstChoice=$pChoice['programmeMajorID'];
                             
                          }
                      }
                      
                      $programmeChoice2=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'choice'=>2),'order_by applicantID ASC'));
                      if(!empty($programmeChoice2))
                      {
                          foreach ($programmeChoice2 as $pChoice2)
                          {
                              $applicantApplicationIDSecond=$pChoice2['applicantApplicationID'];
                              $secondChoice=$pChoice2['programmeMajorID'];
                             
                          }
                      }
                ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="FirstName">First Choice Programme</label>
                            <input type="text" name="" value="<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstChoice);?>" class="form-control" disabled="">
                            
                        </div>
                        
                         <div class="col-lg-6">
                            <label for="Physical Address">Second Choice Programme</label>
                             <input type="text" name="" value="<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondChoice);?>" class="form-control" disabled="">
                            
                        </div>
                        
                    </div>
                    
        <br><br>
        <div class="row">
            <div class="col-lg-5"></div>
            <div class="col-lg-4 pull-right"><a href="app/printreceipt.php?action=getPDF&applicantID=<?php echo $applicantID;?>" target="_blank"><span class="btn btn-success">Print Application Receipt</span></a>
            </div>
        </div>
        
    </div>
    </div>
</div>