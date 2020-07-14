<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$applicantID=$_REQUEST['applicantID'];

$tblName = 'applicantresults';
$tblSubjects='applicantsubjects';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'add'){
        $applicantID=$_POST['applicantID'];
        $exam_body=$_POST['exam_body'];
        $indexYear=$_POST['indexYear'];
        $schoolName=$_POST['schoolName'];
        $examinationlevel=$_POST['examinationlevel'];
        $examinationaward=$_POST['examinationaward'];
        $indexNumber=$_POST['indexNumber'];
        
        if($exam_body=="NECTA")
        {
            $indexNumber=$_POST['indexNumber'];
            
        }
        else if($exam_body=="NECTAO")
        {
            $indexNumber=$_POST['indexNumberOld'];
        }
        else if($exam_body=="Others")
        {
            $indexNumber=$_POST['indexNumberOther'];  
        }
         if($examinationaward=="formfour")
                $award="CSEE";
             else
                $award="ACSEE";
        $applicationYearID=$db->getData('applicants','applicationYearID','applicantID',$applicantID);    
        $examNumber= strtoupper($indexNumber."/".$indexYear);
        if($db->isIndexNumberExist($examNumber,$applicationYearID))
        {
            $boolStatus=false;
        }
        else
        {
            $userData = array(
                'applicantID'=>$applicantID,
                'applicationYearID'=>$applicationYearID,
                'schoolName'=>$schoolName,
                'yearTaken'=>$indexYear,
                'indexNumber'=>$examNumber,
                'examinationAuthority'=>$exam_body,
                'examinationLevel'=>$examinationlevel,
                'award'=>$award,
                'gradeType'=>'Points'
            );

            $insert = $db->insert($tblName,$userData);
            $applicantResultID=$insert;
            $points=0;
            foreach($_POST["subjectCode"] as $code => $subjectCode){
            foreach($_POST["gradeCode"] as $grade => $gradeCode){
                if ($code==$grade){
                    $gradePoints=$db->getData("grades","gradePoint","gradeID",$gradeCode);
                    $applicantResultData=array(
                        'applicantResultID'=>$applicantResultID,
                        'subjectID'=>$subjectCode,
                        'gradeID'=>$gradeCode,
                        'points'=>$gradePoints
                    );
                      $insert=$db->insert($tblSubjects,$applicantResultData); 
                }
            }
            }
        $boolStatus=true;
        }
        if($boolStatus)
        {
            header("Location:index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=unsucc");
        }
    }
    
   else if($_REQUEST['action_type'] == 'dropSchool')
    {
        if(!empty($_GET['id'])){
            $condition = array('applicantResultID' => $_GET['id']);
            
            $update = $db->delete($tblName,$condition);
            $delete=$db->delete($tblSubjects,$condition);
            $statusFlag=true;
            $db->redirect("index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=dropSchool");
        }
    }
    else if($_REQUEST['action_type'] == 'dropSubject')
    {
        if(!empty($_GET['id'])){
            $condition = array('applicantSubjectID' => $_GET['id']);
            $update = $db->delete($tblSubjects,$condition);
            $statusFlag=true;
            $db->redirect("index3.php?sp=edit_educational_background&applicantID=$applicantID&msg=dropSchool");
        }
        
    }

}
if($_POST['doExit'])
{
    header("Location:index3.php?sp=applicantdetails&applicantID=$applicantID");
}
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=applicantdetails&applicantID=$applicantID&msg=error");
}