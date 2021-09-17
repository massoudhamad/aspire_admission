<div class="container">
<div class="row"> 
    <h2>Documents Upload</h2>
<div class="col-md-12">
<div class="pull-right">
                <a href="index3.php?sp=upload_new_document"><span class="btn btn-primary">Upload New Document</span></a>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr>
            </div>
</div>
     <?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>You have successfully upload document</strong>.
</div>";
  }
 else if($_REQUEST['msg']=="deleted") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Document has been droped successfully</strong>.
</div>";
  }
}
?>
        <div class="row">
                    <table  id="example" class="display nowrap" cellspacing="0" width="100%">
                      <thead>
                      <tr>
                        <th width=5>No.</th>
                        <th width=30>Academic Year</th>
                        <th width=400>Title</th>
                        <th width=400>Programme Name</th>
                        <th width=20>Document</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                      <?php
               $db=new DBHelper();
               $upload= $db->getRows('upload',array(' order_by'=>' academicYearID ASC'));
    
               if(!empty($upload))
                {
                    ?>
                    <?php 
                    $count = 0; 
                    foreach($upload as $up)
                    { 
                      $count++;
                      $uploadID=$up['uploadID'];
                      $academicYearID=$up['academicYearID'];
                      $title=$up['title'];
                      $schoolID=$up['schoolID'];
                      $academicYearID=$up['academicYearID'];
                      $url=$up['url'];
                            
                       $academicYear = $db->getRows('academicyears',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academic_year ASC'));
                       foreach($academicYear as $acy)
                       {     
                          $academicYear=$acy['academicYear'];
                       }

                          ?>
                          <tr>
                          <td><?php echo $count;?></td>
                          <td><?php echo $academicYear;?></td>
                          <td><?php echo $title;?></td>
                          <td><?php echo $db->getData("programmemajor","programmeMajor","programmeMajorID",$schoolID);?></td>
                          <td><a href="upload_doc/<?php echo $url;?>" class="glyphicon glyphicon-download-alt" target="_blank"></a></td>
                          <td><a href="action_upload_document.php?action_type=delete&id=<?php echo $uploadID; ?>&yearID=<?php echo $academicYearID;?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Semester Setting?');"></a></td>
                          </tr>
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
    
    
    