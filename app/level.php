<?php $db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
        <style type="text/css">
            #message{
                font-size: 12px;
            }
        </style>
 <div class="page-title">
          <div>
            <h1><i class="fa fa-th-list"></i> Choose Study Level</h1>
            <p>Start your aplication</p>
          </div>
        </div>

<div class="row">
          <div class="col-md-10">
            <div class="card">
                <form class="form-horizontal" name="" action="action_study_level.php" method="post">           
            
         <div class="col-lg-12">
                <?php 
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="unsucc") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Invalid Data,Try again later</strong>.
                    </div>";
                      }
                      else if($_REQUEST['msg']=="error") {
                          echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                        <strong>Sory, Error-Something wrong happen-Contact System Administrator for this Message</strong>.
                    </div>";
                      }
                    }
                ?> 
                </div>
                <?php 
                      $level=$db->getRows("applicantstudylevel", array('where'=>array('applicantID'=>$_SESSION['applicantID']),'order_by applicantID ASC'));
                      if(!empty($level))
                      {
                          foreach ($level as $lvl)
                          {
                              $applicantStudyLevelID=$lvl['applicantStudyLevelID'];
                              $qualificationTypeID=$lvl['qualificationTypeID'];
                              $studyLevelID=$lvl['studyLevelID'];
                          }
                      }
                ?>
              <div class="box-body">
                <div class="form-group">
                    <label for="qualificationType" class="col-sm-4">What is the highest level of Education you have?<br>
                        <div id="message" class="text-success">(We will assess you based on this level)</div>
                    </label>
                  <div class="col-sm-8">
                      <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" required="required">
                         <?php 
                         if(!empty($level)){
                         ?>
                          <option value="<?php echo $qualificationTypeID; ?>" selected="selected"><?php echo $db->getData("qualificationtype", "qualificationName", "qualificationTypeID", $qualificationTypeID);?></option>
                         <?php } else {?>
                          
                          <option value="">Select Qualification Type</option>
                         <?php }?>
                        <?php
                        $qualificationType = $db->getRows('qualificationtype',array('order_by'=>'`rank` ASC'));
                        if(!empty($qualificationType)){ $count = 0; foreach($qualificationType as $type){ $count++;
                         $qualificationName=$type['qualificationName'];
                         $qualificationID=$type['qualificationTypeID'];
                        ?>
                        <option value="<?php echo $qualificationID;?>"><?php echo $qualificationName;?></option>
                        <?php }}?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="studyLevel" class="col-sm-4">What level do you want to apply</label>

                  <div class="col-sm-8">
                      <select name="studyLevelID" id="studyLevelID" class="form-control" required="required">
                          <?php 
                         if(!empty($level)){
                         ?>
                          <option value="<?php echo $studyLevelID;?>" selected="selected"><?php echo $db->getData("studylevels", "studyLevelName", "studyLevelID", $studyLevelID) ?></option>
                          <?php } else {?>  
                          <option value="">--Select Study Year--</option>
                          <?php }?>
                      </select>
                  </div>
                </div>
              </div>
                        
                        <div class="row">
                        <div class="col-lg-6"></div>
                        <?php 
                        if(!empty($level))
                        {
                            ?>
                            <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="edit"/>
                            <input type="hidden" name="applicantStudyLevelID" value="<?php echo $applicantStudyLevelID;?>">
                            <input type="submit" name="doSubmit" value="Save & Continue" class="btn btn-success form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" name="doExit" value="Save & Exit" class="btn btn-danger form-control" />
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
                            <input type="submit" name="doExit" value="Save & Exit" class="btn btn-danger form-control" />
                        </div>
                        <?php }?>
                        </div>
</form> 
</div>
    </div>
                    </div>