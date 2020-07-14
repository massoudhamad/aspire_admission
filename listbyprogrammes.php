<script type="text/javascript">
    
//listbyprogrammes
        $(document).ready(function () {
        var programmeID="<?php echo $_REQUEST['programmeID'];?>";
        var admissionID="<?php echo $_REQUEST['admissionID'];?>";
        var titleheader = $('#titleheader').text();
        $('#listbyprogrammes').DataTable({
		"ajax":
                        {
                        type: 'POST',
                        url: 'data/viewlistbyprogrammes.php?programmeID='+programmeID+'&admissionID='+admissionID,
                        //data: dataString,
                        cache: false
                        },
                "dom": 'Blfrtip',
                "buttons":[
                        {
                            extend:'excel',
                            title: titleheader,
                            footer:false,
                            exportOptions:{
                                columns: [0, 1, 2, 3,4,5,6]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: titleheader,
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3,4,5,6]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: titleheader,
                            footer: true,
                           exportOptions: {
                                columns: [0, 1, 2, 3,4,5,6]
                            },
                            
                        }

                        ],
		"order": []
	});
    });
 </script>
<div class="container">
<?php
    $db=new DBHelper();
?>
    <h2><span id="titleheader">List of applicants for <?php echo $db->getData("programs","programName","programID",$_REQUEST['programmeID']);?></span></h2>
<hr >
<div class="row">
 <div class="col-md-12">   
<table  id="listbyprogrammes" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>No.</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Phone Number</th>
    <th>Ref.Number</th>
    <th>Index Number</th>
    <th>Status</th>
    <th>View</th>
     </tr>
  </thead>
 </table>
 </div></div>  
</div>