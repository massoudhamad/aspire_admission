<?php
session_start();
include('../DB.php');
$db = new DBHelper();
$out_number = $_POST['out_reg_number'];
$applicantID = $_SESSION['applicantID'];
if ($out_number) {
    $api_token = $db->getAPI("OUT", "token");
    if (!empty($api_token)) {
        foreach ($api_token as $api) {
            $token = $api['token'];
            $user = $api['userName'];
        }
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Request>
        <UsernameToken>
        <Username>' . $user . '</Username>
        <SessionToken>' . $token . '</SessionToken>
        </UsernameToken>
        <RequestParameters>
        <RegNo>' . $out_number . '</RegNo>
        </RequestParameters>
        </Request>';

        echo $token."<br>";


    $data_string = $xml;
    $ch = curl_init('http://196.216.247.11/index.php/results/student');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = ['Content-Type: application/xml', 'OUT-Com: default.sp.in'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);
    $array_data = json_decode(json_encode(simplexml_load_string($result)), true);
    var_dump($array_data); 

    $resparameters = $array_data['ResponseParameters'];
    $results = $array_data['ResponseParameters']['Results']['Subject'];
    //var_dump($results);


    /* if ($data['status']['code'] == 200) {
        foreach ($data['params'] as $value) { */

    $name = $resparameters['FirstName'] . " " . $resparameters['MidName'] . " " . $resparameters['Surname'];
?>

    <form name="" action="action_save_foundation_results.php" method="post">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>

                    <th>RegNumber</th>
                    <th>Index Number</th>
                    <th>Name</th>
                    <th>GPA</th>
                    <th>Class</th>
                    <th>Academic Year</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="reg_number" value="<?php echo $resparameters['RegNo']; ?>" class="form-control" readonly> </td>
                    <td><input type="text" name="index_number" value="<?php echo $resparameters['Indexno']; ?>" class="form-control" readonly> </td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" class="form-control" readonly> </td>
                    <td><input type="text" name="gpa" value="<?php echo $resparameters['GPA']; ?>" class="form-control" readonly> </td>
                    <td><input type="text" name="classfication" value="<?php echo $resparameters['Classification']; ?>" class="form-control" readonly> </td>
                    <td><input type="text" name="academic_year" value="<?php echo $resparameters['AcademicYear']; ?>" class="form-control" readonly> </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Grade</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $count=0;
                foreach ($results as $value) {
                    if (is_array($value)) {
                        $code=$value['Code'];
                        $subjectName = $value['SubjectName'];
                        $grade = $value['Grade'];
                        $count++;
                ?>
                                <tr>
                                    <td>
                                        <input type="text" name="subjectCode[]" value="<?php echo $code; ?>" class="form-control" readonly>
                                    </td>
                                            <td>
                                                <input type="text" name="subjectName[]" value="<?php echo $subjectName; ?>" class="form-control" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="gradeCode[]" value="<?php echo $grade; ?>" class="form-control" readonly>
                                            </td>
                                        </tr>

                <?php
                                    }
                                }
                ?>
            </tbody>
        </table>
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <input type="hidden" name="applicantID" value="<?php echo $applicantID; ?>">
            <input type="hidden" name="action_type" value="add" />
            <input type="hidden" name="examinationlevel" value="Equivalent">
            <input type="hidden" name="numbersubjects" value="<?php echo $count; ?>">
            <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
        </div>
    </form>

<?php
    /*}
    else
    {
         echo "<h4 class='text-danger'>Sorry,Invalid Index Number.</h4>";
    }*/
    //} //end of loop
    /*  } else {
        echo "<h4 class='text-danger'>Sorry,Invalid Token.</h4>";
    } */
    /*}else
        {
            echo "<h4 class='text-danger'>Sorry,Your Index Number doest not match with personal details.</h4>";
        }*/
    /*} else {
        echo "<h4 class='text-danger'>Sorry,NECTA API results are not obtained, please <a href='index.php?sz=other_ordinary_results&level=".$level."&id=" . $_SESSION['applicantID'] . "&inumber=" . $indexNumber . "'>click here</a> 
    to add results manually</h4>";
    }*/
}
?>