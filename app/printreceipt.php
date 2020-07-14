<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
if($_REQUEST['action']=="getPDF")
{   
    include '../DB.php';
    $db=new DBHelper();
    require('../fpdf.php');
    $applicantID=$_REQUEST['applicantID'];
    $today=date('d-M-Y H:i:s');
    //$orgName=$db->getOrganizationValue("organizationName");

    $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
    if (!empty($organization)) {
        foreach ($organization as $org) {
            $organizationName = $org['organizationName'];
            $organizationCode = $org['organizationCode'];
            $organizationPicture = "../img/" . $org['organizationPicture'];
            $studentSupport = $org['student_support'];
        }
    } else {
        $organizationName = "Soft Dev Academy";
        $organizationCode = "SDVA";
        $organizationPicture = "../img/SkyChuo.png";
    }
    class PDF extends FPDF
    {		
        function Banner($organizationName,$image)
        {
           $today=date('M d,Y');
                //Logo . 
            $this->setFont('Arial', 'B', 13); 
            $this->Text(70,30,$organizationName);
            $this->Image($image,15,10,40.98,35.22);
            $this->Image('../img/logo.jpg',150,10,35.98,37.22);
            $this->setFont('Arial', 'B', 14); 
            $this->Text(75,40,'APPLICATION RECEIPT');
        }
        function Footer()
        {
            $today2=date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(260,0,'Online Admission System'.$today2,0,1,'L');

        }
    }
    $pdf=new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->setFont('Arial', '', 8);
    $applicantsData=$db->getRows('applicants',array('where'=>array('applicantID'=>$applicantID),'order_by'=>'applicantID ASC'));
   if(!empty($applicantsData)){ 
   foreach($applicantsData as $apps)
   {
       $gender=$apps['gender'];
       $fname= $apps['firstName'];
       $mname=$apps['middleName'];
       $lname=$apps['lastName'];
       $address=$apps['physicalAddress'];
       $phoneNumber=$apps['phoneNumber'];
       if($gender=="Male")
           $sex="Mr.";
       else 
           $sex="Ms.";
       $name="$sex $fname $mname $lname";
       
      $pdf->Banner($organizationName, $organizationPicture);
      $pdf->Ln(40); 
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);
      $pdf->Cell(196,6,"Application Summary","0");
      $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
      $pdf->Cell(6);$pdf->Cell(101,6,"Full Name: ". iconv('ISO-8859-1', 'windows-1252', html_entity_decode($name)),"0");
      $pdf->Cell(98,6,"Ref.Number: ".$db->getData("applicants","refNumber","applicantID",$applicantID),"0");
      $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
      $pdf->Cell(6);$pdf->Cell(101,6,"Address: ".$address,"0");
      $pdf->Cell(98,6,"Phone Number: ".$phoneNumber,"0");
      $pdf->Ln(8);
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);$pdf->Cell(170,6,"Programme(s) Applied:");
      $programmeChoice=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'choice'=>1),'order_by applicantID ASC'));
      if(!empty($programmeChoice))
      {
          foreach ($programmeChoice as $pChoice)
          {
              $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
              $firstChoice=$pChoice['programmeMajorID'];
             
          }
      }

      $programmeChoice2=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'choice'=>2),'order_by applicantID ASC'));
      if(!empty($programmeChoice2))
      {
          foreach ($programmeChoice2 as $pChoice2)
          {
              $applicantApplicationIDSecond=$pChoice2['applicantApplicationID'];
              $secondChoice=$pChoice2['programmeMajorID'];
             
          }
      }
      
     $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
       $pdf->Cell(6);$pdf->Cell(195,6,"First Choice: ".$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $firstChoice),"0");
      $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
       $pdf->Cell(6);$pdf->Cell(195,6,"Second Choice: ".$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $secondChoice),"0");
      $pdf->Ln(10);
     
      $pdf->setFont('Arial', '', 11);
      $pdf->Cell(6); $pdf->Cell(85,6,"Applicant Signature:_____________________","0");
      $pdf->Cell(100,6,"Date of Submission:_________________________","0");
      $pdf->Ln(10);
      $employed=$apps['employmentStatus'];
      if($employed=='yes')
      {
      $pdf->setFont('Arial', '', 11);
       $pdf->Cell(6);$pdf->Cell(185,6,"Comments from Employer:___________________________________________________________","0");
      $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
       $pdf->Cell(6);$pdf->Cell(120,6,"Employers Signature & Stamp:_______________________________","0");
      $pdf->Cell(95,6,"Date:____________________","0");
      $pdf->Ln(12);
      }

      $pdf->setFont('Arial', '', 11);
      $studyLevelID=$db->getStudyLevelIDData($firstChoice);
      $appfees=$db->getApplicationFees($studyLevelID);
      $campus=$db->getCampus($firstChoice);
      if(!empty($campus))
      {
          foreach ($campus as $cp)
          {
              $campusName= $cp['campusName'];
              $campusAddress=$cp['campusAddress'];
              $accountNumber=$cp['accountNumber'];
              $bankName=$cp['bankName'];
              $accountName=$cp['accountName'];
              $campusname="$campusName,$campusAddress";
          }
      }
      if($appfees > 0) {
          $pdf->setFont('Arial', 'B', 12);
          $pdf->Cell(6);$pdf->Cell(170,6,"Application Fee:");
          $pdf->Ln(8);
          $pdf->Cell(6);
          $pdf->Cell(200, 6, "Application Fee is TZS: " . number_format($appfees) . "/= which shall be paid at the " . $bankName . " BANK.");
          $pdf->Ln(8);
          $pdf->setFont('Arial', 'B', 11);
          $pdf->Cell(6);
          $pdf->Cell(50, 6, "Account Name:" . $accountName . "; Account Number:" . $accountNumber);
          $pdf->Ln(12);
          $pdf->setFont('Arial', 'B', 12);
          $pdf->Cell(6);
          $pdf->Cell(170, 6, "Submission of application documents:");
          $pdf->Ln(8);

          $pdf->setFont('Arial', '', 11);
          $pdf->Cell(6);
          $pdf->Cell(200, 6, "PLEASE SEND YOU DOCUMENTS (Form IV Certificate and Bank Pay Slip) THROUGH WHATSAPP NUMBERS ");
          $pdf->Ln(8);
          $pdf->Cell(6);
           $pdf->Cell(200, 6, $studentSupport."  before application deadline");
        //   $pdf->Cell(200, 6, "0785330002, 0715202911, OR 0783610840 before application deadline");
      }
      /*$pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
       $pdf->Cell(6);$pdf->Cell(200,6,"(i) Application fee payment slip(Bank receipt)");
      $pdf->Ln(8);
       $pdf->Cell(6);$pdf->Cell(200,6,"(ii) Photocopies of all your certificates");
      $pdf->Ln(8);
       $pdf->Cell(6);$pdf->Cell(200,6,"(iii) Recent passport size photo attached to this application receipt");*/
      $pdf->Ln(12);
       $pdf->Cell(6);$pdf->Cell(200,6,"Address:");
      $pdf->Ln(8);
       $pdf->Cell(6);$pdf->Cell(200,6,"Department of Admission");
      $pdf->Ln(8);
      $pdf->Cell(6); $pdf->Cell(200,6,"Morogoro Muslim University");
      $pdf->Ln(8);
       $pdf->Cell(6);$pdf->Cell(200,6,"P.O.BOX 1031, Morogoro-Tanzania");
      $pdf->Ln(8);
       $pdf->Cell(6);$pdf->Cell(200,6,"Email:admissions@mum.ac.tz or mumadmit@gmail.com");
      
   }
   //$pdf->Output();
    $pdf->Output("applicationreceipt.pdf","D");
   }
}
?>
