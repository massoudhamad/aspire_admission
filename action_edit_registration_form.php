<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'applicants';
$tblEmployment='employmentstatus';
$tblSponsor='sponsor';
$tblDisability='disability';

$applicantID=$_POST['applicantID'];

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'edit'){
        $fname = htmlentities($_POST['fname'],ENT_QUOTES);
        $mname = htmlentities($_POST['mname'],ENT_QUOTES);
        $lname = htmlentities($_POST['lname'],ENT_QUOTES);
        $oname=htmlentities($_POST['oname'],ENT_QUOTES);
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
        $avnumber=htmlentities($_POST['avnumber'],ENT_QUOTES);
        
     //update applicants first
        $applicantsData = array(
            'firstName'=>$fname,
            'middleName'=>$mname,
            'lastName'=>$lname,
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
            'avnumber'=>$avnumber
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
        header("Location:index3.php?sp=applicantdetails&applicantID=$applicantID");
    }
    else
    {
        header("Location:index3.php?sp=sp=edit_personal_details&applicantID=$applicantID&msg=unsucc");
    }
}

} catch (PDOException $ex) {
    header("Location:index3.php?sp=sp=edit_personal_details&applicantID=$applicantID&msg=error");
    //echo "Data Error".$ex->getMessage();
}