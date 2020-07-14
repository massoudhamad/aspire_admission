<div class="row">
    <?php
    include '../DB.php';
    $db=new DBHelper();
        $token=$db->getAPIToken();
        $indexNumber='S0402/0005/2015';
         $iNumber=explode("/",$indexNumber);
         $centerNumber=$iNumber[0];
         $number=$iNumber[1];
         $yearTaken=$iNumber[2];
         $apiNumber=$centerNumber."-".$number."/1/".$yearTaken;
                 
                       
         $json=file_get_contents("https://api.necta.go.tz/api/public/results/".$apiNumber."/".$token);
         $data = json_decode($json,true);
         echo "Name is ".$data['particulars']['first_name'];     
                ?>
                <table class="table table-striped table-bordered table-condensed">
                      <thead>
                      <tr>
                        <th>Student Name</th>
                        <th>Index Number</th>
                        <th>School Name</th>
                        
                     </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach($data as $value)
                        {
                                $fname=$value['first_name'];
                                $mname=$value['middle_name'];
                                $lname=$value['last_name'];
                                $studentName=$fname." ".$mname." ".$lname;
                                echo "<tr><td>$studentName</td><td>".$value['index_number']."</td><td>".$value['center_name']."</td></tr>";
                        }     
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
                       foreach($data as $value)
                       {
                          if(is_array($value))
                          {
                          foreach ($value as $v)
                          {
                              //$data=array();
                              if(is_array($v))
                              {
                                  foreach ($v as $vv)
                                  {
                                      
                                      if(is_array($vv))
                                      {
                                        $subjectName=$vv['subject_name'];
                                        $grade=$vv['grade'];
                                        echo "<tr><td>".$subjectName."</td><td>".$grade."</td></tr>";
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
            //}
            ?>
     