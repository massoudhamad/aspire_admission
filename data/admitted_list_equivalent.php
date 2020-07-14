<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$academicYearID=$_GET['academicYearID'];
$admissionID=$_GET['admissionID'];

$applicantsData=$db->getApplicantsAdmitted($programmeID,$admissionID,1);
if(!empty($applicantsData))
{
    $i=0;
    foreach ($applicantsData as $data)
    {
        $i++;
        $applicantID=$data['applicantID'];
        $fname=$data['firstName'];
        $mname=$data['middleName'];
        $lname=$data['lastName'];
        $gender=$data['gender'];
        $name="$fname $mname $lname";

        /*$osubjects=$db->getSelectionSubjects($applicantID,"Ordinary");
        if(!empty($osubjects))
        {
            $odata=array();
            $totalPoints=0;
            foreach ($osubjects as $subject) {

                $subjectID=$subject['subjectID'];
                $subjectCode=$subject['subjectCode'];
                $gradeID=$subject['gradeID'];
                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                $points=$subject['points'];
                $totalPoints+=$points;
                $odata[]=$subjectCode."-".$grade;
            }

        }*/

        $osubjects=$db->getSelectionSubjects($applicantID,"Ordinary");
        if(!empty($osubjects))
        {
            $subjects=array();
            $grade=array();
            $arrpoints=array();

            foreach($osubjects as $subject) {
                $subjectID=$subject['subjectID'];
                $gradeID=$subject['gradeID'];
                $points=$subject['points'];
                if(empty($subjects))
                {
                    $subjects[]=$subjectID;
                    $grade[]=$subject['gradeID'];
                    $arrpoints[]=$subject['points'];
                }
                else if(in_array($subjectID,$subjects))
                {
                    for($i=0;$i<sizeof($subjects);$i++)
                    {
                        if($subjects[$i]==$subjectID)
                        {
                            if((int)$arrpoints[$i]<(int)$points)
                            {
                                $subjects[$i]=$subjectID;
                                $grade[$i]=$gradeID;
                                $arrpoints[$i]=$points;
                            }
                        }

                    }
                }
                else
                {
                    $subjects[]=$subjectID;
                    $grade[]=$gradeID;
                    $arrpoints[]=$points;
                }
            }
            $totalPoints=0;$odata=array();
            for($j=0;$j<sizeof($subjects);$j++)
            {
                //echo $subjects[$j]."-".$grade[$j]."-".$arrpoints[$j];
                $subjectCode=$db->getData("subjects","subjectCode","subjectID",$subjects[$j]);
                $gradeCode=$db->getData("grades","gradeCode","gradeID",$grade[$j]);
                $totalPoints+=$arrpoints[$j];
                $odata[]=$subjectCode."-".$gradeCode;
            }
        }

        $oindexumber=$db->getIndexNumber($applicantID,"Ordinary");
        if(!empty($oindexumber))
        {
            $formfour=array();
            foreach ($oindexumber as $fnumber) {
                $indexNumber=$fnumber['indexNumber'];
                $formfour[]=$indexNumber;
            }

        }
        else
        {
            $formfour[]="";
        }


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
            $formsix[]="";
        }

        $eindexumber=$db->getIndexNumber($applicantID,"Equivalent");
        if(!empty($eindexumber))
        {
            $enumber=array();
            foreach ($eindexumber as $enumber) {
                //$indexNumber=$enumber['indexNumber'];
                $indexNumber=$enumber['avn_number'];
                $enumber[]=$indexNumber;
            }

        }
        else
        {
            $enumber[]="";
        }

        /*$applicantPoints=$db->getSelectionPoints($applicantID,'Ordinary');
        if(!empty($applicantPoints))
        {
            $totalPoints=0;
            foreach ($applicantPoints as $appoints) {
                $points=$appoints['points'];
                $totalPoints+=$points;
            }

        }*/

        $asubjects=$db->getSelectionSubjects($applicantID,"Advance");
        if(!empty($osubjects))
        {

            $subjects=array();
            $grade=array();
            $arrpoints=array();

            foreach($asubjects as $subject) {
                $subjectID=$subject['subjectID'];
                $gradeID=$subject['gradeID'];
                $points=$subject['points'];
                if(empty($subjects))
                {
                    $subjects[]=$subjectID;
                    $grade[]=$subject['gradeID'];
                    $arrpoints[]=$subject['points'];
                }
                else if(in_array($subjectID,$subjects))
                {
                    for($i=0;$i<sizeof($subjects);$i++)
                    {
                        if($subjects[$i]==$subjectID)
                        {
                            if((int)$arrpoints[$i]<(int)$points)
                            {
                                $subjects[$i]=$subjectID;
                                $grade[$i]=$gradeID;
                                $arrpoints[$i]=$points;
                            }
                        }

                    }
                }
                else
                {
                    $subjects[]=$subjectID;
                    $grade[]=$gradeID;
                    $arrpoints[]=$points;
                }
            }
            $adata=array();$atotalPoints=0;
            for($j=0;$j<sizeof($subjects);$j++)
            {
                $subjectCode=$db->getData("subjects","subjectCode","subjectID",$subjects[$j]);
                $gradeCode=$db->getData("grades","gradeCode","gradeID",$grade[$j]);
                $atotalPoints+=$arrpoints[$j];
                $adata[]=$subjectCode."-".$gradeCode;
            }
        }
        if($atotalPoints==0)
            $atotalPoints="";

        /*$asubjects=$db->getSelectionSubjects($applicantID,"Advance");
        if(!empty($osubjects))
        {
            $adata=array();
            foreach ($asubjects as $subject) {
                $atotalPoints=0;
                $subjectID=$subject['subjectID'];
                $subjectCode=$subject['subjectCode'];
                $gradeID=$subject['gradeID'];
                $grade=$db->getData("grades","gradeCode","gradeID",$gradeID);
                $points=$subject['points'];
                $atotalPoints+=$points;
                $adata[]=$subjectCode."-".$grade;
            }

        }
        else
        {
            $adata[]="";
            $atotalPoints="";
        }*/
        /*$atotalPoints=0;
          $applicantPoints=$db->getSelectionPoints($applicantID,'Advance');
          if(!empty($applicantPoints))
          {
              $atotalPoints=0;
              foreach ($applicantPoints as $appoints) {
                  $points=$appoints['points'];
                  $atotalPoints+=$points;
              }

          }
         else {
                 $atotalPoints='';
         }*/



        $programmeChoice=$db->getProgramme($applicantID,2);
        if(!empty($programmeChoice))
        {
            foreach ($programmeChoice as $pChoice)
            {
                $programmeData=$pChoice['programCode'];
            }
        }

        $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
        if(!empty($equivalentresults))
        {
            foreach($equivalentresults as $matokeo)
            {
                $applicantResultID=$matokeo['applicantResultID'];
                $schoolName=$matokeo['schoolName'];
                $yearTaken=$matokeo['yearTaken'];
                $eregno=$matokeo['indexNumber'];
                $avnNumber=$matokeo['avn_number'];
                $exam_authority=$matokeo['examinationAuthority'];
                $exam_level=$matokeo['examinationLevel'];
                $award=$matokeo['award'];
                $gradeType=$matokeo['gradeType'];
                $gradePoints=$matokeo['gradePoints'];
            }
        }

        $programmeMajor=$db->getProgrammeMajorID($applicantID);
        if(!empty($programmeMajor))
        {
            foreach ($programmeMajor as $pChoice)
            {
                $programmeMajorID=$pChoice['programmeMajorID'];
                //$combination=$programmeMajorID;
                $combination=$db->getData("programmemajor","major","programmeMajorID",$programmeMajorID);
            }
        }

        if(!empty($avnNumber))
            $eIndexNumber=$avnNumber;
        else
            $eIndexNumber=$eregno;


        $output['data'][] = array(
            $name,
            $gender,
            $combination,
            implode(",",$formfour),
            implode(",",$odata),
            $totalPoints,
            implode(",",$formsix),
            implode(",",$adata),
            $atotalPoints,
            $gradePoints,
            $eIndexNumber,
            $award,
            $db->getData("qualificationtype","qualificationName","qualificationTypeID",$exam_authority),
            $schoolName,
            $programmeData
        );

    }
}

echo json_encode($output);