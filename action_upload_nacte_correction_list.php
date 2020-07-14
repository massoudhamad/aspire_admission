<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
//try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants_nacte_list';

    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            //upload file
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, "r");
            if ($file == NULL) {
                $boolStatus = false;
            } else {
                //$flag=true;
                fgetcsv($handle);
                while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {

                    $verificationID = $filesop[0];
                    $programmeID = $filesop[1];
                    $firstName = $filesop[2];
                    $middleName = $filesop[3];
                    $lastName = $filesop[4];
                    $mobileNumber = $filesop[5];
                    $email = $filesop[6];
                    $formfour = $filesop[7];
                    $formfouryear = $filesop[8];
                    $formsix = $filesop[9];
                    $formsixyear = $filesop[10];
                    $nta4 = $filesop[11];
                    $nta4year = $filesop[12];
                    $nta5 = $filesop[13];
                    $nta5year = $filesop[14];


                    //add users first
                    $userData = array(
                        'admissionID' => $_POST['admissionID'],
                        'student_verification_id' => $verificationID,
                        'programme_id' => $programmeID,
                        'firstname' => $firstName,
                        'secondname' => $middleName,
                        'surname' => $lastName,
                        'mobile_number' => $mobileNumber,
                        'email_address' => $email,
                        'form_four_indexnumber' => $formfour,
                        'form_four_year' => $formfouryear,
                        'form_six_indexnumber' => $formsix,
                        'form_six_year' => $formsixyear,
                        'NTA4_reg' => $nta4,
                        'NTA4_grad_year' => $nta4year,
                        'NTA5_reg' => $nta5,
                        'NTA5_grad_year' => $nta5year,
                        'nacte_status' => 0
                    );
                    $insert = $db->insert($tblName, $userData);
                    $boolStatus = true;
                }

                if ($boolStatus) {
                    header("Location:index3.php?sp=nacte_add_correction_error&msg=succ");
                } else {
                    header("Location:index3.php?sp=nacte_add_correction_error&msg=unsucc");
                }

            }
        }
    }

/*} catch (PDOException $ex) {
    header("Location:index3.php?sp=nacte_add_correction_error&msg=error");
}*/