<?php 
session_start();
require_once("session.php");
  
  require_once("DB.php");
  $auth_user = new DBHelper();
  $userID = $_SESSION['user_session'];
  $user_privilege=$_SESSION['role_session'];
  $user_array=array(1,3,4);
  //if(($user_privilege != 1) || ($user_privilege != 3) || ($user_privilege != 4))

  $organization = $auth_user->getRows('organization', array('order_by' => 'organizationName DESC'));
  if (!empty($organization)) {
    foreach ($organization as $org) {
      $organizationName = $org['organizationName'];
      $organizationCode = $org['organizationCode'];
      $organizationPicture = "../img/" . $org['organizationPicture'];
      $studentSupport = $org['student_support'];
      $orgCode = $org['organizationCode'];
      $_SESSION['orgCode'] = $orgCode;
    }
  } else {
    $organizationName = "Soft Dev Academy";
    $organizationCode = "SDVA";
    $organizationPicture = "../img/SkyChuo.png";
  }


  if(!in_array($user_privilege, $user_array))
  {
      //echo $user_privilege;
      header("Location:logout.php?logout=true");
      exit();
  }
  //else
  //{
  $login=$auth_user->getData("users","login","userID",$userID);
  if($login==0)
  {
      header("Location:index2.php?sz=changepwd");
      exit();
  }
  //else 
  //{
    ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Admission System</title>
    
   <!--<script type="js/jquery-1.12.3.js"></script>
   <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>-->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    

   
    <script src="Scripts/jquery-1.10.2.min.js" type="text/javascript"></script>
   <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  

    <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" type="text/css" href="plugins/datepicker/css/datepicker.css" />
   <!-- <link rel="stylesheet" type="text/css" href="css/dataTables.responsive.css" />-->
    <link rel="stylesheet" href="css/chosen.css">
    <link rel="stylesheet" href="css/chosen.min.css">
    <link rel="stylesheet" href="css/bootstrap-chosen.css">

<script type="text/javascript">
/*$(document).ready(function () {
    var action = window.location.pathname.split('/')[1];

    // If there's no action, highlight the first menu item
    if (action == "") {
        $('ul.nav li:first').addClass('active');
    } else {
        // Highlight current menu
        $('ul.nav a[href="/' + action + '"]').parent().addClass('active');

        // Highlight parent menu item
        $('ul.nav a[href="/' + action + '"]').parents('li').addClass('active');
    }
});*/

$(document).ready(function () {
    var url = window.location;
    $('ul.nav a[href="' + url + '"]').parent().addClass('active');
    $('li.treeview a').filter(function () {
        return this.href == url;
    }).parent().addClass('active').parent().parent().addClass('active');
});
</script>
  <script type="text/javascript">
  $(document).ready(function () {
            $('#example').DataTable(
                {
                   "scrollX":true,
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            message:"List of Actiities",
                            title:"This is testing",
                            exportOptions:{
                                columns:[0,1,2,3]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Waqfs',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Waqfs',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>

<script type="text/javascript">
  $(document).ready(function () {
            $('#exampleexample').dataTable(
                {
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            /*exportOptions:{
                                columns:[0,1,2,3]
                            }*/
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>

<script type="text/javascript">
  $(document).ready(function () {
            $('#exampleexampleexample').dataTable(
                {
                    paging: true,
                    dom: 'Blfrtip',
                    buttons:[
                        {
                            extend:'excel',
                            footer:false,
                            /*exportOptions:{
                                columns:[0,1,2,3]
                            }*/
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List',
                            footer: false,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3]
                            }*/
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List',
                            footer: true,
                           /* exportOptions: {
                                columns: [0, 1, 2, 3,5,6]
                            }*/
                            orientation: 'landscape',
                        }

                        ]
                });
          });
</script>
<script type="text/javascript">
  $(document).ready(function () {
            $('#exampleedit').DataTable(
                {
                    scrollX: true,
                    paging: false,
                   
                });
          });
</script>
<script type="text/javascript">
$(document).ready(function(){ 
$("#select_all").change(function(){
  $(".checkbox_class").prop("checked", $(this).prop("checked"));
  });
});      
</script>
<script type="text/javascript">
  $(document).ready(function () {
            $('#onlydata').dataTable(
                {
                    paging: true,
                    dom: 'Blfrtip'
                });
          });
</script>
   <script type="text/javascript">
    $(document).ready(function () {
        $('#pickyDate').datepicker({
            format: "dd/mm/yyyy",
              autoclose: 1
        });
    });
</script>  


<script>
      $(document).ready(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
</script>

 <script>// Additional logic goes here
  $(document).ready(function(){
  //Chosen
  $("#limitedNumbChosen").chosen({
    //max_selected_options: 3,
    placeholder_text_multiple: "Select Here"
    })
    .bind("chosen:maxselected", function (){
        window.alert("You reached your limited number of selections which is 2 selections!");
    })
});
    </script>
<script>
  $(document).ready(function(){
  var firstName = $('#firstName').text();
  var lastName = $('#lastName').text();
  var intials = $('#firstName').text().charAt(0) + $('#lastName').text().charAt(0);
  var profileImage = $('#profileImage').text(intials);
});
        </script>       
<style type="text/css">
    hr { border: 0.5px solid;}
    .vericaltext{
    width:1px;
    word-wrap: break-word;
    font-family: monospace /* this is just for good looks */
}
    </style> 
        
    <style>
    @media (min-width: 1200px){
    .container, 
    .navbar-static-top .container, 
    .navbar-fixed-top .container, 
    .navbar-fixed-bottom .container {
        width: 100%;
    }
}
       
  .table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
  #profileImage {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: #512DA8;
  font-size: 35px;
  color: #ffffff;
  text-align: center;
  line-height: 120px;
  margin: 0px 60px;
} 

.text-profile
{
  font-size: 14px;
  color: #ffffff;
}
    </style>
    
</head>
   
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header navbar-fixed-top" >

        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">
              SDS
          </span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
                  <img alt="Aspire UAS" style="max-width: 100%;" src="img/aspire_hrp_logo.png"/>
      

          </span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top navbar-fixed-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
            
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                    <?php 
                    if($user_privilege==1)
                    {
                    ?>
                        <li role="presentation" class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                              &nbsp; System <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a runat="server" href="~/About">About</a></li>
 				                <li><a href="ManageUsers.aspx">Manage Users</a></li>
 				                <li><a href="#">Backup</a></li>
                                <li><a href="#">Restore</a></li>

 			                </ul>
                        </li>            
                    <?php }?>
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                 <?php 
                        $userData=$auth_user->getRows("users",array('where'=>array('userID'=>$userID),'order_by'=>'userID'));
                        if(!empty($userData))
                        {
                            foreach($userData as $user)
                            {
                                $fname=$user['firstName'];
                                $lname=$user['lastName'];
                                $sectionID=$user['sectionID'];
                            }
                        }
                        $section=$auth_user->getData("schools","schoolCode","schoolID",$sectionID);
                        $roleID=$auth_user->getData("userroles","roleID","userID",$userID);
                        $roleName=$auth_user->getData("roles","roleName","roleID",$roleID);
                        if($sectionID==0)
                            $section="";
                        else
                            $section="-".$section;
                        $name="$fname $lname";
                       
                        ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                 <!-- The user image in the navbar-->
                 <!--<img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <span class="hidden-xs"><?php echo $name;?>
                      </span> 
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                 
                  <li class="user-header">
                        <div id="profileImage"></div>
                        <span id="firstName" class="text-profile"><?php echo $fname;?></span>
                        <span id="lastName" class="text-profile"><?php echo $lname;?></span><br>
                        <span id="role" class="text-profile">(<?php echo $roleName."".$section;?>)</span>
                    <p>
                      
                     
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                        <a href="index2.php?sz=changepwd" class="btn btn-default btn-flat">Change Password</a>
                    </div>
                    <div class="pull-right">
                        <a href="logout.php?logout=true" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              
            </ul>
          </div>
        </nav>
          <br />
          <br />
          <br />
      </header>
        <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

         <?php 
         include("menu.php");
         ?>
        </section>
        <!-- /.sidebar -->
      </aside>
         <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php include "mainindex.php";?>
            
        </section>
        <br />


          <!--<div class="content-wrapper">
              <?php /*include 'main_index.php'; */?>

          </div>-->


      </div>

    <!-- Main Footer -->
    <br /><br>
      <?php include("footer.php");?>

      

    <!-- REQUIRED JS SCRIPTS -->

        <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!--<script type="js/jquery-1.12.3.js"></script>-->
    <script type="js/jquery-1.12.4.js"></script>
    <!--<script src="js/jquery-1.12.3.js"></script>-->
   <script src="bootstrap/js/bootstrap.min.js"></script>
   <script src="js/bootbox.min.js"></script>
   
   
      <!-- Bootstrap Core JavaScript -->
 
    
    <!-- Plugin JavaScript-->
    
       
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/datepicker/js/bootstrap-datepicker.js"></script>
   
   
    
    <script src="plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <link href="plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- End Plugin for boostrap DataTable -->
    <!-- Start Plugin for boostrap export buttons -->
    <link href="plugins/datatables/buttons.dataTables.min.css" rel="stylesheet" />
    <script src="plugins/datatables/buttons.html5.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/buttons.print.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/jszip.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/pdfmake.min.js" type="text/javascript"></script>
    <script src="plugins/datatables/vfs_fonts.js" type="text/javascript"></script>
    <script src="plugins/datatables/buttons.colVis.min.js" type="text/javascript"></script>
     <link href="plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" />
    <script src="plugins/datatables/dataTables.fixedColumns.min.js" type="text/javascript"></script>

        <script src="js/script.js"></script>

        <script src="js/chosen.jquery.js"></script>
      
        
</body>
</html>
  <?php //}
  //}
  ?>