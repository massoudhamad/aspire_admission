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
        <h2>List of Programs Registered NACTE</h2>
        <hr>
        <div class="col-lg-12">
            <h4><span id="titleheader">List of Programs Registered NACTE</span></h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <form name="register" id="register" method="post" action="action_submit_tcu.php">
                    <table id="admit" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>No.</th>
<!--                            <th width="10"><input type="checkbox" name="select_all" id="select_all"></th>
-->                            <th>Programme ID</th>
                            <th>Programme Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $db=new DBHelper();
                        $api=$db->getAPI("NACTE", "institution");
                        if (!empty($api)) {
                            foreach ($api as $ap) {
                                $token=$ap['token'];
                                $url=$ap['url'];
                            }
                        }
                        /*$url = "http://41.93.40.137/nacteapi/index.php/api/institutions/tkn/kld8b98d00092dad/ape/9aebe24ff1476ed3db07c6e4707dda3a71def9b097f8677e102b5fd731260061/Co/ed1e8be1b3fb2eafb19d59675449289873531733";*/
                        //$url= "https://www.nacte.go.tz/nacteapi/index.php/api/institutions/".$token;
                        

                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPGET, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response_json = curl_exec($ch);
                        curl_close($ch);
                        $response = json_decode($response_json, true);
                        $params = $response['params'];

                       $count=1;
                        foreach($params as $rp) {
                            $programmeName = $rp['programme'];
                            $programmeID = $rp['programe_id'];
                            echo "<tr><td>$count</td>";
                            echo "<td>$programmeID</td>";
                            echo "<td>$programmeName</td>";
                            echo "</tr>";
                            $count++;
                        }
                        ?></tbody>
                    </table>
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-3">
                            <input type="hidden" name="action_type" value="add"/>
                            <input type="submit" name="doAdmit" value="Save Records" class="btn btn-success form-control">
                        </div>
                    </div>
                </form>
            </div>
        </div>

</div>