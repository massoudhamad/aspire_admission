<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';
    $tblRegistration='applicantregistration';
    $tblRemarks='applicantremarks';
    
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
    {
        if($_REQUEST['action_type'] == 'register')
        {
            $programmeID=$_POST['programmeID'];
            $academicYearID=$_POST['academicYearID'];
            $academicYear=$db->getData("academicyears","academicYear","academicYearID",$academicYearID);
            $year=explode("/",$academicYear);
            $year1=$year[0];
            
            $yearSub=substr((string)$year1,2,3);//17
            $yearSubString=substr((string)$year1,1,3);//017
            //getStudyLevel
            $campusID=$db->getProgrammeCampus($programmeID);
            $programmeCode=$db->getData("programs","programCode","programID",$programmeID);
            
            $batchNumber=$db->getRows('programbatch',array('where'=>array('programID'=>$programmeID,'academicYearID'=>$academicYearID),'order_by'=>'programID ASC'));
            if(!empty($batchNumber))
            {
                foreach($batchNumber as $btch)
                {
                    $batchNumber=$btch['batchNumber'];
                    $serialNumber=$btch['serialNumber'];
                }
            }
            
            $id=$_POST['id'];
            $status=false;
            if($_POST['id'])
            {
                foreach ($id as $applicantID)
                {
                    if($db->isApplicantIDExist($applicantID))
                    {
                        
                    }
                    else
                    {
                    //applicantregistration table
                    $number= $db->getMaxRegNumber($programmeID);
                    if($number=="")
                    {
                        $number=$serialNumber;
                    }
                    else
                    {
                      $number=$number;  
                    }
                    $finalNumber=$number+1;
                    
                    if(($campusID==3)||($campusID==6)) //Chwaka
                    {
                        if($db->count_digit($finalNumber)==1)
                        {
                            $finalNumber="00".$finalNumber;
                        }
                        else if($db->count_digit($finalNumber)==2)
                        {
                            $finalNumber="0".$finalNumber;
                        }
                        else
                        {
                            $finalNumber=$finalNumber;
                        }
                    }
                    else 
                    {
                        $finalNumber=$finalNumber;
                    }
                    
                        if($campusID==3)//Chwaka
                        {
                            $registrationNumber=$programmeCode."/".$batchNumber."/".$yearSub."/".$finalNumber."/TZ";
                        }
                        else if($campusID==5)//Maruhubi
                        {
                            $registrationNumber=$programmeCode."/".$yearSubString."/".$finalNumber."/TZ";
                        }
                        else if($campusID==6)//Mbweni
                        {
                            $registrationNumber=$programmeCode."/".$finalNumber."/".$year1;
                        }
                        else //Other Campus-Tunguu,Pemba,Nkurumah,Vuga
                        {
                            $registrationNumber=$programmeCode."/".$batchNumber."/".$yearSub."/".$finalNumber."/TZ";
                        }
                    
                    $regData=array(
                        'academicYearID'=>$academicYearID,
                        'programmeID'=>$programmeID,
                        'applicantID'=>$applicantID,
                        'registrationNumber'=>$registrationNumber,
                        'regNumber'=>$finalNumber
                    );
                    $insert=$db->insert($tblRegistration,$regData);
                    $status=true;
                    
                    //applicants table
                   $conditions=array('applicantID'=>$applicantID);
                    $data=array(
                        'applicantsRemarksID'=>6
                    );
                    $updateapplicants=$db->update($tblName, $data, $conditions);
                    //remarks table
                    $today=date("Y-m-d h:m:s");
                    $appData=array(
                        'applicantID'=>$applicantID,
                        'remarkID'=>6,
                        'processDate'=>$today,
                        'activeStatus'=>1
                    );
                    $insert=$db->insert($tblRemarks,$appData);
                    
                    $status=true;
                }
                }
            }
            if($status)
            {
                header("Location:index3.php?sp=registerapplicants&msg=succ");
            }
            else
            {
                header("Location:index3.php?sp=registerapplicants&msg=unsucc");
            }
    }
    //Un enroll
    else if($_REQUEST['action_type'] == 'unenroll')
    {
     $applicantRegistrationID=$_REQUEST['id'];
        $applicantID=$_REQUEST['applicantID'];
        
                $conditionss=array('applicantRegistrationID'=>$applicantRegistrationID);
                $delete=$db->delete("applicantregistration", $conditionss);
                 
                $conditions=array('applicantID'=>$applicantID);
                $data=array(
                    'applicantsRemarksID'=>3
                );
                $updateapplicants=$db->update($tblName, $data, $conditions);
                
                $appData=array(
                    'applicantID'=>$applicantID,
                    'remarkID'=>3
                );
                $insert=$db->insert($tblRemarks,$appData);
                $boolstatus=true;
            
        
        if($boolstatus)
        {
            header("Location:index3.php?sp=viewregisteredapplicants&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=viewregisteredapplicants&msg=unsucc");
        }
    }
    /*else if(isset($_REQUEST['doReject']) == 'Reject Applicants')
    {
        if(isset($_POST['id']))
        {
            foreach ($id as $applicantID)
            {
                $userData=array(
                    'admissionStatus'=>1
                );
                $condition=array('applicantID'=>$applicantID,'programmeMajorID'=>$programmeMajorID,'choice'=>$choice);
                $updateapp=$db->update($tblApplication, $userData, $condition);
                
                $conditions=array('applicantID'=>$applicantID);
                $data=array(
                    'applicantsRemarksID'=>4
                );
                $updateapplicants=$db->update($tblName, $data, $conditions);
                
                $appData=array(
                    'applicantID'=>$applicantID,
                    'remarkID'=>4,
                    'programID'=>$programmeMajorID
                );
                $insert=$db->insert($tblRemarks,$appData);
                $status=true;
            }
        }
        
        if($status)
        {
            header("Location:index3.php?sp=registerapplicants&msg=succ");
        }
        else
        {
            header("Location:index3.php?sp=registerapplicants&msg=unsucc");
        }
    }*/
  }
} catch (PDOException $ex) {
    $db->redirect("index3.php?sp=registerapplicants&msg=error");
}