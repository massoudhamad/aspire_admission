<?php 
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$roundName=$_GET['roundName'];
$admissionID=$_GET['admissionID'];
$remarkID=$_GET['remarkID'];
$academicYearID=$db->getData("admission_setting","academicYearID","admissionID",$admissionID);

//getcompulsory subjects ffor this program


 $applicantsData=$db->getApplicantsApproved($programmeID,$academicYearID,$admissionID,0,$roundName,$remarkID);
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
                             $failstatus=0;

                             
                             foreach($osubjects as $subject) {
                                 $subjectID=$subject['subjectID'];
                                 $gradeID=$subject['gradeID'];
                                 $points=$subject['points'];
                                 /* if(empty($subjects))
                                 {
                                     $subjects[]=$subjectID;
                                     $grade[]=$subject['gradeID'];
                                     $arrpoints[]=$subject['points'];
                                 }
                                 else  */if(in_array($subjectID,$subjects))
                                 {
                                     for($i=0;$i<sizeof($subjects);$i++)
                                     {
                                         if($subjects[$i]==$subjectID)
                                         //if($subjects[$i]==3 || $subjects[$i]==5 || $subjects[$i]==7)
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
                                        if($programmeID==2)//Nursing
                                        {
                                            if($subjectID==3 || $subjectID==5 || $subjectID==7)
                                            {
                                                $subjects[]=$subjectID;
                                                $grade[]=$gradeID;
                                                $arrpoints[]=$points;
                                            }
                                        }else if($programmeID==3) //Pharmacy
                                        {
                                            if($subjectID==5 || $subjectID==7)
                                            {
                                                $subjects[]=$subjectID;
                                                $grade[]=$gradeID;
                                                $arrpoints[]=$points;
                                            }
                                        }
                                 } 
                             }
                             //print_r($subjects);
                                 $totalPoints=0;$odata=array();
                                 for($j=0;$j<sizeof($subjects);$j++)
                                 {
                                     $subjectCode=$db->getData("subjects","subjectCode","subjectID",$subjects[$j]);
                                     $gradeCode=$db->getData("grades","gradeCode","gradeID",$grade[$j]);
                                     $totalPoints+=$arrpoints[$j];
                                     $odata[]=$subjectCode."-".$gradeCode;
                                 }

                                 //all subject array
                                 $ordinarylevel=array();
                                 
                                 //for($j=0;$j<sizeof($osubjects);$j++)
                                 foreach($osubjects as $subject2) {
                                    $subjectID2=$subject2['subjectID'];
                                    $gradeID2=$subject2['gradeID'];
                                     $subjectCode2=$db->getData("subjects","subjectCode","subjectID",$subjectID2);
                                     $gradeCode2=$db->getData("grades","gradeCode","gradeID", $gradeID2);
                                     $ordinarylevel[]=$subjectCode2."-".$gradeCode2;
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
                implode(",",$ordinarylevel),
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