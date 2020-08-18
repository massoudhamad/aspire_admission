<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
$tblName = 'programrequirements';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $programmeID=$_POST['programmeID'];
        //$allowedSubjects=$_POST['allowedSubjects'];
        //$excludedSubjects=$_POST['excludedSubjects'];
        $compulsorySubjects=$_POST['compulsorySubjects'];
        $compulsoryGrade=$_POST['compulsoryGrade'];
        //$minimumPassGrade=$_POST['minimumPassGrade'];
        $numberPassGrade=$_POST['numberPassGrade'];
        $pointsRequired=$_POST['pointsRequired'];
        $gpa=$_POST['gpa'];
        //$nongpa=$_POST['nongpa'];
        
       /* if($allowedSubjects=="")
        {
            $allowedSubject=0;
        }
        else 
        {
            $allowedSubject=1;
        }
        if($excludedSubjects=="")
        {
            $excludedSubject=0;
        }
        else
        {
           $excludedSubject=1;
        }*/
        
        if($compulsorySubjects=="")
        {
            $comSubject=0;
        }
        else {
            $comSubject=1;
        }
        
        $programmeData=array(
            'programmeMajorID'=>$programmeID,
            'compulsorySubject'=> $comSubject,
            'compulsorySubjectGrade'=>$compulsoryGrade,
            'numberofPassGrade'=>$numberPassGrade,
            'pointsRequired'=>$pointsRequired,
            'equivalentEntryGPA'=>$gpa
        );

        /*$programmeData=array(
            'programmeMajorID'=>$programmeID,
            //'allowedSubject'=> $allowedSubject,
            //'excludedSubject'=> $excludedSubject,
            'compulsorySubject'=> $comSubject,
            'compulsorySubjectGrade'=>$compulsoryGrade,
            //'passGrade'=>$minimumPassGrade,
            'numberofPassGrade'=>$numberPassGrade,
            'pointsRequired'=>$pointsRequired,
            'equivalentEntryGPA'=>$gpa
            //'equivalentEntryNonGPA'=>$nongpa
        );*/
        $insert=$db->insert($tblName,$programmeData);
        $programmeRequirementID=$insert;
        //allowed subjects
        /*$allowed="allowed";
        for($x=0; $x<count($allowedSubjects); $x++)
        {
            $stmt = $db->runQuery("INSERT INTO subjectrequirements (programmeRequirementID,programmeMajorID,subjectID,subjectType) VALUES (:pRID,:pMID,:sub,:subT)");
            $stmt->bindParam(":pRID",$programmeRequirementID,PDO::PARAM_INT);
            $stmt->bindParam(":pMID",$programmeID,PDO::PARAM_INT);
            $stmt->bindParam(":sub",$allowedSubjects[$x],PDO::PARAM_INT);
            $stmt->bindParam(":subT",$allowed,PDO::PARAM_STR);
            $stmt->execute();
        }
         $excluded="excluded";
        for($x=0; $x<count($excludedSubjects); $x++)
        {
            $stmt = $db->runQuery("INSERT INTO subjectrequirements (programmeRequirementID,programmeMajorID,subjectID,subjectType) VALUES (:pRID,:pMID,:sub,:subT)");
            $stmt->bindParam(":pRID",$programmeRequirementID,PDO::PARAM_INT);
            $stmt->bindParam(":pMID",$programmeID,PDO::PARAM_INT);
            $stmt->bindParam(":sub",$excludedSubjects[$x],PDO::PARAM_INT);
            $stmt->bindParam(":subT",$excluded,PDO::PARAM_STR);
            $stmt->execute();
        }*/
         $compulsory="compulsory";
        for($x=0; $x<count($compulsorySubjects); $x++) {
            $compulsoryData = array(
                "programmeRequirementID" => $programmeRequirementID,
                "programmeMajorID" => $programmeID,
                "subjectID" => $compulsorySubjects[$x],
                "subjectType" => $compulsory
            );
            $insertdata=$db->insert("subjectrequirements",$compulsoryData);
        }
       /* for($x=0; $x<count($compulsorySubjects); $x++)
        {
            $stmt = $db->runQuery("INSERT INTO subjectrequirements (programmeRequirementID,programmeMajorID,subjectID,subjectType) VALUES (:pRID,:pMID,:subj,:subT)");
            $stmt->bindParam(":pRID",$programmeRequirementID,PDO::PARAM_INT);
            $stmt->bindParam(":pMID",$programmeID,PDO::PARAM_INT);
            $stmt->bindParam(":subj",$compulsorySubjects[$x],PDO::PARAM_INT);
            $stmt->bindParam(":subT",$compulsory,PDO::PARAM_STR);
            $stmt->execute();
        }*/
        $statusMsg = true;
        header("Location:index3.php?sp=pmapping&msg=succ");

    }elseif($_REQUEST['action_type'] == 'drop'){
        if(!empty($_REQUEST['id'])){
            $condition = array('programRequirementID' => $_REQUEST['id']);
            $update = $db->delete($tblName,$condition);
            $conditions = array('programmeRequirementID' => $_REQUEST['id']);
            $delete=$db->delete("subjectrequirements",$conditions);
            $statusMsg = true;
            header("Location:index3.php?sp=pmapping&msg=edited");
        }
    }
}

