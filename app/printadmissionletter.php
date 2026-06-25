<?php
if (session_status() === PHP_SESSION_NONE) session_start();
/*ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);*/
if($_REQUEST['action']=="getPDF")
{   
    include '../DB.php';
    $db=new DBHelper();
    require('fpdf.php');
    $applicantID=$_REQUEST['applicantID'];
    $today=date('d-M-Y');

    $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
    if (!empty($organization)) {
        foreach ($organization as $org) {
            $organizationName = $org['organizationName'];
            $organizationCode = $org['organizationCode'];
            $organizationPicture = "../img/" . $org['organizationPicture'];
            $studentSupport = $org['student_support'];
            $orgAddress = $org['organizationAddress'];
            $orgPhone = $org['organizationPhone'];
            $orgEmail = $org['organizationEmail'];
            $contact_person=$org['contact_person'];
            $office_name=$org['office_name'];
            $title=$org['title'];
            $signature="../img/".$org['signature'];
            $mumStamp ="../img/mumStamp.png";
        }
    } else {
        $organizationName = "Imperial College of Health and Allied Sciences";
        $organizationCode = "ICHAS";
        $organizationPicture = "../img/ichas-logo.png";
    }
    class PDF extends FPDF
    {		
        function Banner($organizationName,$image)
        { 
            
          $bannerPOBOX = "P.O. BOX 1031 Morogoro, Tanzania.";
          $bannerTel = "Tel: +255 23 2600256; Fax: +255 23 2600286";
          $bannerEmail = "E-mail address: mum@mum.ac.tz,"; 
          $bannerWebsite ="Website: www.mum.ac.tz";

            $organizationName = strtoupper($organizationName);  
            $this->setFont('Arial', 'B', 16);
            $this->Text(56, 15, $organizationName);
            $this->setFont('Arial', '', 15);
            $this->Text(66, 21, $bannerPOBOX);
            $this->setFont('Arial', '', 11);
            $this->Text(70, 26, $bannerTel);
            $this->setFont('Arial', '', 11);
            $this->Text(80, 31, $bannerEmail);
            $this->setFont('Arial', '', 11);
            $this->Text(89, 36, $bannerWebsite);

            $this->Image($image, 15, 8, 36, 34);
            $this->Line(200, 45, 15, 45);

            $this->setFont('Arial', 'B', 14);
            /* $this->Text(75, 40, 'Admission Letter'); */
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
            global $organizationName;
            global $applicationYear;
            $today2=date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            //$this->Line(-30,-16,-15,-15);
            $this->Cell(100,0,$organizationName.$today2,0,1,'L');
            $this->Cell(200,0,"Admission Letter".$applicationYear,0,1,'R');

        }

        //Image watermark
        protected $extgstates = array();

        // alpha: real value from 0 (transparent) to 1 (opaque)
        // bm:    blend mode, one of the following:
        //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
        //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
        function SetAlpha($alpha, $bm = 'Normal')
        {
            // set alpha for stroking (CA) and non-stroking (ca) operations
            $gs = $this->AddExtGState(array('ca' => $alpha, 'CA' => $alpha, 'BM' => '/' . $bm));
            $this->SetExtGState($gs);
        }

        function AddExtGState($parms)
        {
            $n = count($this->extgstates) + 1;
            $this->extgstates[$n]['parms'] = $parms;
            return $n;
        }

        function SetExtGState($gs)
        {
            $this->_out(sprintf('/GS%d gs', $gs));
        }

        function _enddoc()
        {
            if (!empty($this->extgstates) && $this->PDFVersion < '1.4')
            $this->PDFVersion = '1.4';
            parent::_enddoc();
        }

        function _putextgstates()
        {
            for ($i = 1; $i <= count($this->extgstates); $i++) {
                $this->_newobj();
                $this->extgstates[$i]['n'] = $this->n;
                $this->_put('<</Type /ExtGState');
                $parms = $this->extgstates[$i]['parms'];
                $this->_put(sprintf('/ca %.3F', $parms['ca']));
                $this->_put(sprintf('/CA %.3F', $parms['CA']));
                $this->_put('/BM ' . $parms['BM']);
                $this->_put('>>');
                $this->_put('endobj');
            }
        }

        function _putresourcedict()
        {
            parent::_putresourcedict();
            $this->_put('/ExtGState <<');
            foreach ($this->extgstates as $k => $extgstate)
            $this->_put('/GS' . $k . ' ' . $extgstate['n'] . ' 0 R');
            $this->_put('>>');
        }

        function _putresources()
        {
            $this->_putextgstates();
            parent::_putresources();
        }
        //end image watermark
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
       $formfour=$apps['formfour'];
       $applicantNumber=$apps['applicationNumber'];
       $applicationYear=$db->getData("academicyears","academicYear","academicYearID",$apps['applicationYearID']);
       if($gender=="Male")
           $sex="Mr.";
       else 
           $sex="Ms.";
       $name="Dear $sex $fname $mname $lname";


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

      $pdf->Banner($organizationName, $organizationPicture);
      $pdf->Ln(40); 
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);
      $pdf->Cell(101,6,"Ref.Number: ".$db->getData("applicants","refNumber","applicantID",$applicantID),"0");
      $pdf->setFont('Arial', '', 12);
      if($studyLevelID==1)
      {
          $pdf->Cell(50); $pdf->Cell(98,6,$today);
      }
      else
      {
          $pdf->Cell(50); $pdf->Cell(98,6, $today);
      }
      $pdf->Cell(50); $pdf->Cell(98,6, $today);
      $pdf->Ln(8);
      $pdf->setFont('Arial', 'B', 11);
      $pdf->Cell(6);$pdf->Cell(101,6,"". iconv('ISO-8859-1', 'windows-1252', html_entity_decode($name)),"0");

            $pdf->Ln(8);
            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(6);
            $programmeName=$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID);
            $pdf->Cell(101, 6, "" . iconv('ISO-8859-1', 'windows-1252',"Program of Study: ".html_entity_decode($programmeName)), "0");
      
      $pdf->Ln(10);
      $pdf->setFont('Arial', 'B', 12);
      $pdf->Cell(6);$pdf->Cell(170,6,"RE.: OFFER OF ADMISSION TO ".strtoupper($organizationName));

            $pdf->SetAlpha(0.3);
            $pdf->Image($organizationPicture, 30, 50, 150,150);
            $pdf->SetAlpha(1);


     $pdf->Ln(8);
      $pdf->setFont('Arial', '', 11);
      //pdf content
       $pdf->Cell(6);$pdf->MultiCell(0,6, "The Admission Board of the ".$organizationName." is pleased to inform you that you have been admitted into the said program in the academic year ". $applicationYear);
      
       $pdf->setFont('Arial', '', 11);

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
       $dates=$db->getRows("admission_letter_setting",array('where'=>array('studyLevelID'=>$studyLevelID)));
       if(!empty($dates))
       {
           foreach($dates as $dt)
           {
               $orientationDate=$dt['orientationDate'];
               $registrationDate=$dt['registrationDate'];
           }
       }
       
       $pdf->MultiCell(0,6, "Orientation and Registration of new students will be on ". date("d-m-Y",strtotime($orientationDate))." and ". date("d-m-Y",strtotime($registrationDate))." respectively.");
       
     $pdf->Ln(10);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'I', 11);
     $pdf->Cell(85,6,"Sincerely yours,");
    $pdf->Ln(12);
    $pdf->Image($signature,20,155,25,25);
    $pdf->Image($mumStamp,30,198,40,40);
    $pdf->Cell(6);
     $pdf->Ln(18);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(85,6,$contact_person);
     $pdf->Ln(6);
    $pdf->Cell(6);
    $pdf->Cell(85, 6, $title);
    $pdf->Ln(6);
     $pdf->Cell(6);
     $pdf->setFont('Arial', 'B', 11);
     $pdf->Cell(85,6,$organizationName);
     //$pdf->Image('images/stamp.png',45,145,25,25);
  

   
$pdf->Output();
//$pdf->Output($formfour."-".$applicantNumber."-".$applicationYear.".pdf","D");
   }
  }
}
?>
