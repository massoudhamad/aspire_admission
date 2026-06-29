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

            // Resolve image paths against both layouts:
            //   prod  → app/images/letterhead.png  (and similar)
            //   dev   → img/banner.png  (legacy convention)
            $pickFirstFile = static function (...$candidates) {
                foreach ($candidates as $c) {
                    if (!empty($c) && @file_exists($c)) return $c;
                }
                return null;
            };
            // Order matters: prefer files referenced by the organization row
            // (admin-uploaded via the Organization Info page) before falling back
            // to the static app/images/ shipped with the codebase.
            $organizationBanner = $pickFirstFile(
                dirname(__DIR__) . "/img/banner.png",
                __DIR__ . "/images/letterhead.png"
            );
            // Prefer (1) admin-uploaded logo at img/<organizationPicture>, then
            // (2) the LSZ logo we ship in-repo, finally (3) the generic
            // app/images/logo.png (may be a previous tenant's file on prod).
            $organizationPicture = $pickFirstFile(
                dirname(__DIR__) . "/img/" . ($org['organizationPicture'] ?? ''),
                __DIR__ . "/images/lsz_logo.png",
                __DIR__ . "/images/logo.png"
            );
            $signature = $pickFirstFile(
                dirname(__DIR__) . "/img/" . ($org['signature'] ?? ''),
                __DIR__ . "/images/signature.png"
            );

            $studentSupport = $org['student_support'];
            $orgAddress     = $org['organizationAddress'];
            $orgPhone       = $org['organizationPhone'];
            $orgEmail       = $org['organizationEmail'];
            $contact_person = $org['contact_person'];
            $office_name    = $org['office_name'];
            $title          = $org['title'];
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
            // Tenant letterhead: if a pre-rendered full-width banner exists
            // (e.g. /app/images/letterhead.png), use it. Otherwise build the
            // official 3-column LSZ letterhead: English | crest | Swahili.
            $letterhead = __DIR__ . "/images/letterhead.png";
            if (!empty($image) && @file_exists($image) && $image !== $letterhead) {
                $this->Image($image, 12, 10, 190, 50);
                $this->setFont('Arial', 'B', 14);
                return;
            }

            // Geometry (mm). A4 width = 210, margins 10 each side -> 190 usable.
            $leftX  = 10;
            $crestX = 92;   // crest sits centred in the page
            $rightX = 130;
            $topY   = 10;

            // Centre crest
            $crestPath = __DIR__ . "/images/logo.png";
            if (@file_exists($crestPath)) {
                $this->Image($crestPath, $crestX, $topY, 26, 28);
            }

            // --- Left column (English) ---
            $this->SetTextColor(60, 60, 60);
            $this->SetXY($leftX, $topY);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(80, 4.5, "THE LAW SCHOOL OF ZANZIBAR", 0, 2, 'L');

            $this->SetFont('Arial', '', 9);
            $lines = [
                "P.O. Box 1418",
                "Zanzibar - Tanzania",
                "Mobile: +255 659 744557",
                "Website: www.lsz.ac.tz",
                "Email: info@lsz.ac.tz",
                "7 A. A. Karume Road",
                "71104 Urban West, Zanzibar",
            ];
            foreach ($lines as $ln) {
                $this->SetX($leftX);
                $this->Cell(80, 3.7, $ln, 0, 2, 'L');
            }

            // --- Right column (Swahili) ---
            $this->SetXY($rightX, $topY);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(70, 4.5, "SKULI YA SHERIA ZANZIBAR", 0, 2, 'L');

            $this->SetFont('Arial', '', 9);
            $lines_sw = [
                "S.L.P 1418",
                "Zanzibar - Tanzania",
                "Simu: +255 659 744557",
                "Tovuti: www.lsz.ac.tz",
                "Barua pepe: info@lsz.ac.tz",
                "7 Barabara ya A. A. Karume",
                "71104 Mjini Magharibi, Zanzibar",
            ];
            foreach ($lines_sw as $ln) {
                $this->SetX($rightX);
                $this->Cell(70, 3.7, $ln, 0, 2, 'L');
            }

            // Tagline + horizontal rule under the whole letterhead block
            $this->SetXY(0, 46);
            $this->SetFont('Arial', 'B', 10);
            $this->SetTextColor(30, 30, 30);
            $this->Cell(0, 5, 'Michenzani Mall, Block "A", Third Floor', 0, 1, 'C');

            $this->SetDrawColor(110, 110, 110);
            $this->SetLineWidth(0.4);
            $this->Line(10, 53, 200, 53);

            // Reset text colour + font for the rest of the document
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 11);
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
            $this->Cell(100, 0, $organizationName . '  -  ' . $today2, 0, 0, 'L');
            $this->Cell(90, 0, 'Admission Letter ' . $applicationYear, 0, 1, 'R');
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
            // Move cursor below the letterhead block (banner image OR text-banner).
            $pdf->SetY(56);
            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(6);
            $pdf->Cell(120, 5, "Ref.Number: " . $db->getData("applicants", "refNumber", "applicantID", $applicantID), 0, 0, 'L');
            $pdf->setFont('Arial', '', 11);
            $pdf->Cell(60, 5, "Date: " . $today, 0, 1, 'R');
            $pdf->Ln(6);
            $pdf->setFont('Arial', '', 11);
            $pdf->Cell(6);
            $pdf->Cell(180, 5, iconv('ISO-8859-1', 'windows-1252', html_entity_decode($name)), 0, 1, 'L');
            $pdf->Ln(3);
            $pdf->Cell(6);
            $pdf->Cell(180, 5, "Dear Esteemed Student,", 0, 1, 'L');

            /* $pdf->Ln(8);
            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(6);
            $programmeName=$db->getData("programmemajor", "programmeMajor", "programmeMajorID", $programmeMajorID);
            $pdf->Cell(101, 6, "" . iconv('ISO-8859-1', 'windows-1252',"Program of Study: ".html_entity_decode($programmeName)), "0"); */

            $pdf->Ln(6);
            $pdf->setFont('Arial', 'B', 11);
            $appYear = explode("/", $applicationYear);
            $pdf->Cell(6);
            $pdf->Cell(180, 5, "SUBJECT: ADMISSION FOR THE JULY INTAKE " . $applicationYear, 0, 1, 'C');
            $pdf->Cell(6);
            $pdf->Cell(180, 0, '', 'T', 1, 'C');

            // Faded LSZ crest as a centred watermark behind the letter body.
            // Pass width only so FPDF keeps the original aspect ratio
            // (the crest is taller than wide; specifying both warps it).
            if (!empty($organizationPicture) && @file_exists($organizationPicture)) {
                $pdf->SetAlpha(0.15);
                $pdf->Image($organizationPicture, 65, 100, 80);  // 80 mm wide, centred
                $pdf->SetAlpha(1);
            }

            $pdf->Ln(3);
            $pdf->setFont('Arial', '', 10);
            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "On behalf of the Management of the Law School of Zanzibar, I am pleased to inform you that you have been offered a place at the Law School of Zanzibar for one year program, " . $applicationYear . ", for a legal practical course leading to the award of a Post Graduate Certificate of Competence to qualify as an advocate.");
            $pdf->Ln(2);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "You are required to register with the Law School of Zanzibar within two weeks from the opening of the school on 20th July, 2026. Failure to register within the specified time will lead to cancellation of a place.");
            $pdf->Ln(2);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "The admission offer is provisional pending verification of the qualifications as presented on your online application. You will be required at the time of registration, to present in person the original documents you have used in the application. I would, therefore, like to put an emphasis on the following that must be presented to the School:");

            $pdf->Cell(12);
            $pdf->MultiCell(0, 5, "i.   The original Certificate of Ordinary Secondary Education (Form IV);");
            $pdf->Cell(12);
            $pdf->MultiCell(0, 5, "ii.  The original Degree of Law or its equivalent or certified evidence to prove possession of it;");
            $pdf->Cell(12);
            $pdf->MultiCell(0, 5, "iii. The original Advanced Certificate of Secondary Education (where necessary);");
            $pdf->Cell(12);
            $pdf->MultiCell(0, 5, "iv.  Where applicable the relevant original Degree or Diploma or Certificate and Transcript;");
            $pdf->Cell(12);
            $pdf->MultiCell(0, 5, "v.   Three passport size photographs.");
            $pdf->Ln(2);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "NOTE: 1. The duty of the Law School of Zanzibar is to train and award the Post Graduate Diploma in Legal Practice. Other procedures related to admission in the Bar is done by other judicial authorities.");
            $pdf->Ln(1);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "         2. Apart from the fee structure herein attached, you will have to bear some other costs related to living and accommodation.");
            $pdf->Ln(2);

            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "Congratulations and we look forward to seeing you at the School.");
            $pdf->Ln(1);
            $pdf->Cell(6);
            $pdf->MultiCell(0, 5, "Please find the attached Fee Structure.");

            // ===== Signature block — anchored near bottom so it always fits on page 1 =====
            $pdf->Ln(3);
            $pdf->Cell(6);
            $pdf->setFont('Arial', 'I', 11);
            $pdf->Cell(85, 5, "Yours Sincerely,", 0, 1, 'L');

            // Signature image: smaller + positioned closer to text
            $sigY = $pdf->GetY() + 1;
            if (!empty($signature) && @file_exists($signature)) {
                $pdf->Image($signature, 18, $sigY, 22, 14);
            }
            $pdf->SetY($sigY + 16);

            $pdf->setFont('Arial', 'B', 11);
            $pdf->Cell(6);
            $pdf->Cell(85, 5, rtrim((string)($contact_person ?? ''), ', '), 0, 1, 'L');
            $pdf->Cell(6);
            $pdf->Cell(85, 5, strtoupper((string)($title ?? '')) . ',', 0, 1, 'L');
            $pdf->Cell(6);
            $pdf->Cell(85, 5, "LAW SCHOOL OF ZANZIBAR,", 0, 1, 'L');
            $pdf->Cell(6);
            $pdf->Cell(85, 5, "ZANZIBAR.", 0, 1, 'L');

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
            $pdf->Cell(90, 6, "1,300,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(d)Examination Fee", 1);
            $pdf->Cell(90, 6, "50,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(e)Field (Attachment) Supervision Fee", 1);
            $pdf->Cell(90, 6, "50,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(f)Library and Internet Service Fee", 1);
            $pdf->Cell(90, 6, "50,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(g)Institutional Stationery and Photocopy Services", 1);
            $pdf->Cell(90, 6, "50,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(h)Caution Money (deposit)", 1);
            $pdf->Cell(90, 6, "10,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(i)Identity Card", 1);
            $pdf->Cell(90, 6, "10,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(j)Students organization Fee", 1);
            $pdf->Cell(90, 6, "10,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "(k)Graduation Fee", 1);
            $pdf->Cell(90, 6, "20,000.00", 1);
            $pdf->Ln(6);
            $pdf->Cell(6);
            $pdf->Cell(90, 6, "Total", 1);
            $pdf->Cell(90, 6, "1,590,000.00/=", 1);


            //$pdf->Output();
            $pdf->Output($formfour . "-" . $applicantNumber . "-" . $applicationYear . ".pdf", "D");
        }
    }
}
