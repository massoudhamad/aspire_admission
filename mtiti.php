<?php
$ch = curl_init($url);
//setup request to send json via POST       
$data = array(
    'heading'=>array(
    'authorization' => $token,
    'intake' => 'SEPT',
    'programme_id' => $programmeCode,
    'application_year' => '2022',
    'level'=>'4',
    'payment_reference_number' => '',
    ),
    'students'=>array( 
        ['particulars'=>array(
            'firstname' => 'KHAMIS',
            'secondname' => 'A',
            'surname' => 'JUMA',
            'DOB' => '11-03-2003',
            'gender' => 'M',
            'impairement' => 'None',
            'form_four_indexnumber' => 'S1291/0029',
            'form_four_year' => '2003',
            'form_six_indexnumber' => '',
            'form_six_year' => '',
            'NTA4_reg' => '',
            'NTA4_grad_year' => '',
            'NTA5_reg' => '',
            'NTA5_grad_year' => '',
            'email_address' => 'khamisali@gmail.com',
            'mobile_number' => '0773678689',
            'address' => 'Fuoni',
            'region' => 'Mjini Magharibi',
            'district' => 'Magharibi',
            'nationality' => 'Tanzania',
            'next_kin_name' => 'Ali Khamis',
            'next_kin_address' => 'Fuoni',
            'next_kin_email_address'=>'',
            'next_kin_phone' => '0773430489',
            'next_kin_region' => 'Mjini Magharibi',
            'next_kin_relation' => 'Baba'
        )],
    )       
);
$payload = json_encode(array($data));

//attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

//set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
$result = curl_exec($ch);

//close cURL resource
curl_close($ch);
//$output= json_decode($result);
//if($output['code']==200)
$status = true;
?>

<?php
$data = array(
    'heading' => array(
        'authorization' => $token,
        'intake' => 'SEPT',
        'programme_id'=>$programmeID,
        'academic_year' => '2022',
        'level' => '4',
        ),
        'students' => array(
        ['student' => array(
            'student_verification_id'=>'',
            'firstname' => 'KHAMIS',
            'secondname' => 'A',
            'surname' => 'JUMA',
            'mobile_number' => '0774450766',
            'email_address' => 'khamisali@gmail.com',
            'form_four_indexnumber' => 'S1291/0029',
            'form_four_year' => '2003',
            'form_six_indexnumber' => '',
            'form_six_year' => '',
            'NTA4_reg' => '',
            'NTA4_grad_year' => '',
            'NTA5_reg' => '',
            'NTA5_grad_year' => '',
        )]
        )
);
?>
