<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
   <script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });

   
</script>

              <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
              <script type="text/javascript">
              $(document).ready(function()
              {
              $("#programmeID").change(function()
              {
              var id=$(this).val();
              var dataString = 'id='+ id;

              $.ajax
              ({
              type: "POST",
              url: "ajax_studyear.php",
              data: dataString,
              cache: false,
              success: function(html)
              {
              $("#studyYear").html(html);
              } 
              });

              });

              });
              </script>
<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>



<?php $db=new DBHelper();

?>
<div class="container">
  <h1>Programme Course Mapping</h1>
  <hr>
    <div class="col-md-12"> 
    <div class="row">
      
           <h3>Select Programme Name</h3>
            <div class="row">
            <form name="" method="post" action="">
                       <div class="col-lg-4">
                           <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control" required="">
                              <?php
                               $programmes = $db->getRows('programmes',array('order_by'=>'programme_name ASC'));
                               if(!empty($programmes)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programme_name'];
                                $programme_id=$prog['programme_id'];
                               ?>
                               <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" /></div>
          </form>          
        </div>
        <div class="row">
            <hr>
        </div>
        <div class="row">
            <?php
            //Save Records Buttoon

            if((isset($_POST['doSearch'])=="Search Records")||($_REQUEST['action']=="getRecords"))
            {
              $programmeID=$_POST['programmeID'];
              $programmeID=$_REQUEST['programmeID'];
                ?>
                <div class="row"><h4 class="text-info">Register New Course for:
                <?php
                $data= $db->getRows("programmes",array('where'=>array('programme_id'=>$programmeID),' order_by'=>'programme_name ASC'));
               if(!empty($data))
               { 
                    $count = 0; 
                    foreach($data as $dt)
                    { 
                        $count++;
                        $programme_name=$dt['programme_name'];  
                        echo $programme_name;
                    }
                }
                ?></h4></div>



               <form name="" method="post" action="action_programme_mapping.php"> 
               <div class="row">
                   <div class="col-lg-3">
                            <label for="MiddleName">Course Name</label>
                            
                            <select name="courseID" class="form-control" required="">
                            
                              <?php
                              $course = $db->filterCourse($programmeID);
                               if(!empty($course)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($course as $c){ $count++;
                                $course_name=$c['course_name'];
                                $course_code=$c['course_code'];
                                $course_id=$c['course_id'];
                               ?>
                               <option value="<?php echo $course_id;?>"><?php echo $course_code."-".$course_name;?></option>
                               <?php }}
           ?>
                           </select>
                        </div>
                      <div class="col-lg-3">
                           <label for="FirstName">Semester</label>
                            <select name="semisterID" class="form-control" required="">
                              <?php
                                 $semister = $db->getRows('semister',array('order_by'=>'semister_name ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semister_name=$sm['semister_name'];
                                  $semister_id=$sm['semister_id'];
                                 ?>
                                 <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Study Year</label>
                            <select name="studyYear" class="form-control" required="">
                              <?php
                                 $programmeDuration = $db->getRows('programmes',array('where'=>array('programme_id'=>$programmeID),'order_by'=>'programme_name DESC'));
                                 if(!empty($programmeDuration)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($programmeDuration as $pDuration){ $count++;
                                  $programmeDuration=$pDuration['programmeDuration'];
                                  for($x=1;$x<=$programmeDuration;$x++)
                                  {
                                    ?>
                                    <option value="<?php echo $x;?>"><?php echo $x;?></option>
                                    <?php
                                  }
                                 ?>
                                 
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Course Status</label>
                            <select name="courseStatusID" class="form-control" required="">
                              <?php
                                 $course_status = $db->getRows('coursestatus',array('order_by'=>'courseStatus ASC'));
                                 if(!empty($course_status)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($course_status as $cstatus){ $count++;
                                  $courseStatus=$cstatus['courseStatus'];
                                  $courseStatusID=$cstatus['courseStatusID'];
                                 ?>
                                 <option value="<?php echo $courseStatusID;?>"><?php echo $courseStatus;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                      </div>
                 <br>
                  <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control" />
                        </div>
                        <div class="col-lg-3">
                            <input type="submit" value="Cancel" class="btn btn-primary form-control" />
                        </div>
                    </div>
                </form>
<div class="row">
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Mapping data has been inserted successfully</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Programme Mapping Data has been delete successfully</strong>.
</div>";
  }
}
?> 
</div>
                
                <?php
                $data= $db->getRows("programmes",array('where'=>array('programme_id'=>$programmeID),' order_by'=>'programme_name ASC'));
               if(!empty($data))
               { 
                    $i = 0; 
                    foreach($data as $dt)
                    { 
                        $i++;
                        $programme_name=$dt['programme_name'];
                    } 
                }  
                       

                       $data= $db->getRows("programmemaping",array('where'=>array('programmeID'=>$programmeID),' order_by'=>'studyYear ASC'));
                       if(!empty($data))
                       {
                       ?>
                       <div class="row">
                       <h4 class="text-info">List of Registerd Course for:<?php echo $programme_name;?></h4>

                       <table  id="example" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>No.</th>
                          <th>Course Code</th>
                          <th>Course Name</th>
                          <th>Study Year</th>
                          <th>Semester</th>
                          <th>Course Status</th>
                          <th>Status</th>
                          <th>Action</th>
                           </tr>
                        </thead>
                        
                        <tbody>
                       <?php  
                            $count = 0; 
                            foreach($data as $dt)
                            { 
                                $count++;
                                $programmeMappingID=$dt['programmeMappingID'];
                                $courseID=$dt['courseID'];  
                                $semisterID=$dt['semesterID'];
                                $studyYear=$dt['studyYear'];
                                $courseStatusID=$dt['courseStatusID'];
                                 $courseStatus=$dt['courseStatus'];

                       $course=$db->getRows("course",array('where'=>array('course_id'=>$courseID),' order_by'=>'course_name ASC'));
                       if(!empty($course))
                       {
                            foreach($course as $c)
                            { 
                                $cCode=$c['course_code'];  
                                $cName=$c['course_name'];
                              }
                      }

                      $semister = $db->getRows('semister',array('where'=>array('semister_id'=>$semisterID),' order_by'=>'semister_name ASC'));
                                 if(!empty($semister)){
                            
                                  foreach($semister as $sm){
                                  $semister_name=$sm['semister_name'];
                                  $semister_id=$sm['semister_id'];
                                }
                              }

                              $course_status = $db->getRows('coursestatus',array('where'=>array('courseStatusID'=>$courseStatusID),'order_by'=>'courseStatus ASC'));
                                 if(!empty($course_status)){
                                  foreach($course_status as $cstatus){
                                  $courseStatusName=$cstatus['courseStatus'];
                                  $courseStatusID=$cstatus['courseStatusID'];
                                }
                              }

                              if($courseStatus==1)
                              {
                                $status="Active";
                                $link="<a href=''>Disable</a>";
                              }
                              else
                              {
                                $status="Not Active";
                                $link="<a href=''>Enable</a>";
                              }
                 ?>    
                        <tr><td><?php echo $count;?></td><td><?php echo $cCode;?></td><td><?php echo $cName;?></td>
                            <td><?php echo $studyYear;?></td><td><?php echo $semister_name;?></td><td><?php echo $courseStatusName;?></td>
                            <td><?php echo $status;?></td>
                            <td><a href="action_programme_mapping.php?action_type=delete&programmeID=<?php echo $programmeID;?>&id=<?php echo $programmeMappingID; ?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this course?');"></a></td>
                            </tr>
                        
                  <?php
                     
                  }
                    ?>
                    </tbody>
                        </table>
                    </div>
                    <?php
               }
                else
                  echo "<h4 class='text-danger'>No Registered Course</h4>";

                ?>
<?php
            }
?>
        </div>
        </div>
    </div>
    </div>