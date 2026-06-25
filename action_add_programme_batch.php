<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
include 'DB.php';
$db = new DBHelper();
$tblName = 'programbatch';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        
        $number=$_POST['number'];
        for($x=1;$x<=$number;$x++)
        {
            $programmeID=$_POST['programID'.$x];
            $academicYearID=$_POST['academicYearID'.$x];
            if($_POST['batchnumber'.$x]!='' && $_POST['serialnumber'.$x]!='')
            {
                $stmt = $db->runQuery("INSERT INTO programbatch (programID,academicYearID,batchNumber,serialNumber) VALUES (:progID,:acadYearID,:batch,:number)");
                $stmt->bindParam(":progID",$programmeID,PDO::PARAM_INT);
                $stmt->bindParam(":acadYearID",$academicYearID,PDO::PARAM_INT);
                $stmt->bindParam(":batch",$_POST['batchnumber'.$x],PDO::PARAM_INT);
                $stmt->bindParam(":number",$_POST['serialnumber'.$x],PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
        $statusMsg = true;
        header("Location:index3.php?sp=programme_batch&msg=succ");
        
    }elseif($_REQUEST['action_type'] == 'drop'){
        $condition = array('programID' => $_REQUEST['id'],'academicYearID'=>$_REQUEST['yearID']);
        $update = $db->delete($tblName,$condition);
        $statusMsg = true;
        header("Location:index3.php?sp=programmefees&msg=drop");
    }
}

