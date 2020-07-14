<?php
session_start();
include ('../DB.php');
$db=new DBHelper();
$indexNumber=$_POST['indexNumber'];
$level=$_POST['level'];
if($indexNumber) {
    if(strlen($db->getAPIToken())>1) {
        $iNumber = explode("/", $indexNumber);
        $centerNumber = $iNumber[0];
        $number = $iNumber[1];
        $yearTaken = $iNumber[2];
        $apiNumber = $centerNumber . "-" . $number . "/1/" . $yearTaken;
        $token=$db->getAPIToken();
        $json = file_get_contents("https://api.necta.go.tz/api/public/results/" . $apiNumber . "/" . $token);
        $data = json_decode($json, true);
        ?>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>

                <th>Index Number</th>
                <th>School Name</th>
                <th>Points</th>
            </tr>
            </thead>
            <tbody>
            <?php

            echo "<tr><td>" . $data['particulars']['index_number'] . "</td><td>" . $data['particulars']['center_name'] . "</td><td>" . $data['division'][0]['point'] . "</td></tr>";

            ?>
            </tbody>
        </table>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
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
                            foreach ($v as $vv) {
                                if (is_array($vv)) {
                                    $subjectName = $vv['subject_name'];
                                    $grade = $vv['grade'];
                                    echo "<tr><td>" . $subjectName . "</td><td>" . $grade . "</td></tr>";
                                }
                            }
                        }

                    }
                }
            }
            ?>
            </tbody>
        </table>
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