<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';
    $tblApplication = 'applicantapplication';
    $tblRemarks = 'applicantremarks';
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        $programmeMajorID = $_POST['programmeMajorID'];
        $applicantID = $_POST['applicantID'];
        $transferProgrammeID = $_POST['transferProgrammeID'];
        $status = false;
        //transfer
        $userData = array(
            'programmeMajorID' => $transferProgrammeID
        );
        $condition = array('applicantID' => $applicantID, 'choice' => 1);
        $updateapp = $db->update($tblApplication, $userData, $condition);
        //update applicants
        $conditions = array('applicantID' => $applicantID);
        $data = array(
            'transferStatus' => 1
        );
        $updateapplicants = $db->update($tblName, $data, $conditions);

        //insert new row in applicantapplication table
        $newdata=array(
            'applicantID'=>$applicantID,
            'programmeMajorID'=>$programmeMajorID,
            'choice'=>3,
            'admissionStatus'=>0
        );
        $inserttransfer=$db->insert($tblApplication,$newdata);

        $appData = array(
            'applicantID' => $applicantID,
            'remarkID' => 8,
            'programID' => $transferProgrammeID
        );
        $insert = $db->insert($tblRemarks, $appData);

        $formFour=$db->getData('applicants','formfour','applicantID',$applicantID);

        $registeredProgrammeCode=$db->getProgrammeCode($programmeMajorID);
        if(!empty($registeredProgrammeCode))
        {
            foreach ($registeredProgrammeCode as $pcode)
            {
                $programmeCode=$pcode['programCode'];
            }
        }

        $bprogrammeCode=$programmeCode;

        $transferProgrammeCode=$db->getProgrammeCode($transferProgrammeID);
        if(!empty($transferProgrammeCode))
        {
            foreach ($transferProgrammeCode as $traCode)
            {
                $trasferProgrammeCode=$traCode['programCode'];
            }
        }

        $afterProgrammeCode=$trasferProgrammeCode;

        //formsix
        $aindexumber=$db->getIndexNumber($applicantID,"Advance");
        $formsix=array();
        if(!empty($aindexumber))
        {
            $formsix=array();
            foreach ($aindexumber as $fsixnumber) {
                $indexNumber=$fsixnumber['indexNumber'];
                $formsix[]=$indexNumber;
            }

        }
        else
        {
            $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
            if(!empty($equivalentresults))
            {
                foreach($equivalentresults as $matokeo)
                {
                    $eIndexNumber=$matokeo['indexNumber'];
                    $avn_number=$matokeo['avn_number'];
                }
            }
            else
            {
                $eIndexNumber="";
                $avn_number="";

            }
        }

        if(!empty($formsix))
            $alevel=$formsix[0];
        else
            $alevel=$avn_number;

            $traData = array(
                'applicantID' => $applicantID,
                'formFour'=> $formFour,
                'formSix' => $alevel,
                'bProgrammeCode' => $bprogrammeCode,
                'aProgrammeCode'=>$afterProgrammeCode,
                'transferType'=>'Internal'
            );
            $insert = $db->insert("applicant_transfer", $traData);


        //update for registered students
       $getRegisteredStatus=$db->getRows("applicantregistration",array('where'=>array('applicantID'=>$applicantID)));
        if(!empty($getRegisteredStatus))
        {
            /*//update registrationNumber
            $studyLevelID=$db->getStudyLevelIDData($transferProgrammeID);
            if($studyLevelID==5)
                $studyLevelID==2;
            else
                $studyLevelID=$studyLevelID;

            if($studyLevelID==1)
                $regCode='04';
            else if($studyLevelID==2)
                $regCode='03';
            else if($studyLevelID==3)
                $regCode='02';



            //This is for year
            $academicYearID=3;
            $academicYear=$db->getData("academicyears","academicYear","academicYearID",$academicYearID);
            //$academicYear="2018/2019";
            $year=explode("/",$academicYear);
            $year1=$year[0];

            $regNumber = $db->getMaxRegNumber($studyLevelID);
            $finalNumber = $regNumber + 1;
            $registrationNumber = $year1 . "-" . $regCode . "-0" . $finalNumber;*/

                //remarks table
                /*$appData = array(
                    'applicantID' => $applicantID,
                    'remarkID' => 8,
                    'programID' => $transferProgrammeID
                );
                $insert = $db->insert("applicantremarks", $appData);
                $boolStatus = true;


            $boolStatus = true;*/


            $condition_reg = array('applicantID' => $applicantID);
            $regdata = array(
                'programmeID' => $transferProgrammeID,
            );
            $update_applicants = $db->update("applicantregistration", $regdata, $condition_reg);

        }

        $status = true;

        if ($status) {
            header("Location:index3.php?sp=view_applicant_info&applicantID=$applicantID&msg=succ");
        } else {
            header("Location:index3.php?sp=view_applicant_info&applicantID=$applicantID&msg=unsucc");
        }
    }
}catch (PDOException $ex) {
    $db->redirect("index3.php?sp=view_applicant_info&applicantID=$applicantID&msg=error");
}