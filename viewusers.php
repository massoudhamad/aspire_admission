 <script type="text/javascript">
 $(document).ready(function () {
 $("#userdata").DataTable({
		"ajax": "data/userlist.php",
             "dom": 'Blfrtip',
             "buttons":[
                     {
                         extend:'excel',
                         title: 'List of all Users',
                         footer:false,
                         exportOptions:{
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     ,
                     {
                         extend: 'print',
                         title: 'List of all Users',
                         footer: false,
                         exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         }
                     },
                     {
                         extend: 'pdfHtml5',
                         title: 'List of all Users',
                         footer: true,
                        exportOptions: {
                             columns: [0, 1, 2, 3,5,6,7]
                         },
                         
                     }

                     ],
		"order": []
	});
 });
</script>
<div class="container">
<div class="row"> 
<h2 class="text-info">List of All Users</h2>
<div class="col-md-12">
<div class="pull-right">
    <a href='index3.php?sp=addnewuser'><span class="btn btn-success">Add New User</span></a>
            </div>   
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="secc")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>User data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsecc")
  {
    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Username is already Exist!!!</strong>.
</div>";
  }else if($_REQUEST['msg']=="block")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully, You blocked User</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unblock")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully, You Unblock User</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="reset")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Successfully You Reset Password!!!</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
      echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>User data has been edited successfully</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<table  id="userdata" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th width="5px">No.</th>
    <th>Full Name</th>
    <th>Username</th>
    <th>Email</th>
    <th>Phone Number</th>
    <th>Role</th>
    <th>Status</th>
	<th>Action</th>
    <th>Reset</th>
    <th>Edit</th>
     </tr>
  </thead>
 </table>
 </div>
 </div>
 </div>


