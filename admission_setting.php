<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
     <?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Admission Setting data has been inserted successfully</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Admission Setting Data has been delete successfully</strong>.
</div>";
  }
}
?>
        <div id="semestercourse">
            <h3>Admission Setting</h3>
            <div class="row">
            <form name="" method="post" action="action_semester_setting.php">
                         <div class="col-lg-3">
                           <label for="MiddleName">Admission Year</label>
                            <select name="academicYearID" class="form-control" required="">
                              <?php
                               $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear DESC'));
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
                    <select name="admissionIntakeID" class="form-control" required>
                        <?php
                        $addintake = $db->getRows('admission_intake',array('order_by'=>'admissionInTakeID ASC'));
                        if(!empty($addintake)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($addintake as $aintake){ $count++;
                                $admissionInTake=$aintake['admissionInTake'];
                                $admissionInTakeID=$aintake['admissionInTakeID'];
                                ?>
                                <option value="<?php echo $admissionInTakeID;?>"><?php echo $admissionInTake;?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Start Date</label>
                           <input type="date" name="startDate" class="form-control">
                        </div>

                         <div class="col-lg-3">
                           <label for="FirstName">End Date</label>
                             <input type="date" name="endDate" class="form-control">
                        </div>

       </div>             
         <div calss="row">
         <div class="col-lg-9"></div>
         <div class="col-lg-3">
                      <label for=""></label>
                      <input type="hidden" name="action_type" value="add"/>
                      <input type="submit" name="doFind" value="Save Records" class="btn btn-primary form-control" /></div>
                   </form>
        </div>


        <br>
        <div class="row"><br></div>
        
        <div class="row">
            <?php
               $semester= $db->getRows('admission_setting',array(' order_by'=>' yearStatus DESC'));
    
                if(!empty($semester))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Admission Name</th>
                        <th>Admission Year</th>
                          <th>Admission Intake</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($semester as $sm)
                    { 
                      $count++;
                      $academicYearID=$sm['academicYearID'];
                      $admissionInTakeID=$sm['admissionInTakeID'];
                      $admissionName=$sm['admissionName'];
                      $startDate=$sm['startDate'];
                      $endDate=$sm['endDate'];
                      $semesterStatus=$sm['yearStatus'];
                      $admissionID=$sm['admissionID'];
                      if($semesterStatus==1)
                        $status="<span class='label label-success'>Active</span>";
                      else
                        $status="<span class='label label-danger'>Not Active</span>";

                      $academicYear=$db->getData("academicyears","academicYear","academicYearID",$academicYearID);

                        $admissionInTake=$db->getData("admission_intake","admissionInTake","admissionInTakeID",$admissionInTakeID);

                          ?>
                          <tr>
                          <td><?php echo $count;?></td>
                          <td><?php echo $admissionName;?></td>
                          <td><?php echo $academicYear;?></td>
                              <td><?php echo $admissionInTake;?></td>
                          <td><?php echo $startDate;?></td>
                          <td><?php echo $endDate;?></td>
                          <td><?php echo $status;?></td>
                              <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $admissionID;?>">
                                      <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                      <span><strong></strong></span></td>
                          <!--<td><a href="action_semester_setting.php?action_type=delete&id=<?php /*echo $admissionID; */?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Semester Setting?');"></a></td>-->
                          </tr>
                              <div id="message<?php echo $admissionID;?>" class="modal fade" role="dialog">
                                  <div class="modal-dialog">

                                      <!-- Modal content-->
                                      <div class="modal-content">
                                          <form name="" id="" role="form" method="post" action="action_semester_setting.php">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                  <h4 class="modal-title" id="myModalLabel">Update Record</h4>
                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <div class="modal-body">

                                                          <div class="form-group">

                                                              <label for="FirstName">Admission Year</label>
                                                              <select name="admissionYearID" class="form-control" required="">
                                                                  <option value="<?php echo $academicYearID;?>"><?php echo $academicYear; ?></option>
                                                                  <?php
                                                                  $adYear = $db->getRows('academicyears',array('order_by'=>'academicYear ASC'));
                                                                  if(!empty($adYear)){
                                                                      $count = 0; foreach($adYear as $ayear){ $count++;
                                                                          $academic_year=$ayear['academicYear'];
                                                                          $academic_id=$ayear['academicYearID'];
                                                                          ?>
                                                                          <option value="<?php echo $academic_id;?>"><?php echo $academic_year;?></option>
                                                                      <?php }}
                                                                  ?>
                                                              </select>
                                                          </div>

                                                          <div class="form-group">

                                                              <label for="FirstName">Admission Intake</label>
                                                              <select name="admissionInTakeID" class="form-control" required="">
                                                                  <option value="<?php echo $admissionInTakeID;?>"><?php echo $admissionInTake; ?></option>
                                                                  <?php
                                                                  $aitake = $db->getRows('admission_intake',array('order_by'=>'admissionInTakeID ASC'));
                                                                  if(!empty($aitake)){
                                                                      $count = 0; foreach($aitake as $ait){ $count++;
                                                                          $admissionInTakeID=$ait['admissionInTakeID'];
                                                                          $admissionInTake=$ait['admissionInTake'];
                                                                          ?>
                                                                          <option value="<?php echo $admissionInTakeID;?>">
                                                                              <?php echo $admissionInTake;?></option>
                                                                      <?php }
                                                                  }
                                                                  ?>
                                                              </select>
                                                          </div>

                                                          <div class="form-group">
                                                              <label for="FirstName">Start Date</label>
                                                              <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd"
                                                                   data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                  <input class="form-control" size="16" type="text" name="startDate" value="<?php echo $startDate;?>" id="pickyDate4">
                                                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                              </div>
                                                          </div>
                                                          <div class="form-group">
                                                              <label for="FirstName">End Date</label>
                                                              <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd"
                                                                   data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                  <input class="form-control" size="16" type="text" name="endDate" value="<?php echo $endDate;?>" id="pickyDate5">
                                                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                              </div>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label for="email">Current Admission?</label>
                                                          <?php
                                                          if($semesterStatus==1) {
                                                              ?>
                                                              <input type="radio" id="status" name="status" value="1"
                                                                     checked/>Yes
                                                              <input type="radio" id="status" name="status" value="0"/>No
                                                              <?php
                                                          }else
                                                          {
                                                              ?>
                                                              <input type="radio" id="status" name="status" value="1"/>Yes
                                                              <input type="radio" id="status" name="status" value="0" checked/>No
                                                              <?php
                                                          }
                                                          ?>
                                                      </div>

                                                  </div></div>
                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                  <input type="hidden" name="action_type" value="edit"/>
                                                  <input type="hidden" name="id" value="<?php echo $admissionID;?>">
                                                  <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                              </div>
                                          </form>
                                      </div>

                                  </div>
                              </div>

                          <?php 
                        
                    }
                    ?>
                    </tbody>
                    </table>
                    
                    <?php 
                }
             ?>
        </div>
        </div>
        
        
    </div>
    </div>