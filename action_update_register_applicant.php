<?php
session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */

try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants';
    $tblEmployment='employmentstatus';
    $tblSponsor='sponsor';
    $tblDisability='disability';

    $applicantID=$_POST['applicantID'];
    $formfour=$_POST['formfour'];
    $academicYearID=$_POST['academicYearID'];
    $programmeID=$_POST['programmeID'];


    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'edit') {
            $oname=htmlentities($_POST['oname'], ENT_QUOTES);
            $placeOfBirth=htmlentities($_POST['placeOfBirth'], ENT_QUOTES);
            $mstatus=$_POST['mstatus'];
            $citizenship=htmlentities($_POST['citizenship'], ENT_QUOTES);
            $address=htmlentities($_POST['address'], ENT_QUOTES);
            $appemail=htmlentities($_POST['appemail'], ENT_QUOTES);
            $phoneNumber=htmlentities($_POST['phoneNumber'], ENT_QUOTES);
            //Disability
            $disability=$_POST['disability'];
            if ($disability=='ndio') {
                $disability="Yes";
            } else {
                $disability="No";
            }
            $dname=htmlentities($_POST['dname'], ENT_QUOTES);
            $ddescription=htmlentities($_POST['ddescription'], ENT_QUOTES);
            //Employment
            $employed=$_POST['employed'];
            $employer=htmlentities($_POST['employer'], ENT_QUOTES);
            $placework=htmlentities($_POST['placework'], ENT_QUOTES);
            $designation=htmlentities($_POST['designation'], ENT_QUOTES);
            //Next of Kin
            $nextName=htmlentities($_POST['nextName'], ENT_QUOTES);
            $nextAddress=htmlentities($_POST['nextAddress'], ENT_QUOTES);
            $nextPhoneNumber=htmlentities($_POST['nextPhoneNumber'], ENT_QUOTES);
            $relationship=htmlentities($_POST['relationship'], ENT_QUOTES);
            //Sponsor
            $sponsor=htmlentities($_POST['sponsor'], ENT_QUOTES);
            $sponsorname=htmlentities($_POST['sponsorname'], ENT_QUOTES);
            $sponsoraddress=htmlentities($_POST['sponsoraddress'], ENT_QUOTES);
            $sponsorphonenumber=htmlentities($_POST['sponsorphonenumber'], ENT_QUOTES);
            $hosteller=$_POST['hosteller'];
            $religion=$_POST['religion'];

            //update applicants first
            $applicantsData = array(
                'otherNames'=>$oname,
                'placeOfBirth'=>$placeOfBirth,
                'maritalStatus'=>$mstatus,
                'citizenship'=>$citizenship,
                'physicalAddress'=>$address,
                'phoneNumber' =>$phoneNumber,
                'email'=>$appemail,
                'nextOfKinName'=>$nextName,
                'nextOfKinPhoneNumber'=>$nextPhoneNumber,
                'nextOfKinAddress'=>$nextAddress,
                'relationship'=>$relationship,
                'disabilityStatus'=>$disability,
                'employmentStatus'=>$employed,
                'sponsor'=>$sponsor,
                'religion'=>$religion,
                'hosteller'=>$hosteller
            );
            $condition=array('applicantID'=>$applicantID);
            $update = $db->update($tblName, $applicantsData, $condition);

            if ($disability=="Yes") {
                $disabilityData=array(
                    'applicantID'=>$applicantID,
                    'disabilityName'=>$dname,
                    'disabilityDescription'=>$ddescription
                );
                if ($db->isFieldExist($tblDisability, "applicantID", $applicantID)) {
                    $condition=array('applicantID'=>$applicantID);
                    $update=$db->update($tblDisability, $disabilityData, $condition);
                } else {
                    $insertDisability=$db->insert($tblDisability, $disabilityData);
                }
            }

            if ($employed=="yes") {
                $employmentData=array(
                    'applicantID'=>$applicantID,
                    'employer'=>$employer,
                    'placeOfWork'=>$placework,
                    'designation'=>$designation
                );
                if ($db->isFieldExist($tblEmployment, "applicantID", $applicantID)) {
                    $condition=array('applicantID'=>$applicantID);
                    $update=$db->update($tblEmployment, $employmentData, $condition);
                } else {
                    $insertEmp=$db->insert($tblEmployment, $employmentData);
                }
            }

            if ($sponsor=="others") {
                $sponsorData=array(
                    'applicantID'=>$applicantID,
                    'sponsorName'=>$sponsorname,
                    'sponsorAddress'=>$sponsoraddress,
                    'sponsorPhoneNumber'=>$sponsorphonenumber
                );
                if ($db->isFieldExist($tblSponsor, "applicantID", $applicantID)) {
                    $condition=array('applicantID'=>$applicantID);
                    $update=$db->update($tblSponsor, $sponsorData, $condition);
                } else {
                    $insertSponsor=$db->insert($tblSponsor, $sponsorData);
                }
            }

            //documents
            $documentData=array(
                'applicantID'=>$applicantID,
                'medical'=>$_POST['medical'],
                'certificate'=>$_POST['certificate'],
                'formsixcertificate'=>$_POST['formsixcertificate'],
                'other_document'=>$_POST['other_document']
            );

            $insertdocument=$db->insert("documents", $documentData);

            //Register Student

            //University Specific
            if ($_SESSION['orgCode']=="ZU") {
                $programme = $db->getStudyLevelID($programmeID);
                if (!empty($programme)) {
                    foreach ($programme as $cp) {
                        $regCode = $cp['regCode'];
                        $studyLevelID = $cp['studyLevelID'];
                        $schoolCode=$cp['schoolCode'];
                    }
                } else {
                    $regCode = "";
                    $studyLevelID = '';
                    $schoolCode="";
                }

                if ($studyLevelID == 1) {
                    $levelCode = 20;
                } elseif ($studyLevelID == 2 || $studyLevelID == 3 || $studyLevelID==8) {
                    $levelCode = 10;
                } elseif ($studyLevelID == 4) {
                    $levelCode = 30;
                }

                $academicYear = $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);
                $year = explode("/", $academicYear);
                $year1 = $year[0];

                $regYear=substr($year1, 2);


                //applicantregistration table
                if ($db->isApplicantIDExist($applicantID)) {
                } else {
                    $regNumber = $db->getZUMaxRegNumber($schoolCode);
                    if (!empty($regNumber)) {
                        $finalNumber = $regNumber + 1;
                    } else {
                        $finalNumber=1;
                    }

                if ($db->count_digit($finalNumber) >= 3) {
                    $finalNumber = $finalNumber;
                } else if ($db->count_digit($finalNumber) >= 2) {
                    $finalNumber = "0" . $finalNumber;
                } else if ($db->count_digit($finalNumber) >= 1) {
                    $finalNumber = "00" . $finalNumber;
                }   


                    if ($studyLevelID == 1) {
                        $registrationNumber=$regYear."".$levelCode."".$regCode.$finalNumber;
                    } elseif ($studyLevelID == 2 || $studyLevelID==8) {
                        //$regCode=explode("/", $regCode);
                        /* $registrationNumber=$regYear."".$levelCode."".$regCode[1].$finalNumber; */
                        //$regCode=600000;
                        $regCode = 20;
                        $registrationNumber = $regYear . "" . $levelCode . "".$regCode.$finalNumber;
                    } elseif ($studyLevelID == 3) {
                        //$regCode = explode("/", $regCode);
                        $regCode=20;
                        $registrationNumber = $regYear . "" . $levelCode . "" . $regCode . $finalNumber;
                    } elseif ($studyLevelID==4) {
                        //$levelCode=$
                        $regCode=80;
                        $registrationNumber = $regYear . "" . $levelCode . "" . $regCode . $finalNumber;
                    }
                }
            } elseif ($_SESSION['orgCode']=="SUMAIT") {
                $programme = $db->getStudyLevelID($programmeID);
                if (!empty($programme)){
                    foreach ($programme as $cp) {
                        $studyLevelID = $cp['studyLevelID'];
                        $schoolCode = $cp['schoolCode'];
                    }
                } else {
                    $studyLevelID = '';
                    $schoolCode = "";
                }
                //$studyLevelID = $db->getStudyLevelIDData($programmeID);
                if ($studyLevelID == 5)
                    $studyLevelID == 2;
                else
                    $studyLevelID = $studyLevelID;

                if ($studyLevelID == 1)
                    $regCode = '04';
                else if ($studyLevelID == 2)
                    $regCode = '03';
                else if ($studyLevelID == 3)
                    $regCode = '02';
                //This is for year
                $academicYear = $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);
                $year = explode("/", $academicYear);
                $year1 = $year[0];

                $subYear = substr($year1, 2);

                //applicantregistration table
                if ($db->isApplicantIDExist($applicantID)) {
                } else {
                    //19/BC/001
                    $regNumber = $db->getMaxSUMAITRegNumber($programmeID,$academicYearID);
                    if (!empty($regNumber)) {
                        $finalNumber = $regNumber + 1;
                    }
                    else {
                        $finalNumber=1;
                    }

                    if ($db->count_digit($finalNumber) >= 3) {
                        $finalNumber = $finalNumber;
                    } elseif ($db->count_digit($finalNumber) >= 2) {
                        $finalNumber = "0" . $finalNumber;
                    } elseif ($db->count_digit($finalNumber) >= 1) {
                        $finalNumber = "00" . $finalNumber;
                    }
                    $majorCode = $db->getData("programmemajor", "majorCode", "programmeMajorID", $programmeID);

                    if($studyLevelID==2)
                    {
                        $registrationNumber = $subYear."/D/".$majorCode."/".$finalNumber;
                    }
                    else if($studyLevelID==3)
                    {
                        $registrationNumber = $subYear."/C/".$majorCode."/".$finalNumber;
                    }
                    else
                    {
                        $registrationNumber = $subYear . "/" . $majorCode . "/" . $finalNumber;
                    }

                }
            } elseif ($_SESSION['orgCode'] == "IPA") {
                $programme = $db->getStudyLevelID($programmeID);
                if (!empty($programme)) {
                    foreach ($programme as $cp) {
                        $studyLevelID = $cp['studyLevelID'];
                        $schoolCode = $cp['schoolCode'];
                    }
                } else {
                    $studyLevelID = '';
                    $schoolCode = "";
                }
                $academicYear = $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);
                $year = explode("/", $academicYear);
                $year1 = $year[0];

                $subYear = substr($year1, 2);

                //applicantregistration table
                if ($db->isApplicantIDExist($applicantID)) {
                } else {
                    //BA/IPA/001/HRM.2020
                    $regNumber = $db->getMaxSUMAITRegNumber($programmeID, $academicYearID);
                    if (!empty($regNumber)) {
                        $finalNumber = $regNumber + 1;
                    } else {
                        $finalNumber = 1;
                    }

                    if ($db->count_digit($finalNumber) >= 3) {
                        $finalNumber = $finalNumber;
                    } elseif ($db->count_digit($finalNumber) >= 2) {
                        $finalNumber = "0" . $finalNumber;
                    } elseif ($db->count_digit($finalNumber) >= 1) {
                        $finalNumber = "00" . $finalNumber;
                    }
                    $majorCode = $db->getData("programmemajor", "majorCode", "programmeMajorID", $programmeID);

                    $registrationNumber = "BA/IPA/".$finalNumber."/".$majorCode.".".$year1;
                }
            } elseif ($_SESSION['orgCode'] == "MUM") {
            //$studyLevelID = $db->getStudyLevelIDData($programmeID);

            $programme = $db->getStudyLevelID($programmeID);
            if (!empty($programme)) {
                foreach ($programme as $cp) {
                    $studyLevelID = $cp['studyLevelID'];
                    $schoolCode = $cp['schoolCode'];
                }
            } else {
                $studyLevelID = '';
                $schoolCode = "";
            }

                if ($studyLevelID == 5) {
                    $studyLevelID == 2;
                } else {
                    $studyLevelID = $studyLevelID;
                }

                if ($studyLevelID == 1) {
                    $regCode = '04';
                } elseif ($studyLevelID == 2) {
                    $regCode = '03';
                } elseif ($studyLevelID == 3) {
                    $regCode = '02';
                }
                $academicYear = $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);
                $year = explode("/", $academicYear);
                $year1 = $year[0];
                if ($db->isApplicantIDExist($applicantID)) {
                    $registrationNumber = $db->getData("applicantregistration", "registrationNumber", "applicantID", $applicantID);
                } else {
                //$regNumber = $db->getMaxRegNumber($studyLevelID);
                $regNumber = $db->getMaxMUMRegNumber($studyLevelID);
                    if (!empty($regNumber)) {
                        $finalNumber = $regNumber + 1;
                    } else {
                        $finalNumber = 1;
                    }
                    //$finalNumber = $regNumber + 1;
                    if ($studyLevelID == 1) {
                        $registrationNumber = "MUM" . $year1 . "-" . $regCode . "-0" . $finalNumber;
                    } else if($studyLevelID==4){

                    if($db->count_digit($finalNumber) >= 5) {
                            $finalNumber = $finalNumber;
                        } else if ($db->count_digit($finalNumber) >= 4) {
                            $finalNumber = "0" . $finalNumber;
                        } else if ($db->count_digit($finalNumber) >= 3) {
                            $finalNumber = "00" . $finalNumber;
                        }else if ($db->count_digit($finalNumber) >= 2) {
                            $finalNumber = "000" . $finalNumber;
                        } else if ($db->count_digit($finalNumber) >= 1) {
                            $finalNumber = "0000" . $finalNumber;
                        }
                        $registrationNumber = "MUM/MAED/2020/".$finalNumber;
                    }else {
                        $numlength = strlen((string)$finalNumber);

                        if ($numlength == 5) {
                            $registrationNumber = $year1 . "-" . $regCode . "-" . $finalNumber;
                        } elseif ($numlength == 4) {
                            $registrationNumber = $year1 . "-" . $regCode . "-0" . $finalNumber;
                        } elseif ($numlength == 3) {
                            $registrationNumber = $year1 . "-" . $regCode . "-00" . $finalNumber;
                        } elseif ($numlength == 2) {
                            $registrationNumber = $year1 . "-" . $regCode . "-000" . $finalNumber;
                        } elseif ($numlength == 1) {
                            $registrationNumber = $year1 . "-" . $regCode . "-0000" . $finalNumber;
                        }
                        //$registrationNumber = $year1 . "-" . $regCode . "-0" . $finalNumber;
                    }
                }
                /* $studyLevelID=$db->getStudyLevelIDData($programmeID);
                if ($studyLevelID==5) {
                    $studyLevelID==2;
                } else {
                    $studyLevelID=$studyLevelID;
                }

                if ($studyLevelID==1) {
                    $regCode='04';
                } elseif ($studyLevelID==2) {
                    $regCode='03';
                } elseif ($studyLevelID==3) {
                    $regCode='02';
                }
                $academicYear=$db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);
                $year=explode("/", $academicYear);
                $year1=$year[0];
                if ($db->isApplicantIDExist($applicantID)) {
                } else {
                    $regNumber = $db->getMaxRegNumber($studyLevelID);
                    $finalNumber = $regNumber + 1;

                    if ($studyLevelID==1) {
                        $registrationNumber = "MUM" . $year1 . "-" . $regCode . "-0" . $finalNumber;
                    } else {
                        $registrationNumber = $year1 . "-" . $regCode . "-0" . $finalNumber;
                    }
                } */
            }

            //echo $registrationNumber;

            $regData = array(
                    'academicYearID' => $academicYearID,
                    'studyLevelID' => $studyLevelID,
                    'programmeID' => $programmeID,
                    'applicantID' => $applicantID,
                    'registrationNumber' => $registrationNumber,
                    'regNumber' => $finalNumber,
                    'schoolCode'=>$schoolCode
                );
            $insert = $db->insert("applicantregistration", $regData);


            $conditions = array('applicantID' => $applicantID);
            $data = array(
                'applicantsRemarksID' => 6
            );
            $updateapplicants = $db->update("applicants", $data, $conditions);
            //remarks table
            $appData = array(
                'applicantID' => $applicantID,
                'remarkID' => 6,
                'programID' => $programmeID,
                'userID'=>$_SESSION['user_session'],
                'processDate'=> date("Y-m-d H:i:s")
            );
            $insert = $db->insert("applicantremarks", $appData); 
            //upload image
            $imgFile = $_FILES['photo']['name'];
            $tmp_dir = $_FILES['photo']['tmp_name'];
            $imgSize = $_FILES['photo']['size'];
            if (!empty($imgFile)) {
                $upload_dir = 'student_images/'; // upload directory

                $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                // valid image extensions
                    $valid_extensions = array('png','jpg','jpeg'); // valid extensions

                    // rename uploading image
                $userpic = rand(1000, 1000000).".".$imgExt;

                // allow valid image file formats
                if (in_array($imgExt, $valid_extensions)) {
                    // Check file size '5MB'
                    if ($imgSize < 5000000) {
                        move_uploaded_file($tmp_dir, $upload_dir.$userpic);
                        $pictureData=array(
                                'studentPicture'=>$userpic
                            );
                        $condition=array('applicantID'=>$applicantID);
                        $update = $db->update("applicants", $pictureData, $condition);
                    } else {
                        $errMSG = "Sorry, your image file is too large.";
                        $boolStaus=false;
                    }
                } else {
                    $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                    $boolStaus=false;
                }
            }

            $boolStatus = true;
            

            if($boolStatus)
             {
                 header("Location:index3.php?sp=register_applicant&applicantID=$applicantID&formfour=$formfour&msg=succ");
             }
             else
             {
                 header("Location:index3.php?sp=register_applicant&applicantID=$applicantID&formfour=$formfour&msg=unsucc");
             } 
        }
    }

  } catch (PDOException $ex) {
    header("Location:index3.php?sp=register_applicant&applicantID=$applicantID&formfour=$formfour&msg=error");
}  