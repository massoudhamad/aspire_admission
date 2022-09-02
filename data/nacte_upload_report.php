<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());
$programmeID=$_GET['programmeID'];
$admissionID=$_GET['admissionID'];
$remarksID=$_GET['remarksID'];

$applicantsData=$db->getNacteAdmittedList($programmeID,$admissionID,$remarksID);
if(!empty($applicantsData))
{
    $x=0;
    foreach ($applicantsData as $row)
    {
        $x++;
        $applicantID=$row['applicantID'];
        $userID=$row['userID'];
        $indexNumber=$db->getData("users","userName","userID",$userID);
        $fname= $row['firstName'];
        $mname=$row['middleName'];
        $lname=$row['lastName'];
        $gender=$row['gender'];
        $phoneNumber=$row['phoneNumber'];
        $dob=$row['dob'];
        $email=$row['email'];

        if(empty($email))
        {
            $email=$lname."_".$fname."@gmail.com";
        }

        $disabilityStatus=$row['disabilityStatus'];
        $districtID=$row['districtID'];

        $districtName=$db->getData("district","districtName","districtID",$districtID);
        $regionID=$db->getData("district","regionID","districtID",$districtID);

        $regionName=$db->getData("region","regionName","regionID",$regionID);
        //O-Level
        $olevel=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Ordinary'),'order_by applicantID ASC'));
        if(!empty($olevel))
        {
            $formfour=array();$yearO=array();
            foreach($olevel as $matokeo)
            {
                $yearTakenO=$matokeo['yearTaken'];
                $yearO[]=$yearTakenO;
                $indexNumberO=$matokeo['indexNumber'];
                $number=explode("/",$indexNumberO);
                $centerNumber=$number[0];
                $iNumber=$number[1];
                $year=$number[2];
                //$yearTakenO[]=$year;
                $indNumber=$centerNumber."/".$iNumber;
                $formfour[]=$indNumber;
            }
        }
        else
        {
            $yearO="-";
            $indexNumberO="-";
            $formfour="";

        }
        //A-level

        $alevel=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Advance'),'order_by applicantID ASC'));

        if(!empty($alevel))
        {
            $formsix=array();
            $yaken=array();
            foreach($alevel as $matokeo)
            {
                $yearTakenA=$matokeo['yearTaken'];
                $indexNumberA=$matokeo['indexNumber'];
                $number=explode("/",$indexNumberA);
                $centerNumber=$number[0];
                $iNumber=$number[1];
                $year=$number[2];
                //$yearTakenA=$year;
                $indNumber=$centerNumber."/".$iNumber;
                $formsix[]=$indNumber;
                $yaken[]=$yearTakenA;

            }
        }
        else
        {
            $yaken="-";
            $indexNumberA="-";
            $formsix="";
        }

        $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
        if(!empty($equivalentresults))
        {
            foreach($equivalentresults as $matokeo)
            {
                $yearTaken=$matokeo['yearTaken'];
                $indexNumber=$matokeo['indexNumber'];
            }
        }
        else
        {
            $yearTaken="-";
            $indexNumber="-";
        }


        /*$equivalentresults4=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent','examinationAuthority'=>1),'order_by applicantID ASC'));
        if(!empty($equivalentresults4))
        {
            foreach($equivalentresults4 as $matokeo)
            {
                $yearTaken4=$matokeo['yearTaken'];
                $indexNumber4=$matokeo['indexNumber'];
            }
        }
        else
        {
            $yearTaken4="-";
            $indexNumber4="-";
        }*/

        $checkbox="<input type='checkbox' class='checkbox_class' name='applicantID[]' value='$applicantID'>";
        $output['data'][] = array(
            $x,
            $checkbox,
            $fname,
            $mname,
            $lname,
            $dob,
            $gender,
            $disabilityStatus,
            implode(",",$formfour),
            implode(",",$yearO),
            implode(",",$formsix),
            implode(",",$yaken),
            "",
            "",
            $indexNumber,
            $yearTaken,
            $email,
            $phoneNumber,
            $row['physicalAddress'],
            $regionName,
            $districtName,
            $row['nextOfKinName'],
            $row['nextOfKinPhoneNumber'],
            $row['nextOfKinAddress'],
            $row['relationship'],
            $regionName,
            $row['citizenship']
        );

        //$x++;
    }
}

// database connection close


echo json_encode($output);
//$db->close();