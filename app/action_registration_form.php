<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
try {
include '../DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$tblEmployment='employmentstatus';
$tblSponsor='sponsor';
$tblDisability='disability';

$applicantID=$_SESSION['applicantID'];

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'edit'){
        $fname = htmlentities($_POST['fname'],ENT_QUOTES);
        $mname = htmlentities($_POST['mname'],ENT_QUOTES);
        $lname = htmlentities($_POST['lname'],ENT_QUOTES);
        $oname=htmlentities($_POST['oname'],ENT_QUOTES);
        $date=$_POST['date'];
        $month=$_POST['month'];
        $year=$_POST['year'];
        $dob=$year."-".$month."-".$date;
        $placeOfBirth=htmlentities($_POST['placeOfBirth'],ENT_QUOTES);
        $mstatus=$_POST['mstatus'];
        $citizenship=htmlentities($_POST['citizenship'],ENT_QUOTES);
        $address=htmlentities($_POST['address'],ENT_QUOTES);
        $appemail=htmlentities($_POST['appemail'],ENT_QUOTES);
        $phoneNumber=htmlentities($_POST['phoneNumber'],ENT_QUOTES);
        //Disability
        $disability=$_POST['disability'];
        if($disability=='ndio')
            $disability="Yes";
        else 
            $disability="No";
        $dname=htmlentities($_POST['dname'],ENT_QUOTES);
        $ddescription=htmlentities($_POST['ddescription'],ENT_QUOTES);
        //idfentification
        $znzID = htmlentities($_POST['znzID'], ENT_QUOTES);
        $nidaID = htmlentities($_POST['nidaID'], ENT_QUOTES);
        $passport = htmlentities($_POST['passport'], ENT_QUOTES);
        //Employment
        $employed=$_POST['employed'];
        $employer=htmlentities($_POST['employer'],ENT_QUOTES);
        $placework=htmlentities($_POST['placework'],ENT_QUOTES);
        $designation=htmlentities($_POST['designation'],ENT_QUOTES);
        //Next of Kin
        $nextName=htmlentities($_POST['nextName'],ENT_QUOTES);
        $nextAddress=htmlentities($_POST['nextAddress'],ENT_QUOTES);
        $nextPhoneNumber=htmlentities($_POST['nextPhoneNumber'],ENT_QUOTES);
        $relationship=htmlentities($_POST['relationship'],ENT_QUOTES);
        //Sponsor
        $sponsor=htmlentities($_POST['sponsor'],ENT_QUOTES);
        $sponsorname=htmlentities($_POST['sponsorname'],ENT_QUOTES);
        $sponsoraddress=htmlentities($_POST['sponsoraddress'],ENT_QUOTES);
        $sponsorphonenumber=htmlentities($_POST['sponsorphonenumber'],ENT_QUOTES);
        $districtID=htmlentities($_POST['districtID']);
        //$agentID=htmlentities($_POST['agentID']);
        
     //update applicants first
        $applicantsData = array(
            'firstName'=>$fname,
            'middleName'=>$mname,
            'lastName'=>$lname,
            'otherNames'=>$oname,
            'dateOfBirth'=>$dob,
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
            'districtID'=>$districtID,
            'appinfostatus'=>1
        );
        $condition=array('applicantID'=>$applicantID);
        $update = $db->update($tblName,$applicantsData,$condition);
        
        if($disability=="Yes"){
            $disabilityData=array(
                'applicantID'=>$applicantID,
                'disabilityName'=>$dname,
                'disabilityDescription'=>$ddescription
            );
            if($db->isFieldExist($tblDisability, "applicantID", $applicantID))
            {
                $condition=array('applicantID'=>$applicantID);
                $update=$db->update($tblDisability,$disabilityData,$condition);
            }
            else
            {
                $insertDisability=$db->insert($tblDisability,$disabilityData);
            }
            
        }
        
        if($employed=="yes")
        {
              $employmentData=array(
              'applicantID'=>$applicantID,
               'employer'=>$employer,
                'placeOfWork'=>$placework,
                'designation'=>$designation
            );
            if($db->isFieldExist($tblEmployment,"applicantID", $applicantID))
            {
                $condition=array('applicantID'=>$applicantID);
                $update=$db->update($tblEmployment,$employmentData,$condition);
            }
            else
            {
                $insertEmp=$db->insert($tblEmployment,$employmentData);
            }
        }

        if((!empty($znzID))||(!empty($nidaID))||(!empty($passport)))
        {
            $identificationData=array(
                'applicantID'=>$applicantID,
                'zanzibarID'=>$znzID,
                'nationalID'=>$nidaID,
                'passportNumber'=>$passport
            );
                if ($db->isFieldExist("applicant_identification", "applicantID", $applicantID)) {
                    $condition = array('applicantID' => $applicantID);
                    $update = $db->update("applicant_identification", $identificationData, $condition);
                } else {
                    $insertEmp = $db->insert("applicant_identification", $identificationData);
                }
        }
        
        if($sponsor=="others")
        {
            $sponsorData=array(
                'applicantID'=>$applicantID,
                'sponsorName'=>$sponsorname,
                'sponsorAddress'=>$sponsoraddress,
                'sponsorPhoneNumber'=>$sponsorphonenumber
            );
            if($db->isFieldExist($tblSponsor, "applicantID", $applicantID))
            {
                $condition=array('applicantID'=>$applicantID);
                $update=$db->update($tblSponsor,$sponsorData,$condition);
            }
            else
            {
                $insertSponsor=$db->insert($tblSponsor,$sponsorData);
            }
        }
        
        $boolStatus=true;
        
    }
    
    if($boolStatus)
    {
        //header("Location:index.php?sz=personalinfo&msg=succ");
        //header("Location:index.php?sz=payments");
        header("Location:index.php?sz=submit");
    }
    else
    {
        header("Location:index.php?sz=personalinfo&msg=unsucc");
    }
}

} catch (PDOException $ex) {
    header("Location:index.php?sz=personalinfo&msg=error");
}