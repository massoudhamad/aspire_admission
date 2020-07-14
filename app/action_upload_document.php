<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include '../DB.php';
$db = new DBHelper();
$tblName = 'attachment';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
{
    if($_REQUEST['action_type'] == 'add')
    {
        $documentType = $_POST['documentType'];// user name
        $imgFile = $_FILES['user_image']['name'];
        $tmp_dir = $_FILES['user_image']['tmp_name'];
        $imgSize = $_FILES['user_image']['size'];


        if(empty($documentType)){
            $errMSG = "Please Select Document Type.";
        }
        else if(empty($imgFile)){
            $errMSG = "Please Select File.";
        }

        else
        {
            $upload_dir = '../upload_doc/'; // upload directory

            $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

            // valid image extensions
            $valid_extensions = array('pdf','jpg','png'); // valid extensions

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
                $errMSG = "Sorry, only pdf,jpg and png files are allowed.";
            }
        }

        $status=false;
        // if no error occured, continue ....
        if(!isset($errMSG))
        {

            $documentData=array(
                'applicantID'=>$_SESSION['applicantID'],
                'documentType'=>$documentType,
                'fileUrl'=>$userpic
            );
            $insert=$db->insert($tblName,$documentData);
            $status=true;
            if($status)
            {
                //$successMSG = "new record succesfully inserted ...";
                $successMSG="succ";
                header("Location:index.php?sz=attachment&msg=".$successMSG);
                //header("refresh:5;index3.php?sp=document_upload"); // redirects image view page after 5 seconds.
            }
            else
            {
                header("Location:index.php?sz=attachment&msg=".$errMSG);
                //$errMSG = "error while inserting....";
            }
        }
        else {
            header("Location:index.php?sz=attachment&msg=" . $errMSG);
        }
    }
     else  if($_REQUEST['action_type'] == 'drop')
        {
            $condition = array('attachmentID' => $db->my_simple_crypt($_REQUEST['id'],'d'));
            $update = $db->delete($tblName,$condition);
            $statusMsg = true;
            header("Location:index.php?sz=attachment&msg=deleted");
        }
}

