
<?php
session_start();
try{
include '../DB.php';
$db=new DBHelper();
$tblName="applicants";
$tblRemarks='applicantremarks';
if($_REQUEST['applicantID'])
{
	$applicantID=$_REQUEST['applicantID'];
        $processDate=date('Y-m-d H:i:s');
        $remarksData=array(
          'applicantID'=>$applicantID,
          'remarkID'=>1,
          'programID'=>0,
          'processDate'=>$processDate,
          'activeStatus'=>1
        );
        $insert=$db->insert($tblRemarks,$remarksData);
        $year=date('y');
        $year2=$year+1;
        $orgNumber=$db->getOrganizationValue("organizationReference");
        $refNumber = $orgNumber."/".$applicantID."/".$year."-".$year2;
        $data=array(
          'applicantsRemarksID'=>1,
          'refNumber'=>$refNumber
        );
        $condition= array('applicantID'=>$applicantID);
        $update=$db->update($tblName, $data, $condition);
        mail($email,"Online Admission System","Thank You for submitting your application, you will get message notification for any changes in your application");
	if ($update) {
        //submit data to TCU
        if(($db->checkApplicantStudyLevel($applicantID)==1)) 
        {
          //get data of applicant
                      $applicantsData = $db->getApplicantDataTCU($applicantID);
                        if (!empty($applicantsData)) {
                            $i = 0;
                            foreach ($applicantsData as $data) {
                                $i++;
                                $applicantID = $data['applicantID'];
                                $fname = $data['firstName'];
                                $mname = $data['middleName'];
                                $lname = $data['lastName'];
                                $gender = $data['gender'];
                                $dob = $data['dob'];
                                $disabiliyStatus = $data['disabilityStatus'];
                                $nationality = $data['citizenship'];
                                $phoneNumber = $data['phoneNumber'];
                                $email = $data['email'];
                                $entryQualification = $data['entryQualification'];
                                $tcu_status = $data['tcu_status'];
                                $tcu_final = $data['tcu_final'];

                                if($gender=="Male")
                                  $gender="M";
                                else 
                                  $gender="F";

                            
                                if ($entryQualification == 0)
                                    $category = "A";
                                else
                                    $category = "D";


                                $oindexumber = $db->getIndexNumber($applicantID, "Ordinary");
                                if (!empty($oindexumber)) {
                                    $formfour = array();
                                    foreach ($oindexumber as $fnumber) {
                                        $indexNumber = $fnumber['indexNumber'];
                                        $formfour[] = $indexNumber;
                                    }
                                } else {
                                    $formfour[] = "";
                                }



                                $aindexumber = $db->getIndexNumber($applicantID, "Advance");
                                $formsix = array();
                                if (!empty($aindexumber)) {
                                    $formsix = array();
                                    foreach ($aindexumber as $fsixnumber) {
                                        $indexNumber = $fsixnumber['indexNumber'];
                                        $formsix[] = $indexNumber;
                                    }
                                } else {
                                    $formsix[] = "";
                                }

                              

                                $equivalentresults = $db->getRows("applicantresults", array('where' => array('applicantID' => $applicantID, 'examinationLevel' => 'Equivalent'), 'order_by applicantID ASC'));
                                if (!empty($equivalentresults)) {
                                    foreach ($equivalentresults as $matokeo) {
                                        $eIndexNumber = $matokeo['indexNumber'];
                                        $avn_number = $matokeo['avn_number'];
                                    }
                                } else {
                                    $eIndexNumber = "";
                                    $avn_number = "";
                                }


                                if ($category == "A")
                                    $findexNumber = $formsix[0];
                                else {
                                    if ($avn_number == "")
                                        $findexNumber = $eIndexNumber;
                                    else
                                        $findexNumber = $avn_number;
                                }


                                $four = array();
                                for ($x = 1; $x < count($formfour); $x++) {
                                    $four[] = $formfour[$x];
                                }

                                $six = array();
                                for ($x = 1; $x < count($formsix); $x++) {
                                    $six[] = $formsix[$x];
                                }
                                $fourfour = implode(",", $four);
                                $sixsix = implode(",", $six);

          //end of data
            $api_token = $db->getAPI("TCU", "token");
            if (!empty($api_token)) {
                foreach ($api_token as $api) {
                    $token = $api['token'];
                    $user = $api['userName'];
                    $urlform = $api['url'];
                }
            }

        $url = $urlform."/applicants/add";
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>' . $user . '</Username>
        <SessionToken>' . $token . '</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <f4indexno>' . $formfour[0] . '</f4indexno>
        <f6indexno>' . $formsix[0] . '</f6indexno>
        <Gender>'.$gender.'</Gender>
        <Category>' . $category . '</Category>
        <Otherf4indexno>' . $fourfour . '</Otherf4indexno>
        <Otherf6indexno>' . $sixsix . '</Otherf6indexno>
        </RequestParameters>
        </Request>';

            $output = $db->addApplicantTCU($url, $xml);
            $array_data = json_decode(json_encode(simplexml_load_string($output)), true);
            $status = $array_data['Response']['ResponseParameters']['StatusCode'];
            $status_descript = $array_data['Response']['ResponseParameters']['StatusDescription'];

            if ($status == 200) {
                $tcudata = array(
                    'tcu_status' => 1
                );
                $condition = array('applicantID' => $applicantID);
                $updateapplicants = $db->update($tblApplicants, $tcudata, $condition);
                $boolStatus = true;
                $msgs= $status_descript;
            }
            else 
            {
                $msgs = $status_descript;
            }
          }
        }
      }
        //end of data submit to TCU

        echo "Congratulations, your application has been successfully submitted!!!".$msgs;
        //header("Location:index.php");
	}
}
}catch (PDOException $x)
{

}
?>