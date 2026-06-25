<?php
if (session_status() === PHP_SESSION_NONE) session_start();
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


    case 'payments':
        include('payment.php');
        break;
        
    case 'education_background':
        include('education_background.php');
        break;
        
    case 'olevel':
        include('ordinarylevel.php');
        break;
        
    case 'confirm_ordinary_results':
         include('confirm_ordinary_results.php');
         break;



    case 'ordinary_results':
        include('ordinarylevel.php');
        break;

    case 'other_ordinary':
        include('other_ordinary_level.php');
        break;


        case 'other_ordinary_results':
            include('ordinary_level_other.php');
            break;


    case 'alevel':
       //include 'advanced_level.php';
        include 'adv_level.php';
        break;
        
    case 'newsubject':
        include 'addnewsubject.php';
        break;
        
    /*case 'equivalent':
        include 'equivalent_results.php';
        break;*/

    case 'equivalent':
        include 'equ_level.php';
        break;
        
    /*case 'programmechoice':
        include 'programme_choice.php';
        break;*/

    case 'programmechoice':
        include 'pchoice.php';
        break;
        
    case 'programme_choice_verification':
        include('programme_choice_confirmation.php');
        break;
        
    case 'personalinfo':
        include 'registration_form.php';
        break;

        case 'working':
            include 'working_experience.php';
            break;

    case 'referees':
        include 'referees.php';
        break;


        case 'attachment':
            include 'attachment.php';
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

    case 'submit':
        include 'submit_application.php';
        break;

    case 'confirm_applicant_tcu':
        include 'confirm_applicant_tcu.php';
        break;

        case 'request_confirmation_code':
        include 'request_confirmation_code.php';
        break;

    case 'un_confirm_applicant_tcu':
        include 'un_confirm_applicant_tcu.php';
        break;


        case 'pg_education':
            include 'pg_education.php';
            break;

    case 'check_status':
        include 'check_status.php';
        break;
        
    default:
        include('applicationindex.php');
}
?>