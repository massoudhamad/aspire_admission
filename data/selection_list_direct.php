<?php 
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$roundName=$_GET['roundName'];
$admissionID=$_GET['admissionID'];
$academicYearID=$db->getData("admission_setting","academicYearID","admissionID",$admissionID);

 $applicantsData=$db->getApplicantsApproved($programmeID,$academicYearID,$admissionID,0,$roundName);
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
                                 $gradePoints=$db->getData("grades","gradePoint","gradeID",$gradeID);
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
                       
                         /*$applicantPoints=$db->getSelectionPoints($applicantID,'Ordinary');
                         if(!empty($applicantPoints))
                         {
                             $totalPoints=0;
                             foreach ($applicantPoints as $appoints) {
                                 $points=$appoints['points'];
                                 $totalPoints+=$points;
                             }
                             
                         }*/
                         
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
                        
                         
                         /*$asubjects=$db->getSelectionSubjects($applicantID,"Advance");
                         $adata=array();
                         if(!empty($asubjects))
                         {
                             
                             $atotalPoints=0;
                             foreach ($asubjects as $subject) {
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
                         
                        /* else
                         {
                             $adata[]="";
                             $atotalPoints="";
                         }*/
                        
                       
                         /*$applicantPoints=$db->getSelectionPoints($applicantID,'Advance');
                         if(!empty($applicantPoints))
                         {
                             $atotalPoints=0;
                             foreach ($applicantPoints as $appoints) {
                                 $points=$appoints['points'];
                                 $atotalPoints+=$points;
                             }
                             
                         }
                        else {
                            $atotalPoints="";
                        }*/
                         
                         
                         
                      $programmeChoice=$db->getProgramme($applicantID,2);
                      if(!empty($programmeChoice))
                      {
                          foreach ($programmeChoice as $pChoice)
                          {
                             $programmeData=$pChoice['programCode'];
                             $programID=$pChoice['programID'];
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



                $appremarks = $db->getRows("applicantremarks", array('where' => array('applicantID' => $applicantID)));
                if (!empty($appremarks)) {
                    foreach ($appremarks as $remark) {
                        $userID = $remark['userID'];
                        $processDate = $remark['processDate'];
                        $comments=$remark['comments'];
                    }
                } else {
                    $userID = "";
                    $processDate = "";
                    $comments="";
                }
                      
                $output['data'][] = array(
		        $i,
		        $name,
		        $gender,
                $combination,
                implode(",",$formfour),
                implode(",",$odata),
                $totalPoints,
                implode(",",$formsix),
                implode(",",$adata),
                $atotalPoints,
                $programmeData,
                $comments
                );
                      
                     }
                 }

echo json_encode($output);