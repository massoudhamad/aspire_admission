<?php 
$db=new DBHelper();
if(isset($_POST['doProceed']))
{
    $db->redirect("index2.php?sz=registration_form");
}
if(isset($_POST['doExit']))
{
    $db->redirect("logout.php?logout=true");
}
$applicantID=$_SESSION['applicantID'];
?>

<div class="row">
    <div class="col-lg-12">
        <table class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>Programme Name</th>
                        <th>Status</th>
                        <th>Comments</th>
                      </tr>
                      </thead>
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
                      <?php 
                          if($db->checkProgrammeChoice($firstChoice,$applicantID))
                          {
                              $status="Qualify";
                              $comments="Proceed to Next Step";
                          }
                          else
                          {
                              $status="Not Qualify";
                              $comments="Please change this programme";
                          }
                     ?>
                      <?php 
                          if($db->checkProgrammeChoice($secondChoice,$applicantID))
                          {
                              $status2="Qualify";
                              $comments2="No Comments";
                          }
                          else
                          {
                              $status2="Not Qualify";
                              $comments2="Please change this programme";
                          }
                     ?>
                      <tbody>
                          <tr>
                              <td>First Choice:<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstChoice);?></td>
                              <td>
                                  <?php echo $status;?>
                              </td>
                              <td>
                                  <?php echo $comments;?>
                              </td>
                              
                          </tr>
                          <tr>
                              <td>Second Choice:<?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondChoice);?></td>
                              <td>
                                  <?php echo $status2;?>
                              </td>
                              <td>
                                  <?php echo $comments2;?>
                              </td>
                              
                          </tr>
                      </tbody>
        </table>
          
        
    </div>
     <form name="" method="post" action="">
    <div class="col-lg-3"></div>
                        <div class="col-lg-3">
                            <a href="index2.php?sz=programmechoice"><span class="btn btn-success form-control">Previous</span></a>
                        </div>
                        
                        <?php 
                        if($status=="Qualify")
                        {
                        ?>
                            <div class="col-lg-3">
                            <input type="submit" name="doProceed" value="Proceed to Application" class="btn btn-success form-control" />
                        </div>
                        <?php }?>
                        <div class="col-lg-3">
                            <input type="submit" name="doExit" value="Exit & Continue Later" class="btn btn-success form-control" />
                        </div>
     </form>
</div>