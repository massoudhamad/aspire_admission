<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $('#admit').dataTable(
            {
                paging: true,
                scrollX:true,
                dom: 'Blfrtip',
                "lengthMenu": [[10, 25, 50,100,200,300,400,500, -1], [10, 25, 50,100,200,300,400,500, "All"]],
                buttons:[
                    {
                        extend:'csvHtml5',
                        title: titleheader,
                        customize: function (csv) {
                            return titleheader+"\n"+  csv +"\n";
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: titleheader,
                        footer: true,
                        exportOptions: {
                            columns: [0, 2,3,4]
                        }

                    },
                    {
                        //extend:'excel',
                        extend: 'excelHtml5',
                        title: titleheader,
                        footer:true,
                        exportOptions:{
                            columns:[0,2,3,4]
                        }
                    },
                ]
            });
    });
</script>
<?php
$db = new DBHelper();
?>
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <?php
            if(!empty($_REQUEST['msg']))
            {
                if($_REQUEST['msg']=="succ")
                {
                    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>".$_REQUEST['count']." of records has been saved in database</strong>.
</div>";
                }
                else if($_REQUEST['msg']=="unsucc")
                {
                    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry no data saved in database</strong>.
</div>";
                }
            }
            ?>


        </div>
    </div>
        <h2>List of Payments NACTE</h2>
        <hr>
       <!--  <div class="col-lg-12">
            <h4><span id="titleheader">List of Programs Registered NACTE</span></h4>
        </div>
        <hr> -->
        <form name="register" id="register" method="post" action="">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-lg-3">
                        Payment Reference Number: <input type="text" name="payment_reference_number" required>
                    </div>
                    <div class="col-lg-3">
                        <input type="submit" name="doSearch" value="Search Records" class="btn btn-primary form-control" />
                    </div>
                </div>
            </div>
        </form>

        <?php 
        if(isset($_POST['doSearch'])=="Search Records")
        {
            $payment_reference_number=$_POST['payment_reference_number'];
        ?>
        <div class="row">
            <div class="col-md-12">
                    <table id="admit" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th>Payment Reference Number</th>
                          <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $db=new DBHelper();
                        $api=$db->getAPI("NACTE", "balance");
                        if (!empty($api)) {
                            foreach ($api as $ap) {
                                $token=$ap['token'];
                                $url=$ap['url'];
                            }
                        }
                        $url=$url."/".$payment_reference_number."/".$token;
                        /* echo $url; */
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPGET, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response_json = curl_exec($ch);
                        curl_close($ch);
                        $response = json_decode($response_json, true);
                        $params = $response['params'];

                        var_dump($response); 

                       $count=1;
                        foreach($params as $rp) {
                            $balance = $rp['balance'];
                            echo "<tr>";
                            echo "<td>$payment_reference_number</td>";
                            echo "<td>$balance</td>";
                            echo "</tr>";
                        }
                        ?></tbody>
                    </table>
            </div>
        </div>
        <?php
        }
        ?>

</div>