<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
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

                        $equivalentresults=$db->getRows("academic_background", array('where'=>array('applicantID'=>$applicantID,'qualificationID'=>3),'order_by applicantID ASC'));
                        if(!empty($equivalentresults))
                        {
                            foreach($equivalentresults as $matokeo)
                            {
                                $instituteName=$matokeo['institutionName'];
                                $endYear=$matokeo['endYear'];
                                $regNumber=$matokeo['registrationNumber'];
                                $progName=$matokeo['programmeName'];
                                $gpa=$matokeo['gpa'];
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
                    $comments="None";
                }

                $actionButton = '
                <div class="btn-group">
                    <a href="index3.php?sp=pgapplicantdetails&applicantID=' . $applicantID . '"><span class="glyphicon glyphicon-edit"></span>View</a>
                </div>';
                      
                $output['data'][] = array(
		        $i,
		        $name,
		        $gender,
                implode(",",$formfour),
                $progName,
                $gpa,
                $endYear,
                $instituteName,
                $regNumber,
                $comments,
                $actionButton
                );
                      
                     }
                 }

echo json_encode($output);