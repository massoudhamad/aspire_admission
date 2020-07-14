<script type="text/javascript">
        $(document).ready(function () {
        //var programmeID="<?php echo $_REQUEST['programmeID'];?>";
        var titleheader = $('#titleheader').text();
        $('#nactereport').DataTable({
		"ajax":
                        {
                        type: 'POST',
                        url: 'data/nactereport.php',
                        //data: dataString,
                        cache: false
                        },
                "dom": 'Blfrtip',
                "scrollX":true,
                "buttons":[
                        {
                            extend:'excel',
                            title: titleheader,
                            footer:false,
                            exportOptions:{
                                columns: [0, 1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
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
    
<hr >
<div class="row">
 <div class="col-md-12">   
<table  id="nactereport" class="display" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>No.</th>
    <th>First Name</th>
    <th>Middle Name</th>
    <th>Last Name</th>
    <th>DOB</th>
    <th>Gender</th>
    <th>Disability</th>
    <th>Form IV</th>
    <th>Year</th>
    <th>Form VI</th>
    <th>Year</th>
    <th>NTA 4</th>
    <th>Year</th>
    <th>Diploma/NTAs</th>
    <th>Year</th>
    <th>Email</th>
    <th>Address</th>
    <th>Phone</th>
    <th>Region</th>
    <th>Distict</th>
    <th>Next Of Kin</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Relationship</th>
    <th>Next of Kin Region</th>
    <th>Nationality</th>
     </tr>
  </thead>
 </table>
 </div></div>  
</div>