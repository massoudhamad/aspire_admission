<?php $db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/script.js"></script>
        <style type="text/css">
            #message{
                font-size: 12px;
            }
        </style>
<?php
/* Detect whether this tenant is LSZ — if so, this page is reframed
   as "Confirm Your Legal Qualification" and the qualification list is
   filtered to LSZ-relevant entries (Law Degree / Master of Law /
   Diploma). Other tenants fall through to the legacy generic page. */
$LSZ_MODE = false;
try {
    $org = $db->getRows("organization");
    if (!empty($org[0]['organizationCode']) && strtoupper($org[0]['organizationCode']) === 'LSZ') {
        $LSZ_MODE = true;
    }
} catch (Throwable $e) { /* legacy fallback */ }
?>
<div class="page-title">
  <div>
    <?php if ($LSZ_MODE): ?>
      <h1><i class="fa fa-balance-scale"></i> Confirm Your Legal Qualification</h1>
      <p>Tell us which legal qualification you hold so we can match you to the right programme.</p>
    <?php else: ?>
      <h1><i class="fa fa-th-list"></i> Choose Study Level</h1>
      <p>Start your application</p>
    <?php endif; ?>
  </div>
</div>

<?php if ($LSZ_MODE): ?>
<div class="alert alert-info" style="margin:0 0 18px 0;">
  <strong>The Law School of Zanzibar offers two professional programmes:</strong>
  <ul style="margin:8px 0 0 18px;">
    <li><strong>Certified Legal Professional (CLP)</strong> &mdash; for holders of a <em>Bachelor Degree of Law</em> or higher. Leads to the Post Graduate Certificate of Competence to qualify as an <strong>Advocate</strong>.</li>
    <li><strong>Vakil Course</strong> &mdash; for holders of a <em>Diploma in Law</em> or equivalent. Leads to the Certificate of Competence to qualify as a <strong>Vakil</strong>.</li>
  </ul>
</div>
<?php endif; ?>

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
                    <label for="qualificationType" class="col-sm-4">
                        <?php if ($LSZ_MODE): ?>
                            Which legal qualification do you hold?<br>
                            <div id="message" class="text-success">(We will assess you based on this qualification)</div>
                        <?php else: ?>
                            What is the highest level of Education you have?<br>
                            <div id="message" class="text-success">(We will assess you based on this level)</div>
                        <?php endif; ?>
                    </label>
                  <div class="col-sm-8">
                      <select name="qualificationTypeID" id="qualificationTypeID" class="form-control" required="required">
                         <?php
                         if(!empty($level)){
                         ?>
                          <option value="<?php echo $qualificationTypeID; ?>" selected="selected"><?php echo $db->getData("qualificationtype", "qualificationName", "qualificationTypeID", $qualificationTypeID);?></option>
                         <?php } else {?>
                          <option value="">Select Qualification Type</option>
                         <?php }
                         /* LSZ only admits applicants with legal qualifications.
                            For LSZ tenant, limit the dropdown to the relevant
                            qualifications (Bachelor of Law / Master of Law /
                            Diploma in Law). Other tenants see the full list.
                            Identifiers below match qualificationtype IDs in the
                            lsz_admission DB:  3 = Bachelor Degree of Law,
                                              10 = Master of Law,
                                              12 = Diploma. */
                         if ($LSZ_MODE) {
                             $LSZ_ALLOWED = array(3, 10, 12);
                             $qualificationType = $db->getRows('qualificationtype', array('order_by' => '`rank` ASC'));
                             $filtered = array();
                             if (!empty($qualificationType)) {
                                 foreach ($qualificationType as $type) {
                                     if (in_array((int)$type['qualificationTypeID'], $LSZ_ALLOWED, true)) {
                                         $filtered[] = $type;
                                     }
                                 }
                             }
                             $qualificationType = $filtered;
                         } else {
                             $qualificationType = $db->getRows('qualificationtype', array('order_by' => '`rank` ASC'));
                         }
                        if(!empty($qualificationType)){ foreach($qualificationType as $type){
                         $qualificationName=$type['qualificationName'];
                         $qualificationID=$type['qualificationTypeID'];
                        ?>
                        <option value="<?php echo $qualificationID;?>"><?php echo htmlspecialchars($qualificationName);?></option>
                        <?php }}?>
                    </select>
                    <?php if ($LSZ_MODE): ?>
                    <p class="help-block" style="margin-top:8px;">
                        Don't see your qualification? Contact the admissions office &mdash; LSZ admits applicants holding a Bachelor Degree of Law, Master of Law, or Diploma in Law.
                    </p>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="studyLevel" class="col-sm-4">
                      <?php echo $LSZ_MODE ? 'Which programme do you want to apply for?' : 'What level do you want to apply'; ?>
                  </label>

                  <div class="col-sm-8">
                      <select name="studyLevelID" id="studyLevelID" class="form-control" required="required">
                          <?php
                         if(!empty($level)){
                         ?>
                          <option value="<?php echo $studyLevelID;?>" selected="selected"><?php echo $db->getData("studylevels", "studyLevelName", "studyLevelID", $studyLevelID) ?></option>
                          <?php } else { ?>
                          <option value=""><?php echo $LSZ_MODE ? '-- Select Programme --' : '-- Select Study Level --'; ?></option>
                          <?php }
                          /* Show only the two LSZ programmes for LSZ tenant,
                             otherwise show all active study levels. */
                          if ($LSZ_MODE) {
                              $studyLevels = $db->getRows('studylevels', array('where' => array('status' => 1)));
                          } else {
                              $studyLevels = $db->getRows('studylevels', array('where' => array('status' => 1), 'order_by' => 'studyLevelID ASC'));
                          }
                          if (!empty($studyLevels)) {
                              foreach ($studyLevels as $sl) { ?>
                                  <option value="<?php echo $sl['studyLevelID']; ?>"><?php echo htmlspecialchars($sl['studyLevelName']); ?></option>
                          <?php }
                          } ?>
                      </select>
                      <?php if ($LSZ_MODE): ?>
                      <p class="help-block" style="margin-top:8px;">
                          <i class="fa fa-info-circle"></i>
                          <strong>Professional of Law (CLP)</strong> &mdash; for Bachelor / Master of Law holders.
                          <strong>Vakil Course</strong> &mdash; for Diploma in Law holders.
                      </p>
                      <?php endif; ?>
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