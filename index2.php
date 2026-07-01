<?php 
  require_once("session.php");
  require_once("DB.php");
  $db = new DBHelper();
  $userID = $_SESSION['user_session'];
  $userRoleID=$db->getData("userroles","roleID","userID",$userID);
  if($userRoleID==2)
  {
      $applicantsData=$db->getRows('applicants',array('where'=>array('userID'=>$userID),'order_by'=>'applicantID ASC'));
     if(!empty($applicantsData)){ 
       foreach($applicantsData as $apps)
       {
           $applicantID=$apps['applicantID'];
           $_SESSION['applicantID']=$applicantID;
           // Hydrate exam authority + level into the session so downstream
           // pages (e.g. confirm_ordinary_results.php) can branch on them
           // without re-querying the DB.
           $_SESSION['eauthority']     = isset($apps['eauthority'])     ? $apps['eauthority']     : '';
           $_SESSION['admissionLevel'] = isset($apps['admissionLevel']) ? $apps['admissionLevel'] : '';
           $_SESSION['formfour']       = isset($apps['formfour'])       ? $apps['formfour']       : '';
           $fname=$apps['firstName'];
           $mname=$apps['middleName'];
           $lname=$apps['lastName'];
           $name="$fname $lname";
       }
     }
  }
 else
 {
      $userData=$db->getRows('users',array('where'=>array('userID'=>$userID),'order_by'=>'userID ASC'));
     if(!empty($userData)){ 
       foreach($userData as $apps)
       {
           $fname=$apps['firstName'];
           $mname=$apps['middleName'];
           $lname=$apps['lastName'];
           $name="$fname $lname";
       }
     }
 }



$org=$db->getRows("organization");
if(!empty($org))
{
    foreach($org as $og)
    {
        $orgName=$og['organizationName'];
        $orgPicture=$og['organizationPicture'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ASPIRE Online Admission System</title>
    <!-- Bootstrap Core CSS -->
     <script type="js/jquery-1.12.3.js"></script>
   <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/applicant.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/chosen.css">
    <link rel="stylesheet" href="css/chosen.min.css">
    <link rel="stylesheet" href="css/bootstrap-chosen.css">
    <style>
            .table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
        
    </style>
</head>

   <script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
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
 <style type="text/css">
    hr { border: 0.5px solid;}
    </style>    
<body id="page-top" class="index">

    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#page-top"><?php echo $orgName;?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">

                    <li class="page-scroll">
                        <a href="index3.php">Home</a>
                    </li>
                    <li class="page-scroll">
                        <a href="">Logged in as <?php echo $name;?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="index2.php?sz=changepwd">Change Password</a>
                    </li>
                    <li class="page-scroll">
                        <a href="logout.php?logout=true">Logout</a>
                    </li>
                   
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <section class="success">

<div class="container">
    <br>
    <div class="col-lg-12">
        <?php 
        if($_REQUEST['sz']=='education_background')
        {
        ?>
        <a href='index2.php?sz=level'>Study Levels</a>
        <?php
        }
        if($_REQUEST['sz']=='programmechoice')
        {
        ?>
         <a href='index2.php?sz=level'>Study Levels</a>|<a href='index2.php?sz=education_background'>Education Background</a>
        <?php 
        }
        if($_REQUEST['sz']=='registration_form')
        {
        ?>
          <a href='index2.php?sz=level'>Study Levels</a>|<a href='index2.php?sz=education_background'>Education Background</a>|
          <a href='index2.php?sz=programmechoice'>Study Programmes</a>
        <?php 
        }
        if($_REQUEST['sz']=='confirm_registration')
        {
        ?>
          <a href='index2.php?sz=level'>Study Levels</a>|<a href='index2.php?sz=education_background'>Education Background</a>|
          <a href='index2.php?sz=programmechoice'>Study Programmes</a>|<a href='index2.php?sz=registration_form'>Personal Registration</a>
        <?php 
        }
        ?>
    </div>
<div class="col-lg-12">
    <?php
    if($userRoleID==2)
    {
    ?>
    <h3 class="text text-info">Applicant Registration</h3>
    <?php }
    else
    {
        ?>
    <h3 class="text text-info">Password Change</h3>
    <?php
    }
    ?>
</div>
            <div class="row">
                <div class="col-lg-12">
                    <?php 
                    session_start();
                    switch((isset($_GET['sz'])?$_GET['sz'] : ''))
                    {
                        case 'success':
                        include('success.php');
                        break;
                        case 'home':
                        include('home.php');
                        break;
                        
                        case 'level':
                        include('level.php');
                        break;
                    
                        case 'education_background':
                        include('education_background.php');
                        break;
                        
                        case 'olevel':
                        include('ordinarylevel.php');
                        break;
                        
                    
                       case 'alevel':
                       include 'advanced_level.php';
                       break;
                    
                       case 'newsubject':
                       include 'addnewsubject.php';
                       break;
                       
                       case 'equivalent':
                       include 'equivalent_results.php';
                       break;
                   
                       case 'programmechoice':
                       include 'programme_choice.php';
                       break;
                   
                       //Programme Choice Confirmation Page
                        case 'programme_choice_verification':
                        include('programme_choice_confirmation.php');
                        break;
                    
                       case 'registration_form':
                       include 'registration_form.php';
                       break;
                   
                       case 'confirm_registration':
                       include 'confirm_registration.php';
                       break;
                       case 'summary':
                       include 'applicationsummary.php';
                       break;
                   
                       case 'changepwd':
                       include 'changepwd.php';
                       break;
                       
                       
                       case 'applicationindex':
                       include 'applicationindex.php';
                       break;
                   
                       case 'appdetails':
                       include 'appdetails.php';
                       break;

                        default:
                        include('frontpage.php');
                            //include('homestudent.php');
                    }
                    ?>
                 
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include("footeru.php")?>

    <!-- jQuery -->
  
    
   
    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <!-- Plugin JavaScript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/jquery-1.12.3.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>

</body>

</html>
