<?php
$db=new DBHelper();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);

if(isset($_POST['doSubmit']))
{
    $title = $_POST['title'];// user name
    $schoolID = $_POST['schoolID'];// user email
    $academicYearID=$_POST['admissionYearID'];

    $imgFile = $_FILES['user_image']['name'];
    $tmp_dir = $_FILES['user_image']['tmp_name'];
    $imgSize = $_FILES['user_image']['size'];
    
    
    if(empty($title)){
        $errMSG = "Please Enter Title.";
    }
    else if(empty($academicYearID))
    {
        $errMSG = "Please Select Academic Year.";
    }
    else if(empty($schoolID)){
        $errMSG = "Please Select School.";
    }
    else if(empty($imgFile)){
        $errMSG = "Please Select PDF File.";
    }
    
    else
    {
        $upload_dir = 'upload_doc/'; // upload directory
        
        $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
        
        // valid image extensions
        $valid_extensions = array('pdf'); // valid extensions
        
        // rename uploading image
        $userpic = rand(1000,1000000).".".$imgExt;
        
        // allow valid image file formats
        if(in_array($imgExt, $valid_extensions)){
            // Check file size '5MB'
            if($imgSize < 5000000){
                move_uploaded_file($tmp_dir,$upload_dir.$userpic);
            }
            else{
                $errMSG = "Sorry, your file is too large.";
            }
        }
        else{
            $errMSG = "Sorry, only PDF files are allowed.";
        }
    }
    
   
    
    $status=false;
    // if no error occured, continue ....
    if(!isset($errMSG))
    {
        foreach ($_POST['schoolID'] as $schoolID) {
            $userData=array(
                'schoolID'=>$schoolID,
                'academicYearID'=>$academicYearID,
                'title'=>$title,
                'url'=>$userpic
            );
            $insert= $db->insert('upload',$userData);
            $status=true;
        }
        /* for($x=0;$x<count($schoolID);$x++)
        {
            $schoolID=$_POST['schoolID'.$x];
            $academicYearID=$_POST['academicYearID'];
            $title=$_POST['title'];
            
            
            $stmt = $db->runQuery("INSERT INTO upload(schoolID,academicYearID,title,url)VALUES(:school,:academicYear,:ttl,:upic)");
            $stmt->bindParam(':school',$schoolID[$x],PDO::PARAM_STR);
            $stmt->bindParam(':academicYear',$academicYearID,PDO::PARAM_INT);
            $stmt->bindParam(':ttl',$title,PDO::PARAM_STR);
            $stmt->bindParam(':upic',$userpic,PDO::PARAM_STR);
            $stmt->execute();
            $status=true;
        } */
        if($status)
        {
            $successMSG = "new record succesfully inserted ...";
            //header("refresh:5;index3.php?sp=document_upload"); // redirects image view page after 5 seconds.
        }
        else
        {
            $errMSG = "error while inserting....";
        }
    }
}


?>
<div class="container">
<div class="modal-header">
<h2>Upload New Document</h2>
<hr>
</div>  

<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   

<form method="post" enctype="multipart/form-data" class="form-horizontal">

<div class="row">
<div class="col-md-6">
<div class="modal-body">

<div class="form-group">
<label for="email">Title of the Document</label>
<input type="text" id="name" name="title" placeholder="Eg. Zalongwa Instruction Document" class="form-control" />
</div>


<div class="form-group">
<label for="email">Academic Year</label>
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

<div class="form-group">
<label for="email">School</label>
<select name="schoolID[]" id="limitedNumbChosen" multiple="true" class="form-control" required="required">
<?php
$school = $db->getRows('schools',array('order_by'=>'schoolName ASC'));
if(!empty($school)){ $count = 0; foreach($school as $type){ $count++;
 $schoolName=$type['schoolName'];
 $schoolID=$type['schoolID'];
?>
<option value="<?php echo $schoolID;?>"><?php echo $schoolName;?></option>
<?php }}?>
</select>
</div>
<div class="form-group">
<label for="email">Document:</label>
<input class="input-group" type="file" name="user_image" accept="image/pdf" />
</div>
</div>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-3">
    <input type="hidden" name="action_type" value="add"/>
    <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">
   
</div>
    <div class="col-lg-3"> <input type="submit" name="doCancel" value="Cancel" class="btn btn-primary"></div>
</div>
</div>
    
</div>
    </form>
</div>