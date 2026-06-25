<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if($_REQUEST['action']=="getPDF")
{   
    include 'DB.php';
    $db=new DBHelper();
    require('fpdf.php');
    $applicantID=$_REQUEST['applicantID'];
    $today=date('d-M-Y H:i:s');

   
    class PDF extends FPDF
    {
        function Banner($organizationName, $image)
        {
            $today = date('M d,Y');
            //Logo . 
            $this->setFont('Arial', 'B', 13);
            $this->Text(70, 30, $organizationName);
            $this->Image($image, 15, 10, 40.98, 35.22);
            $this->setFont('Arial', 'B', 14);
            $this->Text(75, 40, 'Admission Letter');
        }
        function BasicTable($header)
        {  
            $w = array(10,70,50,50);
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
            $this->Cell(260,0,'Muslim University of Morogoro33 '.$today2,0,1,'L');

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
       
      $pdf->Banner();
      $pdf->Ln(40); 
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);
      $pdf->Cell(101,6,"Ref.Number: ".$db->getData("applicants","refNumber","applicantID",$applicantID),"0");
      $pdf->setFont('Arial', '', 12);
      $pdf->Cell(50); $pdf->Cell(98,6,"23 October,2017");
      $pdf->Ln(8);
      $pdf->setFont('Arial', 'B', 11);
      $pdf->Cell(6);$pdf->Cell(101,6,"". iconv('ISO-8859-1', 'windows-1252', html_entity_decode($name)),"0");
      
      $pdf->Ln(10);
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);$pdf->Cell(170,6,"RE.: OFFER OF ADMISSION TO MUSLIM UNIVERSITY OF MOROGORO");
      $pdf->Ln(10);
      $pdf->setFont('Arial', '', 12);
      $pdf->Cell(6);$pdf->Cell(170,6,"May I draw your attention on the captioned subject.");
      
      $programmeAdmitted=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'admissionStatus'=>1),'order_by applicantID ASC'));
      if(!empty($programmeAdmitted))
      {
        foreach ($programmeAdmitted as $pChoice)
        {
           $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
           $programmeMajorID=$pChoice['programmeMajorID'];
                             
        }
    }
    $campus=$db->getCampus($programmeMajorID);
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
      
      
     $studyLevelID=$db->getStudyLevelID($programmeMajorID);
     if($studyLevelID==2)
         $studyLevel="Two-Years ";
     else if($studyLevelID==3)
         $studyLevel="Three-Years ";
     else
         $studyLevel="";
     $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
      //pdf content
       $pdf->Cell(6);$pdf->Cell(200,6,"Following your application, I am very pleased to inform you that you have been offered a place for");
       $pdf->Ln(6);
       $pdf->Cell(6);
       $pdf->setFont('Arial', 'B', 11);
       $pdf->Cell(200,6,"".$studyLevel.$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID),"0");
      $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"at MUSLIM UNIVERSITY OF MOROGORO(MUM) for the academic year 2018/2019.");
      
     $pdf->Ln(10);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"You are, therefore, required to report at MUM Main Campus on Monday 30th October 2017 at 8.30a.m");
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"ready for a one week orientation and registration subject to passing medical examination and paying at least");
     
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"half of the annual fees of your programme within the first two weeks of the academic year.The rest of the ");
     
     
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"University fees should be paid within the first two weeks of the second semester in the same academic year.");
     
       $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"You will be required to register for the studies at the beginning of each semester. Please bring with you your");
      
     
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"original certificates. ");
      
     
     $pdf->Ln(8);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"Note that, currently the University has no hostel facilities and students admitted to the university have to find ");
      
     /*
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"their own accommodation(except Chwaka and Mbweni Campus).However, we have a list of approved landlords/ ");*/
      
     
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"ladies who will provide rented accommodation to the University students.");
     
     $pdf->Ln(8);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(200,6,"Attached please find MUM fee structure for academic year 2017/2018. We look forward to meeting you at ");
     
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(85,6,"MUM and wish you a successful period of study.");
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(80,6,"Karibu sana");
     
     $pdf->Ln(10);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'I', 11);
     $pdf->Cell(85,6,"Sincerely yours,");
     $pdf->Ln(10);
     $pdf->Image('img/signature.jpg',15,189,25,25);
     $pdf->Ln(23);
     $pdf->Cell(6);
     $pdf->setFont('Arial', '', 11);
     $pdf->Cell(85,6,"Prof. Idris A. Rai");
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(85,6,"Vice Chancellor");
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(85,6,"MUM MOROGORO");
  
   
   $pdf->AddPage();
   $pdf->Banner();
   $pdf->Ln(40);
   $pdf->setFont('Arial', 'B', 12);
   $pdf->Cell(6);$pdf->Cell(170,6,"Fee Structure");

   $programmes=$db->getPrintedProgrammeFees($programmeMajorID);
  if(!empty($programmes)){ 
  foreach($programmes as $prg)
  { 
      $programmeName=$prg['programName'];
      $programmeID=$prg['programID'];
      $academicYearID=$prg['academicYearID'];
  }


      $pdf->Ln(10); 
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);
      $pdf->Cell(200,6,"".$programmeName."-".$db->getData("academicyears","academicYear","academicYearID",$academicYearID));
      
      //loading table
      $pdf->Ln(10); 
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(6,6,'');
      $headerCount = array('No','Fees Type', 'Amount in Tsh', 'Amount in U$');
      $pdf->BasicTable($headerCount);
     
    $totalTz=0;$totalUsa=0;
    $fees = $db->getRows('programmefees',array('where'=>array('programID'=>$programmeID),'order_by'=>'feesTypeID ASC'));    
    if(!empty($fees)){ $count = 0; foreach($fees as $fee){ $count++;
    $feesTypeID=$fee['feesTypeID'];
    $feestz=$fee['feesTz'];
    $feesusa=$fee['feesUsa'];
    $totalTz+=$feestz;
    $totalUsa+=$feesusa;
      
     
    $pdf->setFont('Arial', '', 11);
    $pdf->Cell(6,6,'');
    $pdf->Cell(10,6,$count,1);
    $pdf->Cell(70,6,$db->getData('feestype','feesType','feesTypeID',$feesTypeID),1);
    $pdf->Cell(50,6, number_format($feestz),1);
    $pdf->Cell(50,6, number_format($feesusa),1);
    $pdf->Ln();
    }
   }
  
  $pdf->Cell(6,6,'');
  $pdf->Cell(80,6,"Total Fees",1);
  $pdf->Cell(50,6, number_format($totalTz),1);
  $pdf->Cell(50,6, number_format($totalUsa),1);
   }
   
   $pdf->Ln(8);
   $pdf->setFont('Arial', 'B', 13);
   $pdf->Cell(6,6,'');
   $pdf->Cell(80,6,"All payments should be addressed to",0);
   $pdf->Ln(6);
   $pdf->Cell(6,6,'');$pdf->Cell(50,6,"Account Number:".$accountNumber);
   $pdf->Ln(6);
   $pdf->Cell(6,6,''); $pdf->Cell(50,6,"Account Name: ".$accountName);
   $pdf->Ln(6);
   $pdf->Cell(6,6,'');$pdf->Cell(50,6,"Bank Name:".$bankName);
   
//$pdf->Output();
$pdf->Output("admissionletter.pdf","D");
   }
  }
}
?>
