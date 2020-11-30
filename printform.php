<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);  
if($_REQUEST['action']=="getPDF")
{
    include 'DB.php';
    $db=new DBHelper();
    require('fpdf.php');
    $applicantID=$_REQUEST['applicantID'];
    $academicYearID=$_REQUEST['academicYearID'];
    $academicYear = $db->getData("academicyears", "academicYear", "academicYearID", $academicYearID);
    $today=date('d-M-Y H:i:s');

    $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
    if (!empty($organization)) {
        foreach ($organization as $org) {
            $organizationName = $org['organizationName'];
            $organizationCode = $org['organizationCode'];
            $orgAddress = $org['organizationAddress'];
            $orgPhone = $org['organizationPhone'];
            $orgEmail = $org['organizationEmail'];
        }
    } else {
        $organizationName = "Soft Dev Academy";
        $organizationCode = "SDVA";
        $organizationPicture = "../img/SkyChuo.png";
    }

    class PDF extends FPDF
    {
        function Banner()
        {
            $today=date('M d,Y');
            //Logo .
            $this->Image('img/letterhead.png',10,5,200,45);
            //$this->Image('img/logo.jpg',170,10,35.98,37.22);
        }
        function BasicTable($header)
        {
            $w = array(100,20,70);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }

        function BirthTable($header)
        {
            $w = array(30,40,40,40,40);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }

        function CertificateTable($header)
        {
            $w = array(40,100,50);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }

        function EquaivalentTable($header)
        {
            $w = array(25,25,65,55,20);
            for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],6,$header[$i],1,0,'L',0);
            $this->Ln();

        }

        function Footer()
        {
            $today2=date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(260,0,'Printed Date'.$today2,0,1,'L');

        }
    }
    $pdf=new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage("P");
    $pdf->setFont('Arial', '', 8);
    $applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'applicantID ASC'));
    if(!empty($applicantsData)){
        foreach($applicantsData as $apps)
        {
            $applicantID=$apps['applicantID'];
            $fname=$apps['firstName'];
            $mname=$apps['middleName'];
            $lname=$apps['lastName'];
            $oname=$apps['otherNames'];
            $gender=$apps['gender'];
            $pobirth=$apps['placeOfBirth'];
            $mstatus=$apps['maritalStatus'];
            $citizenship=$apps['citizenship'];
            $districtID=$apps['districtID'];
            $dob=$apps['dateOfBirth'];
            $paddress=$apps['physicalAddress'];
            $pobox=$apps['postalAddress'];
            $pnumber=$apps['phoneNumber'];
            $email=$apps['email'];
            $nkin=$apps['nextOfKinName'];
            $nphone=$apps['nextOfKinPhoneNumber'];
            $naddress=$apps['nextOfKinAddress'];
            $nrelation=$apps['relationship'];
            $dstatus=$apps['disabilityStatus'];

            $empStatus=$apps['employmentStatus'];
            $sponsor=$apps['sponsor'];
            $religion=$apps['religion'];
            $hosteller=$apps['hosteller'];
            $applicantsRemarksID=$apps['applicantsRemarksID'];
            $studentPicture=$apps['studentPicture'];

            

            $name="$fname $mname $lname";

            //$pdf->Image('img/logo.jpg',170,300,35.98,37.22);

            $districtName=$db->getData('district','districtName','districtID',$districtID);
            $regionID=$db->getData('district','regionID','districtID',$districtID);

            $regionName=$db->getData('region','regionName','regionID',$regionID);


            $programmeAdmitted=$db->getAdmittedProgramme($applicantID,1);
            if(!empty($programmeAdmitted))
            {
                foreach ($programmeAdmitted as $padmitt)
                {
                    $programmeID=$padmitt['programmeMajorID'];
                    $programmeAdName=$padmitt['programmeMajor'];
                }
            }


            $pdf->Banner();
            if (!empty($studentPicture)) {
                $pdf->Image('student_images/'.$studentPicture, 170, 8, 35.98, 37.22);
            }
            $pdf->Ln(40);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->Cell(6);
            $pdf->Cell(180,6,"STUDENT REGISTRATION FORM","0",0,'C');
            $pdf->setFont('Arial', '', 12);


            $pdf->Ln(8);
            $pdf->setFont('Arial', '', 11);
            $pdf->Cell(6);$pdf->Cell(140,6,"Prog.Name:".$programmeAdName,0);
            $pdf->Cell(6);$pdf->Cell(50,6,"Academic Year:". $academicYear,0);

            $pdf->Ln(10);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->Cell(6);$pdf->Cell(90,6,"1. Name:".$name);

            $regNumber=$db->getRows("applicantregistration",array('where'=>array('applicantID'=>$applicantID)));
            if(!empty($regNumber))
            {
                foreach($regNumber as $reg)
                {
                    $registrationNumber=$reg['registrationNumber'];
                }
            }
            else
            {
                $registrationNumber="";
            }
            $pdf->Cell(6);$pdf->Cell(90,6,"Reg.Number:".$registrationNumber);
            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"2. Birth Details:");

            $header=array('DOB','Village/Town','District','Region','Country');
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->setFont('Arial', '', 12);
            $pdf->BirthTable($header);

            $pdf->setFont('Arial', '', 10);
            $pdf->Cell(6,6,'');
            $pdf->Cell(30,6,$dob,1);
            $pdf->Cell(40,6,$paddress,1);
            $pdf->Cell(40,6,explode(' ',trim($districtName))[0],1,0);
            $pdf->Cell(40,6,explode(' ',trim($regionName))[0],1,0);
            $pdf->Cell(40,6,$citizenship,1,0);

            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"3. Gender: ".$gender." Marital Status: ".$mstatus);

            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"4. Religion: ".$religion);


            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"5. Postal Address: ".$pobox);

            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"6. Telephone Number: ".$pnumber);

            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"7. Email Address: ".$email);


            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->MultiCell(180,6,"8. Next of Kin: ".strtoupper($nkin)." Address:".strtoupper($naddress)." Phone:".strtoupper($nphone)." Relationship:".strtoupper($nrelation));


            $pdf->Ln(6);
            $pdf->setFont('Arial', '', 12);
            if($dstatus=="No") {
                $disabilityName = "None";
            }
            else
            {
                $disabilityData=$db->getRows("disability",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                foreach($disabilityData as $sp)
                {
                    $disabilityName=$sp['disabilityName'];
                }
            }
            $pdf->Cell(6);$pdf->Cell(180,6,"9. Disability: ".$disabilityName);



            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);

            if($sponsor=="others")
            {
                $sponsorData=$db->getRows("sponsor",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));
                foreach($sponsorData as $sp)
                {
                    $sponsorName=$sp['sponsorName'];

                }
            }
            else
            {
                $sponsorName=$sponsor;
            }
            $pdf->Cell(6);$pdf->Cell(180,6,"10. Sponsor: ".$sponsorName);


            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);

            if($empStatus=="yes")
            {
                $sponsorData=$db->getRows("employmentstatus",array('where'=>array('applicantID'=>$applicantID),'order_by applicantID ASC'));

                foreach($sponsorData as $sp)
                {
                    $employer=$sp['employer'];
                    $placework=$sp['placeOfWork'];
                    $designation=$sp['designation'];
                }
                $pdf->Cell(6);$pdf->Cell(180,6,"11. Employer: ".$employer." Place of Work: ".$placework." Designation: ".$designation);
            }
            else
            {
               $employment=$empStatus;
                $pdf->Cell(6);$pdf->Cell(180,6,"11. Employed: ".strtoupper($employment));
            }


            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"12. Accomodation/Hostel: ".$hosteller);


            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"13. CERTIFICATES OF SECONDARY SCHOOL EXAMINATION (FORM IV) RESULTS: ");
            $pdf->Ln(10);

            $header=array('Index Number','School Name','Examination Authority');
            $pdf->Cell(6);
            $pdf->setFont('Arial', '', 12);
            $pdf->CertificateTable($header);

            $oindexumber=$db->getIndexNumber($applicantID,"Ordinary");
            if(!empty($oindexumber))
            {
                foreach ($oindexumber as $fnumber) {
                    $indexNumber=$fnumber['indexNumber'];
                    $schoolName=$fnumber['schoolName'];
                    $eauthority=$fnumber['examinationAuthority'];

                    $pdf->setFont('Arial', '', 10);
                    $pdf->Cell(6,6,'');
                    $pdf->Cell(40,6,$indexNumber,1);
                    $pdf->Cell(100,6,$schoolName,1);
                    $pdf->Cell(50,6,$eauthority,1,0);
                }

            }
            else
            {
                $pdf->Cell(180,6,"No Data Found",0);
            }




            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"14. ADVANCED CERTIFICATE OF SECONDARY EDUCATION EXAMINATIONS (Form VI) RESULTS: ");

            $pdf->Ln(10);
            $header=array('Index Number','School Name','Examination Authority');
            $pdf->Cell(6);
            $pdf->setFont('Arial', '', 12);


            $aindexumber=$db->getIndexNumber($applicantID,"Advance");
            if(!empty($aindexumber))
            {
                $pdf->CertificateTable($header);
                foreach ($aindexumber as $fsix) {
                    $aindexNumber=$fsix['indexNumber'];
                    $aschoolName=$fsix['schoolName'];
                    $eaauthority=$fsix['examinationAuthority'];

                    $pdf->setFont('Arial', '', 10);
                    $pdf->Cell(6,6,'');
                    $pdf->Cell(40,6,$aindexNumber,1);
                    $pdf->Cell(100,6,$aschoolName,1);
                    $pdf->Cell(50,6,$eaauthority,1,0);
                }

            }
            else
            {
                $pdf->Cell(180,6,"No Data Found",0);
            }

            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"15. TERTIARY INSTITUTION/ COLLEGE ATTENDED/COMPLETED: ");

            $pdf->Ln(7);
            $header=array('Index Number','AVN Number','College Name','Programme Name','GPA');
            $pdf->Cell(6);
            $pdf->setFont('Arial', '', 12);

            $equivalentresults=$db->getRows("applicantresults", array('where'=>array('applicantID'=>$applicantID,'examinationLevel'=>'Equivalent'),'order_by applicantID ASC'));
            if(!empty($equivalentresults))
            {
                $pdf->EquaivalentTable($header);
                foreach($equivalentresults as $matokeo)
                {
                    $eIndexNumber=$matokeo['indexNumber'];
                    $avn_number=$matokeo['avn_number'];
                    $award=$matokeo['award'];
                    $eschoolName=$matokeo['schoolName'];
                    $gradePoints=$matokeo['gradePoints'];

                    $pdf->setFont('Arial', '', 10);
                    $pdf->Cell(6,6,'');
                    $pdf->Cell(25,6,$eIndexNumber,1);
                    $pdf->Cell(25,6,$avn_number,1);
                    $pdf->Cell(65,6,$eschoolName,1);
                    $pdf->Cell(55,6,$award,1,0);
                    $pdf->Cell(20,6,$gradePoints,1,0);

                }
            }
            else
            {
                $pdf->Cell(6);$pdf->Cell(180,6,"No Data Found",0);
            }

            $pdf->Ln(10);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->Cell(6);$pdf->Cell(180,6,"16. DECLARATIONS: ");

            $pdf->Ln(10);
            $pdf->setFont('Arial', 'I', 10);
            $pdf->Cell(8);$pdf->MultiCell(180,6,"1. I affirm that ALL information, which I have provided here, is correct, and complete in every detail.");
            $pdf->Ln(6);
            $pdf->Cell(8);$pdf->MultiCell(180,6,"2. I acknowledge that the ".$organizationName." preserves the right at any stage to vary or reverse any decision regarding my admission or enrolment to the University made on the basis of incorrect or incomplete Information which I have given here.");

            

            $programme=$db->getStudyLevelID($programmeID);
            if(!empty($programme))
            {
                foreach ($programme as $cp)
                {
                    $sname=$cp['schoolName'];
                    $studyLevelID=$cp['studyLevelID'];
                }
            }
            else
            {
                $duration="";
                $sname="";
                $studyLevelID='';

            }

            $pdf->Ln(6);
            $pdf->Cell(8);$pdf->MultiCell(180, 6, "3.  I acknowledge receipt of joining instructions for academic year " . $academicYear . " and confirm my acceptance of a place at the ".$organizationName." in ".$sname);

            $pdf->Ln(6);
            $pdf->Cell(8);$pdf->MultiCell(190,6,"4. I confirm that I will pay all University fees which I am liable for my course of study in time, and I am aware that otherwise action against me shall be taken according to the University regulations.");

            $pdf->Ln(6);
            $pdf->Cell(8);$pdf->MultiCell(190,6,"5. I understand that the University has limited accommodation facilities for Students on the Campus and I shall therefore  be prepared to look for Alternative off-campus accommodation.");


            $pdf->Ln(6);
            $pdf->Cell(8);$pdf->MultiCell(190,6,"6. I am a ware of the terms, conditions, regulations and by- laws of the University as may be approved by the Council from time to time, and I promise to abide by these.");


            $pdf->Ln(6);
            $pdf->Cell(8);$pdf->MultiCell(190,6,"7. I promise solemnly to see the truth, to study diligently, to live circumspectly, to obey University Authority and those to whom my obedience is required, and to do all I can to promote the good of the academic community.");




            $pdf->Ln(15);
            $pdf->setFont('Arial', '', 11);
            $pdf->Cell(6); $pdf->Cell(85,6,"Student Signature:_____________________","0");
            $pdf->Cell(100,6,"Date:_________________________","0");



            $pdf->Ln(15);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->Cell(6);
            $pdf->Cell(180,6,"FOR OFFICE USE ONLY","0",0,'C');


            $today_date=date("l jS \of F Y h:i:s A");

            $getUsers=$db->getRows("applicantremarks",array('where'=>array('applicantID'=>$applicantID)));
            if(!empty($getUsers))
            {
                foreach($getUsers as $usr)
                {
                    $userID=$usr['userID'];
                    $processDate= $usr['processDate'];
                }
            }
            else
            {
                $userID="";
                $processDate="";
            }

            $ufname=$db->getData("users","firstName","userID",$userID);
            $ulname = $db->getData("users", "lastName", "userID", $userID);

            $uname="$ufname $ulname";

            $pdf->Ln(15);
            $pdf->setFont('Arial', '', 11);
            $pdf->Cell(6); $pdf->Cell(120,6,"Registration Officer Name: ".$uname,"0");
            $pdf->Cell(90, 6, "Process Date: " . $processDate, "0");
            $pdf->Ln(15);
            $pdf->Cell(6); $pdf->Cell(85,6,"Signature:_____________________","0");
            $pdf->Cell(100,6,"Date: ".$today_date,"0");





            $pdf->Output();
        }
    }
}
?>
