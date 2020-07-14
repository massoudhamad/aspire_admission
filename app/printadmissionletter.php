<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
if($_REQUEST['action']=="getPDF")
{   
    include '../DB.php';
    $db=new DBHelper();
    require('fpdf.php');
    $applicantID=$_REQUEST['applicantID'];
    $today=date('d-M-Y H:i:s');
    class PDF extends FPDF
    {		
        function Banner()
        {
           $today=date('M d,Y');
                //Logo . 
            $this->Image('images/letterhead.png',10,5,200,45);
        }
        function BasicTable($header)
        {
            $w = array(100,20,70);
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
            $this->Cell(260,0,'Muslim University of Morogoro '.$today2,0,1,'L');

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


       $programmeAdmitted=$db->getRows("applicantapplication", array('where'=>array('applicantID'=>$_SESSION['applicantID'],'admissionStatus'=>1),'order_by applicantID ASC'));
       if(!empty($programmeAdmitted))
       {
           foreach ($programmeAdmitted as $pChoice)
           {
               $applicantApplicationIDFirst=$pChoice['applicantApplicationID'];
               $programmeMajorID=$pChoice['programmeMajorID'];

           }
       }
       $programme=$db->getStudyLevelID($programmeMajorID);
       if(!empty($programme))
       {
           foreach ($programme as $cp)
           {
               $duration=$cp['programDuration'];
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


       /*$studyLevel=$db->getStudyLevelID($programmeMajorID);
       if(!empty($studyLevel))
       {
           foreach($studyLevel as $lvl)
           {
               $studyLevelID=$lvl['studyLevelID'];
           }
       }
       else
       {
           $studyLevelID='';
       }*/

      $pdf->Banner();
      $pdf->Ln(40); 
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);
      $pdf->Cell(101,6,"Ref.Number: ".$db->getData("applicants","refNumber","applicantID",$applicantID),"0");
      $pdf->setFont('Arial', '', 12);
      if($studyLevelID==1)
      {
          $pdf->Cell(50); $pdf->Cell(98,6,"21 August,2019");
      }
      else
      {
          $pdf->Cell(50); $pdf->Cell(98,6,"21 August,2019");
      }
      $pdf->Cell(50); $pdf->Cell(98,6,"21 August,2019");
      $pdf->Ln(8);
      $pdf->setFont('Arial', 'B', 11);
      $pdf->Cell(6);$pdf->Cell(101,6,"". iconv('ISO-8859-1', 'windows-1252', html_entity_decode($name)),"0");
      
      $pdf->Ln(10);
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);$pdf->Cell(170,6,"RE.: OFFER OF ADMISSION TO MUSLIM UNIVERSITY OF MOROGORO");


      


     $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
      //pdf content
       $pdf->Cell(6);$pdf->Cell(200,6,"We are hereby pleased to inform you that following your application to the Muslim University of Morogoro(MUM)");
       $pdf->Ln(6);
      /* $pdf->Cell(6);
       $pdf->setFont('Arial', 'B', 11);*/
       $pdf->Cell(6);
       $pdf->setFont('Arial', '', 11);
       $pdf->Cell(200,6,"for the academic year 2019/2020, you have been offered admission in the ");

       $header=array('Programme Name','Duration','Faculty');
       $pdf->Ln(6);
       $pdf->Cell(6);
       $pdf->setFont('Arial', 'B', 12);
       $pdf->BasicTable($header);

       $pdf->setFont('Arial', '', 10);
       $pdf->Cell(6,6,'');
       $pdf->Cell(100,6,$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID),1);
       $pdf->Cell(20,6,$duration.' years',1,0,'C');
       $pdf->Cell(70,6,$sname,1,0);
       $pdf->Ln(6);

      
     $pdf->Ln(10);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 12);
     $pdf->Cell(200,6,"Registration and Orientation");

       $pdf->Ln(6);
       $pdf->Cell(6);
       $pdf->setFont('Arial', '', 11);
       if($studyLevelID==1)
       {
            $startDate=" Saturday 2nd November, 2019 at 8.30am ";
            $endDate=" Sunday 10th November, 2019 at 04.00pm ";
       }
       else
       {

           $startDate=" Tuesday 15th October, 2019 at 8.30 ";
           $endDate=" Monday 28th October, 2019 at 04.00pm ";
       }
       $pdf->Cell(200,6,"Registration and Orientation of new students will be on ".$startDate." and will end on");

       $pdf->Ln(6);
       $pdf->Cell(6);
       $pdf->setFont('Arial', '', 11);
       $pdf->Cell(200,6,$endDate.". The venue will be ICT Complex, Computer Room 1 for registration");


       $pdf->Ln(6);
       $pdf->Cell(6);
       $pdf->setFont('Arial', '', 11);
       $pdf->Cell(200,6,"and Assembly Hall for Orientation.");

     


     
     $pdf->Ln(10);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'I', 11);
     $pdf->Cell(85,6,"Sincerely yours,");
    $pdf->Ln(12);
    $pdf->Image('images/signature.png',15,145,25,25);
     $pdf->Ln(18);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(85,6,"For: DVC(Academic)");
     $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(85,6,"Muslim University of Morogoro");
     $pdf->Image('images/stamp.png',45,145,25,25);
  

   
$pdf->Output();
//$pdf->Output("admissionletter.pdf","D");
   }
  }
}
?>
