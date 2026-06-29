<?php
session_start();
// ini_set('display_errors', 1);
// error_reporting(E_ALL | E_STRICT);
if ($_REQUEST['action'] == "getPDF") {
    include '../DB.php';
    $db = new DBHelper();
    require('fpdf.php');
    $applicantID = $_REQUEST['applicantID'];
    $today = date('d-M-Y');

    $organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
    if (!empty($organization)) {
        foreach ($organization as $org) {
            $organizationName = $org['organizationName'];
            $organizationCode = $org['organizationCode'];
            $organizationPicture = "../img/" . $org['organizationPicture'];
            $organizationBanner = "../img/banner.png";
            $studentSupport = $org['student_support'];
            $orgAddress = $org['organizationAddress'];
            $orgPhone = $org['organizationPhone'];
            $orgEmail = $org['organizationEmail'];
            $contact_person = $org['contact_person'];
            $office_name = $org['office_name'];
            $title = $org['title'];
            //$signature = "/img/" . $org['signature'];
            $signature = "images/signature.png";
        }
    } else {
        $organizationName = "Soft Dev Academy";
        $organizationCode = "SDVA";
        $organizationPicture = "../img/SkyChuo.png";
    }
    class PDF extends FPDF
    {
        function Banner($organizationName, $image)
        {
            $this->setFont('Arial', 'B', 13);
            $this->Text(70, 30, $organizationName);
            $this->Image($image, 12, 10, 190, 50);
            $this->setFont('Arial', 'B', 14);
            /* $this->Text(75, 40, 'Admission Letter'); */
        }
        function BasicTable($header)
        {
            $w = array(100, 20, 70);
            for ($i = 0; $i < count($header); $i++)
                $this->Cell($w[$i], 6, $header[$i], 1, 0, 'L', 0);
            $this->Ln();
        }
        function Footer()
        {
            global $organizationName;
            global $applicationYear;
            $today2 = date('Y-m-d H:i:s');
            //Position at 1.5 cm from bottom
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            //$this->Line(-30,-16,-15,-15);
            $this->Cell(100, 0, $organizationName . $today2, 0, 1, 'L');
            $this->Cell(200, 0, "Admission Letter" . $applicationYear, 0, 1, 'R');
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
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage("P");
    $pdf->setFont('Arial', '', 8);
    $applicantsData = $db->getRows('applicants', array('where' => array('applicantID' => $applicantID), 'order_by' => 'applicantID ASC'));
    if (!empty($applicantsData)) {
        foreach ($applicantsData as $apps) {
            $gender = $apps['gender'];
            $fname = $apps['firstName'];
            $mname = $apps['middleName'];
            $lname = $apps['lastName'];
            $address = $apps['physicalAddress'];
            $phoneNumber = $apps['phoneNumber'];
            $formfour = $apps['formfour'];
            $applicantNumber = $apps['applicationNumber'];
            $applicationYear = $db->getData("academicyears", "academicYear", "academicYearID", $apps['applicationYearID']);
            if ($gender == "Male")
                $sex = "Mr.";
            else
                $sex = "Ms.";
            $name = "NAME: $sex $fname $mname $lname";


            $programmeAdmitted = $db->getRows("applicantapplication", array('where' => array('applicantID' => $_SESSION['applicantID'], 'admissionStatus' => 1), 'order_by applicantID ASC'));
            if (!empty($programmeAdmitted)) {
                foreach ($programmeAdmitted as $pChoice) {
                    $applicantApplicationIDFirst = $pChoice['applicantApplicationID'];
                    $programmeMajorID = $pChoice['programmeMajorID'];
                }
            }
            $programme = $db->getStudyLevelID($programmeMajorID);
            if (!empty($programme)) {
                foreach ($programme as $cp) {
                    $duration = $cp['programDuration'];
                    $sname = $cp['schoolName'];
                    $studyLevelID = $cp['studyLevelID'];
                }
            } else {
                $duration = "";
                $sname = "";
                $studyLevelID = '';
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

            $pdf->Banner($organizationName, $organizationBanner);
            $pdf->Ln(43);
            $pdf->setFont('Arial', 'B', 12);
            $pdf->Cell(6);
            $pdf->Cell(101, 6, "Ref.Number: " . $db->getData("applicants", "refNumber", "applicantID", $applicantID), "0");
            $pdf->setFont('Arial', '', 12);
            if ($studyLevelID == 1) {
                $pdf->Cell(50);
                $pdf->Cell(98, 6, $today);
            } else {
                $pdf->Cell(50);
                $pdf->Cell(98, 6, $today);
            }
            $pdf->Cell(50);
            $pdf->Cell(98, 6, $today);
            $pdf->Ln(10);
            $pdf->setFont('Arial', '', 12);
            $pdf->Cell(6);
            $pdf->Cell(101, 6, "" . iconv('ISO-8859-1', 'windows-1252', html_entity_decode($name)), "0");
            $pdf->Ln(8);
            $pdf->Cell(6);
            $pdf->Cell(101, 6, "Dear Esteemed Student", "0");

            /* $pdf->Ln(8);
            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(6);
            $programmeName=$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID);
            $pdf->Cell(101, 6, "" . iconv('ISO-8859-1', 'windows-1252',"Program of Study: ".html_entity_decode($programmeName)), "0"); */

            $pdf->Ln(10);
            $pdf->setFont('Arial', 'B', 12);
            $appYear = explode("/", $applicationYear);
            $pdf->Cell(6);
            $pdf->Cell(170, 6, "SUBJECT: ADMISSION FOR THE JULY INTAKE " . $appYear[0], 0, 0, 'C');
            $pdf->Line(44, 86, 158, 86);

            $pdf->SetAlpha(0.3);
            $pdf->Image($organizationPicture, 20, 90, 180, 100);
            $pdf->SetAlpha(1);


            $pdf->Ln(8);
            $pdf->setFont('Arial', '', 11);
            //pdf content
            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "On behalf of the management of the Law School of Zanzibar, I am pleased to inform you that you have been offered a place at the Law School of Zanzibar for the July Intake program, " . $appYear[0] . ", for a legal practical course leading to the award of Certificate of Competence to qualify as a Vakil.");

            $pdf->Ln(4);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "You are required to register with the Law School of Zanzibar within two weeks from the opening of the School on 20th July, 2026. Failure to register within the specified time will lead to cancellation of a place.");


            $pdf->Ln(4);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "The admission offer is provisional pending verification of the qualifications as presented on your online application. You will be required at the time of registration, to present in person the original documents you have used in the application. I would, therefore, like to put an emphasis on the following must be presented to the School:");

            $pdf->Cell(10);
            $pdf->MultiCell(0, 6, "i.   The original Certificate of Ordinary Secondary Education (Form IV);");
            $pdf->Cell(10);
            $pdf->MultiCell(0, 6, "ii.  The original Diploma in Law or its equivalent or certified evidence to prove possession of it;");
            $pdf->Cell(10);
            $pdf->MultiCell(0, 6, "iii. The original Advanced Certificate of Secondary Education (if any);");
            $pdf->Cell(10);
            $pdf->MultiCell(0, 6, "iv.  Three recent passport size photographs.");

            $pdf->Ln(4);
            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "Congratulations and we are looking forward to see you at the School.");

            $pdf->Ln(4);
            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "NOTE. 1.The duty of the Law School of Zanzibar is to train and award the Post Graduate Diploma in Legal Practice. Other procedures related to admission in the Bar is done by other judicial authorities.");

            $pdf->Ln(4);
            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "2. Apart from the fee structure herein attached, you will have to bear some other costs related to living and accommodation.");

            $pdf->Ln(4);
            $pdf->Cell(6);
            $pdf->MultiCell(0, 6, "Please find the attached Fee Structure.");

            /* $pdf->setFont('Arial', '', 11);

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
             */
            $pdf->Ln(8);
            $pdf->Cell(6);
            $pdf->setFont('Arial', 'I', 11);
            $pdf->Cell(85, 6, "Sincerely yours,");
            $pdf->Ln(12);
            $pdf->Image($signature, 15, 225, 25, 25);
            $pdf->Cell(6);
            $pdf->Ln(18);
            $pdf->Cell(6);
            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(85, 6, $contact_person);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(85, 6, $title);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(85, 6, "LAW SCHOOL OF ZANZIBAR");
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(85, 6, "ZANZIBAR");
            //$pdf->Image('images/stamp.png',45,145,25,25);

            $pdf->AliasNbPages();
            $pdf->AddPage();

            $pdf->Cell(180, 6, "TUITION FEE STRUCTURE", 0, 0, 'C');
            $pdf->setFont('Arial', '', 11);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(a)Application Fee", 1);
            $pdf->Cell(90, 6, "20,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(b)Registration Fee", 1);
            $pdf->Cell(90, 6, "20,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(c)Tuition Fee", 1);
            $pdf->Cell(90, 6, "500,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(d)Examination Fee", 1);
            $pdf->Cell(90, 6, "50,000.00", 1);
            // $pdf->Ln(6);
            // $pdf->Cell(6);
            // $pdf->Cell(90, 6, "(e)Field (Attachment) Supervision Fee", 1);
            // $pdf->Cell(90, 6, "50,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(e)Library and Internet Service Fee", 1);
            $pdf->Cell(90, 6, "50,000.00", 1);
            // $pdf->Ln(6);
            // $pdf->Cell(6);
            // $pdf->Cell(90, 6, "(g)Institutional Stationery and Photocopy Services", 1);
            // $pdf->Cell(90, 6, "50,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(f)Caution Money (deposit)", 1);
            $pdf->Cell(90, 6, "10,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(g)Identity Card", 1);
            $pdf->Cell(90, 6, "10,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(h)Students organization Fee", 1);
            $pdf->Cell(90, 6, "10,000.00", 1);
            // $pdf->Ln(6);
            // $pdf->Cell(6);
            // $pdf->Cell(90, 6, "(k)Graduation Fee", 1);
            // $pdf->Cell(90, 6, "20,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "Total", 1);
            $pdf->Cell(90, 6, "670,000.00/=", 1);


            //$pdf->Output();
            $pdf->Output($formfour . "-" . $applicantNumber . "-" . $applicationYear . ".pdf", "D");
        }
    }
}
