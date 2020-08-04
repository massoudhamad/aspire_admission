<?php
session_start();
include ('../DB.php');
$db=new DBHelper();
$indexNumber=$_POST['indexNumber'];
$level=$_POST['level'];
$applicantID=$_SESSION['applicantID'];
if($indexNumber) {
    $api_token = $db->getAPI("NECTA", "token");
        if (!empty($api_token)) {
            foreach ($api_token as $api) {
                $apitToken = $api['token'];
            }
        }

    if(strlen($db->getAPIToken($apitToken))>1) {
        $iNumber = explode("/", $indexNumber);
        $centerNumber = $iNumber[0];
        $number = $iNumber[1];
        $yearTaken = $iNumber[2];
        $apiNumber = $centerNumber . "-" . $number . "/1/" . $yearTaken;
        //$token=$db->getAPIToken();
        //$json = file_get_contents("https://api.necta.go.tz/api/public/results/" . $apiNumber . "/" . $token);
        
        $token = $db->getAPIToken($apitToken);
        $json = file_get_contents("https://api.necta.go.tz/api/public/results/" . $apiNumber . "/" . $token);
        // $url = "https: //api.necta.go.tz/api/public/results/".$apiNumber."/".$token;
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_HTTPGET, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response_json = curl_exec($ch);
        // curl_close($ch);
        // $data = json_decode($response_json, true);

        $data = json_decode($json, true);
        ?>
        <form name="" action="action_confirm_ordinary_results.php" method="post">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>

                <th>Index Number</th>
                <th>School Name</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="text" name="index_number" value="<?php echo $data['particulars']['index_number'];?>" class="form-control" readonly> </td>
                <td><input type="text" name="schoolName" value="<?php echo $data['particulars']['center_name'];?>" class="form-control" readonly> </td>
            </tr>
            <?php

            //echo "<tr><td>" . $data['particulars']['index_number'] . "</td><td>" . $data['particulars']['center_name'] . "</td><</tr>";

            ?>
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
            foreach ($data as $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if (is_array($v)) {
                            $count=0;
                            foreach ($v as $vv) {
                                if (is_array($vv)) {
                                    $subjectName = $vv['subject_name'];
                                    $grade = $vv['grade'];
                                    $count++;
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="subjectCode[]" value="<?php echo $vv['subject_code'];?>" class="form-control" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="subjectName[]" value="<?php echo $vv['subject_name'];?>" class="form-control" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="gradeCode[]" value="<?php echo $vv['grade'];?>" class="form-control" readonly>
                                        </td>
                                    </tr>

                                <?php
                                }
                            }
                        }

                    }
                }
            }
            ?>
            </tbody>
        </table>
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <input type="hidden" name="applicantID" value="<?php echo $applicantID;?>">
                <input type="hidden" name="action_type" value="add"/>
                <input type="hidden" name="examinationlevel" value="Ordinary">
                <input type="hidden" name="examinationaward" value="formfour">
                <input type="hidden" name="indexNumber" value="<?php echo $indexNumber;?>">
                <input type="hidden" name="yearTaken" value="<?php echo $yearTaken;?>">
                <input type="hidden" name="numbersubjects" value="<?php echo $count;?>">
                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-success form-control" />
            </div>
        </form>
        <?php

        //}
        ?>
        </div>

        <?php
    } else {
        echo "<h3 class='text-danger'>Sorry,NECTA API Results are not obtained, please <a href='index.php?sz=ordinary_results&id=" . $_SESSION['applicantID'] . "&inumber=" . $indexNumber . "'>click here</a> 
    to add results manually</h3>";
    }
}
?>