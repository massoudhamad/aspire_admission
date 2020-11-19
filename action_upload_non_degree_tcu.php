<?php
session_start();
/* ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT); */
//try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'applicants_non_degree';

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
                    $name = $filesop[0];
                    $formfour = $filesop[1];
                    $formsix = $filesop[2];
                    $certreg = $filesop[3];
                    $gender = $filesop[4];
                    $nationality = $filesop[5];
                    $impairment = $filesop[6];
                    $dob = $filesop[7];
                    $progcode = $filesop[8];
                    $progname = $filesop[9];
                    $category = $filesop[10];


                    //add users first
                    $userData = array(
                        'name' => $name,
                        'formfour' => $formfour,
                        'formsix' => $formsix,
                        'certreg' => $certreg,
                        'gender' => $gender,
                        'nationality' => $nationality,
                        'impairment' => $impairment,
                        'dob' => $dob,
                        'progcode' => $progcode,
                        'progname' => $progname,
                        'category'=>$category
                    );
                    $insert = $db->insert($tblName, $userData);
                    $boolStatus = true;
                }

                if ($boolStatus) {
                    header("Location:index3.php?sp=upload_non_degree&msg=succ");
                } else {
                    header("Location:index3.php?sp=upload_non_degree&msg=unsucc");
                }

            }
        }
    }

/*} catch (PDOException $ex) {
    header("Location:index3.php?sp=nacte_add_correction_error&msg=error");
}*/