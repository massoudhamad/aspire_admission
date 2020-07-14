<?php $db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<link href="css/validation.css" rel="stylesheet">    
<script type="text/javascript">
$(function(){
$("#chosen").chosen();
    $.validator.setDefaults({ ignore: ":hidden:not(select)" })
    $("#register").validate({
        rules: {chosen:"required"},
        message: {chosen:"Select Programmes"}
    });
});
        </script>

<div class="row" style="padding-bottom: 23%;">

<form class="form-horizontal" name="register" id="register" action="action_programme_choice.php" method="post">   
<div class="col-lg-12">    
        
                
              <div class="well">
                  <fieldset>
                  <legend>Please Choose Study Programme</legend>
                  <div class="col-lg-12">
                <?php 
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="unsucc") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Invalid Data, Try again later</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="error") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Error-Something wrong happen-Contact System Administrator</strong>.
                    </div>";
                      }
                    }
                ?> 
                </div>
                <?php 
                $applicantID=$_SESSION['applicantID'];
                $studyLevelID=$db->getData("applicantstudylevel","studyLevelID","applicantID",$_SESSION['applicantID']);
                      $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'choice'=>1),'order_by applicantID ASC'));
                      if(!empty($programmeChoice))
                      {
                          foreach ($programmeChoice as $pChoice)
                          {
                              $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
                              //$firstChoice=$pChoice['programID'];
                              $firstMajor=$pChoice['programmeMajorID'];
                          }
                      }
                      
                      $programmeChoice2=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'choice'=>2),'order_by applicantID ASC'));
                      if(!empty($programmeChoice2))
                      {
                          foreach ($programmeChoice2 as $pChoice2)
                          {
                              $applicantApplicationIDSecond=$pChoice2['applicantApplicationID'];
                              //$secondChoice=$pChoice2['programID'];
                              $secondMajor=$pChoice2['programmeMajorID'];
                          }
                      }
                ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="FirstName">First Choice Programme</label>
                            <select name="programmeMajorID" id="programmeID" class="form-control chosen-select" required="">
                                <?php 
                                if(!empty($programmeChoice))
                                {
                                ?>
                                 <option value="<?php echo $firstMajor; ?>" selected="selected"><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID",$firstMajor);?></option>  
                                <?php
                                }
                               else
                               {
                                   echo"<option value=''>Please Select Here</option>";
                               }
                            
                            
                            //$programmes = $db->getRows('programs',array('where'=>array('studyLevelID'=>$studyLevelID),'order_by'=>'studyLevelID ASC'));
                            //$programmes=$db->getProgrammeChoice($studyLevelID,$_SESSION['applicantID']);
                            $programmes=$db->getProgrammeMajor($studyLevelID);
                            if(!empty($programmes)){ 
                              foreach($programmes as $prg)
                               { 
                                  $programmeName=$prg['programmeMajor'];
                                  $programmeID=$prg['programmeMajorID'];
                                  echo "<option value='$programmeID'>$programmeName</option>";
                               }

                               }
                               
                            /* $programmeData=$db->programmeChoice($studyLevelID,$_SESSION['applicantID']);
                             if(!empty($programmeData))
                             {
                                 foreach($programmeData as $pd)
                               { 
                                  $programmeName=$pd['programmeMajor'];
                                  $programmeID=$pd['programmeMajorID'];
                                  echo "<option value='$programmeID'>$programmeName</option>";

                               }
                             }*/
                               ?> 
                            </select>
                        </div>
                        
                        <div class="col-lg-6">
                            <label for="Physical Address">Second Choice Programme</label>
                            <select name="secondProgrammeMajorID" id="secondProgrammeID" class="form-control chosen-select" required="">
                                <?php 
                               if(!empty($programmeChoice2))
                               {?>
                                <option value="<?php echo $secondMajor; ?>" selected="selected"><?php echo $db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondMajor);?></option>
                            <?php 
                               }
                               else 
                               {
                                   echo"<option value=''>Please Select Here</option>";
                               }
                           // $programmes=$db->getProgrammeChoice($studyLevelID,$_SESSION['applicantID']);
                            $programmes=$db->getProgrammeMajor($studyLevelID);
                            if(!empty($programmes)){ 
                              foreach($programmes as $prg)
                               { 
                                  $programmeName=$prg['programmeMajor'];
                                  $programmeID=$prg['programmeMajorID'];
                                  echo "<option value='$programmeID'>$programmeName</option>";

                               }

                               }
                               
                            /* $programmeData=$db->programmeChoice($studyLevelID,$_SESSION['applicantID']);
                             if(!empty($programmeData))
                             {
                                 foreach($programmeData as $pd)
                               { 
                                  $programmeName=$pd['programmeMajor'];
                                  $programmeID=$pd['programmeMajorID'];
                                  echo "<option value='$programmeID'>$programmeName</option>";

                               }
                             }*/
                               ?> 
                            </select>
                        </div>
                    </div>
                        </fieldset>
                        
                   </div>                
</div>
                        <div class="col-lg-3"></div>
                        <div class="col-lg-3">
                            <a href="index2.php?sz=education_background"><span class="btn btn-success form-control">Previous</span></a>
                        </div>
                        
                        <?php 
                        if(!empty($programmeChoice) || !empty($programmeChoice2))
                        {
                            ?>
                            <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="edit"/>
                            <input type="hidden" name="firstID" value="<?php echo $applicantApplicationIDFirst;?>">
                            <input type="hidden" name="secondID" value="<?php echo $applicantApplicationIDSecond;?>">
                            <input type="submit" name="doSubmit" value="Save & Continue" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" name="doExit" value="Save & Exit" class="btn btn-success form-control" />
                        </div>
                        <?php
                        }
                        else
                        {
                        ?>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doSubmit" value="Save & Continue" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" name="doExit" value="Save & Exit" class="btn btn-success form-control" />
                        </div>
                        <?php }?>
                        
                       
</form> 
                    </div>
                        
