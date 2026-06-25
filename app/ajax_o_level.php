<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include ('../DB.php');
$db=new DBHelper();
$indexNumber=$_POST['indexNumber'];
$level=$_POST['level'];
$applicantID=$_SESSION['applicantID'];
if($indexNumber) {
    $api_token = $db->getAPI("NECTA", "token");
        if (!empty($api_token)) {
            foreach ($api_token as $api) {
                $token = $api['token'];
            }
        }

        $iNumber = explode("/", $indexNumber);
        $centerNumber = $iNumber[0];
        $number = $iNumber[1];
        $yearTaken = $iNumber[2];
        $apiNumber = $centerNumber . "-" . $number . "/1/" . $yearTaken;
        $index_number=$centerNumber."/".$number;
    

        $data = array(
            "exam_year"=>$yearTaken,
            "exam_id"=>1,
            "index_number"=>$index_number,
            "api_key"=>$token
        );
        $payload = json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.necta.go.tz/api/results/individual',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$payload,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response_json = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response_json, true);
        
        if($data['status']['code']==1)
        {
        ?>
        <form name="" action="action_confirm_ordinary_results.php" method="post">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>

                <th>Index Number</th>
                <th>School Name</th>
                <th>Division</th>
                <th>Points</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="text" name="index_number" value="<?php echo $data['particulars']['index_number'];?>" class="form-control" readonly> </td>
                <td><input type="text" name="schoolName" value="<?php echo $data['particulars']['center_name'];?>" class="form-control" readonly> </td>
                <td><input type="text" name="division"
                                   value="<?php echo $data['results']['division']; ?>" class="form-control"
                                   readonly></td>

                                   <td><input type="text" name="points"
                                   value="<?php echo $data['results']['points']; ?>" class="form-control"
                                   readonly></td>
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
            foreach ($data['subjects'] as $vv) {
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

        }
        else 
        {
            echo $data['status']['message']."<br>Please Contact Admission Officer OR Send this message to him/her, Contact may found from top of the page";
        }
        ?>
        </div>

        <?php
    /* } else {
        echo "<h3 class='text-danger'>Sorry,NECTA API Results are not obtained<br>Please Contact Admission Officer OR Send this message to him/her, Contact may found from top of the page</h3>";
    } */
}
?>