<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array('data' => array());


$applicantsData=$db->getRows("applicants_nacte_list",array('group_by firstname ASC'));
if(!empty($applicantsData))
{
    $x=0;
    foreach ($applicantsData as $row)
    {
        $x++;
        $verID=$row['student_verification_id'];
        $programmeID = $row['programme_id'];
        $firstName=$row['firstname'];
        $middleName=$row['secondname'];
        $lastName=$row['surname'];
        $mobileNumber=$row['mobile_number'];
        $email=$row['email_address'];
        $formfour=$row['form_four_indexnumber'];
        $formfouryear=$row['form_four_year'];
        $formsix=$row['form_six_indexnumber'];
        $formsixyear=$row['form_six_year'];
        $nta4=$row['NTA4_reg'];
        $nta4year=$row['NTA4_grad_year'];
        $nta5=$row['NTA5_reg'];
        $nta5year=$row['NTA5_grad_year'];
        $phoneNumber=$row['mobile_number'];
        $email=$row['email_address'];




        $checkbox="<input type='checkbox' class='checkbox_class' name='verificationID[]' value='$verID'>";
        $output['data'][] = array(
            $x,
            $checkbox,
            $verID,
            $programmeID,
            $firstName,
            $middleName,
            $lastName,
            $formfour,
            $formfouryear,
            $formsix,
            $formsixyear,
            $nta4,
            $nta4year,
            $nta5,
            $nta5year,
            $email,
            $phoneNumber,
            'Sent'
        );

        //$x++;
    }
}

// database connection close


echo json_encode($output);
//$db->close();