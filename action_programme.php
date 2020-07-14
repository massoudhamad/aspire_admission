<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'programs';
$tblMajor='programmemajor';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $hasCombination=$_POST['hascombination'];
        $programmeName=$_POST['name'];
        $userData = array(
            'programName'=>$programmeName,
            'programCode' => $_POST['code'],
            'programDuration' => $_POST['duration'],
            'studyLevelID' => $_POST['studyLevelID'],
            'departmentID'=>$_POST['departmentID'],
            'campusID'=>$_POST['campusID'],
            'programStatus'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $programmeID=$insert;
        if($hasCombination=="yes")
        {
            //foreach($_POST["combination"] as $mjr => $major){
            $major=$_POST['combination'];
            for($x=0;$x< count($major);$x++)
            {
                    $programmeMajor=$programmeName."-".$major[$x];
                    $majorData=array(
                        'programmeID'=>$programmeID,
                        'major'=>$major[$x],
                        'programmeMajor'=>$programmeMajor,
                        'publishStatus'=>1
                    );
                      $insertMajor=$db->insert($tblMajor,$majorData);
            }
        }
        else
        {
             $majorData=array(
                    'programmeID'=>$programmeID,
                    'major'=>'NA',
                    'programmeMajor'=>$programmeName,
                    'publishStatus'=>1
             );
                $insertMajor=$db->insert($tblMajor,$majorData); 
        }
        $statusMsg =true;
        header("Location:index3.php?sp=programmes&msg=succ");
    }elseif($_REQUEST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
           $userData = array(
            'programName'=>$_POST['name'],
            'programCode' => $_POST['org_code'],
            'organizationID'=>$_POST['organizationID'],
            'organizationCode'=>$_POST['code'],
            'programDuration' => $_POST['duration'],
            'studyLevelID' => $_POST['studyLevelID'],
            'departmentID'=>$_POST['departmentID'],
            'campusID'=>$_POST['campusID'],
            'programStatus'=>$_POST['status']
        );
            $condition = array('programID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
           $statusMsg =true;
        header("Location:index3.php?sp=programmes&msg=edited");
        }
    }
    elseif($_REQUEST['action_type']=='drop')
    {
        
        if(!empty($_REQUEST['id'])){
             $condition = array('programID' => $_REQUEST['id']);
             $conditions = array('programmeID' => $_REQUEST['id']);
             $programmeMajorID=$db->getData("programmemajor","programmeMajorID","programmeID",$_REQUEST['id']);
             $conditionReq=array('programmeMajorID'=>$programmeMajorID);
             $deleteRequirements=$db->delete("programrequirements",$conditionReq);
             $deletesub=$db->delete("subjectrequirements", $conditionReq);
             $deleteMajor=$db->delete($tblMajor,$conditions);
             $delete=$db->delete($tblName, $condition);
             $statusMsg =true;
            header("Location:index3.php?sp=programmes&msg=drop");
        }
    }
}